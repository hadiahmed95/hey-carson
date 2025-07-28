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

            $table->decimal('rate', 4, 2)->change();
            $table->integer('communication')->nullable();
            $table->integer('quality')->nullable();
            $table->string('recommendation')->nullable();
            $table->integer('timeToStart')->nullable();
            $table->integer('valueForMoney')->nullable();
            $table->string('valueRange')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {

            $table->integer('rate')->change();
            $table->dropColumn('communication');
            $table->dropColumn('quality');
            $table->dropColumn('recommendation');
            $table->dropColumn('timeToStart');
            $table->dropColumn('valueForMoney');
            $table->dropColumn('valueRange');
        });
    }
};
