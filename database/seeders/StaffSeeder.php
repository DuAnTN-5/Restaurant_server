<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Staff;
use Carbon\Carbon;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staffData = [
            [
                'name' => 'Trần Minh Quân',
                'position' => 'Quản lý',
                'hire_date' => Carbon::createFromFormat('Y-m-d', '2022-01-10'),
                'department' => 'Kinh doanh',
                'salary' => 12000000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Lan Hương',
                'position' => 'Kế toán',
                'hire_date' => Carbon::createFromFormat('Y-m-d', '2021-03-15'),
                'department' => 'Tài chính',
                'salary' => 10000000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Nhật Định',
                'position' => 'Nhân viên',
                'hire_date' => Carbon::createFromFormat('Y-m-d', '2023-05-20'),
                'department' => 'Hỗ trợ',
                'salary' => 8000000.00,
                'status' => 'inactive',
            ],
            [
                'name' => 'Hải',
                'position' => 'Trưởng phòng',
                'hire_date' => Carbon::createFromFormat('Y-m-d', '2020-07-25'),
                'department' => 'Nhân sự',
                'salary' => 15000000.00,
                'status' => 'terminated',
            ],
            [
                'name' => 'Phú',
                'position' => 'Thực tập sinh',
                'hire_date' => Carbon::createFromFormat('Y-m-d', '2023-09-01'),
                'department' => 'Marketing',
                'salary' => 6000000.00,
                'status' => 'active',
            ],
        ];

        foreach ($staffData as $staff) {
            Staff::create($staff);
        }
    }
}
