<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visually_impaired_people', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->enum('sexe', ['M', 'F']);
            $table->integer('age')->nullable();
            $table->string('lieu_residence')->nullable();
            $table->string('telephone');
            $table->string('type_voyance')->nullable();
            $table->boolean('traitement_en_cours')->default(false);
            $table->string('photo_path')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index(['nom', 'prenom']);
            $table->index('sexe');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visually_impaired_people');
    }
};
