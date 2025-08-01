<?php
// database/migrations/2024_01_10_000000_create_solidarity_projects_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('solidarity_projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->decimal('target_amount', 12, 2);
            $table->decimal('current_amount', 12, 2)->default(0);
            $table->string('currency', 3)->default('EUR');
            $table->enum('status', ['planned', 'active', 'completed', 'paused'])->default('planned');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('featured_image_path')->nullable();
            $table->json('beneficiaries_info')->nullable();
            $table->text('impact_description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('solidarity_projects');
    }
};
