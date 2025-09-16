<?php

namespace App\Console\Commands;

use App\Models\Publication;
use Illuminate\Console\Command;

class CleanPublicationContent extends Command
{
    protected $signature = 'publications:clean-content';
    protected $description = 'Clean malformed UTF-8 characters from publication content';

    public function handle()
    {
        $this->info('Cleaning publication content...');

        $publications = Publication::all();
        $cleaned = 0;

        foreach ($publications as $publication) {
            $originalContent = $publication->content;

            // Nettoyer le contenu avec plusieurs méthodes
            $cleanContent = $originalContent;

            // Méthode 1: mb_convert_encoding
            $cleanContent = mb_convert_encoding($cleanContent, 'UTF-8', 'UTF-8');

            // Méthode 2: Supprimer les caractères de contrôle
            $cleanContent = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $cleanContent);

            // Méthode 3: Nettoyer les caractères non-UTF8
            $cleanContent = filter_var($cleanContent, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);

            // Méthode 4: Utiliser iconv pour forcer UTF-8
            $cleanContent = iconv('UTF-8', 'UTF-8//IGNORE', $cleanContent);

            if ($originalContent !== $cleanContent) {
                $publication->update(['content' => $cleanContent]);
                $cleaned++;
                $this->info("Cleaned: {$publication->title}");
            }
        }

        $this->info("Cleaned {$cleaned} publications out of {$publications->count()}");
        return 0;
    }
}
