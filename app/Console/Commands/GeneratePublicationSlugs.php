<?php

namespace App\Console\Commands;

use App\Models\Publication;
use Illuminate\Console\Command;

class GeneratePublicationSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publications:generate-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slugs for publications that don\'t have them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating slugs for publications...');

        $publications = Publication::whereNull('slug')->orWhere('slug', '')->get();

        if ($publications->isEmpty()) {
            $this->info('All publications already have slugs.');
            return 0;
        }

        $bar = $this->output->createProgressBar($publications->count());
        $bar->start();

        foreach ($publications as $publication) {
            $publication->slug = $publication->generateSlug();
            $publication->save();

            $this->line('');
            $this->info("Updated: {$publication->title} -> {$publication->slug}");

            $bar->advance();
        }

        $bar->finish();
        $this->line('');
        $this->info("Successfully generated slugs for {$publications->count()} publications.");

        return 0;
    }
}
