<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Flight;
use App\Models\Event;
use App\Models\TourPackage;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Carousel;
use App\Models\PromoCode;
use Illuminate\Support\Str;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Créer des utilisateurs de test
        $users = [];
        for ($i = 1; $i <= 5; $i++) {
            $users[] = User::create([
                'first_name' => "Client{$i}",
                'last_name' => "Test",
                'email' => "client{$i}@test.com",
                'password' => bcrypt('password'),
                'phone' => "+225070000000{$i}",
                'city' => 'Abidjan',
                'country' => 'Côte d\'Ivoire',
                'is_active' => true,
            ]);
        }

        // Récupérer les catégories
        $categories = Category::all();
        if ($categories->isEmpty()) {
            echo "⚠️  Aucune catégorie trouvée. Exécutez CategorySeeder d'abord.\n";
            return;
        }

        // Créer des compagnies aériennes
        $airlines = [];
        $airlineData = [
            ['name' => 'Air France', 'code' => 'AF', 'country' => 'France'],
            ['name' => 'Air Côte d\'Ivoire', 'code' => 'HF', 'country' => 'Côte d\'Ivoire'],
            ['name' => 'Emirates', 'code' => 'EK', 'country' => 'UAE'],
            ['name' => 'Turkish Airlines', 'code' => 'TK', 'country' => 'Turkey'],
        ];

        foreach ($airlineData as $data) {
            $airlines[] = Airline::firstOrCreate(
                ['code' => $data['code']],
                $data
            );
        }

        // Créer des aéroports
        $airports = [];
        $airportData = [
            ['name' => 'Aéroport Félix Houphouët-Boigny', 'city' => 'Abidjan', 'country' => 'Côte d\'Ivoire', 'iata_code' => 'ABJ'],
            ['name' => 'Aéroport Charles de Gaulle', 'city' => 'Paris', 'country' => 'France', 'iata_code' => 'CDG'],
            ['name' => 'Aéroport de Dubai', 'city' => 'Dubai', 'country' => 'UAE', 'iata_code' => 'DXB'],
            ['name' => 'Aéroport de New York JFK', 'city' => 'New York', 'country' => 'USA', 'iata_code' => 'JFK'],
        ];

        foreach ($airportData as $data) {
            $airports[] = Airport::firstOrCreate(
                ['iata_code' => $data['iata_code']],
                $data
            );
        }

        // Créer des vols
        echo "✓ Création de vols...\n";
        for ($i = 1; $i <= 10; $i++) {
            Flight::create([
                'airline_id' => $airlines[array_rand($airlines)]->id,
                'flight_number' => 'AF' . (1000 + $i),
                'departure_airport_id' => $airports[0]->id,
                'arrival_airport_id' => $airports[array_rand($airports)]->id,
                'departure_date' => now()->addDays(rand(1, 30)),
                'departure_time' => sprintf('%02d:00:00', rand(6, 22)),
                'arrival_date' => now()->addDays(rand(1, 30)),
                'arrival_time' => sprintf('%02d:00:00', rand(6, 22)),
                'duration' => rand(120, 720),
                'aircraft_type' => 'Boeing 777',
                'economy_seats' => 200,
                'business_seats' => 50,
                'first_class_seats' => 20,
                'economy_price' => rand(300000, 800000),
                'business_price' => rand(1000000, 2000000),
                'first_class_price' => rand(2500000, 5000000),
                'available_economy' => rand(50, 200),
                'available_business' => rand(10, 50),
                'available_first_class' => rand(5, 20),
                'status' => 'scheduled',
                'is_active' => true,
            ]);
        }

        // Créer des événements
        echo "✓ Création d'événements...\n";
        $eventTypes = ['sport', 'concert', 'theater', 'festival'];
        $sportTypes = ['football', 'tennis', 'basketball', 'formula1'];
        $cities = ['Paris', 'Londres', 'Madrid', 'Abidjan', 'New York'];

        for ($i = 1; $i <= 10; $i++) {
            $eventType = $eventTypes[array_rand($eventTypes)];
            Event::create([
                'category_id' => $categories->random()->id,
                'title_fr' => "Événement Test {$i}",
                'title_en' => "Test Event {$i}",
                'slug' => Str::slug("evenement-test-{$i}") . '-' . time() . $i,
                'description_fr' => "Description de l'événement test {$i}",
                'description_en' => "Description of test event {$i}",
                'event_type' => $eventType,
                'sport_type' => $eventType === 'sport' ? $sportTypes[array_rand($sportTypes)] : null,
                'venue_name' => "Stade Test {$i}",
                'venue_address' => "Adresse {$i}",
                'city' => $cities[array_rand($cities)],
                'country' => 'France',
                'event_date' => now()->addDays(rand(1, 60)),
                'event_time' => sprintf('%02d:00:00', rand(14, 22)),
                'organizer' => "Organisateur {$i}",
                'min_price' => rand(10000, 50000),
                'max_price' => rand(100000, 500000),
                'total_seats' => rand(1000, 50000),
                'available_seats' => rand(500, 40000),
                'is_featured' => rand(0, 1),
                'is_active' => true,
            ]);
        }

        // Créer des packages touristiques
        echo "✓ Création de packages...\n";
        $packageTypes = ['helicopter', 'private_jet', 'cruise', 'safari', 'city_tour'];
        $destinations = ['Paris', 'Dubai', 'Maldives', 'Safari Kenya', 'New York'];

        for ($i = 1; $i <= 10; $i++) {
            TourPackage::create([
                'category_id' => $categories->random()->id,
                'title_fr' => "Package Test {$i}",
                'title_en' => "Test Package {$i}",
                'slug' => Str::slug("package-test-{$i}") . '-' . time() . $i,
                'description_fr' => "Description du package {$i}",
                'description_en' => "Package description {$i}",
                'package_type' => $packageTypes[array_rand($packageTypes)],
                'destination' => $destinations[array_rand($destinations)],
                'duration' => rand(1, 14),
                'duration_text_fr' => rand(1, 14) . ' jours',
                'duration_text_en' => rand(1, 14) . ' days',
                'departure_city' => 'Abidjan',
                'price' => rand(500000, 5000000),
                'discount_price' => rand(400000, 4500000),
                'max_participants' => rand(2, 20),
                'min_participants' => 1,
                'is_featured' => rand(0, 1),
                'is_active' => true,
                'rating' => rand(35, 50) / 10,
                'total_reviews' => rand(0, 100),
            ]);
        }

        // Créer des carrousels
        echo "✓ Création de carrousels...\n";
        for ($i = 1; $i <= 5; $i++) {
            Carousel::create([
                'title_fr' => "Slide {$i}",
                'title_en' => "Slide {$i}",
                'subtitle_fr' => "Sous-titre {$i}",
                'subtitle_en' => "Subtitle {$i}",
                'image' => "carousel/slide-{$i}.jpg",
                'link_url' => '/events',
                'button_text_fr' => 'Découvrir',
                'button_text_en' => 'Discover',
                'order_position' => $i,
                'is_active' => true,
            ]);
        }

        // Créer des codes promo
        echo "✓ Création de codes promo...\n";
        $promoCodes = ['WELCOME10', 'SUMMER20', 'VIP30', 'FLASH15', 'WEEKEND25'];
        foreach ($promoCodes as $index => $code) {
            PromoCode::firstOrCreate(
                ['code' => $code],
                [
                    'description_fr' => "Réduction {$code}",
                    'description_en' => "Discount {$code}",
                    'discount_type' => rand(0, 1) ? 'percentage' : 'fixed',
                    'discount_value' => rand(10, 30),
                    'min_purchase_amount' => rand(50000, 200000),
                    'usage_limit' => rand(50, 500),
                    'used_count' => 0,
                    'valid_from' => now(),
                    'valid_until' => now()->addMonths(3),
                    'applicable_to' => 'all',
                    'is_active' => true,
                ]
            );
        }

        // Créer quelques réservations
        echo "✓ Création de réservations...\n";
        $flights = Flight::all();
        $events = Event::all();
        
        for ($i = 1; $i <= 5; $i++) {
            Booking::create([
                'booking_number' => 'BK' . strtoupper(Str::random(8)),
                'user_id' => $users[array_rand($users)]->id,
                'booking_type' => 'flight',
                'flight_id' => $flights->random()->id,
                'booking_date' => now(),
                'travel_date' => now()->addDays(rand(5, 30)),
                'number_of_passengers' => rand(1, 4),
                'seat_class' => ['economy', 'business', 'first_class'][array_rand(['economy', 'business', 'first_class'])],
                'total_amount' => rand(300000, 2000000),
                'currency' => 'XOF',
                'final_amount' => rand(300000, 2000000),
                'status' => ['pending', 'confirmed'][array_rand(['pending', 'confirmed'])],
                'payment_status' => ['pending', 'paid'][array_rand(['pending', 'paid'])],
            ]);
        }

        echo "\n✅ Données de test créées avec succès!\n";
        echo "   - 5 utilisateurs\n";
        echo "   - 10 vols\n";
        echo "   - 10 événements\n";
        echo "   - 10 packages\n";
        echo "   - 5 carrousels\n";
        echo "   - 5 codes promo\n";
        echo "   - 5 réservations\n";
    }
}
