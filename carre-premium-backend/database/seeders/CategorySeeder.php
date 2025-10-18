<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Catégories principales
        $mainCategories = [
            [
                'id' => 1,
                'name_fr' => 'Vols',
                'name_en' => 'Flights',
                'slug' => 'flights',
                'description_fr' => 'Réservez vos billets d\'avion pour toutes destinations',
                'description_en' => 'Book your flight tickets to all destinations',
                'icon' => 'airplane',
                'parent_id' => null,
                'order_position' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name_fr' => 'Événements Sportifs',
                'name_en' => 'Sports Events',
                'slug' => 'sports-events',
                'description_fr' => 'Billets pour les plus grands événements sportifs',
                'description_en' => 'Tickets for the biggest sports events',
                'icon' => 'trophy',
                'parent_id' => null,
                'order_position' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name_fr' => 'Événements Culturels',
                'name_en' => 'Cultural Events',
                'slug' => 'cultural-events',
                'description_fr' => 'Concerts, théâtre, festivals et plus',
                'description_en' => 'Concerts, theater, festivals and more',
                'icon' => 'music',
                'parent_id' => null,
                'order_position' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name_fr' => 'Packages Touristiques',
                'name_en' => 'Tour Packages',
                'slug' => 'tour-packages',
                'description_fr' => 'Découvrez nos offres de voyages tout compris',
                'description_en' => 'Discover our all-inclusive travel packages',
                'icon' => 'suitcase',
                'parent_id' => null,
                'order_position' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name_fr' => 'Hélicoptère',
                'name_en' => 'Helicopter',
                'slug' => 'helicopter',
                'description_fr' => 'Vols en hélicoptère et tours panoramiques',
                'description_en' => 'Helicopter flights and scenic tours',
                'icon' => 'helicopter',
                'parent_id' => null,
                'order_position' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'name_fr' => 'Jet Privé',
                'name_en' => 'Private Jet',
                'slug' => 'private-jet',
                'description_fr' => 'Location de jets privés pour vos déplacements',
                'description_en' => 'Private jet rental for your travels',
                'icon' => 'plane-departure',
                'parent_id' => null,
                'order_position' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($mainCategories);

        // Sous-catégories sportives
        $sportsSubCategories = [
            [
                'name_fr' => 'Tennis',
                'name_en' => 'Tennis',
                'slug' => 'tennis',
                'description_fr' => 'Roland Garros, Wimbledon, US Open...',
                'description_en' => 'Roland Garros, Wimbledon, US Open...',
                'icon' => 'tennis-ball',
                'parent_id' => 2,
                'order_position' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_fr' => 'Football',
                'name_en' => 'Football',
                'slug' => 'football',
                'description_fr' => 'Champions League, CAN, Coupe du Monde...',
                'description_en' => 'Champions League, AFCON, World Cup...',
                'icon' => 'football',
                'parent_id' => 2,
                'order_position' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_fr' => 'Formule 1',
                'name_en' => 'Formula 1',
                'slug' => 'formula-1',
                'description_fr' => 'Grands Prix de Formule 1',
                'description_en' => 'Formula 1 Grand Prix',
                'icon' => 'racing',
                'parent_id' => 2,
                'order_position' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_fr' => 'Basketball',
                'name_en' => 'Basketball',
                'slug' => 'basketball',
                'description_fr' => 'NBA, Eurobasket...',
                'description_en' => 'NBA, Eurobasket...',
                'icon' => 'basketball',
                'parent_id' => 2,
                'order_position' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($sportsSubCategories);

        // Sous-catégories culturelles
        $culturalSubCategories = [
            [
                'name_fr' => 'Concerts',
                'name_en' => 'Concerts',
                'slug' => 'concerts',
                'description_fr' => 'Concerts de musique live',
                'description_en' => 'Live music concerts',
                'icon' => 'microphone',
                'parent_id' => 3,
                'order_position' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_fr' => 'Festivals',
                'name_en' => 'Festivals',
                'slug' => 'festivals',
                'description_fr' => 'Festivals de musique et culturels',
                'description_en' => 'Music and cultural festivals',
                'icon' => 'calendar-star',
                'parent_id' => 3,
                'order_position' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_fr' => 'Théâtre',
                'name_en' => 'Theater',
                'slug' => 'theater',
                'description_fr' => 'Pièces de théâtre et spectacles',
                'description_en' => 'Theater plays and shows',
                'icon' => 'masks-theater',
                'parent_id' => 3,
                'order_position' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($culturalSubCategories);
    }
}
