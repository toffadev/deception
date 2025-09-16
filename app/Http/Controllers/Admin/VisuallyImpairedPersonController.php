<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisuallyImpairedPerson;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VisuallyImpairedPersonController extends Controller
{
    /**
     * Afficher la liste des personnes malvoyantes
     */
    public function index(Request $request)
    {
        $query = VisuallyImpairedPerson::query();

        // Filtres
        if ($request->filled('sexe')) {
            $query->where('sexe', $request->sexe);
        }

        if ($request->filled('type_voyance')) {
            $query->where('type_voyance', $request->type_voyance);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('traitement')) {
            if ($request->traitement === 'oui') {
                $query->where('traitement_en_cours', true);
            } elseif ($request->traitement === 'non') {
                $query->where('traitement_en_cours', false);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%")
                    ->orWhere('lieu_residence', 'like', "%{$search}%");
            });
        }

        $people = $query->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('VisuallyImpairedPeople', [
            'people' => $people,
            'sexes' => VisuallyImpairedPerson::SEXES,
            'typesVoyance' => VisuallyImpairedPerson::TYPES_VOYANCE,
            'filters' => $request->only(['sexe', 'type_voyance', 'status', 'traitement', 'search']),
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
        return Inertia::render('VisuallyImpairedPeople', [
            'sexes' => VisuallyImpairedPerson::SEXES,
            'typesVoyance' => VisuallyImpairedPerson::TYPES_VOYANCE,
            'currentUser' => Auth::user()
        ]);
    }

    /**
     * Enregistrer une nouvelle personne malvoyante
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'sexe' => 'required|in:' . implode(',', array_keys(VisuallyImpairedPerson::SEXES)),
            'age' => 'nullable|integer|min:0|max:150',
            'lieu_residence' => 'nullable|string|max:255',
            'telephone' => 'required|string|max:20',
            'type_voyance' => 'nullable|in:' . implode(',', array_keys(VisuallyImpairedPerson::TYPES_VOYANCE)),
            'traitement_en_cours' => 'boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ], [
            'nom.required' => 'Le nom est requis.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'prenom.required' => 'Le prénom est requis.',
            'prenom.max' => 'Le prénom ne peut pas dépasser 255 caractères.',
            'sexe.required' => 'Le sexe est requis.',
            'sexe.in' => 'Le sexe sélectionné n\'est pas valide.',
            'age.integer' => 'L\'âge doit être un nombre entier.',
            'age.min' => 'L\'âge ne peut pas être négatif.',
            'age.max' => 'L\'âge ne peut pas dépasser 150 ans.',
            'lieu_residence.max' => 'Le lieu de résidence ne peut pas dépasser 255 caractères.',
            'telephone.required' => 'Le téléphone est requis.',
            'telephone.max' => 'Le téléphone ne peut pas dépasser 20 caractères.',
            'type_voyance.in' => 'Le type de voyance sélectionné n\'est pas valide.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.mimes' => 'L\'image doit être au format: jpeg, png, jpg, gif.',
            'photo.max' => 'L\'image ne peut pas dépasser 2MB.',
            'sort_order.integer' => 'L\'ordre de tri doit être un nombre entier.',
            'sort_order.min' => 'L\'ordre de tri ne peut pas être négatif.',
        ]);

        try {
            DB::beginTransaction();

            // Gestion de l'upload de la photo
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('visually-impaired/photos', 'public');
            }

            // Si aucun sort_order n'est fourni, prendre le suivant
            if (!isset($validated['sort_order'])) {
                $validated['sort_order'] = VisuallyImpairedPerson::max('sort_order') + 1;
            }

            $personData = [
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'sexe' => $validated['sexe'],
                'age' => $validated['age'] ?? null,
                'lieu_residence' => $validated['lieu_residence'] ?? null,
                'telephone' => $validated['telephone'],
                'type_voyance' => $validated['type_voyance'] ?? null,
                'traitement_en_cours' => $validated['traitement_en_cours'] ?? false,
                'photo_path' => $photoPath,
                'sort_order' => $validated['sort_order'],
                'is_active' => $validated['is_active'] ?? true,
            ];

            VisuallyImpairedPerson::create($personData);

            DB::commit();

            return redirect()->route('admin.visually-impaired.index')
                ->with('success', 'Personne malvoyante enregistrée avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            // Supprimer le fichier uploadé en cas d'erreur
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }

            Log::error('Erreur lors de la création de la personne malvoyante:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'request_data' => $request->except('photo')
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de l\'enregistrement'])
                ->withInput();
        }
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(VisuallyImpairedPerson $visuallyImpaired)
    {
        return Inertia::render('Admin/VisuallyImpairedPeople', [
            'person' => $visuallyImpaired,
            'sexes' => VisuallyImpairedPerson::SEXES,
            'typesVoyance' => VisuallyImpairedPerson::TYPES_VOYANCE,
            'currentUser' => Auth::user()
        ]);
    }

    /**
     * Mettre à jour une personne malvoyante
     */
    public function update(Request $request, VisuallyImpairedPerson $visuallyImpaired)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'sexe' => 'required|in:' . implode(',', array_keys(VisuallyImpairedPerson::SEXES)),
            'age' => 'nullable|integer|min:0|max:150',
            'lieu_residence' => 'nullable|string|max:255',
            'telephone' => 'required|string|max:20',
            'type_voyance' => 'nullable|in:' . implode(',', array_keys(VisuallyImpairedPerson::TYPES_VOYANCE)),
            'traitement_en_cours' => 'boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_photo' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ], [
            'nom.required' => 'Le nom est requis.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'prenom.required' => 'Le prénom est requis.',
            'prenom.max' => 'Le prénom ne peut pas dépasser 255 caractères.',
            'sexe.required' => 'Le sexe est requis.',
            'sexe.in' => 'Le sexe sélectionné n\'est pas valide.',
            'age.integer' => 'L\'âge doit être un nombre entier.',
            'age.min' => 'L\'âge ne peut pas être négatif.',
            'age.max' => 'L\'âge ne peut pas dépasser 150 ans.',
            'lieu_residence.max' => 'Le lieu de résidence ne peut pas dépasser 255 caractères.',
            'telephone.required' => 'Le téléphone est requis.',
            'telephone.max' => 'Le téléphone ne peut pas dépasser 20 caractères.',
            'type_voyance.in' => 'Le type de voyance sélectionné n\'est pas valide.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.mimes' => 'L\'image doit être au format: jpeg, png, jpg, gif.',
            'photo.max' => 'L\'image ne peut pas dépasser 2MB.',
            'sort_order.integer' => 'L\'ordre de tri doit être un nombre entier.',
            'sort_order.min' => 'L\'ordre de tri ne peut pas être négatif.',
        ]);

        try {
            DB::beginTransaction();

            $oldPhotoPath = $visuallyImpaired->photo_path;
            $photoPath = $oldPhotoPath;

            // Gestion de la suppression de la photo
            if ($validated['remove_photo'] ?? false) {
                $photoPath = null;
            }

            // Gestion de l'upload d'une nouvelle photo
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('visually-impaired/photos', 'public');
            }

            $personData = [
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'sexe' => $validated['sexe'],
                'age' => $validated['age'] ?? null,
                'lieu_residence' => $validated['lieu_residence'] ?? null,
                'telephone' => $validated['telephone'],
                'type_voyance' => $validated['type_voyance'] ?? null,
                'traitement_en_cours' => $validated['traitement_en_cours'] ?? $visuallyImpaired->traitement_en_cours,
                'photo_path' => $photoPath,
                'sort_order' => $validated['sort_order'] ?? $visuallyImpaired->sort_order,
                'is_active' => $validated['is_active'] ?? $visuallyImpaired->is_active,
            ];

            $visuallyImpaired->update($personData);

            // Supprimer l'ancienne photo si nécessaire
            if ($oldPhotoPath && $oldPhotoPath !== $photoPath && Storage::disk('public')->exists($oldPhotoPath)) {
                Storage::disk('public')->delete($oldPhotoPath);
            }

            DB::commit();

            return redirect()->route('admin.visually-impaired.index')
                ->with('success', 'Personne malvoyante mise à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            // Supprimer le nouveau fichier uploadé en cas d'erreur
            if ($request->hasFile('photo') && $photoPath && $photoPath !== $oldPhotoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }

            Log::error('Erreur lors de la mise à jour de la personne malvoyante:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'person_id' => $visuallyImpaired->id,
                'user_id' => Auth::id(),
                'request_data' => $request->except('photo')
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour'])
                ->withInput();
        }
    }

    /**
     * Supprimer définitivement une personne malvoyante
     */
    public function destroy(VisuallyImpairedPerson $visuallyImpaired)
    {
        try {
            DB::beginTransaction();

            $photoPath = $visuallyImpaired->photo_path;

            // Supprimer la personne
            $visuallyImpaired->delete();

            // Supprimer la photo du stockage
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }

            DB::commit();

            return redirect()->route('admin.visually-impaired.index')
                ->with('success', 'Personne malvoyante supprimée définitivement avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors de la suppression de la personne malvoyante:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'person_id' => $visuallyImpaired->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la suppression']);
        }
    }

    /**
     * Basculer le statut actif/inactif d'une personne malvoyante
     */
    public function toggleStatus(VisuallyImpairedPerson $visuallyImpaired)
    {
        try {
            $visuallyImpaired->update(['is_active' => !$visuallyImpaired->is_active]);

            $status = $visuallyImpaired->is_active ? 'activée' : 'désactivée';
            return redirect()->back()
                ->with('success', "Personne malvoyante {$status} avec succès");
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut de la personne malvoyante:', [
                'message' => $e->getMessage(),
                'person_id' => $visuallyImpaired->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors du changement de statut']);
        }
    }

    /**
     * Réorganiser l'ordre des personnes malvoyantes
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'people' => 'required|array',
            'people.*.id' => 'required|exists:visually_impaired_people,id',
            'people.*.sort_order' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['people'] as $personData) {
                VisuallyImpairedPerson::where('id', $personData['id'])
                    ->update(['sort_order' => $personData['sort_order']]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ordre des personnes malvoyantes ou aveugles mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors de la réorganisation des personnes malvoyantes:', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la réorganisation'
            ], 500);
        }
    }
}
