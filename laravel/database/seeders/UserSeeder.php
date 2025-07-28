<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->truncate();


        User::insert([
            [
                'first_name' => 'Admin',
                'last_name' => 'Shopexperts',
                'role_id' => 1,
                'email' => 'admin@shopexperts.com',
                'url' => 'shopexperts.com',
                'password' => Hash::make('Vm}I8W30D5K?'),
                'created_at' => Carbon::now()
            ],
            [
                'first_name' => 'Client',
                'last_name' => 'User',
                'role_id' => 2,
                'email' => 'client.user@example.com',
                'url' => 'client.example.com',
                'password' => Hash::make('ClientPassword-02-HeyCarson'),
                'created_at' => Carbon::now()
            ],
            [
                'first_name' => 'Expert',
                'last_name' => 'User',
                'role_id' => 3,
                'email' => 'expert.user@example.com',
                'url' => 'expert.example.com',
                'password' => Hash::make('ExpertPassword-03-HeyCarson'),
                'created_at' => Carbon::now()
            ]
        ]);
    }
}
