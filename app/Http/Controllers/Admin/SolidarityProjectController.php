<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SolidarityProject;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SolidarityProjectController extends Controller
{
    /**
     * Afficher la liste des projets de solidarité
     */
    public function index(Request $request)
    {
        $query = SolidarityProject::withCount('donations');

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('featured')) {
            if ($request->featured === 'yes') {
                $query->where('is_featured', true);
            } elseif ($request->featured === 'no') {
                $query->where('is_featured', false);
            }
        }

        if ($request->filled('progress')) {
            switch ($request->progress) {
                case 'not_started':
                    $query->where('current_amount', 0);
                    break;
                case 'in_progress':
                    $query->where('current_amount', '>', 0)
                        ->whereColumn('current_amount', '<', 'target_amount');
                    break;
                case 'completed':
                    $query->whereColumn('current_amount', '>=', 'target_amount');
                    break;
            }
        }

        if ($request->filled('date_range')) {
            $now = Carbon::now();
            switch ($request->date_range) {
                case 'upcoming':
                    $query->where('start_date', '>', $now);
                    break;
                case 'current':
                    $query->where('start_date', '<=', $now)
                        ->where(function ($q) use ($now) {
                            $q->whereNull('end_date')
                                ->orWhere('end_date', '>=', $now);
                        });
                    break;
                case 'expired':
                    $query->where('end_date', '<', $now);
                    break;
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('impact_description', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        switch ($sortBy) {
            case 'progress':
                $query->selectRaw('*, (current_amount / target_amount * 100) as progress_percentage')
                    ->orderBy('progress_percentage', $sortDirection);
                break;
            case 'target_amount':
                $query->orderBy('target_amount', $sortDirection);
                break;
            case 'current_amount':
                $query->orderBy('current_amount', $sortDirection);
                break;
            case 'start_date':
                $query->orderBy('start_date', $sortDirection);
                break;
            case 'end_date':
                $query->orderBy('end_date', $sortDirection);
                break;
            case 'title':
                $query->orderBy('title', $sortDirection);
                break;
            default:
                $query->orderBy('created_at', $sortDirection);
                break;
        }

        $projects = $query->paginate(15);

        // Ajouter les attributs calculés
        $projects->getCollection()->transform(function ($project) {
            $project->progress_percentage = $project->getProgressPercentageAttribute();
            $project->remaining_amount = $project->getRemainingAmountAttribute();
            return $project;
        });

        return Inertia::render('SolidarityProjects', [ // Changé de 'Admin/SolidarityProjects/Index' à 'SolidarityProjects'
            'projects' => $projects,
            'statuses' => [
                'planned' => 'Planifié',
                'active' => 'Actif',
                'completed' => 'Terminé',
                'paused' => 'En pause'
            ],
            'currencies' => ['EUR', 'USD', 'XOF'],
            'filters' => $request->only(['status', 'featured', 'progress', 'date_range', 'search', 'sort', 'direction']),
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
        return Inertia::render('Admin/SolidarityProjects/Create', [
            'statuses' => [
                'planned' => 'Planifié',
                'active' => 'Actif',
                'completed' => 'Terminé',
                'paused' => 'En pause'
            ],
            'currencies' => ['EUR', 'USD', 'XOF'],
            'currentUser' => Auth::user()
        ]);
    }

    /**
     * Enregistrer un nouveau projet de solidarité
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:0|max:999999999999.99',
            'current_amount' => 'nullable|numeric|min:0|max:999999999999.99',
            'currency' => 'required|string|size:3|in:EUR,USD,XOF',
            'status' => 'required|in:planned,active,completed,paused',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'beneficiaries_info' => 'nullable|array',
            'beneficiaries_info.*.name' => 'required_with:beneficiaries_info|string|max:255',
            'beneficiaries_info.*.description' => 'nullable|string',
            'beneficiaries_info.*.contact' => 'nullable|string|max:255',
            'impact_description' => 'nullable|string',
            'is_featured' => 'boolean',
        ], [
            'title.required' => 'Le titre est requis.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description est requise.',
            'target_amount.required' => 'Le montant cible est requis.',
            'target_amount.numeric' => 'Le montant cible doit être un nombre.',
            'target_amount.min' => 'Le montant cible ne peut pas être négatif.',
            'target_amount.max' => 'Le montant cible est trop élevé.',
            'current_amount.numeric' => 'Le montant actuel doit être un nombre.',
            'current_amount.min' => 'Le montant actuel ne peut pas être négatif.',
            'currency.required' => 'La devise est requise.',
            'currency.size' => 'La devise doit faire exactement 3 caractères.',
            'currency.in' => 'La devise sélectionnée n\'est pas valide.',
            'status.required' => 'Le statut est requis.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.',
            'start_date.required' => 'La date de début est requise.',
            'start_date.date' => 'La date de début doit être une date valide.',
            'start_date.after_or_equal' => 'La date de début ne peut pas être dans le passé.',
            'end_date.date' => 'La date de fin doit être une date valide.',
            'end_date.after' => 'La date de fin doit être postérieure à la date de début.',
            'featured_image.image' => 'Le fichier doit être une image.',
            'featured_image.mimes' => 'L\'image doit être au format: jpeg, png, jpg, gif, webp.',
            'featured_image.max' => 'L\'image ne peut pas dépasser 5MB.',
            'beneficiaries_info.*.name.required_with' => 'Le nom du bénéficiaire est requis.',
            'beneficiaries_info.*.name.max' => 'Le nom du bénéficiaire ne peut pas dépasser 255 caractères.',
        ]);

        try {
            DB::beginTransaction();

            // Gestion de l'upload de l'image
            $imagePath = null;
            if ($request->hasFile('featured_image')) {
                $imagePath = $request->file('featured_image')->store('solidarity-projects/images', 'public');
            }

            $projectData = [
                'title' => $validated['title'],
                'description' => $validated['description'],
                'target_amount' => $validated['target_amount'],
                'current_amount' => $validated['current_amount'] ?? 0,
                'currency' => $validated['currency'],
                'status' => $validated['status'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'] ?? null,
                'featured_image_path' => $imagePath,
                'beneficiaries_info' => $validated['beneficiaries_info'] ?? null,
                'impact_description' => $validated['impact_description'] ?? null,
                'is_featured' => $validated['is_featured'] ?? false,
            ];

            SolidarityProject::create($projectData);

            DB::commit();

            return redirect()->route('admin.solidarity-projects.index')
                ->with('success', 'Projet de solidarité créé avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            // Supprimer le fichier uploadé en cas d'erreur
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            Log::error('Erreur lors de la création du projet de solidarité:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'request_data' => $request->except('featured_image')
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création du projet'])
                ->withInput();
        }
    }

    /**
     * Afficher un projet spécifique
     */
    public function show(SolidarityProject $solidarityProject)
    {
        $solidarityProject->load(['donations' => function ($query) {
            $query->with('user')->where('status', 'completed')->latest()->take(10);
        }]);

        // Ajouter les attributs calculés
        $solidarityProject->progress_percentage = $solidarityProject->getProgressPercentageAttribute();
        $solidarityProject->remaining_amount = $solidarityProject->getRemainingAmountAttribute();
        $solidarityProject->donations_count = $solidarityProject->donations()->where('status', 'completed')->count();
        $solidarityProject->total_donations = $solidarityProject->donations()->where('status', 'completed')->sum('amount');

        return Inertia::render('Admin/SolidarityProjects/Show', [
            'project' => $solidarityProject
        ]);
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(SolidarityProject $solidarityProject)
    {
        return Inertia::render('Admin/SolidarityProjects/Edit', [
            'project' => $solidarityProject,
            'statuses' => [
                'planned' => 'Planifié',
                'active' => 'Actif',
                'completed' => 'Terminé',
                'paused' => 'En pause'
            ],
            'currencies' => ['EUR', 'USD', 'XOF'],
            'currentUser' => Auth::user()
        ]);
    }

    /**
     * Mettre à jour un projet
     */
    public function update(Request $request, SolidarityProject $solidarityProject)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:0|max:999999999999.99',
            'current_amount' => 'nullable|numeric|min:0|max:999999999999.99',
            'currency' => 'required|string|size:3|in:EUR,USD,XOF',
            'status' => 'required|in:planned,active,completed,paused',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'remove_image' => 'boolean',
            'beneficiaries_info' => 'nullable|array',
            'beneficiaries_info.*.name' => 'required_with:beneficiaries_info|string|max:255',
            'beneficiaries_info.*.description' => 'nullable|string',
            'beneficiaries_info.*.contact' => 'nullable|string|max:255',
            'impact_description' => 'nullable|string',
            'is_featured' => 'boolean',
        ], [
            'title.required' => 'Le titre est requis.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description est requise.',
            'target_amount.required' => 'Le montant cible est requis.',
            'target_amount.numeric' => 'Le montant cible doit être un nombre.',
            'target_amount.min' => 'Le montant cible ne peut pas être négatif.',
            'current_amount.numeric' => 'Le montant actuel doit être un nombre.',
            'current_amount.min' => 'Le montant actuel ne peut pas être négatif.',
            'currency.required' => 'La devise est requise.',
            'currency.size' => 'La devise doit faire exactement 3 caractères.',
            'status.required' => 'Le statut est requis.',
            'start_date.required' => 'La date de début est requise.',
            'end_date.after' => 'La date de fin doit être postérieure à la date de début.',
            'featured_image.image' => 'Le fichier doit être une image.',
            'featured_image.max' => 'L\'image ne peut pas dépasser 5MB.',
        ]);

        try {
            DB::beginTransaction();

            $oldImagePath = $solidarityProject->featured_image_path;
            $imagePath = $oldImagePath;

            // Gestion de la suppression de l'image
            if ($validated['remove_image'] ?? false) {
                $imagePath = null;
            }

            // Gestion de l'upload d'une nouvelle image
            if ($request->hasFile('featured_image')) {
                $imagePath = $request->file('featured_image')->store('solidarity-projects/images', 'public');
            }

            $projectData = [
                'title' => $validated['title'],
                'description' => $validated['description'],
                'target_amount' => $validated['target_amount'],
                'current_amount' => $validated['current_amount'] ?? $solidarityProject->current_amount,
                'currency' => $validated['currency'],
                'status' => $validated['status'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'] ?? null,
                'featured_image_path' => $imagePath,
                'beneficiaries_info' => $validated['beneficiaries_info'] ?? null,
                'impact_description' => $validated['impact_description'] ?? null,
                'is_featured' => $validated['is_featured'] ?? $solidarityProject->is_featured,
            ];

            $solidarityProject->update($projectData);

            // Supprimer l'ancienne image si nécessaire
            if ($oldImagePath && $oldImagePath !== $imagePath && Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }

            DB::commit();

            return redirect()->route('admin.solidarity-projects.index')
                ->with('success', 'Projet de solidarité mis à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            // Supprimer la nouvelle image en cas d'erreur
            if ($request->hasFile('featured_image') && $imagePath && $imagePath !== $oldImagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            Log::error('Erreur lors de la mise à jour du projet de solidarité:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'project_id' => $solidarityProject->id,
                'user_id' => Auth::id(),
                'request_data' => $request->except('featured_image')
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour du projet'])
                ->withInput();
        }
    }

    /**
     * Supprimer définitivement un projet
     */
    public function destroy(SolidarityProject $solidarityProject)
    {
        try {
            DB::beginTransaction();

            // Vérifier s'il y a des donations associées
            $donationsCount = $solidarityProject->donations()->count();

            if ($donationsCount > 0) {
                return redirect()->back()
                    ->withErrors(['error' => "Impossible de supprimer ce projet car il a {$donationsCount} donation(s) associée(s). Veuillez d'abord gérer les donations."]);
            }

            $imagePath = $solidarityProject->featured_image_path;

            // Supprimer le projet
            $solidarityProject->delete();

            // Supprimer l'image du stockage
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            DB::commit();

            return redirect()->route('admin.solidarity-projects.index')
                ->with('success', 'Projet de solidarité supprimé définitivement avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors de la suppression du projet de solidarité:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'project_id' => $solidarityProject->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la suppression du projet']);
        }
    }

    /**
     * Basculer le statut mis en avant
     */
    public function toggleFeatured(SolidarityProject $solidarityProject)
    {
        try {
            $solidarityProject->update(['is_featured' => !$solidarityProject->is_featured]);

            $status = $solidarityProject->is_featured ? 'mis en avant' : 'retiré de la mise en avant';
            return redirect()->back()
                ->with('success', "Projet {$status} avec succès");
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut de mise en avant:', [
                'message' => $e->getMessage(),
                'project_id' => $solidarityProject->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors du changement de statut']);
        }
    }

    /**
     * Changer le statut d'un projet
     */
    public function updateStatus(Request $request, SolidarityProject $solidarityProject)
    {
        $validated = $request->validate([
            'status' => 'required|in:planned,active,completed,paused',
        ]);

        try {
            $oldStatus = $solidarityProject->status;
            $solidarityProject->update(['status' => $validated['status']]);

            $statusLabels = [
                'planned' => 'planifié',
                'active' => 'actif',
                'completed' => 'terminé',
                'paused' => 'en pause'
            ];

            return redirect()->back()
                ->with('success', "Statut du projet changé de '{$statusLabels[$oldStatus]}' à '{$statusLabels[$validated['status']]}'");
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut du projet:', [
                'message' => $e->getMessage(),
                'project_id' => $solidarityProject->id,
                'status' => $validated['status'],
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors du changement de statut']);
        }
    }

    /**
     * Recalculer le montant actuel basé sur les donations
     */
    public function recalculateAmount(SolidarityProject $solidarityProject)
    {
        try {
            $oldAmount = $solidarityProject->current_amount;
            $solidarityProject->updateCurrentAmount();
            $newAmount = $solidarityProject->fresh()->current_amount;

            return redirect()->back()
                ->with('success', "Montant recalculé: {$oldAmount} → {$newAmount} {$solidarityProject->currency}");
        } catch (\Exception $e) {
            Log::error('Erreur lors du recalcul du montant:', [
                'message' => $e->getMessage(),
                'project_id' => $solidarityProject->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors du recalcul']);
        }
    }

    /**
     * Obtenir les statistiques des projets
     */
    public function statistics()
    {
        try {
            $stats = [
                'total_projects' => SolidarityProject::count(),
                'active_projects' => SolidarityProject::where('status', 'active')->count(),
                'completed_projects' => SolidarityProject::where('status', 'completed')->count(),
                'total_target_amount' => SolidarityProject::sum('target_amount'),
                'total_raised_amount' => SolidarityProject::sum('current_amount'),
                'featured_projects' => SolidarityProject::where('is_featured', true)->count(),
                'projects_by_status' => SolidarityProject::selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->pluck('count', 'status'),
                'average_progress' => SolidarityProject::selectRaw('AVG(current_amount / target_amount * 100) as avg_progress')
                    ->where('target_amount', '>', 0)
                    ->value('avg_progress'),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des statistiques:', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json(['error' => 'Erreur lors de la récupération des statistiques'], 500);
        }
    }
}
