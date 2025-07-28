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
        Schema::create('review_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expert_id');
            $table->string('client_full_name');
            $table->string('client_company_name')->nullable();
            $table->string('client_company_website')->nullable();
            $table->string('project_id');
            $table->boolean('hired_on_shopexperts')->default(false);
            $table->boolean('repeated_client')->default(false);
            $table->boolean('is_client_reviewed')->default(false);
            $table->string('project_value_range')->nullable();
            $table->text('message');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_requests');
    }
};
