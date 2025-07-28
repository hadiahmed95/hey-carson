<?php

namespace Database\Seeders;

use App\Models\AdminSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admin_settings')->truncate();

        AdminSetting::insert([
            [
                'type' => 'moderate_projects',
                'value' => 1
            ],
            [
                'type' => 'moderate_questions',
                'value' => 1
            ],
        ]);
    }
}
