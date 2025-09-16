<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class GenerateUnsubscribeTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:generate-unsubscribe-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère les tokens de désinscription pour tous les utilisateurs qui n\'en ont pas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Génération des tokens de désinscription pour les utilisateurs...');

        $usersCount = 0;
        User::whereNull('unsubscribe_token')->chunk(50, function ($users) use (&$usersCount) {
            foreach ($users as $user) {
                $user->generateUnsubscribeToken();
                $usersCount++;
            }
        });

        $this->info("Tokens générés pour {$usersCount} utilisateur(s).");

        return 0;
    }
}
