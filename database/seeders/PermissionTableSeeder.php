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

            'food-list',
            'food-create',
            'food-edit',
            'food-delete',

            'menu-list',
            'menu-create',
            'menu-edit',
            'menu-delete',

            'swimmingPool-list',
            'swimmingPool-create',
            'swimmingPool-edit',
            'swimmingPool-delete',

            'aboutUs-list',
            'aboutUs-create',
            'aboutUs-edit',
            'aboutUs-delete',

            'socialMedia-list',
            'socialMedia-create',
            'socialMedia-edit',
            'socialMedia-delete',

            'logs-list',
            'logs-delete'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
