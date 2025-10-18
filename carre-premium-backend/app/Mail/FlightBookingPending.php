<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Email de confirmation au client que sa demande de réservation
 * a été reçue et est en cours de traitement
 */
class FlightBookingPending extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $flightData;
    public $passengersData;

    public function __construct(Booking $booking, array $flightData, array $passengersData)
    {
        $this->booking = $booking;
        $this->flightData = $flightData;
        $this->passengersData = $passengersData;
    }

    public function build()
    {
        return $this->subject('✅ Demande de Réservation Reçue - ' . $this->booking->booking_reference)
                    ->view('emails.flight-booking-pending');
    }
}
