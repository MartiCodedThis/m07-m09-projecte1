<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class VisibilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $visibilities = [
            ['id' => 1, 'name' => 'public'],
            ['id' => 2, 'name' => 'contacts'],
            ['id' => 3, 'name' => 'private'],
        ];

        DB::table('visibilities')->insert($visibilities);
    }
}
