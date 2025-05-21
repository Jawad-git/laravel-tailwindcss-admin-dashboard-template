<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;



class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $permissions = [


            'admin-list',
            'admin-create',
            'admin-edit',
            'admin-delete',
            'admin-profile',

            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            'room-list',
            'room-create',
            'room-edit',
            'room-delete',

            'category-list',
            'category-create',
            'category-edit',
            'category-delete',

            'amenity-list',
            'amenity-create',
            'amenity-edit',
            'amenity-delete',

            
            'food-list',
            'food-create',
            'food-edit',
            'food-delete',
            
            'cuisine-list',
            'cuisine-create',
            'cuisine-edit',
            'cuisine-delete',

            'analytics-list',
            'main-list',

            'restaurant-list',
            'restaurant-edit',
            'pool-list',
            'pool-edit',
            'about-list',
            'about-edit',
            'social-list',
            'social-edit',

            'logs-list',
            'logs-delete'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
