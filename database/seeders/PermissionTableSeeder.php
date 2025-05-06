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

            'restaurant-list',
            'restaurant-create',
            'restaurant-edit',
            'restaurant-delete',

            'swimming-pool-list',
            'swimming-pool-create',
            'swimming-pool-edit',
            'swimming-pool-delete',

            'about-us-list',
            'about-us-create',
            'about-us-edit',
            'about-us-delete',

            'social-media-list',
            'social-media-create',
            'social-media-edit',
            'social-media-delete',

            'logs-list',
            'logs-delete'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
