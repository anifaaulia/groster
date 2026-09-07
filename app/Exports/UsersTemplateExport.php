<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        return [
            ['Budi Santoso',  'budi@example.com',  'password123', 'Gema Foundation', 'participant'],
            ['Siti Aminah',   'siti@example.com',  'password123', 'Gema Foundation', 'participant'],
        ];
    }

    public function headings(): array
    {
        return ['nama', 'email', 'password', 'instansi', 'peran'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 30,
            'C' => 18,
            'D' => 25,
            'E' => 15,
        ];
    }
}
