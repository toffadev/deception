<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Afficher la liste des utilisateurs
     */
    public function index(Request $request)
    {
        $query = User::withTrashed()
            ->withCount(['publications', 'comments', 'donations', 'reports']);

        // Filtres
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('auth_provider')) {
            $query->where('auth_provider', $request->auth_provider);
        }

        if ($request->filled('deleted')) {
            if ($request->deleted === 'only') {
                $query->onlyTrashed();
            } elseif ($request->deleted === 'without') {
                $query->withoutTrashed();
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('pseudo', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Tri par défaut : les plus récents
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $allowedSorts = ['created_at', 'pseudo', 'email', 'last_login_at', 'status'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $users = $query->paginate(20);

        // Statistiques rapides
        $statistics = [
            'total_users' => User::withoutTrashed()->count(),
            'active_users' => User::withoutTrashed()->where('status', 'active')->count(),
            'suspended_users' => User::withoutTrashed()->where('status', 'suspended')->count(),
            'banned_users' => User::withoutTrashed()->where('status', 'banned')->count(),
            'deleted_users' => User::onlyTrashed()->count(),
            'admins_count' => User::withoutTrashed()->where('role', 'admin')->count(),
        ];

        return Inertia::render('Users', [
            'users' => $users,
            'statistics' => $statistics,
            'filters' => $request->only(['role', 'status', 'auth_provider', 'deleted', 'search', 'sort_by', 'sort_direction']),
            'flash' => [
                'success' => session('success'),
                'error' => session('error')
            ]
        ]);
    }

    /**
     * Afficher le détail d'un utilisateur
     */
    public function show(User $user)
    {
        $user->load([
            'publications' => function ($query) {
                $query->withTrashed()->latest()->take(10);
            },
            'comments' => function ($query) {
                $query->withTrashed()->latest()->take(10);
            },
            'donations' => function ($query) {
                $query->latest()->take(10);
            },
            'reports' => function ($query) {
                $query->latest()->take(10);
            },
            'reviewedReports' => function ($query) {
                $query->latest()->take(10);
            }
        ]);

        // Statistiques de l'utilisateur
        $userStats = [
            'total_publications' => $user->publications()->withTrashed()->count(),
            'total_comments' => $user->comments()->withTrashed()->count(),
            'total_donations' => $user->donations()->sum('amount'),
            'reports_made' => $user->reports()->count(),
            'reports_received' => \App\Models\Report::where('reportable_type', User::class)
                ->where('reportable_id', $user->id)->count(),
            'account_age_days' => $user->created_at->diffInDays(now()),
            'last_activity' => $user->last_login_at,
        ];

        return Inertia::render('Admin/Users/Show', [
            'user' => $user,
            'userStats' => $userStats,
            'currentUser' => Auth::user()
        ]);
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'currentUser' => Auth::user()
        ]);
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, User $user)
    {
        // Empêcher la modification de son propre compte
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->withErrors(['error' => 'Vous ne pouvez pas modifier votre propre compte depuis cette interface.']);
        }

        $rules = [
            'pseudo' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:client,admin',
            'status' => 'required|in:active,suspended,banned',
            'birth_date' => 'required|date|before:today',
            'anonymous_by_default' => 'boolean',
            'password' => 'nullable|string|min:8|confirmed',
            'notification_preferences' => 'nullable|array',
        ];

        $validated = $request->validate($rules, [
            'pseudo.required' => 'Le pseudo est requis.',
            'pseudo.unique' => 'Ce pseudo est déjà utilisé.',
            'pseudo.max' => 'Le pseudo ne peut pas dépasser 255 caractères.',
            'email.required' => 'L\'email est requis.',
            'email.email' => 'L\'email doit être une adresse valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'role.required' => 'Le rôle est requis.',
            'role.in' => 'Le rôle doit être : client ou admin.',
            'status.required' => 'Le statut est requis.',
            'status.in' => 'Le statut doit être : actif, suspendu ou banni.',
            'birth_date.required' => 'La date de naissance est requise.',
            'birth_date.date' => 'La date de naissance doit être une date valide.',
            'birth_date.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        try {
            DB::beginTransaction();

            $userData = [
                'pseudo' => $validated['pseudo'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'status' => $validated['status'],
                'birth_date' => $validated['birth_date'],
                'anonymous_by_default' => $validated['anonymous_by_default'] ?? false,
                'notification_preferences' => $validated['notification_preferences'],
            ];

            // Mise à jour du mot de passe si fourni
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $user->update($userData);

            DB::commit();

            return redirect()->route('admin.users.show', $user)
                ->with('success', 'Utilisateur mis à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors de la mise à jour de l\'utilisateur:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id,
                'admin_id' => Auth::id(),
                'request_data' => $request->except(['password', 'password_confirmation'])
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour de l\'utilisateur'])
                ->withInput();
        }
    }

    /**
     * Changer le statut d'un utilisateur (suspendre, bannir, réactiver)
     */
    public function changeStatus(Request $request, User $user)
    {
        // Empêcher la modification de son propre statut
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->withErrors(['error' => 'Vous ne pouvez pas modifier votre propre statut.']);
        }

        $validated = $request->validate([
            'status' => 'required|in:active,suspended,banned',
            'reason' => 'nullable|string|max:500',
        ], [
            'status.required' => 'Le statut est requis.',
            'status.in' => 'Le statut doit être : actif, suspendu ou banni.',
            'reason.max' => 'La raison ne peut pas dépasser 500 caractères.',
        ]);

        try {
            $oldStatus = $user->status;
            $user->update(['status' => $validated['status']]);

            // Log de l'action administrative
            Log::info('Changement de statut utilisateur:', [
                'user_id' => $user->id,
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
                'reason' => $validated['reason'] ?? null,
                'admin_id' => Auth::id(),
            ]);

            $statusMessages = [
                'active' => 'Utilisateur réactivé avec succès',
                'suspended' => 'Utilisateur suspendu avec succès',
                'banned' => 'Utilisateur banni avec succès',
            ];

            return redirect()->back()
                ->with('success', $statusMessages[$validated['status']]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut:', [
                'message' => $e->getMessage(),
                'user_id' => $user->id,
                'admin_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors du changement de statut']);
        }
    }

    /**
     * Changer le rôle d'un utilisateur
     */
    public function changeRole(Request $request, User $user)
    {
        // Empêcher la modification de son propre rôle
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->withErrors(['error' => 'Vous ne pouvez pas modifier votre propre rôle.']);
        }

        $validated = $request->validate([
            'role' => 'required|in:client,admin',
        ], [
            'role.required' => 'Le rôle est requis.',
            'role.in' => 'Le rôle doit être : client ou admin.',
        ]);

        try {
            $oldRole = $user->role;
            $user->update(['role' => $validated['role']]);

            // Log de l'action administrative
            Log::info('Changement de rôle utilisateur:', [
                'user_id' => $user->id,
                'old_role' => $oldRole,
                'new_role' => $validated['role'],
                'admin_id' => Auth::id(),
            ]);

            $roleMessages = [
                'client' => 'Utilisateur rétrogradé en client avec succès',
                'admin' => 'Utilisateur promu administrateur avec succès',
            ];

            return redirect()->back()
                ->with('success', $roleMessages[$validated['role']]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de rôle:', [
                'message' => $e->getMessage(),
                'user_id' => $user->id,
                'admin_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors du changement de rôle']);
        }
    }

    /**
     * Supprimer un utilisateur (soft delete)
     */
    public function destroy(User $user)
    {
        // Empêcher la suppression de son propre compte
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->withErrors(['error' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }

        try {
            $user->delete(); // Soft delete

            Log::info('Suppression d\'utilisateur:', [
                'user_id' => $user->id,
                'user_pseudo' => $user->pseudo,
                'admin_id' => Auth::id(),
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'Utilisateur supprimé avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'utilisateur:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id,
                'admin_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la suppression de l\'utilisateur']);
        }
    }

    /**
     * Restaurer un utilisateur supprimé
     */
    public function restore($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            $user->restore();

            Log::info('Restauration d\'utilisateur:', [
                'user_id' => $user->id,
                'user_pseudo' => $user->pseudo,
                'admin_id' => Auth::id(),
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'Utilisateur restauré avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la restauration de l\'utilisateur:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $id,
                'admin_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la restauration de l\'utilisateur']);
        }
    }

    /**
     * Supprimer définitivement un utilisateur
     */
    public function forceDelete($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);

            // Empêcher la suppression définitive de son propre compte
            if ($user->id === Auth::id()) {
                return redirect()->back()
                    ->withErrors(['error' => 'Vous ne pouvez pas supprimer définitivement votre propre compte.']);
            }

            $userPseudo = $user->pseudo;
            $user->forceDelete();

            Log::warning('Suppression définitive d\'utilisateur:', [
                'user_id' => $id,
                'user_pseudo' => $userPseudo,
                'admin_id' => Auth::id(),
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'Utilisateur supprimé définitivement');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression définitive de l\'utilisateur:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $id,
                'admin_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la suppression définitive']);
        }
    }

    /**
     * Réinitialiser le mot de passe d'un utilisateur
     */
    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'new_password.required' => 'Le nouveau mot de passe est requis.',
            'new_password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
            'new_password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        try {
            $user->update([
                'password' => Hash::make($validated['new_password']),
            ]);

            Log::info('Réinitialisation de mot de passe par admin:', [
                'user_id' => $user->id,
                'admin_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('success', 'Mot de passe réinitialisé avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la réinitialisation du mot de passe:', [
                'message' => $e->getMessage(),
                'user_id' => $user->id,
                'admin_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la réinitialisation du mot de passe']);
        }
    }

    /**
     * Afficher les statistiques des utilisateurs
     */
    public function statistics()
    {
        try {
            // Statistiques générales
            $generalStats = [
                'total_users' => User::withoutTrashed()->count(),
                'active_users' => User::where('status', 'active')->count(),
                'suspended_users' => User::where('status', 'suspended')->count(),
                'banned_users' => User::where('status', 'banned')->count(),
                'deleted_users' => User::onlyTrashed()->count(),
                'verified_users' => User::whereNotNull('email_verified_at')->count(),
                'google_users' => User::where('auth_provider', 'google')->count(),
                'admins' => User::where('role', 'admin')->count(),
            ];

            // Inscriptions par mois (12 derniers mois)
            $registrationsByMonth = collect(range(11, 0))->map(function ($monthsAgo) {
                $date = now()->subMonths($monthsAgo);
                return [
                    'month' => $date->format('Y-m'),
                    'month_name' => $date->translatedFormat('F Y'),
                    'count' => User::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count(),
                ];
            });

            // Répartition par âge
            $ageRanges = [
                '18-25' => User::whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 18 AND 25')->count(),
                '26-35' => User::whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 26 AND 35')->count(),
                '36-45' => User::whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 36 AND 45')->count(),
                '46-55' => User::whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 46 AND 55')->count(),
                '56+' => User::whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) > 55')->count(),
            ];

            // Activité récente
            $recentActivity = [
                'today' => User::whereDate('last_login_at', today())->count(),
                'this_week' => User::where('last_login_at', '>=', now()->startOfWeek())->count(),
                'this_month' => User::where('last_login_at', '>=', now()->startOfMonth())->count(),
                'never_logged' => User::whereNull('last_login_at')->count(),
            ];

            return Inertia::render('Admin/Users/Statistics', [
                'generalStats' => $generalStats,
                'registrationsByMonth' => $registrationsByMonth,
                'ageRanges' => $ageRanges,
                'recentActivity' => $recentActivity,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du calcul des statistiques utilisateurs:', [
                'message' => $e->getMessage(),
                'admin_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors du calcul des statistiques']);
        }
    }
}
