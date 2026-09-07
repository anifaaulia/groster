<?php

namespace App\Mail;

use App\Models\RoomBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RoomBookingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $type;

    /**
     * Create a new message instance.
     */
    public function __construct(RoomBooking $booking, string $type)
    {
        $this->booking = $booking;
        $this->type = $type; // 'requested_admin', 'requested_pic', 'approved', 'rejected'
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $roomName = $this->booking->room ? $this->booking->room->name : 'Ruangan';
        $subject = match ($this->type) {
            'requested_admin' => "[G-Roster] Pengajuan Peminjaman Ruangan Baru - {$roomName}",
            'requested_pic' => "[G-Roster] Konfirmasi Pengajuan Peminjaman Ruangan - {$roomName}",
            'approved' => "[G-Roster] Peminjaman Ruangan DISETUJUI - {$roomName}",
            'rejected' => "[G-Roster] Peminjaman Ruangan DITOLAK - {$roomName}",
            default => "[G-Roster] Informasi Peminjaman Ruangan - {$roomName}",
        };

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.roombooking',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
