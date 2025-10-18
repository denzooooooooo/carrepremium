<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('Aucun utilisateur trouvé. Veuillez d\'abord exécuter UserSeeder.');
            return;
        }

        $bookings = [
            [
                'booking_number' => 'BK' . date('Ymd') . '001',
                'user_id' => $users->random()->id,
                'booking_type' => 'flight',
                'flight_id' => null,
                'event_id' => null,
                'package_id' => null,
                'booking_date' => now(),
                'travel_date' => Carbon::now()->addDays(15),
                'number_of_passengers' => 2,
                'passenger_details' => json_encode([
                    [
                        'first_name' => 'Jean',
                        'last_name' => 'Kouassi',
                        'passport' => 'CI123456'
                    ],
                    [
                        'first_name' => 'Marie',
                        'last_name' => 'Kouassi',
                        'passport' => 'CI123457'
                    ]
                ]),
                'seat_class' => 'economy',
                'seat_numbers' => '12A, 12B',
                'total_amount' => 450000,
                'currency' => 'XOF',
                'discount_amount' => 0,
                'tax_amount' => 45000,
                'final_amount' => 495000,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'confirmed_at' => now(),
            ],
            [
                'booking_number' => 'BK' . date('Ymd') . '002',
                'user_id' => $users->random()->id,
                'booking_type' => 'event',
                'flight_id' => null,
                'event_id' => null,
                'package_id' => null,
                'booking_date' => now()->subDays(2),
                'travel_date' => Carbon::now()->addDays(30),
                'number_of_passengers' => 1,
                'passenger_details' => json_encode([
                    [
                        'first_name' => 'Pierre',
                        'last_name' => 'Martin'
                    ]
                ]),
                'total_amount' => 75000,
                'currency' => 'XOF',
                'discount_amount' => 5000,
                'tax_amount' => 7000,
                'final_amount' => 77000,
                'status' => 'pending',
                'payment_status' => 'pending',
            ],
            [
                'booking_number' => 'BK' . date('Ymd') . '003',
                'user_id' => $users->random()->id,
                'booking_type' => 'package',
                'flight_id' => null,
                'event_id' => null,
                'package_id' => null,
                'booking_date' => now()->subDays(5),
                'travel_date' => Carbon::now()->addDays(45),
                'number_of_passengers' => 4,
                'passenger_details' => json_encode([
                    [
                        'first_name' => 'Fatou',
                        'last_name' => 'Sow',
                        'passport' => 'SN789012'
                    ],
                    [
                        'first_name' => 'Amadou',
                        'last_name' => 'Sow',
                        'passport' => 'SN789013'
                    ],
                    [
                        'first_name' => 'Aïcha',
                        'last_name' => 'Sow',
                        'passport' => 'SN789014'
                    ],
                    [
                        'first_name' => 'Omar',
                        'last_name' => 'Sow',
                        'passport' => 'SN789015'
                    ]
                ]),
                'total_amount' => 1200000,
                'currency' => 'XOF',
                'discount_amount' => 100000,
                'tax_amount' => 110000,
                'final_amount' => 1210000,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'confirmed_at' => now()->subDays(4),
            ],
            [
                'booking_number' => 'BK' . date('Ymd') . '004',
                'user_id' => $users->random()->id,
                'booking_type' => 'flight',
                'flight_id' => null,
                'event_id' => null,
                'package_id' => null,
                'booking_date' => now()->subDays(10),
                'travel_date' => Carbon::now()->addDays(7),
                'number_of_passengers' => 1,
                'passenger_details' => json_encode([
                    [
                        'first_name' => 'Sophie',
                        'last_name' => 'Dubois',
                        'passport' => 'FR456789'
                    ]
                ]),
                'seat_class' => 'business',
                'seat_numbers' => '3A',
                'total_amount' => 850000,
                'currency' => 'XOF',
                'discount_amount' => 50000,
                'tax_amount' => 80000,
                'final_amount' => 880000,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'confirmed_at' => now()->subDays(9),
            ],
            [
                'booking_number' => 'BK' . date('Ymd') . '005',
                'user_id' => $users->random()->id,
                'booking_type' => 'event',
                'flight_id' => null,
                'event_id' => null,
                'package_id' => null,
                'booking_date' => now()->subHours(3),
                'travel_date' => Carbon::now()->addDays(20),
                'number_of_passengers' => 2,
                'passenger_details' => json_encode([
                    [
                        'first_name' => 'Koffi',
                        'last_name' => 'Yao'
                    ],
                    [
                        'first_name' => 'Awa',
                        'last_name' => 'Cissé'
                    ]
                ]),
                'total_amount' => 120000,
                'currency' => 'XOF',
                'discount_amount' => 0,
                'tax_amount' => 12000,
                'final_amount' => 132000,
                'status' => 'pending',
                'payment_status' => 'pending',
            ],
            [
                'booking_number' => 'BK' . date('Ymd') . '006',
                'user_id' => $users->random()->id,
                'booking_type' => 'flight',
                'flight_id' => null,
                'event_id' => null,
                'package_id' => null,
                'booking_date' => now()->subDays(20),
                'travel_date' => Carbon::now()->subDays(5),
                'number_of_passengers' => 1,
                'passenger_details' => json_encode([
                    [
                        'first_name' => 'Thomas',
                        'last_name' => 'Bernard',
                        'passport' => 'FR987654'
                    ]
                ]),
                'seat_class' => 'economy',
                'seat_numbers' => '25C',
                'total_amount' => 380000,
                'currency' => 'XOF',
                'discount_amount' => 0,
                'tax_amount' => 38000,
                'final_amount' => 418000,
                'status' => 'completed',
                'payment_status' => 'paid',
                'confirmed_at' => now()->subDays(19),
            ],
            [
                'booking_number' => 'BK' . date('Ymd') . '007',
                'user_id' => $users->random()->id,
                'booking_type' => 'package',
                'flight_id' => null,
                'event_id' => null,
                'package_id' => null,
                'booking_date' => now()->subDays(7),
                'travel_date' => Carbon::now()->addDays(60),
                'number_of_passengers' => 2,
                'passenger_details' => json_encode([
                    [
                        'first_name' => 'Aïcha',
                        'last_name' => 'Bamba',
                        'passport' => 'CI654321'
                    ],
                    [
                        'first_name' => 'Moussa',
                        'last_name' => 'Bamba',
                        'passport' => 'CI654322'
                    ]
                ]),
                'total_amount' => 950000,
                'currency' => 'XOF',
                'discount_amount' => 50000,
                'tax_amount' => 90000,
                'final_amount' => 990000,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'confirmed_at' => now()->subDays(6),
            ],
            [
                'booking_number' => 'BK' . date('Ymd') . '008',
                'user_id' => $users->random()->id,
                'booking_type' => 'flight',
                'flight_id' => null,
                'event_id' => null,
                'package_id' => null,
                'booking_date' => now()->subDays(3),
                'travel_date' => Carbon::now()->addDays(10),
                'number_of_passengers' => 1,
                'passenger_details' => json_encode([
                    [
                        'first_name' => 'Marie',
                        'last_name' => 'Diallo',
                        'passport' => 'CI111222'
                    ]
                ]),
                'seat_class' => 'economy',
                'seat_numbers' => '18F',
                'total_amount' => 420000,
                'currency' => 'XOF',
                'discount_amount' => 20000,
                'tax_amount' => 40000,
                'final_amount' => 440000,
                'status' => 'cancelled',
                'payment_status' => 'refunded',
                'cancellation_reason' => 'Demande du client',
                'cancelled_at' => now()->subDays(2),
            ],
        ];

        foreach ($bookings as $bookingData) {
            Booking::create($bookingData);
        }

        $this->command->info('✅ 8 réservations de test créées avec succès!');
    }
}
