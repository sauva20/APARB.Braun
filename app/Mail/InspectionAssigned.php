<?php

namespace App\Mail;

use App\Models\JadwalInspeksi;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InspectionAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $jadwal;

    public $pin;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, JadwalInspeksi $jadwal, ?string $pin = null)
    {
        $this->user = $user;
        $this->jadwal = $jadwal;
        $this->pin = $pin;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pemberitahuan Jadwal Inspeksi APAR',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.inspection_assigned',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
