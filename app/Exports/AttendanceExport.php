<?php

namespace App\Exports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected Event $event;
    protected ?string $dateFrom;
    protected ?string $dateTo;

    public function __construct(Event $event, ?string $dateFrom = null, ?string $dateTo = null)
    {
        $this->event = $event;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        $query = $this->event->attendances()->with(['user', 'room']);

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        return $query->orderBy('created_at')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Peserta',
            'Email',
            'Ruangan',
            'Status Kehadiran',
            'Waktu Tercatat',
        ];
    }

    public function map($att): array
    {
        static $no = 0;
        $no++;

        $statusLabel = match ($att->status) {
            'on_time'    => 'Hadir (Tepat Waktu)',
            'late'       => 'Terlambat',
            'left_early' => 'Pulang Cepat',
            default      => $att->status,
        };

        return [
            $no,
            $att->user ? $att->user->name  : '-',
            $att->user ? $att->user->email : '-',
            $att->room ? $att->room->name  : '-',
            $statusLabel,
            $att->created_at ? $att->created_at->format('Y-m-d H:i:s') : '-',
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
        return 'Presensi ' . $this->event->name;
    }
}
