<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Institution::create([
            'name' => 'Default Institution',
            'address' => 'Default Address',
            'contact_person' => 'Default Person',
            'contact_phone' => '0000000000'
        ]);
    }
}
