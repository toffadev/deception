<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Afficher la page de contact
     */
    public function index()
    {
        return Inertia::render('Contact', [
            'isAuthenticated' => Auth::check(),
            'user' => Auth::user() ? [
                'id' => Auth::user()->id,
                'pseudo' => Auth::user()->pseudo,
                'email' => Auth::user()->email,
            ] : null,
        ]);
    }

    /**
     * Envoyer un message de contact (pour une future implémentation)
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:2000',
        ], [
            'name.required' => 'Le nom est requis.',
            'email.required' => 'L\'email est requis.',
            'email.email' => 'L\'email doit être valide.',
            'subject.required' => 'Le sujet est requis.',
            'message.required' => 'Le message est requis.',
            'message.min' => 'Le message doit contenir au moins 10 caractères.',
            'message.max' => 'Le message ne peut pas dépasser 2000 caractères.',
        ]);

        try {
            // Log du message pour l'administration
            Log::info('Nouveau message de contact reçu', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'message' => substr($validated['message'], 0, 100) . '...',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);

            // Ici vous pourriez envoyer un email aux administrateurs
            // Mail::to(config('mail.admin_email'))->send(new ContactMessage($validated));

            return response()->json([
                'success' => true,
                'message' => 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi du message de contact:', [
                'message' => $e->getMessage(),
                'data' => $validated
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.'
            ], 500);
        }
    }
}
