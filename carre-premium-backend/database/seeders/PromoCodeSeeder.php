<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PromoCode;
use Carbon\Carbon;

class PromoCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promoCodes = [
            [
                'code' => 'BIENVENUE2024',
                'description_fr' => 'Code de bienvenue pour les nouveaux clients - 10,000 XOF de réduction',
                'description_en' => 'Welcome code for new customers - 10,000 XOF discount',
                'discount_type' => 'fixed',
                'discount_value' => 10000,
                'min_purchase_amount' => 50000,
                'max_discount_amount' => null,
                'usage_limit' => null, // Illimité
                'used_count' => 0,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addYear(),
                'applicable_to' => 'all',
                'is_active' => true
            ],
            [
                'code' => 'PROMO10',
                'description_fr' => 'Réduction de 10% sur toutes les réservations',
                'description_en' => '10% discount on all bookings',
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'min_purchase_amount' => 100000,
                'max_discount_amount' => 50000,
                'usage_limit' => 1000,
                'used_count' => 0,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(3),
                'applicable_to' => 'all',
                'is_active' => true
            ],
            [
                'code' => 'VOLS15',
                'description_fr' => 'Réduction de 15% sur les vols uniquement',
                'description_en' => '15% discount on flights only',
                'discount_type' => 'percentage',
                'discount_value' => 15,
                'min_purchase_amount' => 200000,
                'max_discount_amount' => 100000,
                'usage_limit' => 500,
                'used_count' => 0,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(6),
                'applicable_to' => 'flights',
                'is_active' => true
            ],
            [
                'code' => 'VIP500',
                'description_fr' => 'Code VIP - 50,000 XOF de réduction pour les grosses réservations',
                'description_en' => 'VIP Code - 50,000 XOF discount for large bookings',
                'discount_type' => 'fixed',
                'discount_value' => 50000,
                'min_purchase_amount' => 500000,
                'max_discount_amount' => null,
                'usage_limit' => null,
                'used_count' => 0,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addYear(),
                'applicable_to' => 'all',
                'is_active' => true
            ],
            [
                'code' => 'EVENTS20',
                'description_fr' => 'Réduction de 20% sur les événements',
                'description_en' => '20% discount on events',
                'discount_type' => 'percentage',
                'discount_value' => 20,
                'min_purchase_amount' => 50000,
                'max_discount_amount' => 30000,
                'usage_limit' => 200,
                'used_count' => 0,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(2),
                'applicable_to' => 'events',
                'is_active' => true
            ],
            [
                'code' => 'PACKAGE25',
                'description_fr' => 'Réduction de 25% sur les packages touristiques',
                'description_en' => '25% discount on tour packages',
                'discount_type' => 'percentage',
                'discount_value' => 25,
                'min_purchase_amount' => 300000,
                'max_discount_amount' => 150000,
                'usage_limit' => 100,
                'used_count' => 0,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(4),
                'applicable_to' => 'packages',
                'is_active' => true
            ],
            [
                'code' => 'SAVE20K',
                'description_fr' => 'Économisez 20,000 XOF sur votre réservation',
                'description_en' => 'Save 20,000 XOF on your booking',
                'discount_type' => 'fixed',
                'discount_value' => 20000,
                'min_purchase_amount' => 150000,
                'max_discount_amount' => null,
                'usage_limit' => 500,
                'used_count' => 0,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(3),
                'applicable_to' => 'all',
                'is_active' => true
            ],
            [
                'code' => 'FLASH50',
                'description_fr' => 'Vente flash - 50,000 XOF de réduction (limité)',
                'description_en' => 'Flash sale - 50,000 XOF discount (limited)',
                'discount_type' => 'fixed',
                'discount_value' => 50000,
                'min_purchase_amount' => 400000,
                'max_discount_amount' => null,
                'usage_limit' => 50, // Très limité
                'used_count' => 0,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addDays(7), // 7 jours seulement
                'applicable_to' => 'all',
                'is_active' => true
            ]
        ];

        foreach ($promoCodes as $promoCode) {
            PromoCode::create($promoCode);
        }

        $this->command->info('✅ 8 codes promo créés avec succès!');
        $this->command->info('');
        $this->command->info('Codes disponibles:');
        $this->command->info('- BIENVENUE2024: 10,000 XOF (nouveau client)');
        $this->command->info('- PROMO10: 10% de réduction');
        $this->command->info('- VOLS15: 15% sur les vols');
        $this->command->info('- VIP500: 50,000 XOF (grosses réservations)');
        $this->command->info('- EVENTS20: 20% sur les événements');
        $this->command->info('- PACKAGE25: 25% sur les packages');
        $this->command->info('- SAVE20K: 20,000 XOF de réduction');
        $this->command->info('- FLASH50: 50,000 XOF (vente flash 7 jours)');
    }
}
