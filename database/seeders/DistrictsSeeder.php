<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/DistrictsSeeder.php
public function run()
{
    DB::table('districts')->insert([
        // Đà Nẵng
        ['province_id' => 1, 'name' => 'Hải Châu'],
        ['province_id' => 1, 'name' => 'Sơn Trà'],
        ['province_id' => 1, 'name' => 'Ngũ Hành Sơn'],
        ['province_id' => 1, 'name' => 'Thanh Khê'],
        ['province_id' => 1, 'name' => 'Liên Chiểu'],
        ['province_id' => 1, 'name' => 'Cẩm Lệ'],
        ['province_id' => 1, 'name' => 'Hoà Vang'],
        ['province_id' => 1, 'name' => 'Hoàng Sa'],  // Đảo Hoàng Sa

    ]);
}

}
