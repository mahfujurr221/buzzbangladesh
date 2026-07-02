<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()['cache']->forget('spatie.permission.cache');

        // Create permissions
        $permissions = [
            //role
            'list-role',
            'create-role',
            'edit-role',
            'delete-role',

            //permission
            'list-permission',
            'create-permission',
            'edit-permission',
            'delete-permission',

            //user
            'list-user',
            'create-user',
            'edit-user',
            'delete-user',

            //profile
            'list-profile',
            'edit-profile',
            'delete-profile',

            //setting
            'update-setting',

            //brand
            'list-brand',
            'create-brand',
            'edit-brand',
            'delete-brand',

            //category
            'list-category',
            'create-category',
            'edit-category',
            'delete-category',

            // Sub Category Modules
            'list-subcategory',
            'create-subcategory',
            'edit-subcategory',
            'delete-subcategory',
            
            // Product Modules
            'list-product',
            'create-product',
            'edit-product',
            'delete-product',

            //size
            'list-size',
            'create-size',
            'edit-size',
            'delete-size',

            //color
            'list-color',
            'create-color',
            'edit-color',
            'delete-color',

            //dashboard
            'dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $role = Role::findByName('Admin');
        $role->givePermissionTo(Permission::all());
    }
}
