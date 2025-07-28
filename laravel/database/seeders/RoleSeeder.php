<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->truncate();


        Role::insert([
            ['name' => 'admin'],
            ['name' => 'client'],
            ['name' => 'expert'],
            ['name' => 'member'],
        ]);
    }
}
