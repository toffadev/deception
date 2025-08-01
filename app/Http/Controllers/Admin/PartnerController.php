<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    /**
     * Afficher la liste des partenaires
     */
    public function index(Request $request)
    {
        $query = Partner::query();

        // Filtres
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('website_url', 'like', "%{$search}%");
            });
        }

        $partners = $query->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Partners', [
            'partners' => $partners,
            'categories' => Partner::CATEGORIES,
            'filters' => $request->only(['category', 'status', 'search']),
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
        return Inertia::render('Admin/Partners/Create', [
            'categories' => Partner::CATEGORIES,
            'currentUser' => Auth::user()
        ]);
    }

    /**
     * Enregistrer un nouveau partenaire
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website_url' => 'nullable|url|max:255',
            'category' => 'required|in:' . implode(',', array_keys(Partner::CATEGORIES)),
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Le nom du partenaire est requis.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description est requise.',
            'logo.image' => 'Le fichier doit être une image.',
            'logo.mimes' => 'L\'image doit être au format: jpeg, png, jpg, gif, svg.',
            'logo.max' => 'L\'image ne peut pas dépasser 2MB.',
            'website_url.url' => 'L\'URL du site web doit être valide.',
            'website_url.max' => 'L\'URL ne peut pas dépasser 255 caractères.',
            'category.required' => 'La catégorie est requise.',
            'category.in' => 'La catégorie sélectionnée n\'est pas valide.',
            'sort_order.integer' => 'L\'ordre de tri doit être un nombre entier.',
            'sort_order.min' => 'L\'ordre de tri ne peut pas être négatif.',
        ]);

        try {
            DB::beginTransaction();

            // Gestion de l'upload du logo
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('partners/logos', 'public');
            }

            // Si aucun sort_order n'est fourni, prendre le suivant
            if (!isset($validated['sort_order'])) {
                $validated['sort_order'] = Partner::max('sort_order') + 1;
            }

            $partnerData = [
                'name' => $validated['name'],
                'description' => $validated['description'],
                'logo_path' => $logoPath,
                'website_url' => $validated['website_url'] ?? null,
                'category' => $validated['category'],
                'sort_order' => $validated['sort_order'],
                'is_active' => $validated['is_active'] ?? true,
            ];

            Partner::create($partnerData);

            DB::commit();

            return redirect()->route('admin.partners.index')
                ->with('success', 'Partenaire créé avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            // Supprimer le fichier uploadé en cas d'erreur
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }

            Log::error('Erreur lors de la création du partenaire:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'request_data' => $request->except('logo')
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création du partenaire'])
                ->withInput();
        }
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Partner $partner)
    {
        return Inertia::render('Admin/Partners/Edit', [
            'partner' => $partner,
            'categories' => Partner::CATEGORIES,
            'currentUser' => Auth::user()
        ]);
    }

    /**
     * Mettre à jour un partenaire
     */
    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'remove_logo' => 'boolean',
            'website_url' => 'nullable|url|max:255',
            'category' => 'required|in:' . implode(',', array_keys(Partner::CATEGORIES)),
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Le nom du partenaire est requis.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description est requise.',
            'logo.image' => 'Le fichier doit être une image.',
            'logo.mimes' => 'L\'image doit être au format: jpeg, png, jpg, gif, svg.',
            'logo.max' => 'L\'image ne peut pas dépasser 2MB.',
            'website_url.url' => 'L\'URL du site web doit être valide.',
            'website_url.max' => 'L\'URL ne peut pas dépasser 255 caractères.',
            'category.required' => 'La catégorie est requise.',
            'category.in' => 'La catégorie sélectionnée n\'est pas valide.',
            'sort_order.integer' => 'L\'ordre de tri doit être un nombre entier.',
            'sort_order.min' => 'L\'ordre de tri ne peut pas être négatif.',
        ]);

        try {
            DB::beginTransaction();

            $oldLogoPath = $partner->logo_path;
            $logoPath = $oldLogoPath;

            // Gestion de la suppression du logo
            if ($validated['remove_logo'] ?? false) {
                $logoPath = null;
            }

            // Gestion de l'upload d'un nouveau logo
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('partners/logos', 'public');
            }

            $partnerData = [
                'name' => $validated['name'],
                'description' => $validated['description'],
                'logo_path' => $logoPath,
                'website_url' => $validated['website_url'] ?? null,
                'category' => $validated['category'],
                'sort_order' => $validated['sort_order'] ?? $partner->sort_order,
                'is_active' => $validated['is_active'] ?? $partner->is_active,
            ];

            $partner->update($partnerData);

            // Supprimer l'ancien logo si nécessaire
            if ($oldLogoPath && $oldLogoPath !== $logoPath && Storage::disk('public')->exists($oldLogoPath)) {
                Storage::disk('public')->delete($oldLogoPath);
            }

            DB::commit();

            return redirect()->route('admin.partners.index')
                ->with('success', 'Partenaire mis à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            // Supprimer le nouveau fichier uploadé en cas d'erreur
            if ($request->hasFile('logo') && $logoPath && $logoPath !== $oldLogoPath && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }

            Log::error('Erreur lors de la mise à jour du partenaire:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'partner_id' => $partner->id,
                'user_id' => Auth::id(),
                'request_data' => $request->except('logo')
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour du partenaire'])
                ->withInput();
        }
    }

    /**
     * Supprimer définitivement un partenaire
     */
    public function destroy(Partner $partner)
    {
        try {
            DB::beginTransaction();

            $logoPath = $partner->logo_path;

            // Supprimer le partenaire
            $partner->delete();

            // Supprimer le logo du stockage
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }

            DB::commit();

            return redirect()->route('admin.partners.index')
                ->with('success', 'Partenaire supprimé définitivement avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors de la suppression du partenaire:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'partner_id' => $partner->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la suppression du partenaire']);
        }
    }

    /**
     * Basculer le statut actif/inactif d'un partenaire
     */
    public function toggleStatus(Partner $partner)
    {
        try {
            $partner->update(['is_active' => !$partner->is_active]);

            $status = $partner->is_active ? 'activé' : 'désactivé';
            return redirect()->back()
                ->with('success', "Partenaire {$status} avec succès");
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut du partenaire:', [
                'message' => $e->getMessage(),
                'partner_id' => $partner->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors du changement de statut']);
        }
    }

    /**
     * Réorganiser l'ordre des partenaires
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'partners' => 'required|array',
            'partners.*.id' => 'required|exists:partners,id',
            'partners.*.sort_order' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['partners'] as $partnerData) {
                Partner::where('id', $partnerData['id'])
                    ->update(['sort_order' => $partnerData['sort_order']]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ordre des partenaires mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors de la réorganisation des partenaires:', [
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
