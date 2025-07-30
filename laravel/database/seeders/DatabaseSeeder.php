<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSettingsSeeder::class,
            BannerSeeder::class,
            NewDashboardClientSeeder::class,
            PackagedServicesSeeder::class,
            InvoicesAndStatusHistorySeeder::class,
            EventSeeder::class,
            ProfileSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            ServiceCategoriesSeeder::class,
        ]);
    }
}
