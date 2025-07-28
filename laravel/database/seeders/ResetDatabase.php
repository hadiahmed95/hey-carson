<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResetDatabase extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('applications')->truncate();
        DB::table('assignments')->truncate();
        DB::table('banners')->truncate();
        DB::table('client_funds')->truncate();
        DB::table('events')->truncate();
        DB::table('expert_funds')->truncate();
        DB::table('messages')->truncate();
        DB::table('offers')->truncate();
        DB::table('payments')->truncate();
        DB::table('payouts')->truncate();
        DB::table('profiles')->truncate();
        DB::table('projects')->truncate();
        DB::table('reviews')->truncate();
        DB::table('roles')->truncate();
        DB::table('teams')->truncate();
        DB::table('users')->truncate();
        DB::table('user_events')->truncate();
    }
}
