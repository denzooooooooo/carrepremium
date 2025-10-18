<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Payment::with(['booking.user'])
            ->orderBy('created_at', 'desc');

        if (isset($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if (isset($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        if (isset($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (isset($this->filters['payment_method'])) {
            $query->where('payment_method', $this->filters['payment_method']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Date',
            'Transaction ID',
            'Client',
            'Email',
            'Réservation',
            'Type Réservation',
            'Montant (XOF)',
            'Méthode',
            'Statut',
            'Commission (XOF)',
            'Net (XOF)',
            'TVA 18% (XOF)',
            'Devise',
        ];
    }

    public function map($payment): array
    {
        $booking = $payment->booking;
        $user = $booking->user;
        
        $commission = $booking->commission_amount ?? 0;
        $net = $payment->amount - $commission;
        $tva = $payment->amount * 0.18; // 18% TVA Côte d'Ivoire

        return [
            $payment->id,
            $payment->created_at->format('d/m/Y H:i'),
            $payment->transaction_id,
            $user->first_name . ' ' . $user->last_name,
            $user->email,
            $booking->booking_reference,
            ucfirst($booking->booking_type),
            number_format($payment->amount, 2, '.', ''),
            strtoupper($payment->payment_method),
            ucfirst($payment->status),
            number_format($commission, 2, '.', ''),
            number_format($net, 2, '.', ''),
            number_format($tva, 2, '.', ''),
            $payment->currency ?? 'XOF',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '10b981']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 18,
            'C' => 25,
            'D' => 25,
            'E' => 30,
            'F' => 20,
            'G' => 18,
            'H' => 15,
            'I' => 15,
            'J' => 12,
            'K' => 15,
            'L' => 15,
            'M' => 15,
            'N' => 10,
        ];
    }
}
