<?php
// database/migrations/2024_01_02_000000_create_publications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('custom_author_name')->nullable(); // For manually entered author names
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['testimony', 'reflection', 'poetry']);
            $table->boolean('is_anonymous')->default(false);
            $table->enum('status', ['draft', 'published', 'moderated', 'hidden'])->default('published');
            $table->integer('views_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->integer('reactions_count')->default(0);
            $table->decimal('donations_amount', 10, 2)->default(0); // Total des dons reçus
            $table->json('auto_tags')->nullable(); // Tags générés automatiquement
            $table->text('moderation_reason')->nullable();
            $table->timestamp('moderated_at')->nullable();
            $table->foreignId('moderated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'status']);
            $table->index(['user_id', 'status']);
            $table->fullText(['title', 'content']); // MySQL Full-Text Index
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    public function down()
    {
        Schema::dropIfExists('publications');
    }
};
