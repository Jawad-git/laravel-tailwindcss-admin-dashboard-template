<?php

namespace Database\Seeders;

use App\Models\SocialMedia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SocialMedia::create(['name' => 'WhatsApp',]);
        SocialMedia::create(['name' => 'Telegram',]);
        SocialMedia::create(['name' => 'Instagram',]);
        SocialMedia::create(['name' => 'Facebook',]);
        SocialMedia::create(['name' => 'X']);
        SocialMedia::create(['name' => 'YouTube',]);
        SocialMedia::create(['name' => 'TikTok']);
    }
}
