<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class UpdateSourceForUsersWithNullPartnerId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update 'source' to 'Website Direct' where 'partner_id' is null
        DB::table('users')
            ->whereNull('partner_id')
            ->update(['source' => 'Website Direct']);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('users')
            ->whereNull('partner_id')
            ->update(['source' => null]);
    }
}
