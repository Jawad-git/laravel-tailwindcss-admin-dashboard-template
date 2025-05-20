<?php

namespace Database\Seeders;

use App\Models\SwimmingPool;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Services\LanguageManagementService;
use Illuminate\Database\Seeder;

class SwimmingPoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $restaurant = SwimmingPool::create();
        $languages = LanguageManagementService::getLanguages();
        foreach ($languages['data'] as $value) {
            $restaurant->translations()->updateOrCreate(
                [
                    'language_id' => $value['id'],
                    'model_type' => SwimmingPool::class,
                ],
                [
                    'name' => "this is the default swimming pool name in " . $value['name'],
                    'description' => "this is the default swimming pool description in " . $value['name']
                ]
            );
        }
    }
}
