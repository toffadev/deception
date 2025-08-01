<?php
// database/migrations/2024_01_12_000000_create_financial_reports_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('period_type', ['monthly', 'quarterly', 'yearly']);
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('total_donations', 12, 2);
            $table->decimal('total_expenses', 12, 2);
            $table->decimal('administrative_costs', 12, 2);
            $table->json('breakdown'); // Détail des dépenses
            $table->string('report_file_path')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('financial_reports');
    }
};
