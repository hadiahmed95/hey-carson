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
        Schema::create('experts_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expert_id');
            $table->integer('leads')->default(0);
            $table->integer('cta_clicks')->default(0);
            $table->integer('listing_page_visits')->default(0);
            $table->integer('unique_visits')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('expert_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experts_stats');
    }
};
