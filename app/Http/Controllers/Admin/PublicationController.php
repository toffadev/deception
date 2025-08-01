<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PublicationController extends Controller
{
    /**
     * Afficher la liste des publications
     */
    public function index(Request $request)
    {
        $query = Publication::with(['user', 'moderator'])
            ->withTrashed(); // Inclure les publications supprimées (soft delete)

        // Filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('custom_author_name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('pseudo', 'like', "%{$search}%");
                    });
            });
        }

        $publications = $query->orderBy('created_at', 'desc')->paginate(15);

        return Inertia::render('Publications', [
            'publications' => $publications,
            'filters' => $request->only(['type', 'status', 'search']),
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
        $users = User::select('id', 'pseudo')->orderBy('pseudo')->get();

        return Inertia::render('Admin/Publications/Create', [
            'users' => $users,
            'currentUser' => Auth::user()
        ]);
    }

    /**
     * Enregistrer une nouvelle publication
     */
    public function store(Request $request)
    {
        $rules = [
            'author_type' => 'required|in:current_user,existing_user,custom,anonymous',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:testimony,reflection,poetry',
            'status' => 'required|in:draft,published,moderated,hidden',
            'auto_tags' => 'nullable|array',
            'moderation_reason' => 'nullable|string|required_if:status,moderated,hidden',
        ];

        // Ajout de règles conditionnelles selon le type d'auteur
        if ($request->author_type === 'existing_user') {
            $rules['user_id'] = 'required|exists:users,id';
        } elseif ($request->author_type === 'custom') {
            $rules['custom_author_name'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules, [
            'author_type.required' => 'Le type d\'auteur est requis.',
            'author_type.in' => 'Le type d\'auteur doit être : utilisateur actuel, utilisateur existant, personnalisé ou anonyme.',
            'user_id.required' => 'L\'auteur est requis lorsque vous sélectionnez un utilisateur existant.',
            'user_id.exists' => 'L\'auteur sélectionné n\'existe pas.',
            'custom_author_name.required' => 'Le nom de l\'auteur est requis lorsque vous sélectionnez un auteur personnalisé.',
            'title.required' => 'Le titre est requis.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'content.required' => 'Le contenu est requis.',
            'type.required' => 'Le type de publication est requis.',
            'type.in' => 'Le type de publication doit être : témoignage, réflexion ou poésie.',
            'status.required' => 'Le statut est requis.',
            'status.in' => 'Le statut doit être : brouillon, publié, modéré ou masqué.',
            'moderation_reason.required_if' => 'La raison de modération est requise pour les statuts modéré ou masqué.',
        ]);

        try {
            DB::beginTransaction();

            // Préparation des données de publication
            $publicationData = [
                'title' => $validated['title'],
                'content' => $validated['content'],
                'type' => $validated['type'],
                'status' => $validated['status'],
                'is_anonymous' => $validated['author_type'] === 'anonymous',
                'moderation_reason' => $validated['moderation_reason'] ?? null,
                'auto_tags' => $validated['auto_tags'] ?? null,
            ];

            // Gestion de l'auteur selon le type
            switch ($validated['author_type']) {
                case 'current_user':
                    $publicationData['user_id'] = Auth::id();
                    break;
                case 'existing_user':
                    $publicationData['user_id'] = $validated['user_id'];
                    break;
                case 'custom':
                    $publicationData['custom_author_name'] = $validated['custom_author_name'];
                    break;
                case 'anonymous':
                    // is_anonymous est déjà défini à true
                    $publicationData['user_id'] = Auth::id(); // L'admin est l'auteur "technique"
                    break;
            }

            // Ajouter les données de modération si nécessaire
            if (in_array($validated['status'], ['moderated', 'hidden'])) {
                $publicationData['moderated_at'] = now();
                $publicationData['moderated_by'] = Auth::id();
            }

            // Auto-génération de tags basique (peut être améliorée avec une IA)
            if (empty($publicationData['auto_tags'])) {
                $publicationData['auto_tags'] = $this->generateAutoTags($validated['content'], $validated['type']);
            }

            Publication::create($publicationData);

            DB::commit();

            return redirect()->route('admin.publications.index')
                ->with('success', 'Publication créée avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors de la création de la publication:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création de la publication'])
                ->withInput();
        }
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Publication $publication)
    {
        $users = User::select('id', 'pseudo')->orderBy('pseudo')->get();
        $publication->load(['user', 'moderator']);

        // Déterminer le type d'auteur
        $authorType = 'existing_user'; // Par défaut
        if ($publication->is_anonymous) {
            $authorType = 'anonymous';
        } elseif (!empty($publication->custom_author_name)) {
            $authorType = 'custom';
        } elseif ($publication->user_id === Auth::id()) {
            $authorType = 'current_user';
        }

        return Inertia::render('Publications/Edit', [
            'publication' => array_merge($publication->toArray(), ['author_type' => $authorType]),
            'users' => $users,
            'currentUser' => Auth::user()
        ]);
    }

    /**
     * Mettre à jour une publication
     */
    public function update(Request $request, Publication $publication)
    {
        $rules = [
            'author_type' => 'required|in:current_user,existing_user,custom,anonymous',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:testimony,reflection,poetry',
            'status' => 'required|in:draft,published,moderated,hidden',
            'auto_tags' => 'nullable|array',
            'moderation_reason' => 'nullable|string|required_if:status,moderated,hidden',
        ];

        // Ajout de règles conditionnelles selon le type d'auteur
        if ($request->author_type === 'existing_user') {
            $rules['user_id'] = 'required|exists:users,id';
        } elseif ($request->author_type === 'custom') {
            $rules['custom_author_name'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules, [
            'author_type.required' => 'Le type d\'auteur est requis.',
            'author_type.in' => 'Le type d\'auteur doit être : utilisateur actuel, utilisateur existant, personnalisé ou anonyme.',
            'user_id.required' => 'L\'auteur est requis lorsque vous sélectionnez un utilisateur existant.',
            'user_id.exists' => 'L\'auteur sélectionné n\'existe pas.',
            'custom_author_name.required' => 'Le nom de l\'auteur est requis lorsque vous sélectionnez un auteur personnalisé.',
            'title.required' => 'Le titre est requis.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'content.required' => 'Le contenu est requis.',
            'type.required' => 'Le type de publication est requis.',
            'type.in' => 'Le type de publication doit être : témoignage, réflexion ou poésie.',
            'status.required' => 'Le statut est requis.',
            'status.in' => 'Le statut doit être : brouillon, publié, modéré ou masqué.',
            'moderation_reason.required_if' => 'La raison de modération est requise pour les statuts modéré ou masqué.',
        ]);

        try {
            DB::beginTransaction();

            // Préparation des données de publication
            $publicationData = [
                'title' => $validated['title'],
                'content' => $validated['content'],
                'type' => $validated['type'],
                'status' => $validated['status'],
                'is_anonymous' => $validated['author_type'] === 'anonymous',
                'moderation_reason' => $validated['moderation_reason'] ?? null,
                'auto_tags' => $validated['auto_tags'] ?? null,
            ];

            // Réinitialiser les champs d'auteur
            $publicationData['user_id'] = null;
            $publicationData['custom_author_name'] = null;

            // Gestion de l'auteur selon le type
            switch ($validated['author_type']) {
                case 'current_user':
                    $publicationData['user_id'] = Auth::id();
                    break;
                case 'existing_user':
                    $publicationData['user_id'] = $validated['user_id'];
                    break;
                case 'custom':
                    $publicationData['custom_author_name'] = $validated['custom_author_name'];
                    break;
                case 'anonymous':
                    // is_anonymous est déjà défini à true
                    $publicationData['user_id'] = Auth::id(); // L'admin est l'auteur "technique"
                    break;
            }

            // Gestion de la modération
            $wasModerated = in_array($publication->status, ['moderated', 'hidden']);
            $isBeingModerated = in_array($validated['status'], ['moderated', 'hidden']);

            if (!$wasModerated && $isBeingModerated) {
                // Nouvelle modération
                $publicationData['moderated_at'] = now();
                $publicationData['moderated_by'] = Auth::id();
            } elseif ($wasModerated && !$isBeingModerated) {
                // Suppression de la modération
                $publicationData['moderated_at'] = null;
                $publicationData['moderated_by'] = null;
                $publicationData['moderation_reason'] = null;
            }

            // Régénération des auto-tags si le contenu a changé
            if ($publication->content !== $validated['content']) {
                $publicationData['auto_tags'] = $this->generateAutoTags($validated['content'], $validated['type']);
            }

            $publication->update($publicationData);

            DB::commit();

            return redirect()->route('admin.publications.index')
                ->with('success', 'Publication mise à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors de la mise à jour de la publication:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'publication_id' => $publication->id,
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour de la publication'])
                ->withInput();
        }
    }

    /**
     * Supprimer une publication (soft delete)
     */
    public function destroy(Publication $publication)
    {
        try {
            $publication->delete(); // Soft delete

            return redirect()->route('admin.publications.index')
                ->with('success', 'Publication supprimée avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la publication:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'publication_id' => $publication->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la suppression de la publication']);
        }
    }

    /**
     * Restaurer une publication supprimée
     */
    public function restore($id)
    {
        try {
            $publication = Publication::withTrashed()->findOrFail($id);
            $publication->restore();

            return redirect()->route('admin.publications.index')
                ->with('success', 'Publication restaurée avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la restauration de la publication:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'publication_id' => $id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la restauration de la publication']);
        }
    }

    /**
     * Supprimer définitivement une publication
     */
    public function forceDelete($id)
    {
        try {
            $publication = Publication::withTrashed()->findOrFail($id);
            $publication->forceDelete();

            return redirect()->route('admin.publications.index')
                ->with('success', 'Publication supprimée définitivement');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression définitive de la publication:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'publication_id' => $id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la suppression définitive']);
        }
    }

    /**
     * Modérer une publication (changer son statut)
     */
    public function moderate(Request $request, Publication $publication)
    {
        $validated = $request->validate([
            'status' => 'required|in:published,moderated,hidden',
            'moderation_reason' => 'nullable|string|required_if:status,moderated,hidden',
        ]);

        try {
            $updateData = [
                'status' => $validated['status'],
                'moderation_reason' => $validated['moderation_reason'] ?? null,
            ];

            if (in_array($validated['status'], ['moderated', 'hidden'])) {
                $updateData['moderated_at'] = now();
                $updateData['moderated_by'] = Auth::id();
            } else {
                $updateData['moderated_at'] = null;
                $updateData['moderated_by'] = null;
                $updateData['moderation_reason'] = null;
            }

            $publication->update($updateData);

            $statusMessages = [
                'published' => 'Publication approuvée avec succès',
                'moderated' => 'Publication modérée avec succès',
                'hidden' => 'Publication masquée avec succès',
            ];

            return redirect()->back()
                ->with('success', $statusMessages[$validated['status']]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la modération de la publication:', [
                'message' => $e->getMessage(),
                'publication_id' => $publication->id,
                'status' => $validated['status'],
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la modération']);
        }
    }

    /**
     * Générer des tags automatiques basiques
     */
    private function generateAutoTags(string $content, string $type): array
    {
        $tags = [];

        // Ajouter le type comme tag
        $typeLabels = [
            'testimony' => 'témoignage',
            'reflection' => 'réflexion',
            'poetry' => 'poésie'
        ];
        $tags[] = $typeLabels[$type] ?? $type;

        // Mots-clés courants par type
        $keywords = [
            'testimony' => ['expérience', 'vécu', 'histoire', 'parcours', 'témoignage'],
            'reflection' => ['pensée', 'réflexion', 'méditation', 'analyse', 'questionnement'],
            'poetry' => ['poème', 'vers', 'rime', 'poésie', 'émotion']
        ];

        // Rechercher des mots-clés dans le contenu
        $contentLower = strtolower($content);
        foreach ($keywords[$type] ?? [] as $keyword) {
            if (strpos($contentLower, $keyword) !== false) {
                $tags[] = $keyword;
            }
        }

        // Limiter à 5 tags maximum
        return array_slice(array_unique($tags), 0, 5);
    }
}
