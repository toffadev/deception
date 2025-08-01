<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectMedia;
use App\Models\SolidarityProject;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProjectMediaController extends Controller
{
    /**
     * Afficher la liste des médias de projet
     */
    public function index(Request $request)
    {
        $query = ProjectMedia::with(['solidarityProject']);

        // Filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('project_id')) {
            $query->where('solidarity_project_id', $request->project_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('alt_text', 'like', "%{$search}%")
                    ->orWhereHas('solidarityProject', function ($projectQuery) use ($search) {
                        $projectQuery->where('title', 'like', "%{$search}%");
                    });
            });
        }

        $projectMedia = $query->orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(15);
        $projects = SolidarityProject::select('id', 'title')->orderBy('title')->get();

        return Inertia::render('ProjectMedias', [
            'projectMedia' => $projectMedia,
            'projects' => $projects,
            'filters' => $request->only(['type', 'project_id', 'search']),
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
        $projects = SolidarityProject::select('id', 'title')->orderBy('title')->get();

        return Inertia::render('Admin/ProjectMedia/Create', [
            'projects' => $projects,
            'currentUser' => Auth::user()
        ]);
    }

    /**
     * Enregistrer un nouveau média de projet
     */
    public function store(Request $request)
    {
        $rules = [
            'solidarity_project_id' => 'required|exists:solidarity_projects,id',
            'type' => 'required|in:image,video',
            'file' => 'required|file|max:10240', // 10MB max
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'alt_text' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ];

        // Validation spécifique selon le type
        if ($request->type === 'image') {
            $rules['file'] = 'required|file|image|mimes:jpeg,png,jpg,gif,webp|max:5120'; // 5MB pour images
        } elseif ($request->type === 'video') {
            $rules['file'] = 'required|file|mimes:mp4,avi,mov,wmv,webm|max:50240'; // 50MB pour vidéos
        }

        $validated = $request->validate($rules, [
            'solidarity_project_id.required' => 'Le projet de solidarité est requis.',
            'solidarity_project_id.exists' => 'Le projet de solidarité sélectionné n\'existe pas.',
            'type.required' => 'Le type de média est requis.',
            'type.in' => 'Le type de média doit être : image ou vidéo.',
            'file.required' => 'Le fichier est requis.',
            'file.image' => 'Le fichier doit être une image valide.',
            'file.mimes' => 'Le format de fichier n\'est pas supporté.',
            'file.max' => 'Le fichier est trop volumineux.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'alt_text.max' => 'Le texte alternatif ne peut pas dépasser 255 caractères.',
            'sort_order.integer' => 'L\'ordre de tri doit être un nombre entier.',
            'sort_order.min' => 'L\'ordre de tri ne peut pas être négatif.',
        ]);

        try {
            DB::beginTransaction();

            // Upload du fichier
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('project-media/' . $validated['type'] . 's', $fileName, 'public');

            // Déterminer l'ordre de tri si non fourni
            if (!isset($validated['sort_order'])) {
                $maxOrder = ProjectMedia::where('solidarity_project_id', $validated['solidarity_project_id'])
                    ->max('sort_order');
                $validated['sort_order'] = ($maxOrder ?? -1) + 1;
            }

            // Préparation des données
            $mediaData = [
                'solidarity_project_id' => $validated['solidarity_project_id'],
                'type' => $validated['type'],
                'file_path' => $filePath,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'alt_text' => $validated['alt_text'],
                'sort_order' => $validated['sort_order'],
            ];

            ProjectMedia::create($mediaData);

            DB::commit();

            return redirect()->route('admin.project-media.index')
                ->with('success', 'Média de projet créé avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            // Supprimer le fichier en cas d'erreur
            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            Log::error('Erreur lors de la création du média de projet:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'request_data' => $request->except(['file'])
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création du média'])
                ->withInput();
        }
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(ProjectMedia $projectMedia)
    {
        $projects = SolidarityProject::select('id', 'title')->orderBy('title')->get();
        $projectMedia->load('solidarityProject');

        return Inertia::render('Admin/ProjectMedia/Edit', [
            'projectMedia' => $projectMedia,
            'projects' => $projects,
            'currentUser' => Auth::user()
        ]);
    }

    /**
     * Mettre à jour un média de projet
     */
    public function update(Request $request, ProjectMedia $projectMedia)
    {
        $rules = [
            'solidarity_project_id' => 'required|exists:solidarity_projects,id',
            'type' => 'required|in:image,video',
            'file' => 'nullable|file|max:10240', // 10MB max, optionnel pour update
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'alt_text' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ];

        // Validation spécifique selon le type si un nouveau fichier est fourni
        if ($request->hasFile('file')) {
            if ($request->type === 'image') {
                $rules['file'] = 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:5120';
            } elseif ($request->type === 'video') {
                $rules['file'] = 'nullable|file|mimes:mp4,avi,mov,wmv,webm|max:50240';
            }
        }

        $validated = $request->validate($rules, [
            'solidarity_project_id.required' => 'Le projet de solidarité est requis.',
            'solidarity_project_id.exists' => 'Le projet de solidarité sélectionné n\'existe pas.',
            'type.required' => 'Le type de média est requis.',
            'type.in' => 'Le type de média doit être : image ou vidéo.',
            'file.image' => 'Le fichier doit être une image valide.',
            'file.mimes' => 'Le format de fichier n\'est pas supporté.',
            'file.max' => 'Le fichier est trop volumineux.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'alt_text.max' => 'Le texte alternatif ne peut pas dépasser 255 caractères.',
            'sort_order.integer' => 'L\'ordre de tri doit être un nombre entier.',
            'sort_order.min' => 'L\'ordre de tri ne peut pas être négatif.',
        ]);

        try {
            DB::beginTransaction();

            $mediaData = [
                'solidarity_project_id' => $validated['solidarity_project_id'],
                'type' => $validated['type'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'alt_text' => $validated['alt_text'],
                'sort_order' => $validated['sort_order'] ?? $projectMedia->sort_order,
            ];

            // Gestion du nouveau fichier si fourni
            if ($request->hasFile('file')) {
                // Supprimer l'ancien fichier
                if ($projectMedia->file_path && Storage::disk('public')->exists($projectMedia->file_path)) {
                    Storage::disk('public')->delete($projectMedia->file_path);
                }

                // Upload du nouveau fichier
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('project-media/' . $validated['type'] . 's', $fileName, 'public');
                $mediaData['file_path'] = $filePath;
            }

            $projectMedia->update($mediaData);

            DB::commit();

            return redirect()->route('admin.project-media.index')
                ->with('success', 'Média de projet mis à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            // Supprimer le nouveau fichier en cas d'erreur
            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            Log::error('Erreur lors de la mise à jour du média de projet:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'project_media_id' => $projectMedia->id,
                'user_id' => Auth::id(),
                'request_data' => $request->except(['file'])
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour du média'])
                ->withInput();
        }
    }

    /**
     * Supprimer définitivement un média de projet
     */
    public function destroy(ProjectMedia $projectMedia)
    {
        try {
            DB::beginTransaction();

            // Supprimer le fichier du stockage
            if ($projectMedia->file_path && Storage::disk('public')->exists($projectMedia->file_path)) {
                Storage::disk('public')->delete($projectMedia->file_path);
            }

            // Supprimer l'enregistrement de la base de données
            $projectMedia->delete();

            DB::commit();

            return redirect()->route('admin.project-media.index')
                ->with('success', 'Média de projet supprimé avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors de la suppression du média de projet:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'project_media_id' => $projectMedia->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la suppression du média']);
        }
    }

    /**
     * Mettre à jour l'ordre des médias
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:project_media,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['items'] as $item) {
                ProjectMedia::where('id', $item['id'])
                    ->update(['sort_order' => $item['sort_order']]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ordre des médias mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors de la mise à jour de l\'ordre des médias:', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
                'items' => $validated['items']
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour de l\'ordre'
            ], 500);
        }
    }
}
