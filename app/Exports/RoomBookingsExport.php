<?php

namespace App\Exports;

use App\Models\RoomBooking;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RoomBookingsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function collection()
    {
        return RoomBooking::with(['room', 'user', 'event'])->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Ruangan',
            'Kegiatan / Acara',
            'Peminjam',
            'Tanggal',
            'Waktu Mulai',
            'Waktu Selesai',
            'Status',
        ];
    }

    public function map($booking): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $booking->room  ? $booking->room->name  : '-',
            $booking->event ? $booking->event->name : '-',
            $booking->user  ? $booking->user->name  : '-',
            Carbon::parse($booking->date)->format('Y-m-d'),
            Carbon::parse($booking->start_time)->format('H:i'),
            Carbon::parse($booking->end_time)->format('H:i'),
            $booking->is_active ? 'Disetujui' : 'Menunggu Verifikasi',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Peminjaman Ruangan';
    }
}
