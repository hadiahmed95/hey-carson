<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'first_name' => 'Jonathan',
                'last_name' => 'K.',
                'role_id' => 1,
                'email' => 'jon@shopexperts.com',
                'url' => 'shopexperts.com',
                'password' => Hash::make('Vm}I8W30D5K?'),
                'created_at' => Carbon::now()
            ],
            [
                'first_name' => 'Evgeniya',
                'last_name' => 'S.',
                'role_id' => 1,
                'email' => 'evgeniya@shopexperts.com',
                'url' => 'shopexperts.com',
                'password' => Hash::make('Vm}I8W30D5K?'),
                'created_at' => Carbon::now()
            ],
            [
                'first_name' => 'Gabriel',
                'last_name' => 'M.',
                'role_id' => 1,
                'email' => 'gabriel@shopexperts.com',
                'url' => 'shopexperts.com',
                'password' => Hash::make('Vm}I8W30D5K?'),
                'created_at' => Carbon::now()
            ],
            [
                'first_name' => 'Ana',
                'last_name' => 'N.',
                'role_id' => 1,
                'email' => 'ana@shopexperts.com',
                'url' => 'shopexperts.com',
                'password' => Hash::make('Vm}I8W30D5K?'),
                'created_at' => Carbon::now()
            ],
            [
                'first_name' => 'Lidia',
                'last_name' => 'Y.',
                'role_id' => 1,
                'email' => 'lidia@shopexperts.com',
                'url' => 'shopexperts.com',
                'password' => Hash::make('Vm}I8W30D5K?'),
                'created_at' => Carbon::now()
            ],
        ]);
    }
}
