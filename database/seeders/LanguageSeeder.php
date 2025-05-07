<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Language::create(['name' => 'English', 'code' => 'en', 'direction' => 'ltr']);
        Language::create(['name' => 'Arabic', 'code' => 'ar', 'direction' => 'rtl']);
        Language::create(['name' => 'Farsi', 'code' => 'fa', 'direction' => 'rtl']);
    }
}
