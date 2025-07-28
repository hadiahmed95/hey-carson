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
            $table->string('paypal_email')->nullable()->after('hourly_rate');
            $table->string('wise_email')->nullable()->after('hourly_rate');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('password_changed')->nullable()->after('remember_token');
            $table->string('photo')->nullable()->after('remember_token');
            $table->string('project_notifications')->default('instant')->after('remember_token');
            $table->string('new_messages')->default('daily')->after('remember_token');
            $table->string('timezone')->nullable()->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('paypal_email');
            $table->dropColumn('wise_email');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('password_changed');
            $table->dropColumn('photo');
            $table->dropColumn('project_notifications');
            $table->dropColumn('new_messages');
            $table->dropColumn('timezone');
        });
    }
};
