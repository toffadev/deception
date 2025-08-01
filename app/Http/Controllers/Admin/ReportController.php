<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\Publication;
use App\Models\Comment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Afficher la liste des signalements
     */
    public function index(Request $request)
    {
        $query = Report::with(['reporter', 'reviewer', 'reportable']);

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }

        if ($request->filled('reportable_type')) {
            $query->where('reportable_type', $request->reportable_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('moderator_notes', 'like', "%{$search}%")
                    ->orWhereHas('reporter', function ($userQuery) use ($search) {
                        $userQuery->where('pseudo', 'like', "%{$search}%");
                    });
            });
        }

        // Tri par défaut : les plus récents en attente, puis par date
        $sortBy = $request->get('sort_by', 'priority');
        $sortDirection = $request->get('sort_direction', 'desc');

        if ($sortBy === 'priority') {
            // Tri personnalisé : pending d'abord, puis par date de création
            $query->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
                ->orderBy('created_at', 'desc');
        } else {
            $allowedSorts = ['created_at', 'reviewed_at', 'status', 'reason'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortDirection);
            }
        }

        $reports = $query->paginate(20);

        // Statistiques rapides
        $statistics = [
            'total_reports' => Report::count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'reviewed_reports' => Report::where('status', 'reviewed')->count(),
            'resolved_reports' => Report::where('status', 'resolved')->count(),
            'dismissed_reports' => Report::where('status', 'dismissed')->count(),
            'today_reports' => Report::whereDate('created_at', today())->count(),
        ];

        // Données pour les filtres
        $filterData = [
            'reasons' => Report::REASONS,
            'reportable_types' => [
                'App\\Models\\Publication' => 'Publications',
                'App\\Models\\Comment' => 'Commentaires',
                'App\\Models\\User' => 'Utilisateurs',
            ],
            'statuses' => [
                'pending' => 'En attente',
                'reviewed' => 'Examiné',
                'resolved' => 'Résolu',
                'dismissed' => 'Rejeté',
            ],
        ];

        return Inertia::render('Reports', [
            'reports' => $reports,
            'statistics' => $statistics,
            'filterData' => $filterData,
            'filters' => $request->only(['status', 'reason', 'reportable_type', 'date_from', 'date_to', 'search', 'sort_by', 'sort_direction']),
            'flash' => [
                'success' => session('success'),
                'error' => session('error')
            ]
        ]);
    }

    /**
     * Afficher le détail d'un signalement
     */
    public function show(Report $report)
    {
        $report->load(['reporter', 'reviewer', 'reportable']);

        // Charger des informations supplémentaires selon le type d'objet signalé
        if ($report->reportable_type === Publication::class) {
            $report->reportable->load(['user', 'moderator']);
        } elseif ($report->reportable_type === Comment::class) {
            $report->reportable->load(['user', 'publication']);
        }

        // Historique des autres signalements sur le même objet
        $relatedReports = Report::where('reportable_type', $report->reportable_type)
            ->where('reportable_id', $report->reportable_id)
            ->where('id', '!=', $report->id)
            ->with(['reporter', 'reviewer'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Historique des signalements du même rapporteur
        $reporterHistory = Report::where('reporter_id', $report->reporter_id)
            ->where('id', '!=', $report->id)
            ->with(['reportable'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return Inertia::render('Admin/Reports/Show', [
            'report' => $report,
            'relatedReports' => $relatedReports,
            'reporterHistory' => $reporterHistory,
            'currentUser' => Auth::user()
        ]);
    }

    /**
     * Traiter un signalement (changer son statut)
     */
    public function process(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:reviewed,resolved,dismissed',
            'moderator_notes' => 'nullable|string|max:1000',
            'action_on_content' => 'nullable|in:none,moderate,hide,delete',
            'action_on_user' => 'nullable|in:none,warn,suspend,ban',
        ], [
            'status.required' => 'Le statut est requis.',
            'status.in' => 'Le statut doit être : examiné, résolu ou rejeté.',
            'moderator_notes.max' => 'Les notes de modération ne peuvent pas dépasser 1000 caractères.',
            'action_on_content.in' => 'L\'action sur le contenu doit être : aucune, modérer, masquer ou supprimer.',
            'action_on_user.in' => 'L\'action sur l\'utilisateur doit être : aucune, avertir, suspendre ou bannir.',
        ]);

        try {
            DB::beginTransaction();

            // Mise à jour du signalement
            $report->update([
                'status' => $validated['status'],
                'moderator_notes' => $validated['moderator_notes'],
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id(),
            ]);

            // Actions sur le contenu signalé
            if (isset($validated['action_on_content']) && $validated['action_on_content'] !== 'none') {
                $this->executeContentAction($report->reportable, $validated['action_on_content']);
            }

            // Actions sur l'utilisateur
            if (isset($validated['action_on_user']) && $validated['action_on_user'] !== 'none') {
                $this->executeUserAction($report, $validated['action_on_user']);
            }

            // Log de l'action
            Log::info('Traitement de signalement:', [
                'report_id' => $report->id,
                'status' => $validated['status'],
                'action_on_content' => $validated['action_on_content'] ?? 'none',
                'action_on_user' => $validated['action_on_user'] ?? 'none',
                'moderator_id' => Auth::id(),
            ]);

            DB::commit();

            $statusMessages = [
                'reviewed' => 'Signalement marqué comme examiné',
                'resolved' => 'Signalement résolu avec succès',
                'dismissed' => 'Signalement rejeté',
            ];

            return redirect()->back()
                ->with('success', $statusMessages[$validated['status']]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors du traitement du signalement:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'report_id' => $report->id,
                'moderator_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors du traitement du signalement']);
        }
    }

    /**
     * Traitement en lot des signalements
     */
    public function bulkProcess(Request $request)
    {
        $validated = $request->validate([
            'report_ids' => 'required|array|min:1',
            'report_ids.*' => 'exists:reports,id',
            'action' => 'required|in:resolve,dismiss,mark_reviewed',
            'moderator_notes' => 'nullable|string|max:1000',
        ], [
            'report_ids.required' => 'Aucun signalement sélectionné.',
            'report_ids.array' => 'Les signalements doivent être fournis sous forme de tableau.',
            'report_ids.min' => 'Au moins un signalement doit être sélectionné.',
            'report_ids.*.exists' => 'Un ou plusieurs signalements sélectionnés n\'existent pas.',
            'action.required' => 'L\'action est requise.',
            'action.in' => 'L\'action doit être : résoudre, rejeter ou marquer comme examiné.',
        ]);

        try {
            DB::beginTransaction();

            $statusMap = [
                'resolve' => 'resolved',
                'dismiss' => 'dismissed',
                'mark_reviewed' => 'reviewed',
            ];

            $updatedCount = Report::whereIn('id', $validated['report_ids'])
                ->update([
                    'status' => $statusMap[$validated['action']],
                    'moderator_notes' => $validated['moderator_notes'],
                    'reviewed_at' => now(),
                    'reviewed_by' => Auth::id(),
                ]);

            Log::info('Traitement en lot de signalements:', [
                'report_ids' => $validated['report_ids'],
                'action' => $validated['action'],
                'updated_count' => $updatedCount,
                'moderator_id' => Auth::id(),
            ]);

            DB::commit();

            $actionMessages = [
                'resolve' => 'Signalements résolus avec succès',
                'dismiss' => 'Signalements rejetés avec succès',
                'mark_reviewed' => 'Signalements marqués comme examinés',
            ];

            return redirect()->back()
                ->with('success', $actionMessages[$validated['action']] . " ({$updatedCount} signalements)");
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors du traitement en lot:', [
                'message' => $e->getMessage(),
                'report_ids' => $validated['report_ids'],
                'moderator_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors du traitement en lot']);
        }
    }

    /**
     * Afficher les statistiques des signalements
     */
    public function statistics(Request $request)
    {
        try {
            $period = $request->get('period', '30'); // 30 jours par défaut
            $startDate = now()->subDays($period);

            // Statistiques générales
            $generalStats = [
                'total_reports' => Report::count(),
                'pending_reports' => Report::where('status', 'pending')->count(),
                'resolved_reports' => Report::where('status', 'resolved')->count(),
                'dismissed_reports' => Report::where('status', 'dismissed')->count(),
                'period_reports' => Report::where('created_at', '>=', $startDate)->count(),
                'average_resolution_time' => $this->getAverageResolutionTime(),
            ];

            // Signalements par jour (période sélectionnée)
            $reportsByDay = collect(range($period - 1, 0))->map(function ($daysAgo) {
                $date = now()->subDays($daysAgo);
                return [
                    'date' => $date->format('Y-m-d'),
                    'date_formatted' => $date->format('d/m'),
                    'count' => Report::whereDate('created_at', $date)->count(),
                ];
            });

            // Répartition par raison
            $reportsByReason = collect(Report::REASONS)->map(function ($label, $reason) {
                return [
                    'reason' => $reason,
                    'label' => $label,
                    'count' => Report::where('reason', $reason)->count(),
                ];
            })->filter(function ($item) {
                return $item['count'] > 0;
            })->values();

            // Répartition par type de contenu
            $reportsByType = [
                'publications' => Report::where('reportable_type', Publication::class)->count(),
                'comments' => Report::where('reportable_type', Comment::class)->count(),
                'users' => Report::where('reportable_type', User::class)->count(),
            ];

            // Top des rapporteurs
            $topReporters = Report::select('reporter_id')
                ->selectRaw('COUNT(*) as reports_count')
                ->with('reporter:id,pseudo')
                ->groupBy('reporter_id')
                ->orderBy('reports_count', 'desc')
                ->take(10)
                ->get();

            // Efficacité de modération
            $moderationEfficiency = [
                'total_reviewed' => Report::whereIn('status', ['reviewed', 'resolved', 'dismissed'])->count(),
                'resolved_percentage' => $this->getResolvedPercentage(),
                'average_response_time' => $this->getAverageResponseTime(),
            ];

            return Inertia::render('Admin/Reports/Statistics', [
                'generalStats' => $generalStats,
                'reportsByDay' => $reportsByDay,
                'reportsByReason' => $reportsByReason,
                'reportsByType' => $reportsByType,
                'topReporters' => $topReporters,
                'moderationEfficiency' => $moderationEfficiency,
                'selectedPeriod' => $period,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du calcul des statistiques de signalements:', [
                'message' => $e->getMessage(),
                'moderator_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors du calcul des statistiques']);
        }
    }

    /**
     * Exécuter une action sur le contenu signalé
     */
    private function executeContentAction($content, string $action): void
    {
        if (!$content) {
            return;
        }

        switch ($action) {
            case 'moderate':
                if (method_exists($content, 'update')) {
                    $content->update([
                        'status' => 'moderated',
                        'moderated_at' => now(),
                        'moderated_by' => Auth::id(),
                        'moderation_reason' => 'Signalement traité par modération',
                    ]);
                }
                break;

            case 'hide':
                if (method_exists($content, 'update')) {
                    $content->update([
                        'status' => 'hidden',
                        'moderated_at' => now(),
                        'moderated_by' => Auth::id(),
                        'moderation_reason' => 'Contenu masqué suite à signalement',
                    ]);
                }
                break;

            case 'delete':
                if (method_exists($content, 'delete')) {
                    $content->delete();
                }
                break;
        }

        Log::info('Action sur contenu signalé:', [
            'content_type' => get_class($content),
            'content_id' => $content->id,
            'action' => $action,
            'moderator_id' => Auth::id(),
        ]);
    }

    /**
     * Exécuter une action sur l'utilisateur
     */
    private function executeUserAction(Report $report, string $action): void
    {
        // Déterminer l'utilisateur cible selon le type de contenu signalé
        $targetUser = null;

        if ($report->reportable_type === User::class) {
            $targetUser = $report->reportable;
        } elseif ($report->reportable && isset($report->reportable->user)) {
            $targetUser = $report->reportable->user;
        }

        if (!$targetUser || $targetUser->id === Auth::id()) {
            return; // Ne pas agir sur soi-même
        }

        switch ($action) {
            case 'warn':
                // Ici, vous pourriez implémenter un système d'avertissements
                // Pour l'instant, on log juste l'action
                Log::info('Avertissement utilisateur:', [
                    'user_id' => $targetUser->id,
                    'reason' => 'Signalement',
                    'moderator_id' => Auth::id(),
                ]);
                break;

            case 'suspend':
                $targetUser->update(['status' => 'suspended']);
                break;

            case 'ban':
                $targetUser->update(['status' => 'banned']);
                break;
        }

        Log::info('Action sur utilisateur suite à signalement:', [
            'user_id' => $targetUser->id,
            'action' => $action,
            'report_id' => $report->id,
            'moderator_id' => Auth::id(),
        ]);
    }

    /**
     * Calculer le temps moyen de résolution
     */
    private function getAverageResolutionTime(): float
    {
        $resolvedReports = Report::whereIn('status', ['resolved', 'dismissed'])
            ->whereNotNull('reviewed_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, reviewed_at)) as avg_hours')
            ->first();

        return round($resolvedReports->avg_hours ?? 0, 1);
    }

    /**
     * Calculer le pourcentage de signalements résolus
     */
    private function getResolvedPercentage(): float
    {
        $totalProcessed = Report::whereIn('status', ['resolved', 'dismissed'])->count();
        $totalReports = Report::count();

        if ($totalReports == 0) {
            return 0;
        }

        return round(($totalProcessed / $totalReports) * 100, 1);
    }

    /**
     * Calculer le temps moyen de première réponse
     */
    private function getAverageResponseTime(): float
    {
        $respondedReports = Report::whereNotNull('reviewed_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, reviewed_at)) as avg_hours')
            ->first();

        return round($respondedReports->avg_hours ?? 0, 1);
    }

    /**
     * Marquer un signalement comme prioritaire (méthode bonus)
     */
    public function togglePriority(Report $report)
    {
        try {
            // Ici, vous pourriez ajouter un champ 'is_priority' à votre table reports
            // Pour l'instant, on utilise les notes de modération pour indiquer la priorité

            $isPriority = strpos($report->moderator_notes ?? '', '[PRIORITÉ]') !== false;

            if ($isPriority) {
                $notes = str_replace('[PRIORITÉ] ', '', $report->moderator_notes ?? '');
            } else {
                $notes = '[PRIORITÉ] ' . ($report->moderator_notes ?? '');
            }

            $report->update(['moderator_notes' => $notes]);

            $message = $isPriority ? 'Priorité retirée' : 'Signalement marqué comme prioritaire';

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de priorité:', [
                'message' => $e->getMessage(),
                'report_id' => $report->id,
                'moderator_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue']);
        }
    }
}
