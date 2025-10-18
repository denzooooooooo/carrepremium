<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventSeatZone;
use App\Models\Category;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les catégories sportives et culturelles
        $sportsCategory = Category::where('slug', 'sports-events')->first();
        $culturalCategory = Category::where('slug', 'cultural-events')->first();

        // Si les catégories n'existent pas, les créer
        if (!$sportsCategory) {
            $sportsCategory = Category::create([
                'name_fr' => 'Événements Sportifs',
                'name_en' => 'Sports Events',
                'slug' => 'sports-events',
                'icon' => 'trophy',
                'order_position' => 2,
                'is_active' => true,
            ]);
        }

        if (!$culturalCategory) {
            $culturalCategory = Category::create([
                'name_fr' => 'Événements Culturels',
                'name_en' => 'Cultural Events',
                'slug' => 'cultural-events',
                'icon' => 'music',
                'order_position' => 3,
                'is_active' => true,
            ]);
        }

        // Créer des événements sportifs
        $events = [
            // Roland Garros
            [
                'category_id' => $sportsCategory->id,
                'title_fr' => 'Roland Garros 2025 - Finale Hommes',
                'title_en' => 'Roland Garros 2025 - Men\'s Final',
                'slug' => 'roland-garros-2025-finale-hommes',
                'description_fr' => 'Assistez à la finale du tournoi de tennis le plus prestigieux au monde sur terre battue. Une expérience inoubliable au stade Philippe-Chatrier.',
                'description_en' => 'Attend the final of the world\'s most prestigious clay court tennis tournament. An unforgettable experience at Philippe-Chatrier stadium.',
                'event_type' => 'sport',
                'sport_type' => 'tennis',
                'venue_name' => 'Stade Roland Garros',
                'venue_address' => '2 Avenue Gordon Bennett, 75016 Paris',
                'city' => 'Paris',
                'country' => 'France',
                'event_date' => Carbon::now()->addMonths(6)->format('Y-m-d'),
                'event_time' => '15:00:00',
                'end_date' => Carbon::now()->addMonths(6)->format('Y-m-d'),
                'end_time' => '19:00:00',
                'organizer' => 'Fédération Française de Tennis',
                'min_price' => 150000,
                'max_price' => 500000,
                'total_seats' => 15000,
                'available_seats' => 12500,
                'is_featured' => true,
                'is_active' => true,
            ],
            // Champions League
            [
                'category_id' => $sportsCategory->id,
                'title_fr' => 'UEFA Champions League - Finale 2025',
                'title_en' => 'UEFA Champions League - Final 2025',
                'slug' => 'champions-league-finale-2025',
                'description_fr' => 'La finale de la plus grande compétition de clubs européens. Vivez l\'émotion du football au plus haut niveau.',
                'description_en' => 'The final of Europe\'s greatest club competition. Experience football at the highest level.',
                'event_type' => 'sport',
                'sport_type' => 'football',
                'venue_name' => 'Stade de France',
                'venue_address' => 'Saint-Denis, France',
                'city' => 'Paris',
                'country' => 'France',
                'event_date' => Carbon::now()->addMonths(5)->format('Y-m-d'),
                'event_time' => '21:00:00',
                'end_date' => Carbon::now()->addMonths(5)->format('Y-m-d'),
                'end_time' => '23:30:00',
                'organizer' => 'UEFA',
                'min_price' => 200000,
                'max_price' => 800000,
                'total_seats' => 80000,
                'available_seats' => 65000,
                'is_featured' => true,
                'is_active' => true,
            ],
            // Formule 1
            [
                'category_id' => $sportsCategory->id,
                'title_fr' => 'Grand Prix de Monaco F1 2025',
                'title_en' => 'Monaco F1 Grand Prix 2025',
                'slug' => 'grand-prix-monaco-f1-2025',
                'description_fr' => 'Le circuit le plus mythique de la Formule 1. Vivez la course dans les rues de Monte-Carlo.',
                'description_en' => 'The most legendary F1 circuit. Experience the race through the streets of Monte-Carlo.',
                'event_type' => 'sport',
                'sport_type' => 'formula1',
                'venue_name' => 'Circuit de Monaco',
                'venue_address' => 'Monte-Carlo, Monaco',
                'city' => 'Monaco',
                'country' => 'Monaco',
                'event_date' => Carbon::now()->addMonths(4)->format('Y-m-d'),
                'event_time' => '15:00:00',
                'end_date' => Carbon::now()->addMonths(4)->format('Y-m-d'),
                'end_time' => '17:00:00',
                'organizer' => 'FIA',
                'min_price' => 300000,
                'max_price' => 1500000,
                'total_seats' => 50000,
                'available_seats' => 35000,
                'is_featured' => true,
                'is_active' => true,
            ],
            // CAN
            [
                'category_id' => $sportsCategory->id,
                'title_fr' => 'CAN 2025 - Match d\'ouverture',
                'title_en' => 'AFCON 2025 - Opening Match',
                'slug' => 'can-2025-match-ouverture',
                'description_fr' => 'Coupe d\'Afrique des Nations 2025. Assistez au match d\'ouverture de la plus grande compétition africaine.',
                'description_en' => 'Africa Cup of Nations 2025. Attend the opening match of Africa\'s greatest competition.',
                'event_type' => 'sport',
                'sport_type' => 'football',
                'venue_name' => 'Stade Olympique d\'Ebimpé',
                'venue_address' => 'Anyama, Abidjan',
                'city' => 'Abidjan',
                'country' => 'Côte d\'Ivoire',
                'event_date' => Carbon::now()->addMonths(3)->format('Y-m-d'),
                'event_time' => '18:00:00',
                'end_date' => Carbon::now()->addMonths(3)->format('Y-m-d'),
                'end_time' => '20:00:00',
                'organizer' => 'CAF',
                'min_price' => 25000,
                'max_price' => 150000,
                'total_seats' => 60000,
                'available_seats' => 45000,
                'is_featured' => true,
                'is_active' => true,
            ],
            // Concert
            [
                'category_id' => $culturalCategory->id,
                'title_fr' => 'Concert Burna Boy - Abidjan 2025',
                'title_en' => 'Burna Boy Concert - Abidjan 2025',
                'slug' => 'concert-burna-boy-abidjan-2025',
                'description_fr' => 'Le géant de l\'Afrobeat en concert exceptionnel à Abidjan. Une soirée mémorable avec le Grammy Award Winner.',
                'description_en' => 'The Afrobeat giant in an exceptional concert in Abidjan. A memorable evening with the Grammy Award Winner.',
                'event_type' => 'concert',
                'sport_type' => null,
                'venue_name' => 'Palais de la Culture',
                'venue_address' => 'Plateau, Abidjan',
                'city' => 'Abidjan',
                'country' => 'Côte d\'Ivoire',
                'event_date' => Carbon::now()->addMonths(2)->format('Y-m-d'),
                'event_time' => '20:00:00',
                'end_date' => Carbon::now()->addMonths(2)->format('Y-m-d'),
                'end_time' => '23:30:00',
                'organizer' => 'Showbiz CI',
                'min_price' => 35000,
                'max_price' => 200000,
                'total_seats' => 8000,
                'available_seats' => 5500,
                'is_featured' => true,
                'is_active' => true,
            ],
            // Festival
            [
                'category_id' => $culturalCategory->id,
                'title_fr' => 'FEMUA 2025 - Festival des Musiques Urbaines',
                'title_en' => 'FEMUA 2025 - Urban Music Festival',
                'slug' => 'femua-2025-festival',
                'description_fr' => 'Le plus grand festival de musiques urbaines d\'Afrique de l\'Ouest. 3 jours de concerts non-stop.',
                'description_en' => 'West Africa\'s biggest urban music festival. 3 days of non-stop concerts.',
                'event_type' => 'festival',
                'sport_type' => null,
                'venue_name' => 'Anoumabo',
                'venue_address' => 'Anoumabo, Abidjan',
                'city' => 'Abidjan',
                'country' => 'Côte d\'Ivoire',
                'event_date' => Carbon::now()->addMonths(7)->format('Y-m-d'),
                'event_time' => '18:00:00',
                'end_date' => Carbon::now()->addMonths(7)->addDays(2)->format('Y-m-d'),
                'end_time' => '02:00:00',
                'organizer' => 'FEMUA Organisation',
                'min_price' => 15000,
                'max_price' => 75000,
                'total_seats' => 20000,
                'available_seats' => 18000,
                'is_featured' => false,
                'is_active' => true,
            ],
            // Festival Coachella
            [
                'category_id' => $culturalCategory->id,
                'title_fr' => 'Festival Coachella 2025',
                'title_en' => 'Coachella Festival 2025',
                'slug' => 'festival-coachella-2025',
                'description_fr' => 'Le festival de musique et d\'arts le plus célèbre au monde. Une expérience unique dans le désert californien.',
                'description_en' => 'The world\'s most famous music and arts festival. A unique experience in the California desert.',
                'event_type' => 'festival',
                'sport_type' => null,
                'venue_name' => 'Empire Polo Club',
                'venue_address' => 'Indio, California',
                'city' => 'Indio',
                'country' => 'États-Unis',
                'event_date' => Carbon::now()->addMonths(4)->format('Y-m-d'),
                'event_time' => '12:00:00',
                'end_date' => Carbon::now()->addMonths(4)->addDays(2)->format('Y-m-d'),
                'end_time' => '01:00:00',
                'organizer' => 'Goldenvoice',
                'min_price' => 400000,
                'max_price' => 1200000,
                'total_seats' => 125000,
                'available_seats' => 95000,
                'is_featured' => true,
                'is_active' => true,
            ],
            // NBA Finals
            [
                'category_id' => $sportsCategory->id,
                'title_fr' => 'NBA Finals 2025 - Match 7',
                'title_en' => 'NBA Finals 2025 - Game 7',
                'slug' => 'nba-finals-2025-match-7',
                'description_fr' => 'Le match décisif des finales NBA. Assistez au couronnement du champion de basketball.',
                'description_en' => 'The decisive NBA Finals game. Witness the crowning of the basketball champion.',
                'event_type' => 'sport',
                'sport_type' => 'basketball',
                'venue_name' => 'Madison Square Garden',
                'venue_address' => 'New York, NY',
                'city' => 'New York',
                'country' => 'États-Unis',
                'event_date' => Carbon::now()->addMonths(6)->format('Y-m-d'),
                'event_time' => '21:00:00',
                'end_date' => Carbon::now()->addMonths(6)->format('Y-m-d'),
                'end_time' => '23:30:00',
                'organizer' => 'NBA',
                'min_price' => 500000,
                'max_price' => 2500000,
                'total_seats' => 20000,
                'available_seats' => 12000,
                'is_featured' => true,
                'is_active' => true,
            ],
            // Wimbledon
            [
                'category_id' => $sportsCategory->id,
                'title_fr' => 'Wimbledon 2025 - Finale Dames',
                'title_en' => 'Wimbledon 2025 - Ladies Final',
                'slug' => 'wimbledon-2025-finale-dames',
                'description_fr' => 'La finale du tournoi de tennis le plus prestigieux sur gazon. Une tradition britannique incontournable.',
                'description_en' => 'The final of the most prestigious grass court tennis tournament. An unmissable British tradition.',
                'event_type' => 'sport',
                'sport_type' => 'tennis',
                'venue_name' => 'All England Lawn Tennis Club',
                'venue_address' => 'Church Road, Wimbledon',
                'city' => 'Londres',
                'country' => 'Royaume-Uni',
                'event_date' => Carbon::now()->addMonths(7)->format('Y-m-d'),
                'event_time' => '14:00:00',
                'end_date' => Carbon::now()->addMonths(7)->format('Y-m-d'),
                'end_time' => '17:00:00',
                'organizer' => 'All England Club',
                'min_price' => 180000,
                'max_price' => 600000,
                'total_seats' => 15000,
                'available_seats' => 11000,
                'is_featured' => true,
                'is_active' => true,
            ],
        ];

        foreach ($events as $eventData) {
            $event = Event::create($eventData);

            // Créer des zones de sièges pour chaque événement
            if ($event->event_type === 'sport') {
                $zones = [
                    [
                        'zone_name_fr' => 'Tribune VIP',
                        'zone_name_en' => 'VIP Stand',
                        'zone_code' => 'VIP',
                        'price' => $event->max_price,
                        'total_seats' => (int)($event->total_seats * 0.1),
                        'available_seats' => (int)($event->available_seats * 0.1),
                        'description_fr' => 'Accès VIP avec services premium',
                        'description_en' => 'VIP access with premium services',
                    ],
                    [
                        'zone_name_fr' => 'Tribune Principale',
                        'zone_name_en' => 'Main Stand',
                        'zone_code' => 'MAIN',
                        'price' => ($event->min_price + $event->max_price) / 2,
                        'total_seats' => (int)($event->total_seats * 0.4),
                        'available_seats' => (int)($event->available_seats * 0.4),
                        'description_fr' => 'Vue optimale sur l\'événement',
                        'description_en' => 'Optimal view of the event',
                    ],
                    [
                        'zone_name_fr' => 'Tribune Populaire',
                        'zone_name_en' => 'General Stand',
                        'zone_code' => 'GEN',
                        'price' => $event->min_price,
                        'total_seats' => (int)($event->total_seats * 0.5),
                        'available_seats' => (int)($event->available_seats * 0.5),
                        'description_fr' => 'Places à prix accessible',
                        'description_en' => 'Affordable seats',
                    ],
                ];
            } else {
                // Pour les concerts et festivals
                $zones = [
                    [
                        'zone_name_fr' => 'Carré Or',
                        'zone_name_en' => 'Gold Area',
                        'zone_code' => 'GOLD',
                        'price' => $event->max_price,
                        'total_seats' => (int)($event->total_seats * 0.2),
                        'available_seats' => (int)($event->available_seats * 0.2),
                        'description_fr' => 'Zone premium près de la scène',
                        'description_en' => 'Premium area near the stage',
                    ],
                    [
                        'zone_name_fr' => 'Fosse',
                        'zone_name_en' => 'Pit',
                        'zone_code' => 'PIT',
                        'price' => ($event->min_price + $event->max_price) / 2,
                        'total_seats' => (int)($event->total_seats * 0.3),
                        'available_seats' => (int)($event->available_seats * 0.3),
                        'description_fr' => 'Debout devant la scène',
                        'description_en' => 'Standing in front of stage',
                    ],
                    [
                        'zone_name_fr' => 'Gradin',
                        'zone_name_en' => 'Bleachers',
                        'zone_code' => 'BLEACH',
                        'price' => $event->min_price,
                        'total_seats' => (int)($event->total_seats * 0.5),
                        'available_seats' => (int)($event->available_seats * 0.5),
                        'description_fr' => 'Places assises',
                        'description_en' => 'Seated places',
                    ],
                ];
            }

            foreach ($zones as $zoneData) {
                $zoneData['event_id'] = $event->id;
                EventSeatZone::create($zoneData);
            }
        }

        $this->command->info('✅ 9 événements créés avec succès!');
        $this->command->info('✅ 27 zones de sièges créées!');
    }
}
