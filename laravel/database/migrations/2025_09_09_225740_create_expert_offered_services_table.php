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
        Schema::create('expert_offered_services', function (Blueprint $table) {
            $table->id();
            $table->uuid('expert_id')->index();
            $table->uuid('category_id');
            $table->string('category_name', 180);

            $table->uuid('subservice1_id')->nullable();
            $table->string('subservice1_name', 180)->nullable();

            $table->uuid('subservice2_id')->nullable();
            $table->string('subservice2_name', 180)->nullable();

            $table->uuid('subservice3_id')->nullable();
            $table->string('subservice3_name', 180)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('category_id');
            $table->unique(
                ['expert_id','category_id','subservice1_id','subservice2_id','subservice3_id'],
                'expert_offered_services_unique_combo'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (config('app.env') == 'local') {
            Schema::dropIfExists('expert_offered_services');
        }
    }
};
