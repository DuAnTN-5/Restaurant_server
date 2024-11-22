<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/WardsSeeder.php
public function run()
{
    DB::table('wards')->insert([
        // Đà Nẵng - Quận Hải Châu (district_id: 1)
        ['district_id' => 1, 'name' => 'Phường Hải Châu 1'],
        ['district_id' => 1, 'name' => 'Phường Hải Châu 2'],
        ['district_id' => 1, 'name' => 'Phường Thạch Thang'],
        ['district_id' => 1, 'name' => 'Phường Thanh Bình'],
        ['district_id' => 1, 'name' => 'Phường Thuận Phước'],
        ['district_id' => 1, 'name' => 'Phường Hòa Thuận Đông'],
        ['district_id' => 1, 'name' => 'Phường Hòa Thuận Tây'],
        ['district_id' => 1, 'name' => 'Phường Nam Dương'],
        ['district_id' => 1, 'name' => 'Phường Bình Hiên'],
        ['district_id' => 1, 'name' => 'Phường Bình Thuận'],
        ['district_id' => 1, 'name' => 'Phường Hòa Cường Bắc'],
        ['district_id' => 1, 'name' => 'Phường Hòa Cường Nam'],

        // Đà Nẵng - Quận Sơn Trà (district_id: 2)
        ['district_id' => 2, 'name' => 'Phường An Hải Bắc'],
        ['district_id' => 2, 'name' => 'Phường An Hải Đông'],
        ['district_id' => 2, 'name' => 'Phường An Hải Tây'],
        ['district_id' => 2, 'name' => 'Phường Mân Thái'],
        ['district_id' => 2, 'name' => 'Phường Nại Hiên Đông'],
        ['district_id' => 2, 'name' => 'Phường Phước Mỹ'],

        // Đà Nẵng - Quận Ngũ Hành Sơn (district_id: 3)
        ['district_id' => 3, 'name' => 'Phường Khuê Mỹ'],
        ['district_id' => 3, 'name' => 'Phường Hòa Hải'],
        ['district_id' => 3, 'name' => 'Phường Mỹ An'],

        // Đà Nẵng - Quận Thanh Khê (district_id: 4)
        ['district_id' => 4, 'name' => 'Phường Xuân Hà'],
        ['district_id' => 4, 'name' => 'Phường Tân Chính'],
        ['district_id' => 4, 'name' => 'Phường Thạc Gián'],
        ['district_id' => 4, 'name' => 'Phường Chính Gián'],
        ['district_id' => 4, 'name' => 'Phường Vĩnh Trung'],
        ['district_id' => 4, 'name' => 'Phường Thanh Khê Tây'],
        ['district_id' => 4, 'name' => 'Phường Thanh Khê Đông'],
        ['district_id' => 4, 'name' => 'Phường Hòa Khê'],

        // Đà Nẵng - Quận Liên Chiểu (district_id: 5)
        ['district_id' => 5, 'name' => 'Phường Hòa Minh'],
        ['district_id' => 5, 'name' => 'Phường Hòa Khánh Bắc'],
        ['district_id' => 5, 'name' => 'Phường Hòa Khánh Nam'],
        ['district_id' => 5, 'name' => 'Phường Hòa Hiệp Bắc'],
        ['district_id' => 5, 'name' => 'Phường Hòa Hiệp Nam'],

        // Đà Nẵng - Quận Cẩm Lệ (district_id: 6)
        ['district_id' => 6, 'name' => 'Phường Khuê Trung'],
        ['district_id' => 6, 'name' => 'Phường Hòa Thọ Tây'],
        ['district_id' => 6, 'name' => 'Phường Hòa Thọ Đông'],
        ['district_id' => 6, 'name' => 'Phường Hòa Xuân'],

        // Đà Nẵng - Huyện Hòa Vang (district_id: 7)
        ['district_id' => 7, 'name' => 'Xã Hòa Bắc'],
        ['district_id' => 7, 'name' => 'Xã Hòa Liên'],
        ['district_id' => 7, 'name' => 'Xã Hòa Ninh'],
        ['district_id' => 7, 'name' => 'Xã Hòa Sơn'],
        ['district_id' => 7, 'name' => 'Xã Hòa Nhơn'],
        ['district_id' => 7, 'name' => 'Xã Hòa Phú'],
        ['district_id' => 7, 'name' => 'Xã Hòa Phong'],
        ['district_id' => 7, 'name' => 'Xã Hòa Châu'],
        ['district_id' => 7, 'name' => 'Xã Hòa Tiến'],
        ['district_id' => 7, 'name' => 'Xã Hòa Khương'],

        // Đà Nẵng - Huyện đảo Hoàng Sa (district_id: 8)
        ['district_id' => 8, 'name' => 'Xã Hoàng Sa']  // Đảo Hoàng Sa
    ]);
}

}
