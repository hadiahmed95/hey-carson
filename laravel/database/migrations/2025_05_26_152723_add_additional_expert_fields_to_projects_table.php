<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalExpertFieldsToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'is_additional_experts')) {
                $table->boolean('is_additional_experts')->default(false);
            }

            if (!Schema::hasColumn('projects', 'additional_experts')) {
                $table->json('additional_experts')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'is_additional_experts',
                'additional_experts',
            ]);
        });
    }
}
