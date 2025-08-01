<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinancialReport;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class FinancialReportController extends Controller
{
    /**
     * Afficher la liste des rapports financiers
     */
    public function index(Request $request)
    {
        $query = FinancialReport::query();

        // Filtres
        if ($request->filled('period_type')) {
            $query->where('period_type', $request->period_type);
        }

        if ($request->filled('is_published')) {
            $published = $request->is_published === 'true';
            $query->where('is_published', $published);
        }

        if ($request->filled('year')) {
            $query->whereYear('period_start', $request->year);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $reports = $query->orderBy('period_start', 'desc')->paginate(15);

        // Années disponibles pour le filtre
        $availableYears = FinancialReport::selectRaw('YEAR(period_start) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return Inertia::render('Admin/FinancialReports/Index', [
            'reports' => $reports,
            'availableYears' => $availableYears,
            'filters' => $request->only(['period_type', 'is_published', 'year', 'search']),
            'flash' => [
                'success' => session('success'),
                'error' => session('error')
            ]
        ]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return Inertia::render('Admin/FinancialReports/Create', [
            'currentUser' => Auth::user(),
            'currentDate' => now()->format('Y-m-d')
        ]);
    }

    /**
     * Enregistrer un nouveau rapport financier
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'period_type' => 'required|in:monthly,quarterly,yearly',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'total_donations' => 'required|numeric|min:0',
            'total_expenses' => 'required|numeric|min:0',
            'administrative_costs' => 'required|numeric|min:0|lte:total_expenses',
            'breakdown' => 'required|array',
            'breakdown.*.category' => 'required|string|max:255',
            'breakdown.*.amount' => 'required|numeric|min:0',
            'breakdown.*.description' => 'nullable|string',
            'report_file' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls|max:10240',
            'is_published' => 'boolean',
        ];

        $validated = $request->validate($rules, [
            'title.required' => 'Le titre est requis.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description est requise.',
            'period_type.required' => 'Le type de période est requis.',
            'period_type.in' => 'Le type de période doit être : mensuel, trimestriel ou annuel.',
            'period_start.required' => 'La date de début est requise.',
            'period_start.date' => 'La date de début doit être une date valide.',
            'period_end.required' => 'La date de fin est requise.',
            'period_end.date' => 'La date de fin doit être une date valide.',
            'period_end.after' => 'La date de fin doit être postérieure à la date de début.',
            'total_donations.required' => 'Le total des dons est requis.',
            'total_donations.numeric' => 'Le total des dons doit être un nombre.',
            'total_donations.min' => 'Le total des dons ne peut pas être négatif.',
            'total_expenses.required' => 'Le total des dépenses est requis.',
            'total_expenses.numeric' => 'Le total des dépenses doit être un nombre.',
            'total_expenses.min' => 'Le total des dépenses ne peut pas être négatif.',
            'administrative_costs.required' => 'Les coûts administratifs sont requis.',
            'administrative_costs.numeric' => 'Les coûts administratifs doivent être un nombre.',
            'administrative_costs.min' => 'Les coûts administratifs ne peuvent pas être négatifs.',
            'administrative_costs.lte' => 'Les coûts administratifs ne peuvent pas dépasser le total des dépenses.',
            'breakdown.required' => 'Le détail des dépenses est requis.',
            'breakdown.array' => 'Le détail des dépenses doit être un tableau.',
            'breakdown.*.category.required' => 'La catégorie de dépense est requise.',
            'breakdown.*.amount.required' => 'Le montant de la dépense est requis.',
            'breakdown.*.amount.numeric' => 'Le montant de la dépense doit être un nombre.',
            'breakdown.*.amount.min' => 'Le montant de la dépense ne peut pas être négatif.',
            'report_file.file' => 'Le fichier de rapport doit être un fichier valide.',
            'report_file.mimes' => 'Le fichier de rapport doit être au format PDF, DOC, DOCX, XLSX ou XLS.',
            'report_file.max' => 'Le fichier de rapport ne peut pas dépasser 10 MB.',
        ]);

        try {
            DB::beginTransaction();

            // Validation personnalisée : vérifier que la somme du breakdown correspond aux dépenses
            $breakdownTotal = collect($validated['breakdown'])->sum('amount');
            if (abs($breakdownTotal - $validated['total_expenses']) > 0.01) {
                return redirect()->back()
                    ->withErrors(['breakdown' => 'La somme du détail des dépenses doit correspondre au total des dépenses.'])
                    ->withInput();
            }

            // Validation des périodes selon le type
            $this->validatePeriodDates($validated['period_type'], $validated['period_start'], $validated['period_end']);

            // Upload du fichier si fourni
            $filePath = null;
            if ($request->hasFile('report_file')) {
                $file = $request->file('report_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('financial-reports', $fileName, 'public');
            }

            // Préparation des données
            $reportData = [
                'title' => $validated['title'],
                'description' => $validated['description'],
                'period_type' => $validated['period_type'],
                'period_start' => $validated['period_start'],
                'period_end' => $validated['period_end'],
                'total_donations' => $validated['total_donations'],
                'total_expenses' => $validated['total_expenses'],
                'administrative_costs' => $validated['administrative_costs'],
                'breakdown' => $validated['breakdown'],
                'report_file_path' => $filePath,
                'is_published' => $validated['is_published'] ?? false,
                'published_at' => ($validated['is_published'] ?? false) ? now() : null,
            ];

            FinancialReport::create($reportData);

            DB::commit();

            return redirect()->route('admin.financial-reports.index')
                ->with('success', 'Rapport financier créé avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            // Supprimer le fichier en cas d'erreur
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            Log::error('Erreur lors de la création du rapport financier:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'request_data' => $request->except(['report_file'])
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création du rapport financier'])
                ->withInput();
        }
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(FinancialReport $financialReport)
    {
        return Inertia::render('Admin/FinancialReports/Edit', [
            'financialReport' => $financialReport,
            'currentUser' => Auth::user()
        ]);
    }

    /**
     * Mettre à jour un rapport financier
     */
    public function update(Request $request, FinancialReport $financialReport)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'period_type' => 'required|in:monthly,quarterly,yearly',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'total_donations' => 'required|numeric|min:0',
            'total_expenses' => 'required|numeric|min:0',
            'administrative_costs' => 'required|numeric|min:0|lte:total_expenses',
            'breakdown' => 'required|array',
            'breakdown.*.category' => 'required|string|max:255',
            'breakdown.*.amount' => 'required|numeric|min:0',
            'breakdown.*.description' => 'nullable|string',
            'report_file' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls|max:10240',
            'is_published' => 'boolean',
        ];

        $validated = $request->validate($rules, [
            'title.required' => 'Le titre est requis.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description est requise.',
            'period_type.required' => 'Le type de période est requis.',
            'period_type.in' => 'Le type de période doit être : mensuel, trimestriel ou annuel.',
            'period_start.required' => 'La date de début est requise.',
            'period_start.date' => 'La date de début doit être une date valide.',
            'period_end.required' => 'La date de fin est requise.',
            'period_end.date' => 'La date de fin doit être une date valide.',
            'period_end.after' => 'La date de fin doit être postérieure à la date de début.',
            'total_donations.required' => 'Le total des dons est requis.',
            'total_donations.numeric' => 'Le total des dons doit être un nombre.',
            'total_donations.min' => 'Le total des dons ne peut pas être négatif.',
            'total_expenses.required' => 'Le total des dépenses est requis.',
            'total_expenses.numeric' => 'Le total des dépenses doit être un nombre.',
            'total_expenses.min' => 'Le total des dépenses ne peut pas être négatif.',
            'administrative_costs.required' => 'Les coûts administratifs sont requis.',
            'administrative_costs.numeric' => 'Les coûts administratifs doivent être un nombre.',
            'administrative_costs.min' => 'Les coûts administratifs ne peuvent pas être négatifs.',
            'administrative_costs.lte' => 'Les coûts administratifs ne peuvent pas dépasser le total des dépenses.',
            'breakdown.required' => 'Le détail des dépenses est requis.',
            'breakdown.array' => 'Le détail des dépenses doit être un tableau.',
            'breakdown.*.category.required' => 'La catégorie de dépense est requise.',
            'breakdown.*.amount.required' => 'Le montant de la dépense est requis.',
            'breakdown.*.amount.numeric' => 'Le montant de la dépense doit être un nombre.',
            'breakdown.*.amount.min' => 'Le montant de la dépense ne peut pas être négatif.',
            'report_file.file' => 'Le fichier de rapport doit être un fichier valide.',
            'report_file.mimes' => 'Le fichier de rapport doit être au format PDF, DOC, DOCX, XLSX ou XLS.',
            'report_file.max' => 'Le fichier de rapport ne peut pas dépasser 10 MB.',
        ]);

        try {
            DB::beginTransaction();

            // Validation personnalisée : vérifier que la somme du breakdown correspond aux dépenses
            $breakdownTotal = collect($validated['breakdown'])->sum('amount');
            if (abs($breakdownTotal - $validated['total_expenses']) > 0.01) {
                return redirect()->back()
                    ->withErrors(['breakdown' => 'La somme du détail des dépenses doit correspondre au total des dépenses.'])
                    ->withInput();
            }

            // Validation des périodes selon le type
            $this->validatePeriodDates($validated['period_type'], $validated['period_start'], $validated['period_end']);

            // Préparation des données
            $reportData = [
                'title' => $validated['title'],
                'description' => $validated['description'],
                'period_type' => $validated['period_type'],
                'period_start' => $validated['period_start'],
                'period_end' => $validated['period_end'],
                'total_donations' => $validated['total_donations'],
                'total_expenses' => $validated['total_expenses'],
                'administrative_costs' => $validated['administrative_costs'],
                'breakdown' => $validated['breakdown'],
                'is_published' => $validated['is_published'] ?? false,
            ];

            // Gestion de la publication
            $wasPublished = $financialReport->is_published;
            $isBeingPublished = $validated['is_published'] ?? false;

            if (!$wasPublished && $isBeingPublished) {
                $reportData['published_at'] = now();
            } elseif ($wasPublished && !$isBeingPublished) {
                $reportData['published_at'] = null;
            }

            // Gestion du fichier
            if ($request->hasFile('report_file')) {
                // Supprimer l'ancien fichier
                if ($financialReport->report_file_path && Storage::disk('public')->exists($financialReport->report_file_path)) {
                    Storage::disk('public')->delete($financialReport->report_file_path);
                }

                // Upload du nouveau fichier
                $file = $request->file('report_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('financial-reports', $fileName, 'public');
                $reportData['report_file_path'] = $filePath;
            }

            $financialReport->update($reportData);

            DB::commit();

            return redirect()->route('admin.financial-reports.index')
                ->with('success', 'Rapport financier mis à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            // Supprimer le nouveau fichier en cas d'erreur
            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            Log::error('Erreur lors de la mise à jour du rapport financier:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'financial_report_id' => $financialReport->id,
                'user_id' => Auth::id(),
                'request_data' => $request->except(['report_file'])
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour du rapport financier'])
                ->withInput();
        }
    }

    /**
     * Supprimer définitivement un rapport financier
     */
    public function destroy(FinancialReport $financialReport)
    {
        try {
            DB::beginTransaction();

            // Supprimer le fichier du stockage
            if ($financialReport->report_file_path && Storage::disk('public')->exists($financialReport->report_file_path)) {
                Storage::disk('public')->delete($financialReport->report_file_path);
            }

            // Supprimer l'enregistrement de la base de données
            $financialReport->delete();

            DB::commit();

            return redirect()->route('admin.financial-reports.index')
                ->with('success', 'Rapport financier supprimé avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors de la suppression du rapport financier:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'financial_report_id' => $financialReport->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la suppression du rapport financier']);
        }
    }

    /**
     * Publier ou dépublier un rapport financier
     */
    public function togglePublication(FinancialReport $financialReport)
    {
        try {
            $isPublished = !$financialReport->is_published;

            $financialReport->update([
                'is_published' => $isPublished,
                'published_at' => $isPublished ? now() : null,
            ]);

            $message = $isPublished
                ? 'Rapport financier publié avec succès'
                : 'Rapport financier dépublié avec succès';

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut de publication:', [
                'message' => $e->getMessage(),
                'financial_report_id' => $financialReport->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors du changement de statut']);
        }
    }

    /**
     * Afficher les statistiques des rapports financiers
     */
    public function statistics(Request $request)
    {
        try {
            $year = $request->get('year', now()->year);

            // Statistiques générales pour l'année
            $reports = FinancialReport::whereYear('period_start', $year)->get();

            $statistics = [
                'total_reports' => $reports->count(),
                'published_reports' => $reports->where('is_published', true)->count(),
                'total_donations' => $reports->sum('total_donations'),
                'total_expenses' => $reports->sum('total_expenses'),
                'total_administrative_costs' => $reports->sum('administrative_costs'),
                'average_efficiency_rate' => $reports->avg(function ($report) {
                    return $report->efficiency_rate;
                }),
                'net_amount' => $reports->sum('total_donations') - $reports->sum('total_expenses'),
            ];

            // Données mensuelles pour les graphiques
            $monthlyData = [];
            for ($month = 1; $month <= 12; $month++) {
                $monthReports = $reports->filter(function ($report) use ($month) {
                    return Carbon::parse($report->period_start)->month === $month;
                });

                $monthlyData[] = [
                    'month' => $month,
                    'month_name' => Carbon::create(null, $month)->translatedFormat('F'),
                    'donations' => $monthReports->sum('total_donations'),
                    'expenses' => $monthReports->sum('total_expenses'),
                    'administrative_costs' => $monthReports->sum('administrative_costs'),
                    'reports_count' => $monthReports->count(),
                ];
            }

            // Répartition par type de période
            $periodTypeStats = $reports->groupBy('period_type')->map(function ($group, $type) {
                return [
                    'type' => $type,
                    'count' => $group->count(),
                    'total_donations' => $group->sum('total_donations'),
                    'total_expenses' => $group->sum('total_expenses'),
                ];
            })->values();

            // Années disponibles
            $availableYears = FinancialReport::selectRaw('YEAR(period_start) as year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year');

            return Inertia::render('Admin/FinancialReports/Statistics', [
                'statistics' => $statistics,
                'monthlyData' => $monthlyData,
                'periodTypeStats' => $periodTypeStats,
                'availableYears' => $availableYears,
                'selectedYear' => $year,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du calcul des statistiques:', [
                'message' => $e->getMessage(),
                'year' => $year,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors du calcul des statistiques']);
        }
    }

    /**
     * Valider les dates de période selon le type
     */
    private function validatePeriodDates(string $periodType, string $periodStart, string $periodEnd): void
    {
        $start = Carbon::parse($periodStart);
        $end = Carbon::parse($periodEnd);

        switch ($periodType) {
            case 'monthly':
                if (!$start->isSameMonth($end)) {
                    throw new \InvalidArgumentException('Pour un rapport mensuel, les dates de début et de fin doivent être dans le même mois.');
                }
                break;

            case 'quarterly':
                $quarterStart = $start->copy()->startOfQuarter();
                $quarterEnd = $start->copy()->endOfQuarter();

                if (!$start->isSameDay($quarterStart) || !$end->isSameDay($quarterEnd)) {
                    throw new \InvalidArgumentException('Pour un rapport trimestriel, les dates doivent correspondre exactement au début et à la fin d\'un trimestre.');
                }
                break;

            case 'yearly':
                if (!$start->isSameYear($end) || !$start->isStartOfYear() || !$end->isEndOfYear()) {
                    throw new \InvalidArgumentException('Pour un rapport annuel, les dates doivent correspondre exactement au début et à la fin d\'une année.');
                }
                break;
        }
    }

    /**
     * Télécharger le fichier de rapport
     */
    public function downloadReport(FinancialReport $financialReport)
    {
        try {
            if (!$financialReport->report_file_path || !Storage::disk('public')->exists($financialReport->report_file_path)) {
                return redirect()->back()
                    ->withErrors(['error' => 'Le fichier de rapport n\'existe pas']);
            }

            return Storage::disk('public')->download(
                $financialReport->report_file_path,
                'Rapport_' . $financialReport->title . '_' . $financialReport->period_start->format('Y-m') . '.pdf'
            );
        } catch (\Exception $e) {
            Log::error('Erreur lors du téléchargement du rapport:', [
                'message' => $e->getMessage(),
                'financial_report_id' => $financialReport->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors du téléchargement']);
        }
    }
}
