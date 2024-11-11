<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvincesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/ProvincesSeeder.php
public function run()
{
    DB::table('provinces')->insert([
        ['name' => 'Đà Nẵng'],
        ['name' => 'Hà Nội'],
        ['name' => 'Hồ Chí Minh'],
        // Thêm các tỉnh khác vào đây
    ]);
}

}
