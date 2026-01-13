<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employeeId = 'bdfe188b-19a5-49dc-ac81-437cd3c9f56b';

        $startDate = Carbon::now()->subMonths(6)->startOfDay();
        $endDate   = Carbon::now()->startOfDay();

        $data = [];

        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {

            // Skip Saturday (6) & Sunday (0)
            if ($date->isWeekend()) {
                continue;
            }

            // Clock in antara 08:00 - 09:00
            $clockIn = $date->copy()->setTime(
                rand(8, 9),
                rand(0, 59),
                0
            );

            // Jam kerja 8 - 10 jam
            $workHours = rand(8, 10);
            $clockOut = $clockIn->copy()->addHours($workHours)->addMinutes(rand(0, 30));

            $data[] = [
                'employee_id' => $employeeId,
                'date'        => $date->toDateString(),
                'clock_in'    => $clockIn->format('H:i:s'),
                'clock_out'   => $clockOut->format('H:i:s'),
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        DB::table('attendances')->insert($data);
    }
}
