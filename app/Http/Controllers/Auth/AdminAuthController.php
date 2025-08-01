<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    /**
     * Afficher le formulaire de connexion admin
     */
    public function showLoginForm(): Response
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Traiter la connexion admin
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Format d\'adresse email invalide.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Vérifier que l'utilisateur existe et est admin
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Aucun utilisateur trouvé avec cette adresse email.',
            ]);
        }

        if ($user->role !== 'admin') {
            throw ValidationException::withMessages([
                'email' => 'Accès réservé aux administrateurs.',
            ]);
        }

        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => 'Votre compte administrateur est suspendu.',
            ]);
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Mettre à jour la dernière connexion
            /** @var User $user */
            $user = Auth::user();
            $user->updateLastLogin();

            return redirect()->intended(route('admin.home'))
                ->with('success', 'Connexion administrateur réussie !');
        }

        throw ValidationException::withMessages([
            'password' => 'Mot de passe incorrect.',
        ]);
    }

    /**
     * Déconnexion admin
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.auth.login')
            ->with('success', 'Déconnexion administrateur réussie.');
    }

    /**
     * Afficher le formulaire mot de passe oublié admin
     */
    public function showForgotPasswordForm(): Response
    {
        return Inertia::render('Admin/Auth/ForgotPassword');
    }

    /**
     * Envoyer le lien de réinitialisation admin
     */
    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Format d\'adresse email invalide.',
        ]);

        // Vérifier que l'utilisateur est bien admin
        $user = User::where('email', $request->email)->first();

        if (!$user || $user->role !== 'admin') {
            throw ValidationException::withMessages([
                'email' => 'Aucun administrateur trouvé avec cette adresse email.',
            ]);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Un lien de réinitialisation a été envoyé à votre adresse email administrateur.');
        }

        throw ValidationException::withMessages([
            'email' => 'Erreur lors de l\'envoi du lien de réinitialisation.',
        ]);
    }

    /**
     * Afficher le formulaire de réinitialisation admin
     */
    public function showResetPasswordForm(Request $request, string $token): Response
    {
        return Inertia::render('Admin/Auth/ResetPassword', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Réinitialiser le mot de passe admin
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Format d\'adresse email invalide.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        // Vérifier que l'utilisateur est bien admin
        $user = User::where('email', $request->email)->first();

        if (!$user || $user->role !== 'admin') {
            throw ValidationException::withMessages([
                'email' => 'Aucun administrateur trouvé avec cette adresse email.',
            ]);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Vérifier encore une fois que c'est bien un admin
                if ($user->role !== 'admin') {
                    throw ValidationException::withMessages([
                        'email' => 'Accès réservé aux administrateurs.',
                    ]);
                }

                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('admin.auth.login')
                ->with('success', 'Votre mot de passe administrateur a été réinitialisé avec succès.');
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
