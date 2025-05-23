<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userSuperAdmin = User::create([
            'name' => 'Admin Admin',
            'email' => 'admin@stagelive.co',
            'password' => Hash::make('secret'),
            'phone_code' => 121
        ]);
        $roleSuperAdmin = Role::FirstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);

        $permissions = Permission::all();

        $roleSuperAdmin->syncPermissions($permissions);

        $userSuperAdmin->assignRole($roleSuperAdmin);
    }
}
