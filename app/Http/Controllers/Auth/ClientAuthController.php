<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ClientAuthController extends Controller
{
    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegisterForm(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Traiter l'inscription
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'pseudo' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'birth_date' => 'required|date|before:today',
            'terms' => 'required|accepted',
        ], [
            'pseudo.required' => 'Le pseudonyme est obligatoire.',
            'pseudo.unique' => 'Ce pseudonyme est déjà utilisé.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'birth_date.required' => 'La date de naissance est obligatoire.',
            'birth_date.before' => 'Vous devez être né avant aujourd\'hui.',
            'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
        ]);

        // Vérification de l'âge minimum (13 ans)
        $birthDate = Carbon::parse($request->birth_date);
        $age = $birthDate->diffInYears(Carbon::now());

        if ($age < 13) {
            throw ValidationException::withMessages([
                'birth_date' => 'Vous devez avoir au moins 13 ans pour vous inscrire.'
            ]);
        }

        $user = User::create([
            'role' => 'client',
            'pseudo' => $request->pseudo,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birth_date' => $request->birth_date,
            'status' => 'active',
            'charter_accepted_at' => now(),
            'auth_provider' => 'local',
            'email_verified_at' => now(), // Auto-vérifier pour éviter les problèmes d'email
        ]);

        // Commenter temporairement l'événement qui cause l'erreur d'email
        // event(new Registered($user));

        Auth::login($user);

        return redirect()->route('client.home')->with('success', 'Inscription réussie ! Bienvenue dans notre communauté.');
    }

    /**
     * Afficher le formulaire de connexion
     */
    public function showLoginForm(): Response
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Traiter la connexion
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

        // Vérifier d'abord si l'email existe
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Aucun compte n\'est associé à cette adresse email.',
            ]);
        }

        // Vérifier si l'utilisateur peut se connecter (statut actif)
        if (!$user->canLogin()) {
            throw ValidationException::withMessages([
                'email' => 'Votre compte est temporairement suspendu. Contactez l\'administration.',
            ]);
        }

        // Tentative de connexion
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Mettre à jour la dernière connexion
            /** @var User $user */
            $user = Auth::user();
            $user->updateLastLogin();

            return redirect()->intended(route('client.home'))
                ->with('success', 'Connexion réussie !');
        }

        // Si on arrive ici, c'est que le mot de passe est incorrect
        throw ValidationException::withMessages([
            'password' => 'Le mot de passe saisi est incorrect.',
        ]);
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.home')
            ->with('success', 'Déconnexion réussie.');
    }

    /**
     * Afficher le formulaire mot de passe oublié
     */
    public function showForgotPasswordForm(): Response
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    /**
     * Envoyer le lien de réinitialisation
     */
    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Format d\'adresse email invalide.',
        ]);

        // Vérifier d'abord si l'utilisateur existe
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Aucun compte n\'est associé à cette adresse email.',
            ]);
        }

        // Vérifier si l'utilisateur peut recevoir un reset
        if (!$user->canLogin()) {
            throw ValidationException::withMessages([
                'email' => 'Votre compte est temporairement suspendu. Contactez l\'administration.',
            ]);
        }

        // Générer un token de réinitialisation
        $token = Str::random(64);

        // Sauvegarder le token dans la table password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Envoyer notre email personnalisé
        try {
            Mail::to($user->email)->send(new \App\Mail\ResetPasswordMail($user, $token));

            return back()->with('success', 'Un lien de réinitialisation a été envoyé à votre adresse email.');
        } catch (\Exception $e) {
            Log::error('Erreur envoi email reset password: ' . $e->getMessage());

            throw ValidationException::withMessages([
                'email' => 'Une erreur est survenue lors de l\'envoi de l\'email. Veuillez réessayer.',
            ]);
        }
    }

    /**
     * Afficher le formulaire de réinitialisation
     */
    public function showResetPasswordForm(Request $request, string $token): Response
    {
        return Inertia::render('Auth/ResetPassword', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Réinitialiser le mot de passe
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

        // Vérifier que l'utilisateur existe
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Aucun compte n\'est associé à cette adresse email.',
            ]);
        }

        // Vérifier le token
        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenRecord || !Hash::check($request->token, $tokenRecord->token)) {
            throw ValidationException::withMessages([
                'token' => 'Ce lien de réinitialisation est invalide.',
            ]);
        }

        // Vérifier que le token n'a pas expiré (60 minutes)
        if (now()->diffInMinutes($tokenRecord->created_at) > 60) {
            throw ValidationException::withMessages([
                'token' => 'Ce lien de réinitialisation a expiré.',
            ]);
        }

        // Mettre à jour le mot de passe
        $user->forceFill([
            'password' => Hash::make($request->password)
        ]);
        $user->save();

        // Supprimer le token utilisé
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Déclencher l'événement
        event(new PasswordReset($user));

        return redirect()->route('auth.login')
            ->with('success', 'Votre mot de passe a été réinitialisé avec succès.');
    }

    /**
     * Rediriger vers Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Gérer le callback Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($user) {
                // Utilisateur existant
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'auth_provider' => 'google',
                        'avatar' => $googleUser->avatar,
                    ]);
                }

                $user->update(['last_login_at' => now()]);
                Auth::login($user);

                return redirect()->route('client.home')
                    ->with('success', 'Connexion Google réussie !');
            } else {
                // Nouvel utilisateur - Générer un pseudo unique
                $basePseudo = $googleUser->name ?? 'Utilisateur';
                $pseudo = $basePseudo;
                $counter = 1;

                // Vérifier l'unicité du pseudo
                while (User::where('pseudo', $pseudo)->exists()) {
                    $pseudo = $basePseudo . '_' . $counter;
                    $counter++;
                }

                $user = User::create([
                    'role' => 'client',
                    'pseudo' => $pseudo,
                    'email' => $googleUser->email,
                    'password' => null, // Pas de mot de passe pour les utilisateurs Google
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'auth_provider' => 'google',
                    'status' => 'active',
                    'birth_date' => now()->subYears(18), // Date par défaut
                    'charter_accepted_at' => now(),
                    'email_verified_at' => now(),
                ]);

                Auth::login($user);

                return redirect()->route('client.home')
                    ->with('success', 'Inscription Google réussie ! Bienvenue dans notre communauté.');
            }
        } catch (\Exception $e) {
            // Log l'erreur pour le débogage
            Log::error('Erreur Google OAuth: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('auth.login')
                ->with('error', 'Erreur lors de la connexion avec Google. Veuillez réessayer. ' .
                    (config('app.debug') ? $e->getMessage() : ''));
        }
    }

    /**
     * Afficher la page de vérification email
     */
    public function showVerifyEmailForm(): Response
    {
        return Inertia::render('Auth/VerifyEmail');
    }

    /**
     * Envoyer une notification de vérification email
     */
    public function sendVerificationEmail(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('client.home');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Un nouveau lien de vérification a été envoyé à votre adresse email.');
    }

    /**
     * Vérifier l'email
     */
    public function verifyEmail(Request $request, string $id, string $hash): RedirectResponse
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            throw ValidationException::withMessages([
                'email' => 'Lien de vérification invalide.',
            ]);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('client.home')
                ->with('info', 'Votre email est déjà vérifié.');
        }

        $user->markEmailAsVerified();

        return redirect()->route('client.home')
            ->with('success', 'Votre email a été vérifié avec succès !');
    }

    /**
     * Désabonner un utilisateur des notifications email
     */
    public function unsubscribe(Request $request, User $user, string $token): RedirectResponse
    {
        try {
            // Vérifier que le token correspond
            if (!$user->unsubscribe_token || $user->unsubscribe_token !== $token) {
                return redirect()->route('client.home')
                    ->with('error', 'Lien de désinscription invalide ou expiré.');
            }

            // Désactiver les notifications email
            $user->update(['email_notifications' => false]);

            Log::info('Utilisateur désabonné des notifications email', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'token' => $token
            ]);

            return redirect()->route('client.home')
                ->with('success', 'Vous avez été désabonné des notifications email avec succès. Vous pouvez réactiver les notifications depuis votre profil.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la désinscription des notifications', [
                'user_id' => $user->id,
                'token' => $token,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('client.home')
                ->with('error', 'Une erreur est survenue lors de la désinscription. Veuillez réessayer.');
        }
    }
}
