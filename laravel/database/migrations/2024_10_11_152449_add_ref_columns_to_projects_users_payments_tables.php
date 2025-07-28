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
        Schema::table('projects_users_payments_tables', function (Blueprint $table) {
            Schema::table('projects', function (Blueprint $table) {
                $table->uuid('click_id')->nullable()->after('id');
            });

            Schema::table('users', function (Blueprint $table) {
                $table->uuid('click_id')->nullable()->after('id');
                $table->uuid('partner_id')->nullable()->after('id');
                $table->uuid('program_id')->nullable()->after('id');
            });

            Schema::table('payments', function (Blueprint $table) {
                $table->uuid('click_id')->nullable()->after('id');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects_users_payments_tables', function (Blueprint $table) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('click_id');
            });

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('click_id');
                $table->dropColumn('partner_id');
                $table->dropColumn('program_id');
            });

            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn('click_id');
            });
        });
    }
};
