<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('banners')->truncate();


        Banner::insert([
            [
                'type' => 'info',
                'content_type' => 'expertMatched'
            ],
            [
                'type' => 'success',
                'content_type' => 'clientOffer'
            ],
            [
                'type' => 'success',
                'content_type' => 'clientScope'
            ],
            [
                'type' => 'info',
                'content_type' => 'teamAdded'
            ],
            [
                'type' => 'info',
                'content_type' => 'expertCompleted'
            ],
            [
                'type' => 'critical',
                'content_type' => 'clientCompleted'
            ],
            [
                'type' => 'success',
                'content_type' => 'clientCompleted'
            ],
        ]);
    }
}
