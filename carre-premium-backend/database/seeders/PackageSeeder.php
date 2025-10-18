<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TourPackage;
use App\Models\Category;
use Carbon\Carbon;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les catégories
        $packagesCategory = Category::where('slug', 'tour-packages')->first();
        $helicopterCategory = Category::where('slug', 'helicopter')->first();
        $jetCategory = Category::where('slug', 'private-jet')->first();

        // Si les catégories n'existent pas, les créer
        if (!$packagesCategory) {
            $packagesCategory = Category::create([
                'name_fr' => 'Packages Touristiques',
                'name_en' => 'Tour Packages',
                'slug' => 'tour-packages',
                'icon' => 'suitcase',
                'order_position' => 4,
                'is_active' => true,
            ]);
        }

        if (!$helicopterCategory) {
            $helicopterCategory = Category::create([
                'name_fr' => 'Hélicoptère',
                'name_en' => 'Helicopter',
                'slug' => 'helicopter',
                'icon' => 'helicopter',
                'order_position' => 5,
                'is_active' => true,
            ]);
        }

        if (!$jetCategory) {
            $jetCategory = Category::create([
                'name_fr' => 'Jet Privé',
                'name_en' => 'Private Jet',
                'slug' => 'private-jet',
                'icon' => 'plane-departure',
                'order_position' => 6,
                'is_active' => true,
            ]);
        }

        // Créer des packages touristiques
        $packages = [
            // Hélicoptère - Tour d'Abidjan
            [
                'category_id' => $helicopterCategory->id,
                'title_fr' => 'Tour d\'Abidjan en Hélicoptère - Vue Panoramique',
                'title_en' => 'Abidjan Helicopter Tour - Panoramic View',
                'slug' => 'tour-abidjan-helicoptere',
                'description_fr' => 'Découvrez Abidjan comme jamais auparavant ! Survolez la lagune Ébrié, le Plateau, Cocody et admirez la beauté de la capitale économique ivoirienne depuis les airs. Une expérience inoubliable de 30 minutes.',
                'description_en' => 'Discover Abidjan like never before! Fly over the Ébrié lagoon, Plateau, Cocody and admire the beauty of the Ivorian economic capital from the air. An unforgettable 30-minute experience.',
                'package_type' => 'helicopter',
                'destination' => 'Abidjan, Côte d\'Ivoire',
                'duration' => 1,
                'duration_text_fr' => '30 minutes',
                'duration_text_en' => '30 minutes',
                'departure_city' => 'Abidjan',
                'price' => 250000,
                'discount_price' => null,
                'max_participants' => 4,
                'min_participants' => 1,
                'included_services_fr' => json_encode([
                    'Vol en hélicoptère de 30 minutes',
                    'Pilote professionnel expérimenté',
                    'Assurance tous risques',
                    'Photos aériennes offertes',
                    'Briefing de sécurité'
                ]),
                'included_services_en' => json_encode([
                    '30-minute helicopter flight',
                    'Experienced professional pilot',
                    'Comprehensive insurance',
                    'Complimentary aerial photos',
                    'Safety briefing'
                ]),
                'excluded_services_fr' => json_encode([
                    'Transport vers l\'héliport',
                    'Repas et boissons'
                ]),
                'excluded_services_en' => json_encode([
                    'Transport to heliport',
                    'Meals and drinks'
                ]),
                'itinerary_fr' => json_encode([
                    ['time' => '09:00', 'activity' => 'Accueil à l\'héliport de Port-Bouët'],
                    ['time' => '09:15', 'activity' => 'Briefing de sécurité'],
                    ['time' => '09:30', 'activity' => 'Décollage - Survol du Plateau'],
                    ['time' => '09:45', 'activity' => 'Vue sur Cocody et la Riviera'],
                    ['time' => '10:00', 'activity' => 'Retour et atterrissage']
                ]),
                'itinerary_en' => json_encode([
                    ['time' => '09:00', 'activity' => 'Welcome at Port-Bouët heliport'],
                    ['time' => '09:15', 'activity' => 'Safety briefing'],
                    ['time' => '09:30', 'activity' => 'Takeoff - Plateau flyover'],
                    ['time' => '09:45', 'activity' => 'View of Cocody and Riviera'],
                    ['time' => '10:00', 'activity' => 'Return and landing']
                ]),
                'available_dates' => json_encode([
                    Carbon::now()->addDays(7)->format('Y-m-d'),
                    Carbon::now()->addDays(14)->format('Y-m-d'),
                    Carbon::now()->addDays(21)->format('Y-m-d'),
                    Carbon::now()->addDays(28)->format('Y-m-d'),
                ]),
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.8,
                'total_reviews' => 24,
            ],

            // Jet Privé - Abidjan-Paris
            [
                'category_id' => $jetCategory->id,
                'title_fr' => 'Jet Privé Abidjan - Paris',
                'title_en' => 'Private Jet Abidjan - Paris',
                'slug' => 'jet-prive-abidjan-paris',
                'description_fr' => 'Voyagez dans le luxe absolu entre Abidjan et Paris. Notre jet privé vous offre confort, intimité et flexibilité horaire. Service VIP de bout en bout avec salon privé et restauration gastronomique.',
                'description_en' => 'Travel in absolute luxury between Abidjan and Paris. Our private jet offers comfort, privacy and schedule flexibility. End-to-end VIP service with private lounge and gourmet catering.',
                'package_type' => 'private_jet',
                'destination' => 'Paris, France',
                'duration' => 1,
                'duration_text_fr' => '6 heures de vol',
                'duration_text_en' => '6 hours flight',
                'departure_city' => 'Abidjan',
                'price' => 15000000,
                'discount_price' => 14000000,
                'max_participants' => 8,
                'min_participants' => 1,
                'included_services_fr' => json_encode([
                    'Vol en jet privé Gulfstream G650',
                    'Salon VIP à l\'aéroport',
                    'Restauration gastronomique à bord',
                    'Bar premium et champagne',
                    'WiFi haut débit',
                    'Équipage dédié',
                    'Transfert limousine (Abidjan et Paris)'
                ]),
                'included_services_en' => json_encode([
                    'Gulfstream G650 private jet flight',
                    'VIP airport lounge',
                    'Gourmet catering on board',
                    'Premium bar and champagne',
                    'High-speed WiFi',
                    'Dedicated crew',
                    'Limousine transfer (Abidjan and Paris)'
                ]),
                'excluded_services_fr' => json_encode([
                    'Visa et formalités',
                    'Assurance voyage personnelle'
                ]),
                'excluded_services_en' => json_encode([
                    'Visa and formalities',
                    'Personal travel insurance'
                ]),
                'itinerary_fr' => json_encode([
                    ['time' => '20:00', 'activity' => 'Accueil au salon VIP Abidjan'],
                    ['time' => '21:00', 'activity' => 'Décollage vers Paris'],
                    ['time' => '03:00', 'activity' => 'Atterrissage Paris Le Bourget'],
                    ['time' => '03:30', 'activity' => 'Transfert limousine vers votre destination']
                ]),
                'itinerary_en' => json_encode([
                    ['time' => '20:00', 'activity' => 'Welcome at Abidjan VIP lounge'],
                    ['time' => '21:00', 'activity' => 'Takeoff to Paris'],
                    ['time' => '03:00', 'activity' => 'Landing Paris Le Bourget'],
                    ['time' => '03:30', 'activity' => 'Limousine transfer to your destination']
                ]),
                'available_dates' => json_encode([
                    Carbon::now()->addDays(10)->format('Y-m-d'),
                    Carbon::now()->addDays(17)->format('Y-m-d'),
                    Carbon::now()->addDays(24)->format('Y-m-d'),
                ]),
                'is_featured' => true,
                'is_active' => true,
                'rating' => 5.0,
                'total_reviews' => 12,
            ],

            // Safari - Parc National de la Comoé
            [
                'category_id' => $packagesCategory->id,
                'title_fr' => 'Safari 3 Jours - Parc National de la Comoé',
                'title_en' => '3-Day Safari - Comoé National Park',
                'slug' => 'safari-parc-comoe',
                'description_fr' => 'Partez à l\'aventure dans le plus grand parc national de Côte d\'Ivoire. Observez éléphants, lions, buffles et une faune exceptionnelle dans leur habitat naturel. Hébergement en lodge de luxe.',
                'description_en' => 'Embark on an adventure in Ivory Coast\'s largest national park. Observe elephants, lions, buffaloes and exceptional wildlife in their natural habitat. Luxury lodge accommodation.',
                'package_type' => 'safari',
                'destination' => 'Parc National de la Comoé',
                'duration' => 3,
                'duration_text_fr' => '3 jours / 2 nuits',
                'duration_text_en' => '3 days / 2 nights',
                'departure_city' => 'Abidjan',
                'price' => 850000,
                'discount_price' => 750000,
                'max_participants' => 6,
                'min_participants' => 2,
                'included_services_fr' => json_encode([
                    'Transport 4x4 climatisé depuis Abidjan',
                    '2 nuits en lodge de luxe',
                    'Tous les repas (pension complète)',
                    '4 safaris guidés',
                    'Guide naturaliste francophone',
                    'Droits d\'entrée au parc',
                    'Eau minérale illimitée'
                ]),
                'included_services_en' => json_encode([
                    'Air-conditioned 4x4 transport from Abidjan',
                    '2 nights in luxury lodge',
                    'All meals (full board)',
                    '4 guided safaris',
                    'French-speaking naturalist guide',
                    'Park entrance fees',
                    'Unlimited mineral water'
                ]),
                'excluded_services_fr' => json_encode([
                    'Boissons alcoolisées',
                    'Pourboires',
                    'Dépenses personnelles'
                ]),
                'excluded_services_en' => json_encode([
                    'Alcoholic beverages',
                    'Tips',
                    'Personal expenses'
                ]),
                'itinerary_fr' => json_encode([
                    ['day' => 'Jour 1', 'activity' => 'Départ d\'Abidjan (6h) - Arrivée au lodge (14h) - Safari de fin d\'après-midi'],
                    ['day' => 'Jour 2', 'activity' => 'Safari matinal - Déjeuner au lodge - Safari de l\'après-midi - Dîner sous les étoiles'],
                    ['day' => 'Jour 3', 'activity' => 'Safari à l\'aube - Petit-déjeuner - Retour vers Abidjan (arrivée 18h)']
                ]),
                'itinerary_en' => json_encode([
                    ['day' => 'Day 1', 'activity' => 'Departure from Abidjan (6am) - Arrival at lodge (2pm) - Late afternoon safari'],
                    ['day' => 'Day 2', 'activity' => 'Morning safari - Lunch at lodge - Afternoon safari - Dinner under the stars'],
                    ['day' => 'Day 3', 'activity' => 'Dawn safari - Breakfast - Return to Abidjan (arrival 6pm)']
                ]),
                'available_dates' => json_encode([
                    Carbon::now()->addDays(15)->format('Y-m-d'),
                    Carbon::now()->addDays(30)->format('Y-m-d'),
                    Carbon::now()->addDays(45)->format('Y-m-d'),
                ]),
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.7,
                'total_reviews' => 18,
            ],

            // Croisière - Îles Éhotilé
            [
                'category_id' => $packagesCategory->id,
                'title_fr' => 'Croisière Îles Éhotilé - Journée Découverte',
                'title_en' => 'Éhotilé Islands Cruise - Discovery Day',
                'slug' => 'croisiere-iles-ehotile',
                'description_fr' => 'Échappez-vous pour une journée paradisiaque aux îles Éhotilé. Croisière en bateau, plages de sable fin, déjeuner de fruits de mer et découverte de la culture locale. Un havre de paix à 2h d\'Abidjan.',
                'description_en' => 'Escape for a heavenly day to the Éhotilé islands. Boat cruise, sandy beaches, seafood lunch and discovery of local culture. A haven of peace 2 hours from Abidjan.',
                'package_type' => 'cruise',
                'destination' => 'Îles Éhotilé, Assinie',
                'duration' => 1,
                'duration_text_fr' => '1 journée complète',
                'duration_text_en' => '1 full day',
                'departure_city' => 'Abidjan',
                'price' => 125000,
                'discount_price' => null,
                'max_participants' => 12,
                'min_participants' => 4,
                'included_services_fr' => json_encode([
                    'Transport en bus climatisé A/R',
                    'Croisière en bateau',
                    'Déjeuner fruits de mer',
                    'Boissons non alcoolisées',
                    'Guide accompagnateur',
                    'Gilets de sauvetage',
                    'Assurance'
                ]),
                'included_services_en' => json_encode([
                    'Round-trip air-conditioned bus',
                    'Boat cruise',
                    'Seafood lunch',
                    'Non-alcoholic beverages',
                    'Tour guide',
                    'Life jackets',
                    'Insurance'
                ]),
                'excluded_services_fr' => json_encode([
                    'Boissons alcoolisées',
                    'Activités nautiques optionnelles',
                    'Souvenirs'
                ]),
                'excluded_services_en' => json_encode([
                    'Alcoholic beverages',
                    'Optional water activities',
                    'Souvenirs'
                ]),
                'itinerary_fr' => json_encode([
                    ['time' => '07:00', 'activity' => 'Départ d\'Abidjan'],
                    ['time' => '09:00', 'activity' => 'Arrivée à Assinie - Embarquement'],
                    ['time' => '09:30', 'activity' => 'Croisière vers les îles'],
                    ['time' => '10:30', 'activity' => 'Baignade et détente sur la plage'],
                    ['time' => '13:00', 'activity' => 'Déjeuner fruits de mer'],
                    ['time' => '15:00', 'activity' => 'Visite du village Éhotilé'],
                    ['time' => '16:30', 'activity' => 'Retour vers Abidjan'],
                    ['time' => '18:30', 'activity' => 'Arrivée à Abidjan']
                ]),
                'itinerary_en' => json_encode([
                    ['time' => '07:00', 'activity' => 'Departure from Abidjan'],
                    ['time' => '09:00', 'activity' => 'Arrival in Assinie - Boarding'],
                    ['time' => '09:30', 'activity' => 'Cruise to the islands'],
                    ['time' => '10:30', 'activity' => 'Swimming and relaxation on the beach'],
                    ['time' => '13:00', 'activity' => 'Seafood lunch'],
                    ['time' => '15:00', 'activity' => 'Visit to Éhotilé village'],
                    ['time' => '16:30', 'activity' => 'Return to Abidjan'],
                    ['time' => '18:30', 'activity' => 'Arrival in Abidjan']
                ]),
                'available_dates' => json_encode([
                    Carbon::now()->addDays(5)->format('Y-m-d'),
                    Carbon::now()->addDays(12)->format('Y-m-d'),
                    Carbon::now()->addDays(19)->format('Y-m-d'),
                    Carbon::now()->addDays(26)->format('Y-m-d'),
                ]),
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.5,
                'total_reviews' => 32,
            ],

            // City Tour - Grand Bassam
            [
                'category_id' => $packagesCategory->id,
                'title_fr' => 'Grand Bassam - Patrimoine UNESCO',
                'title_en' => 'Grand Bassam - UNESCO Heritage',
                'slug' => 'grand-bassam-unesco',
                'description_fr' => 'Découvrez l\'ancienne capitale coloniale, classée au patrimoine mondial de l\'UNESCO. Architecture coloniale, musée national, plages et artisanat local. Une plongée dans l\'histoire ivoirienne.',
                'description_en' => 'Discover the former colonial capital, UNESCO World Heritage Site. Colonial architecture, national museum, beaches and local crafts. A dive into Ivorian history.',
                'package_type' => 'city_tour',
                'destination' => 'Grand-Bassam',
                'duration' => 1,
                'duration_text_fr' => 'Demi-journée',
                'duration_text_en' => 'Half day',
                'departure_city' => 'Abidjan',
                'price' => 45000,
                'discount_price' => null,
                'max_participants' => 15,
                'min_participants' => 2,
                'included_services_fr' => json_encode([
                    'Transport climatisé A/R',
                    'Guide historien',
                    'Entrée au musée national',
                    'Visite du quartier France',
                    'Eau minérale'
                ]),
                'included_services_en' => json_encode([
                    'Round-trip air-conditioned transport',
                    'Historian guide',
                    'National museum entrance',
                    'France quarter visit',
                    'Mineral water'
                ]),
                'excluded_services_fr' => json_encode([
                    'Repas',
                    'Achats personnels',
                    'Pourboires'
                ]),
                'excluded_services_en' => json_encode([
                    'Meals',
                    'Personal purchases',
                    'Tips'
                ]),
                'itinerary_fr' => json_encode([
                    ['time' => '09:00', 'activity' => 'Départ d\'Abidjan'],
                    ['time' => '09:45', 'activity' => 'Arrivée à Grand-Bassam'],
                    ['time' => '10:00', 'activity' => 'Visite du musée national du costume'],
                    ['time' => '11:00', 'activity' => 'Tour du quartier France colonial'],
                    ['time' => '12:00', 'activity' => 'Marché artisanal'],
                    ['time' => '12:30', 'activity' => 'Retour vers Abidjan'],
                    ['time' => '13:15', 'activity' => 'Arrivée à Abidjan']
                ]),
                'itinerary_en' => json_encode([
                    ['time' => '09:00', 'activity' => 'Departure from Abidjan'],
                    ['time' => '09:45', 'activity' => 'Arrival in Grand-Bassam'],
                    ['time' => '10:00', 'activity' => 'Visit to national costume museum'],
                    ['time' => '11:00', 'activity' => 'Colonial France quarter tour'],
                    ['time' => '12:00', 'activity' => 'Craft market'],
                    ['time' => '12:30', 'activity' => 'Return to Abidjan'],
                    ['time' => '13:15', 'activity' => 'Arrival in Abidjan']
                ]),
                'available_dates' => json_encode([
                    Carbon::now()->addDays(3)->format('Y-m-d'),
                    Carbon::now()->addDays(6)->format('Y-m-d'),
                    Carbon::now()->addDays(10)->format('Y-m-d'),
                    Carbon::now()->addDays(13)->format('Y-m-d'),
                    Carbon::now()->addDays(17)->format('Y-m-d'),
                ]),
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.3,
                'total_reviews' => 45,
            ],
        ];

        foreach ($packages as $packageData) {
            TourPackage::create($packageData);
        }

        $this->command->info('✅ 5 packages touristiques créés avec succès!');
        $this->command->info('   - 1 Tour en hélicoptère');
        $this->command->info('   - 1 Jet privé');
        $this->command->info('   - 1 Safari');
        $this->command->info('   - 1 Croisière');
        $this->command->info('   - 1 City tour');
    }
}
