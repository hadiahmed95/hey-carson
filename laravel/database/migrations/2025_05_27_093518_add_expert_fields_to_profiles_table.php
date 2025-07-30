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
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('expert_type')->default('freelancer')->after('status');
            $table->string('agency_name')->nullable()->after('expert_type');
            $table->string('partner_tier')->nullable()->after('agency_name');
            $table->string('partner_link_directory')->nullable()->after('partner_tier');
            $table->string('linkedIn_url')->nullable()->after('partner_link_directory');
            $table->string('min_project_budget')->nullable()->after('linkedIn_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'expert_type',
                'agency_name',
                'partner_tier',
                'partner_link_directory',
                'linkedIn_url',
                'min_project_budget',
            ]);
        });
    }
};
