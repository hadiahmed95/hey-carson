<?php

namespace Database\Seeders;

use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('profiles')->truncate();


        Profile::insert([
            [
                'user_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'country' => 'Canada',
                'url' => 'expert.example.com',
                'about' => 'Example Expert bio',
                'role' => 'Designer', // Designer, Frontend developer, Backend developer
                'experience' => '10+ years', // Less than a years, 1-3 years, 3-5 years, 5-10 years, 10+ years
                'availability' => '40+ hours per week',
                'eng_level' => 'Native',
                'hourly_rate' => 90.00,
                'status' => 'active' // pending, deactivated, active
            ]
        ]);
    }
}
