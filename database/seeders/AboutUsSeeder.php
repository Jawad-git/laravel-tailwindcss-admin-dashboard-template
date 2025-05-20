<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Services\LanguageManagementService;
use App\Models\AboutUs;
use Illuminate\Database\Seeder;

class AboutUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $aboutus = AboutUs::create();
        $languages = LanguageManagementService::getLanguages();
        foreach ($languages['data'] as $value) {
            $aboutus->translations()->updateOrCreate(
                [
                    'language_id' => $value['id'],
                    'model_type' => AboutUs::class,
                ],
                [
                    'description' => "this is the default about us description in " . $value['name']
                ]
            );
        }
    }
}
