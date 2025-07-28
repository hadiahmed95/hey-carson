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
        Schema::table('payouts', function (Blueprint $table) {
            $table->dropColumn('total');
            $table->dropColumn('admin_fee');

            $table->string('type')->default('paypal')->after('amount');
            $table->string('status')->default('created')->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payouts', function (Blueprint $table) {
            $table->float('total')->after('amount')->default(0);
            $table->float('admin_fee')->after('amount')->default(0);
            $table->dropColumn('type');
            $table->dropColumn('status');
        });
    }
};
