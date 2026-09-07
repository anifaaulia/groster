<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Institution
            'read institution',
            'manage institution',

            // User
            'read user',
            'manage user',

            // Room
            'read room',
            'manage room',

            // Facility
            'read facility',
            'manage facility',

            // Event
            'read event',
            'manage event',

            // Room Booking
            'read booking',
            'manage booking',
            'approve booking',
            'change booking status',

            // Attendance
            'read attendance',
            'manage attendance',
            'scan attendance'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        /* Admin Sistem */
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->givePermissionTo($permissions);

        /* PIC (Person In Charge) */
        $pic = Role::firstOrCreate(['name' => 'pic', 'guard_name' => 'web']);
        $pic->givePermissionTo([
            'read room',
            'read facility',
            'read booking',
            'read event',
            'read attendance',
            'scan attendance'
        ]);

        /* Peserta (Participant) */
        $participant = Role::firstOrCreate(['name' => 'participant', 'guard_name' => 'web']);
        $participant->givePermissionTo([
            'read room',
            'read event',
            'read attendance'
        ]);
    }
}