<?php
// database/migrations/2024_01_07_000000_create_donations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();

            // Donateur (nullable pour dons anonymes non connectés)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Types de dons
            $table->enum('type', ['platform', 'blind_support']); // plateforme ou malvoyants
            $table->foreignId('publication_id')->nullable()->constrained()->nullOnDelete(); // Pour dons sous publications

            // Montant et fréquence
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('EUR');
            $table->enum('frequency', ['one_time', 'monthly'])->default('one_time');

            // Paiement Stripe
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_subscription_id')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');

            // Anonymat et infos donateur
            $table->boolean('is_anonymous')->default(false);
            $table->json('anonymous_donor_info')->nullable(); // Pour dons anonymes (email, nom)
            $table->boolean('tax_receipt_requested')->default(false);
            $table->string('tax_receipt_path')->nullable();

            // Message facultatif
            $table->text('message')->nullable();

            // Timestamps
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('next_payment_at')->nullable(); // Pour récurrents
            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index(['publication_id']);
            $table->index(['user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('donations');
    }
};
