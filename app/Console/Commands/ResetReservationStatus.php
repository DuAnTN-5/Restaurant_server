<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation; // Thêm dòng import cho lớp Reservation
use App\Models\Table;      // Thêm dòng import cho lớp Table

class ResetReservationStatus extends Command
{

    protected $signature = 'app:reset-reservation-status';


    protected $description = 'Reset trạng thái đặt chỗ và bàn';

    public function handle()
    {

        Reservation::resetStatusAfterDayEnd();
        Reservation::resetStatusAfterThreeHours();
        Table::resetTablesAfterDayEnd();

        $this->info('Trạng thái đặt chỗ và bàn đã được cập nhật.');
    }
}
