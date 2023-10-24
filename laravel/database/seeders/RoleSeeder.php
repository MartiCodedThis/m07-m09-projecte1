<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'name' => 'author'],
            ['id' => 2, 'name' => 'editor'],
            ['id' => 3, 'name' => 'admin'],
        ];

        DB::table('roles')->insert($roles);
    }
}
