<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Services\LanguageManagementService;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $restaurant = Restaurant::create();
        $languages = LanguageManagementService::getLanguages();
        foreach ($languages['data'] as $value) {
            $restaurant->translations()->updateOrCreate(
                [
                    'language_id' => $value['id'],
                    'model_type' => Restaurant::class,
                ],
                [
                    'name' => "this is the default restaurant name in " . $value['name'],
                    'description' => "this is the default restaurant description in " . $value['name']
                ]
            );
        }
    }
}
