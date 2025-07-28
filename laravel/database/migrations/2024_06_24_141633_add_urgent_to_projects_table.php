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
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('urgent')->default(false)->after('client_id');
            $table->unsignedBigInteger('preferred_expert')->nullable()->change();
            $table->renameColumn('preferred_expert', 'preferred_expert_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('urgent');
            $table->string('preferred_expert_id')->nullable()->change();
            $table->renameColumn('preferred_expert_id', 'preferred_expert');
        });
    }
};
