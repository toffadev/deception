<?php
// database/migrations/2024_01_06_000000_create_reactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('reactable'); // Polymorphic (publications, comments)
            $table->enum('type', ['heart', 'cry', 'pray', 'thank_you', 'understand', 'courage']);
            $table->timestamps();

            $table->unique(['user_id', 'reactable_id', 'reactable_type', 'type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reactions');
    }
};
