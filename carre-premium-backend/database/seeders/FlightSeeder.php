<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Flight;
use App\Models\Airline;
use App\Models\Airport;
use Carbon\Carbon;

class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des compagnies aériennes
        $airlines = [
            ['name' => 'Air France', 'code' => 'AF', 'country' => 'France'],
            ['name' => 'Air Côte d\'Ivoire', 'code' => 'HF', 'country' => 'Côte d\'Ivoire'],
            ['name' => 'Emirates', 'code' => 'EK', 'country' => 'UAE'],
            ['name' => 'Turkish Airlines', 'code' => 'TK', 'country' => 'Turkey'],
            ['name' => 'Ethiopian Airlines', 'code' => 'ET', 'country' => 'Ethiopia'],
        ];

        foreach ($airlines as $airlineData) {
            Airline::create($airlineData);
        }

        // Créer des aéroports
        $airports = [
            ['name' => 'Aéroport Félix Houphouët-Boigny', 'city' => 'Abidjan', 'country' => 'Côte d\'Ivoire', 'iata_code' => 'ABJ', 'icao_code' => 'DIAP'],
            ['name' => 'Aéroport Charles de Gaulle', 'city' => 'Paris', 'country' => 'France', 'iata_code' => 'CDG', 'icao_code' => 'LFPG'],
            ['name' => 'Aéroport de Dakar', 'city' => 'Dakar', 'country' => 'Sénégal', 'iata_code' => 'DSS', 'icao_code' => 'GOBD'],
            ['name' => 'Aéroport de Dubaï', 'city' => 'Dubaï', 'country' => 'UAE', 'iata_code' => 'DXB', 'icao_code' => 'OMDB'],
            ['name' => 'Aéroport d\'Istanbul', 'city' => 'Istanbul', 'country' => 'Turkey', 'iata_code' => 'IST', 'icao_code' => 'LTFM'],
            ['name' => 'Aéroport de Lagos', 'city' => 'Lagos', 'country' => 'Nigeria', 'iata_code' => 'LOS', 'icao_code' => 'DNMM'],
        ];

        foreach ($airports as $airportData) {
            Airport::create($airportData);
        }

        $airlineIds = Airline::pluck('id')->toArray();
        $airportIds = Airport::pluck('id')->toArray();

        // Créer des vols
        $flights = [
            [
                'airline_id' => $airlineIds[0], // Air France
                'flight_number' => 'AF702',
                'departure_airport_id' => $airportIds[0], // Abidjan
                'arrival_airport_id' => $airportIds[1], // Paris
                'departure_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'departure_time' => '23:30:00',
                'arrival_date' => Carbon::now()->addDays(6)->format('Y-m-d'),
                'arrival_time' => '06:45:00',
                'duration' => 375, // 6h15
                'aircraft_type' => 'Boeing 777-300ER',
                'economy_seats' => 250,
                'business_seats' => 40,
                'first_class_seats' => 10,
                'economy_price' => 450000,
                'business_price' => 1200000,
                'first_class_price' => 2500000,
                'available_economy' => 180,
                'available_business' => 25,
                'available_first_class' => 8,
                'status' => 'scheduled',
                'is_active' => true,
            ],
            [
                'airline_id' => $airlineIds[1], // Air Côte d'Ivoire
                'flight_number' => 'HF201',
                'departure_airport_id' => $airportIds[0], // Abidjan
                'arrival_airport_id' => $airportIds[2], // Dakar
                'departure_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
                'departure_time' => '10:00:00',
                'arrival_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
                'arrival_time' => '12:30:00',
                'duration' => 150, // 2h30
                'aircraft_type' => 'Airbus A320',
                'economy_seats' => 150,
                'business_seats' => 20,
                'first_class_seats' => 0,
                'economy_price' => 180000,
                'business_price' => 450000,
                'first_class_price' => null,
                'available_economy' => 120,
                'available_business' => 15,
                'available_first_class' => 0,
                'status' => 'scheduled',
                'is_active' => true,
            ],
            [
                'airline_id' => $airlineIds[2], // Emirates
                'flight_number' => 'EK787',
                'departure_airport_id' => $airportIds[0], // Abidjan
                'arrival_airport_id' => $airportIds[3], // Dubaï
                'departure_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
                'departure_time' => '14:00:00',
                'arrival_date' => Carbon::now()->addDays(8)->format('Y-m-d'),
                'arrival_time' => '02:30:00',
                'duration' => 750, // 12h30
                'aircraft_type' => 'Airbus A380',
                'economy_seats' => 400,
                'business_seats' => 76,
                'first_class_seats' => 14,
                'economy_price' => 650000,
                'business_price' => 1800000,
                'first_class_price' => 3500000,
                'available_economy' => 320,
                'available_business' => 50,
                'available_first_class' => 10,
                'status' => 'scheduled',
                'is_active' => true,
            ],
            [
                'airline_id' => $airlineIds[3], // Turkish Airlines
                'flight_number' => 'TK538',
                'departure_airport_id' => $airportIds[1], // Paris
                'arrival_airport_id' => $airportIds[4], // Istanbul
                'departure_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
                'departure_time' => '18:45:00',
                'arrival_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
                'arrival_time' => '23:30:00',
                'duration' => 225, // 3h45
                'aircraft_type' => 'Boeing 737-800',
                'economy_seats' => 160,
                'business_seats' => 20,
                'first_class_seats' => 0,
                'economy_price' => 280000,
                'business_price' => 750000,
                'first_class_price' => null,
                'available_economy' => 95,
                'available_business' => 12,
                'available_first_class' => 0,
                'status' => 'scheduled',
                'is_active' => true,
            ],
            [
                'airline_id' => $airlineIds[4], // Ethiopian Airlines
                'flight_number' => 'ET921',
                'departure_airport_id' => $airportIds[0], // Abidjan
                'arrival_airport_id' => $airportIds[5], // Lagos
                'departure_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
                'departure_time' => '08:15:00',
                'arrival_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
                'arrival_time' => '10:00:00',
                'duration' => 105, // 1h45
                'aircraft_type' => 'Boeing 737-700',
                'economy_seats' => 120,
                'business_seats' => 12,
                'first_class_seats' => 0,
                'economy_price' => 150000,
                'business_price' => 380000,
                'first_class_price' => null,
                'available_economy' => 85,
                'available_business' => 8,
                'available_first_class' => 0,
                'status' => 'scheduled',
                'is_active' => true,
            ],
            [
                'airline_id' => $airlineIds[0], // Air France
                'flight_number' => 'AF703',
                'departure_airport_id' => $airportIds[1], // Paris
                'arrival_airport_id' => $airportIds[0], // Abidjan
                'departure_date' => Carbon::now()->addDays(4)->format('Y-m-d'),
                'departure_time' => '11:00:00',
                'arrival_date' => Carbon::now()->addDays(4)->format('Y-m-d'),
                'arrival_time' => '17:15:00',
                'duration' => 375, // 6h15
                'aircraft_type' => 'Boeing 777-300ER',
                'economy_seats' => 250,
                'business_seats' => 40,
                'first_class_seats' => 10,
                'economy_price' => 480000,
                'business_price' => 1250000,
                'first_class_price' => 2600000,
                'available_economy' => 200,
                'available_business' => 30,
                'available_first_class' => 7,
                'status' => 'scheduled',
                'is_active' => true,
            ],
            [
                'airline_id' => $airlineIds[1], // Air Côte d'Ivoire
                'flight_number' => 'HF105',
                'departure_airport_id' => $airportIds[0], // Abidjan
                'arrival_airport_id' => $airportIds[1], // Paris
                'departure_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'departure_time' => '22:00:00',
                'arrival_date' => Carbon::now()->format('Y-m-d'),
                'arrival_time' => '05:30:00',
                'duration' => 390, // 6h30
                'aircraft_type' => 'Airbus A330-200',
                'economy_seats' => 200,
                'business_seats' => 30,
                'first_class_seats' => 0,
                'economy_price' => 420000,
                'business_price' => 1100000,
                'first_class_price' => null,
                'available_economy' => 0,
                'available_business' => 0,
                'available_first_class' => 0,
                'status' => 'completed',
                'is_active' => false,
            ],
        ];

        foreach ($flights as $flightData) {
            Flight::create($flightData);
        }

        $this->command->info('✅ 7 vols créés avec succès!');
        $this->command->info('✅ 5 compagnies aériennes créées!');
        $this->command->info('✅ 6 aéroports créés!');
    }
}
