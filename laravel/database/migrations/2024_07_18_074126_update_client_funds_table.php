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
        Schema::table('client_funds', function (Blueprint $table) {
            $table->float('price')->after('prepaid_hours');
            $table->boolean('used_pack')->default(0)->after('prepaid_hours');
            $table->integer('used_hours')->default(0)->after('prepaid_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_funds', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('used_pack');
            $table->dropColumn('used_hours');
        });
    }
};
