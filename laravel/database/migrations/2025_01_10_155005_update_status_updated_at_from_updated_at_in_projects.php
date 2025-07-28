<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class UpdateStatusUpdatedAtFromUpdatedAtInProjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('projects')
            ->whereNull('status_updated_at')
            ->update([
                'status_updated_at' => DB::raw('updated_at')
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('projects')
            ->whereNull('status_updated_at')
            ->update([
                'status_updated_at' => null
            ]);
    }
}
