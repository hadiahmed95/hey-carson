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
        Schema::table('reviews', function (Blueprint $table) {
            $table->boolean('is_edited')->default(false)->after('comment');
            $table->string('review_source')->nullable()->after('is_edited');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('lead_id')->nullable()->after('client_id');
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews_and_projects_tables', function (Blueprint $table) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropColumn(['is_edited', 'review_source']);
            });

            Schema::table('projects', function (Blueprint $table) {
                $table->dropForeign(['lead_id']);
                $table->dropColumn('lead_id');
            });
        });
    }
};
