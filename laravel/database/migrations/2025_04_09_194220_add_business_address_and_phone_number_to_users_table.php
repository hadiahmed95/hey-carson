<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBusinessAddressAndPhoneNumberToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('business_address')->nullable();
            $table->string('phone_number')->nullable()->after('business_address');
            $table->string('languages')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove 'business_address' and 'phone_number' columns
            $table->dropColumn('business_address');
            $table->dropColumn('phone_number');
            $table->dropColumn('languages');
        });
    }
}
