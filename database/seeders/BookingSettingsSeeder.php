<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('booking_settings')->insert([
            ['key' => 'slot_duration_minutes', 'value' => '120', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'open_time', 'value' => '10:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'close_time', 'value' => '22:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'capacity_per_unit', 'value' => '5', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
