<?php

namespace Database\Seeders;

use App\Livewire\SwimmingPool;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            DashboardTableSeeder::class,
            LanguageSeeder::class,
            PermissionTableSeeder::class,
            CreateAdminUserSeeder::class,
            RestaurantSeeder::class,
            AboutUsSeeder::class,
            SwimmingPoolSeeder::class,
            WeekdaySeeder::class,

        ]);
    }
}
