<?php

namespace Database\Seeders;

use App\Models\Weekday;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WeekdaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Weekday::create(['name' => 'Sunday', 'shorthand' => 'sun', 'number' => 0]);
        Weekday::create(['name' => 'Monday', 'shorthand' => 'mon', 'number' => 1]);
        Weekday::create(['name' => 'Tuesday', 'shorthand' => 'tue', 'number' => 2]);
        Weekday::create(['name' => 'Wednesday', 'shorthand' => 'wed', 'number' => 3]);
        Weekday::create(['name' => 'Thursday', 'shorthand' => 'thu', 'number' => 4]);
        Weekday::create(['name' => 'Friday', 'shorthand' => 'fri', 'number' => 5]);
        Weekday::create(['name' => 'Saturday', 'shorthand' => 'sat', 'number' => 6]);
    }
}
