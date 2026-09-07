<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PelatihanPolijatiSeeder extends Seeder
{
    public function run(): void
    {
        $institution = \App\Models\Institution::first();
        $institutionId = $institution ? $institution->id : null;

        // PIC — Rakha Bagus
        $pic = User::firstOrCreate(
            ['email' => 'rakha@groster.com'],
            [
                'name'           => 'Rakha Bagus',
                'password'       => Hash::make('1234'),
                'institution_id' => $institutionId,
            ]
        );
        if (!$pic->hasRole('pic')) {
            $pic->assignRole('pic');
        }

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

        $participantUsers = [];
        foreach ($participants as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'           => $data['name'],
                    'password'       => Hash::make('1234'),
                    'institution_id' => $institutionId,
                ]
            );
            if (!$user->hasRole('participant')) {
                $user->assignRole('participant');
            }
            $participantUsers[] = $user;
        }

        // Event
        $event = Event::create([
            'name'        => 'Pelatihan Mahasiswa Polijati',
            'user_id'     => $pic->id,
            'start_date'  => '2026-07-05 01:00:00',
            'end_date'    => '2026-07-05 22:40:00',
            'is_approved' => 1,
        ]);

        // Assign semua user ke acara
        $allUserIds = collect($participantUsers)->pluck('id')->push($pic->id);
        $event->participants()->attach($allUserIds);
    }
}
