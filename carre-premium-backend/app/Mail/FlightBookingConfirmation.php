<?php

namespace App\Mail;

use App\Models\FlightBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FlightBookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $passengers;
    public $segments;
    public $extras;

    /**
     * Create a new message instance.
     */
    public function __construct(FlightBooking $booking)
    {
        $this->booking = $booking->load('user', 'booking.payment');
        $this->passengers = json_decode($booking->passengers_data, true) ?? [];
        $this->segments = json_decode($booking->segments_data, true) ?? [];
        $this->extras = json_decode($booking->extras_data, true) ?? [];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✈️ Confirmation de Réservation - Vol ' . $this->booking->pnr,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.flight-booking-confirmation',
            with: [
                'booking' => $this->booking,
                'user' => $this->booking->user,
                'passengers' => $this->passengers,
                'segments' => $this->segments,
                'extras' => $this->extras,
            ],
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
