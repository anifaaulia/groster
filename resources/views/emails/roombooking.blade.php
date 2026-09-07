<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Peminjaman Ruangan</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f6f9fc;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #f6f9fc;
            padding: 40px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .header {
            padding: 40px 30px;
            text-align: center;
            color: #ffffff;
        }
        .header.pending {
            background: linear-gradient(135deg, #ff9800, #ffb74d);
        }
        .header.approved {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
        }
        .header.rejected {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .content {
            padding: 40px 30px;
            color: #333333;
            line-height: 1.6;
        }
        .content h2 {
            font-size: 20px;
            margin-top: 0;
            color: #1a1a1a;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            margin-bottom: 25px;
        }
        .status-badge.pending {
            background-color: #fff3e0;
            color: #e65100;
        }
        .status-badge.approved {
            background-color: #e8f8f5;
            color: #117a65;
        }
        .status-badge.rejected {
            background-color: #fdebd0;
            color: #ba4a00;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            background-color: #f8fafc;
            border-radius: 8px;
            overflow: hidden;
        }
        .details-table td {
            padding: 15px 20px;
            border-bottom: 1px solid #edf2f7;
            font-size: 14px;
        }
        .details-table td.label {
            font-weight: 600;
            color: #718096;
            width: 35%;
        }
        .details-table td.value {
            color: #2d3748;
            font-weight: 500;
        }
        .details-table tr:last-child td {
            border-bottom: none;
        }
        .btn {
            display: inline-block;
            background-color: #4f46e5;
            color: #ffffff !important;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);
            text-align: center;
        }
        .footer {
            padding: 30px;
            text-align: center;
            font-size: 12px;
            color: #a0aec0;
            background-color: #f8fafc;
            border-top: 1px solid #edf2f7;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            @php
                $statusClass = 'pending';
                $statusText = 'Menunggu Verifikasi';
                if ($type === 'approved') {
                    $statusClass = 'approved';
                    $statusText = 'Disetujui';
                } elseif ($type === 'rejected') {
                    $statusClass = 'rejected';
                    $statusText = 'Ditolak';
                }
            @endphp

            <div class="header {{ $statusClass }}">
                <h1>Notifikasi G-Roster</h1>
            </div>

            <div class="content">
                @if($type === 'requested_admin')
                    <h2>Halo Admin,</h2>
                    <p>Ada pengajuan peminjaman ruangan baru yang membutuhkan verifikasi Anda. Berikut adalah rincian pengajuan tersebut:</p>
                    <div class="status-badge pending">Menunggu Verifikasi</div>
                @elseif($type === 'requested_pic')
                    <h2>Halo {{ $booking->user->name }},</h2>
                    <p>Pengajuan peminjaman ruangan Anda telah berhasil dikirim dan saat ini sedang menunggu verifikasi oleh Admin Sistem. Rincian pengajuan Anda:</p>
                    <div class="status-badge pending">Menunggu Verifikasi</div>
                @elseif($type === 'approved')
                    <h2>Halo {{ $booking->user->name }},</h2>
                    <p>Kabar baik! Pengajuan peminjaman ruangan Anda telah <strong>DISETUJUI</strong> oleh Admin Sistem. Rincian peminjaman Anda:</p>
                    <div class="status-badge approved">Disetujui</div>
                @elseif($type === 'rejected')
                    <h2>Halo {{ $booking->user->name }},</h2>
                    <p>Mohon maaf, pengajuan peminjaman ruangan Anda telah <strong>DITOLAK</strong> oleh Admin Sistem. Rincian pengajuan:</p>
                    <div class="status-badge rejected">Ditolak / Dibatalkan</div>
                @endif

                <table class="details-table">
                    <tr>
                        <td class="label">Ruangan</td>
                        <td class="value">{{ $booking->room ? $booking->room->name : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Kegiatan Acara</td>
                        <td class="value">{{ $booking->event ? $booking->event->name : 'Tanpa Acara khusus' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Peminjam (PIC)</td>
                        <td class="value">{{ $booking->user ? $booking->user->name : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tanggal</td>
                        <td class="value">{{ \Carbon\Carbon::parse($booking->date)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Waktu</td>
                        <td class="value">
                            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} s.d 
                            {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WIB
                        </td>
                    </tr>
                </table>

                @if($type === 'requested_admin')
                    <div style="text-align: center;">
                        <a href="{{ route('roombookings.show', $booking->id) }}" class="btn">Proses Verifikasi Sekarang</a>
                    </div>
                @else
                    <div style="text-align: center;">
                        <a href="{{ route('roombookings.index') }}" class="btn">Lihat Peminjaman Saya</a>
                    </div>
                @endif
            </div>

            <div class="footer">
                <p>Email ini dikirim secara otomatis oleh Sistem G-Roster.</p>
                <p>&copy; {{ date('Y') }} G-Roster. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
