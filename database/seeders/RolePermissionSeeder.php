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

            // settings
            'view-website-setting',
            'update-website-setting',
            'view-backend-setting',
            'update-backend-setting',

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

            //customer
            'list-customer',
            'create-customer',
            'edit-customer',
            'delete-customer',

            //stock
            'list-stock',
            'manage-stock',

            //order status
            'list-order-status',
            'create-order-status',
            'edit-order-status',
            'delete-order-status',

            //orders
            'list-online-order',
            'list-sale-order',
            'list-returned-order',
            'list-canceled-order',
            'view-order',
            'create-order',
            'edit-order',
            'delete-order',
            
            //instagram
            'list-instagram',
            'create-instagram',
            'edit-instagram',
            'delete-instagram',
            
            //order status changes
            'change-status-pending',
            'change-status-received',
            'change-status-packed',
            'change-status-shipped',
            'change-status-delivered',
            'change-status-canceled',
            'change-status-returned',
            //banners
            'list-banner',
            'create-banner',
            'edit-banner',
            'delete-banner',
            //pages
            'list-page',
            'create-page',
            'edit-page',
            'delete-page',

            // Seasons
            'list-season',
            'create-season',
            'edit-season',
            'delete-season',

            // Discounts
            'list-discount',
            'create-discount',
            'edit-discount',
            'delete-discount',

            // Flash Modals
            'list-flash-modal',
            'create-flash-modal',
            'edit-flash-modal',
            'delete-flash-modal',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $operatorRole = Role::firstOrCreate(['name' => 'Operator', 'guard_name' => 'web']);
        $warehouseManagerRole = Role::firstOrCreate(['name' => 'Warehouse Manager', 'guard_name' => 'web']);

        // Give all permissions to Super Admin
        $superAdminRole->givePermissionTo(Permission::all());
        
        // Create Buzz User (Super Admin)
        $buzzAdmin = \App\Models\User::firstOrCreate(
            ['email' => 'buzz@gmail.com'],
            [
                'fname' => 'Super',
                'lname' => 'Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('buzzadmin'),
            ]
        );
        $buzzAdmin->assignRole('Super Admin');

        // Create Developer User (Super Admin)
        $developer = \App\Models\User::firstOrCreate(
            ['email' => 'tiger@gmail.com'],
            [
                'fname' => 'Tiger',
                'lname' => 'Developer',
                'password' => \Illuminate\Support\Facades\Hash::make('tiger'),
            ]
        );
        $developer->assignRole('Super Admin');
    }
}
