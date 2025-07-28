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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('expert_id');

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('freelancer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');

        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('freelancer_id')->after('client_id');
        });
    }
};
