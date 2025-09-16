<?php

namespace App\Observers;

use App\Models\Publication;
use App\Models\User;
use App\Mail\PublicationPublishedNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PublicationObserver
{
    /**
     * Handle the Publication "created" event.
     */
    public function created(Publication $publication): void
    {
        //
    }

    /**
     * Handle the Publication "updated" event.
     */
    public function updated(Publication $publication): void
    {
        Log::info('Observer updated() appelé', [
            'publication_id' => $publication->id,
            'current_status' => $publication->status,
            'is_dirty_status' => $publication->isDirty('status'),
            'dirty_attributes' => $publication->getDirty()
        ]);

        // Vérifier si le statut a changé vers "published"
        if ($publication->wasChanged('status') && $publication->status === 'published') {
            Log::info('Statut changé vers published, envoi des notifications', [
                'publication_id' => $publication->id,
                'title' => $publication->title
            ]);
            $this->sendPublicationNotifications($publication);
        }
    }

    /**
     * Handle the Publication "deleted" event.
     */
    public function deleted(Publication $publication): void
    {
        //
    }

    /**
     * Handle the Publication "restored" event.
     */
    public function restored(Publication $publication): void
    {
        //
    }

    /**
     * Handle the Publication "force deleted" event.
     */
    public function forceDeleted(Publication $publication): void
    {
        //
    }

    /**
     * Envoyer les notifications par email à tous les utilisateurs
     */
    private function sendPublicationNotifications(Publication $publication): void
    {
        try {
            Log::info('Publication publiée, envoi des notifications', [
                'publication_id' => $publication->id,
                'publication_title' => $publication->title
            ]);

            // Récupérer tous les utilisateurs actifs qui veulent recevoir des notifications
            $users = User::where('status', 'active')
                ->where('email_notifications', true)
                ->whereNotNull('email_verified_at')
                ->whereNotNull('email')
                ->chunk(50, function ($userChunk) use ($publication) {
                    foreach ($userChunk as $user) {
                        try {
                            // Générer le token de désinscription si nécessaire
                            $user->generateUnsubscribeToken();

                            // Envoyer l'email en arrière-plan
                            Mail::to($user->email)->queue(
                                new PublicationPublishedNotification($publication, $user)
                            );

                            Log::debug('Email de notification envoyé', [
                                'user_id' => $user->id,
                                'user_email' => $user->email,
                                'publication_id' => $publication->id
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Erreur lors de l\'envoi de notification à un utilisateur', [
                                'user_id' => $user->id,
                                'user_email' => $user->email,
                                'publication_id' => $publication->id,
                                'error' => $e->getMessage()
                            ]);
                        }
                    }
                });

            Log::info('Envoi des notifications terminé', [
                'publication_id' => $publication->id
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi des notifications pour une publication', [
                'publication_id' => $publication->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
