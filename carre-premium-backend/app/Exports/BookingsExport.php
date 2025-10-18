<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Query pour récupérer les réservations
     */
    public function query()
    {
        $query = Booking::with(['user', 'payment', 'flightBooking'])
            ->orderBy('created_at', 'desc');

        // Filtres
        if (isset($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if (isset($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        if (isset($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (isset($this->filters['booking_type'])) {
            $query->where('booking_type', $this->filters['booking_type']);
        }

        return $query;
    }

    /**
     * En-têtes des colonnes
     */
    public function headings(): array
    {
        return [
            'ID',
            'Référence',
            'PNR',
            'Client',
            'Email',
            'Téléphone',
            'Type',
            'Montant (XOF)',
            'Commission (XOF)',
            'Net (XOF)',
            'Statut',
            'Paiement',
            'Date Création',
            'Date Voyage',
        ];
    }

    /**
     * Mapper les données
     */
    public function map($booking): array
    {
        $pnr = '';
        $travelDate = '';
        
        if ($booking->booking_type == 'flight' && $booking->flightBooking) {
            $pnr = $booking->flightBooking->pnr ?? '';
            $flightData = json_decode($booking->flightBooking->flight_data, true);
            if (isset($flightData['itineraries'][0]['segments'][0]['departure']['at'])) {
                $travelDate = date('d/m/Y', strtotime($flightData['itineraries'][0]['segments'][0]['departure']['at']));
            }
        }

        $commission = $booking->commission_amount ?? 0;
        $net = $booking->total_amount - $commission;

        return [
            $booking->id,
            $booking->booking_reference,
            $pnr,
            $booking->user->first_name . ' ' . $booking->user->last_name,
            $booking->user->email,
            $booking->user->phone ?? 'N/A',
            ucfirst($booking->booking_type),
            number_format($booking->total_amount, 2, '.', ''),
            number_format($commission, 2, '.', ''),
            number_format($net, 2, '.', ''),
            ucfirst($booking->status),
            $booking->payment ? ucfirst($booking->payment->status) : 'En attente',
            $booking->created_at->format('d/m/Y H:i'),
            $travelDate,
        ];
    }

    /**
     * Styles pour l'Excel
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '667eea']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }

    /**
     * Largeurs des colonnes
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 20,
            'C' => 15,
            'D' => 25,
            'E' => 30,
            'F' => 15,
            'G' => 12,
            'H' => 15,
            'I' => 15,
            'J' => 15,
            'K' => 12,
            'L' => 12,
            'M' => 18,
            'N' => 15,
        ];
    }
}
