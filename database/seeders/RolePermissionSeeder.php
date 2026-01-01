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
        // إنشاء الصلاحيات
        $permissions = [
            'view tasks',
            'create tasks',
            'edit tasks',
            'delete tasks',
            'manage users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // إنشاء دور Admin وإعطائه كل الصلاحيات
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        // إنشاء دور User وإعطائه صلاحيات المهام فقط
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->syncPermissions(['view tasks', 'create tasks', 'edit tasks', 'delete tasks']);
    }
}
