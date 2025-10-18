<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Email de notification à l'équipe admin pour traitement manuel
 * d'une demande de réservation de vol
 */
class AdminFlightBookingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $flightData;
    public $passengersData;
    public $servicesData;

    public function __construct(Booking $booking, array $flightData, array $passengersData, array $servicesData = [])
    {
        $this->booking = $booking;
        $this->flightData = $flightData;
        $this->passengersData = $passengersData;
        $this->servicesData = $servicesData;
    }

    public function build()
    {
        return $this->subject('🔔 Nouvelle Demande de Réservation Vol - ' . $this->booking->booking_reference)
                    ->view('emails.admin-flight-booking-notification');
    }
}
