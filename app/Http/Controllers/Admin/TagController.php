<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Afficher la liste des tags
     */
    public function index(Request $request)
    {
        $query = Tag::withCount('publications');

        // Filtres
        if ($request->filled('suggested')) {
            if ($request->suggested === 'yes') {
                $query->where('is_suggested', true);
            } elseif ($request->suggested === 'no') {
                $query->where('is_suggested', false);
            }
        }

        if ($request->filled('usage')) {
            if ($request->usage === 'used') {
                $query->where('usage_count', '>', 0);
            } elseif ($request->usage === 'unused') {
                $query->where('usage_count', 0);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');

        switch ($sortBy) {
            case 'usage':
                $query->orderBy('usage_count', $sortDirection);
                break;
            case 'created':
                $query->orderBy('created_at', $sortDirection);
                break;
            case 'name':
            default:
                $query->orderBy('name', $sortDirection);
                break;
        }

        $tags = $query->paginate(20);

        return Inertia::render('Tags', [
            'tags' => $tags,
            'filters' => $request->only(['suggested', 'usage', 'search', 'sort', 'direction']),
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
        return Inertia::render('Admin/Tags/Create', [
            'currentUser' => Auth::user()
        ]);
    }

    /**
     * Enregistrer un nouveau tag
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
            'slug' => 'nullable|string|max:255|unique:tags,slug',
            'color' => 'required|string|regex:/^#[A-Fa-f0-9]{6}$/',
            'is_suggested' => 'boolean',
        ], [
            'name.required' => 'Le nom du tag est requis.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'name.unique' => 'Ce nom de tag existe déjà.',
            'slug.max' => 'Le slug ne peut pas dépasser 255 caractères.',
            'slug.unique' => 'Ce slug existe déjà.',
            'color.required' => 'La couleur est requise.',
            'color.regex' => 'La couleur doit être un code hexadécimal valide (ex: #3B82F6).',
        ]);

        try {
            DB::beginTransaction();

            // Générer le slug automatiquement s'il n'est pas fourni
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            // Vérifier l'unicité du slug généré
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Tag::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            $tagData = [
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'color' => $validated['color'],
                'usage_count' => 0,
                'is_suggested' => $validated['is_suggested'] ?? false,
            ];

            Tag::create($tagData);

            DB::commit();

            return redirect()->route('admin.tags.index')
                ->with('success', 'Tag créé avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors de la création du tag:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création du tag'])
                ->withInput();
        }
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Tag $tag)
    {
        return Inertia::render('Tags/Edit', [
            'tag' => $tag,
            'currentUser' => Auth::user()
        ]);
    }

    /**
     * Mettre à jour un tag
     */
    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
            'slug' => 'nullable|string|max:255|unique:tags,slug,' . $tag->id,
            'color' => 'required|string|regex:/^#[A-Fa-f0-9]{6}$/',
            'is_suggested' => 'boolean',
        ], [
            'name.required' => 'Le nom du tag est requis.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'name.unique' => 'Ce nom de tag existe déjà.',
            'slug.max' => 'Le slug ne peut pas dépasser 255 caractères.',
            'slug.unique' => 'Ce slug existe déjà.',
            'color.required' => 'La couleur est requise.',
            'color.regex' => 'La couleur doit être un code hexadécimal valide (ex: #3B82F6).',
        ]);

        try {
            DB::beginTransaction();

            // Générer le slug automatiquement s'il n'est pas fourni ou si le nom a changé
            if (empty($validated['slug']) || ($tag->name !== $validated['name'] && $validated['slug'] === $tag->slug)) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            // Vérifier l'unicité du slug généré (en excluant le tag actuel)
            if ($validated['slug'] !== $tag->slug) {
                $originalSlug = $validated['slug'];
                $counter = 1;
                while (Tag::where('slug', $validated['slug'])->where('id', '!=', $tag->id)->exists()) {
                    $validated['slug'] = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }

            $tagData = [
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'color' => $validated['color'],
                'is_suggested' => $validated['is_suggested'] ?? $tag->is_suggested,
            ];

            $tag->update($tagData);

            DB::commit();

            return redirect()->route('admin.tags.index')
                ->with('success', 'Tag mis à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors de la mise à jour du tag:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'tag_id' => $tag->id,
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour du tag'])
                ->withInput();
        }
    }

    /**
     * Supprimer définitivement un tag
     */
    public function destroy(Tag $tag)
    {
        try {
            DB::beginTransaction();

            // Vérifier si le tag est utilisé dans des publications
            $publicationsCount = $tag->publications()->count();

            if ($publicationsCount > 0) {
                return redirect()->back()
                    ->withErrors(['error' => "Impossible de supprimer ce tag car il est utilisé dans {$publicationsCount} publication(s). Retirez-le d'abord des publications."]);
            }

            // Supprimer le tag
            $tag->delete();

            DB::commit();

            return redirect()->route('admin.tags.index')
                ->with('success', 'Tag supprimé définitivement avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors de la suppression du tag:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'tag_id' => $tag->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la suppression du tag']);
        }
    }

    /**
     * Supprimer plusieurs tags en une fois
     */
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'tag_ids' => 'required|array|min:1',
            'tag_ids.*' => 'exists:tags,id',
        ], [
            'tag_ids.required' => 'Aucun tag sélectionné.',
            'tag_ids.min' => 'Veuillez sélectionner au moins un tag.',
            'tag_ids.*.exists' => 'Un ou plusieurs tags sélectionnés n\'existent pas.',
        ]);

        try {
            DB::beginTransaction();

            $tagIds = $validated['tag_ids'];

            // Vérifier si des tags sont utilisés dans des publications
            $usedTags = Tag::whereIn('id', $tagIds)
                ->whereHas('publications')
                ->with('publications:id')
                ->get();

            if ($usedTags->isNotEmpty()) {
                $usedTagNames = $usedTags->pluck('name')->implode(', ');
                return redirect()->back()
                    ->withErrors(['error' => "Impossible de supprimer les tags suivants car ils sont utilisés dans des publications : {$usedTagNames}"]);
            }

            // Supprimer les tags
            $deletedCount = Tag::whereIn('id', $tagIds)->delete();

            DB::commit();

            return redirect()->route('admin.tags.index')
                ->with('success', "{$deletedCount} tag(s) supprimé(s) avec succès");
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors de la suppression multiple de tags:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'tag_ids' => $validated['tag_ids'] ?? [],
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la suppression des tags']);
        }
    }

    /**
     * Basculer le statut suggéré d'un tag
     */
    public function toggleSuggested(Tag $tag)
    {
        try {
            $tag->update(['is_suggested' => !$tag->is_suggested]);

            $status = $tag->is_suggested ? 'ajouté aux suggestions' : 'retiré des suggestions';
            return redirect()->back()
                ->with('success', "Tag {$status} avec succès");
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut de suggestion du tag:', [
                'message' => $e->getMessage(),
                'tag_id' => $tag->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors du changement de statut']);
        }
    }

    /**
     * Obtenir des suggestions de tags pour l'autocomplétion
     */
    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');

        $tags = Tag::where('name', 'like', "%{$query}%")
            ->orWhere('slug', 'like', "%{$query}%")
            ->orderBy('usage_count', 'desc')
            ->orderBy('name', 'asc')
            ->limit(10)
            ->get(['id', 'name', 'slug', 'color', 'usage_count']);

        return response()->json($tags);
    }

    /**
     * Nettoyer les tags inutilisés
     */
    public function cleanup()
    {
        try {
            DB::beginTransaction();

            // Supprimer les tags avec usage_count = 0 et qui ne sont pas suggérés
            $deletedCount = Tag::where('usage_count', 0)
                ->where('is_suggested', false)
                ->delete();

            DB::commit();

            return redirect()->route('admin.tags.index')
                ->with('success', "{$deletedCount} tag(s) inutilisé(s) supprimé(s) avec succès");
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors du nettoyage des tags:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors du nettoyage des tags']);
        }
    }

    /**
     * Recalculer les compteurs d'utilisation
     */
    public function recalculateUsage()
    {
        try {
            DB::beginTransaction();

            $tags = Tag::withCount('publications')->get();

            foreach ($tags as $tag) {
                $tag->update(['usage_count' => $tag->publications_count]);
            }

            DB::commit();

            return redirect()->route('admin.tags.index')
                ->with('success', 'Compteurs d\'utilisation recalculés avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors du recalcul des compteurs:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors du recalcul']);
        }
    }
}
