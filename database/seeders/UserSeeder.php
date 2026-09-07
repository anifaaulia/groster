<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $institution = \App\Models\Institution::first();
        $institutionId = $institution ? $institution->id : null;

        // Admin Sistem
        $admin = User::create([
            'name'           => 'Admin Sistem',
            'email'          => 'admin@groster.com',
            'password'       => Hash::make('password'),
            'institution_id' => $institutionId,
        ]);
        $admin->assignRole('admin');

        // PIC (Person In Charge) — default
        $pic = User::create([
            'name'           => 'PIC G-Roster',
            'email'          => 'pic@groster.com',
            'password'       => Hash::make('password'),
            'institution_id' => $institutionId,
        ]);
        $pic->assignRole('pic');

        // PIC — Rakha Bagus
        $rakha = User::create([
            'name'           => 'Rakha Bagus',
            'email'          => 'rakha@groster.com',
            'password'       => Hash::make('1234'),
            'institution_id' => $institutionId,
        ]);
        $rakha->assignRole('pic');

        // Participants
        $participants = [
            ['name' => 'Ashya Julia Pratiwi', 'email' => 'ashya@groster.com'],
            ['name' => 'Silvia',               'email' => 'silvia@groster.com'],
            ['name' => 'Nur Ailla Kusuma',     'email' => 'nurailla@groster.com'],
            ['name' => 'Desta Mandela',        'email' => 'desta@groster.com'],
            ['name' => 'Farrel Mahardyka A',   'email' => 'farrel@groster.com'],
            ['name' => 'Akbar Barokah',        'email' => 'akbar@groster.com'],
            ['name' => 'Rahmat Hidayat',       'email' => 'rahmat@groster.com'],
            ['name' => 'Muhamad Yusuf',        'email' => 'yusuf@groster.com'],
            ['name' => 'Rhifa Varhiellio E',   'email' => 'rhifa@groster.com'],
        ];

        foreach ($participants as $data) {
            $user = User::create([
                'name'           => $data['name'],
                'email'          => $data['email'],
                'password'       => Hash::make('1234'),
                'institution_id' => $institutionId,
            ]);
            $user->assignRole('participant');
        }
    }
}
