<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('profiles')->truncate();

        Schema::table('profiles', function (Blueprint $table) {
            $table->float('hourly_rate')->change()->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('profiles')->truncate();

        Schema::table('profiles', function (Blueprint $table) {
            $table->string('hourly_rate')->change();
        });
    }
};
