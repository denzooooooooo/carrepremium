<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PricingRule;

class PricingRulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rules = [
            [
                'product_type' => 'flight',
                'rule_name' => 'Marge Vols Domestiques',
                'category' => 'domestic',
                'margin_type' => 'percentage',
                'margin_value' => 15.00,
                'min_price' => null,
                'max_price' => null,
                'priority' => 10,
                'description' => 'Marge appliquée sur tous les vols domestiques en Côte d\'Ivoire',
                'is_active' => true,
            ],
            [
                'product_type' => 'flight',
                'rule_name' => 'Marge Vols Internationaux',
                'category' => 'international',
                'margin_type' => 'percentage',
                'margin_value' => 20.00,
                'min_price' => null,
                'max_price' => null,
                'priority' => 10,
                'description' => 'Marge appliquée sur tous les vols internationaux',
                'is_active' => true,
            ],
            [
                'product_type' => 'flight',
                'rule_name' => 'Marge Vols Régionaux (Afrique)',
                'category' => 'regional',
                'margin_type' => 'percentage',
                'margin_value' => 18.00,
                'min_price' => null,
                'max_price' => null,
                'priority' => 10,
                'description' => 'Marge appliquée sur les vols vers d\'autres pays africains',
                'is_active' => true,
            ],
            [
                'product_type' => 'flight',
                'rule_name' => 'Marge Classe Affaires',
                'category' => 'business',
                'margin_type' => 'percentage',
                'margin_value' => 12.00,
                'min_price' => 500000,
                'max_price' => null,
                'priority' => 15,
                'description' => 'Marge réduite pour les billets classe affaires (prix élevés)',
                'is_active' => true,
            ],
            [
                'product_type' => 'event',
                'rule_name' => 'Marge Événements Sportifs',
                'category' => 'sport',
                'margin_type' => 'percentage',
                'margin_value' => 25.00,
                'min_price' => null,
                'max_price' => null,
                'priority' => 5,
                'description' => 'Marge appliquée sur tous les événements sportifs',
                'is_active' => true,
            ],
            [
                'product_type' => 'event',
                'rule_name' => 'Marge Événements VIP',
                'category' => 'vip',
                'margin_type' => 'percentage',
                'margin_value' => 30.00,
                'min_price' => 100000,
                'max_price' => null,
                'priority' => 20,
                'description' => 'Marge premium pour les billets VIP et zones privilégiées',
                'is_active' => true,
            ],
            [
                'product_type' => 'event',
                'rule_name' => 'Marge Concerts',
                'category' => 'concert',
                'margin_type' => 'percentage',
                'margin_value' => 28.00,
                'min_price' => null,
                'max_price' => null,
                'priority' => 5,
                'description' => 'Marge appliquée sur les concerts et événements musicaux',
                'is_active' => true,
            ],
            [
                'product_type' => 'package',
                'rule_name' => 'Marge Packages Standard',
                'category' => null,
                'margin_type' => 'percentage',
                'margin_value' => 30.00,
                'min_price' => null,
                'max_price' => 500000,
                'priority' => 5,
                'description' => 'Marge standard pour les packages touristiques',
                'is_active' => true,
            ],
            [
                'product_type' => 'package',
                'rule_name' => 'Marge Packages Luxe',
                'category' => 'luxury',
                'margin_type' => 'percentage',
                'margin_value' => 35.00,
                'min_price' => 500000,
                'max_price' => null,
                'priority' => 10,
                'description' => 'Marge premium pour les packages de luxe (hélicoptère, jet privé)',
                'is_active' => true,
            ],
            [
                'product_type' => 'package',
                'rule_name' => 'Marge Hélicoptère',
                'category' => 'helicopter',
                'margin_type' => 'percentage',
                'margin_value' => 40.00,
                'min_price' => null,
                'max_price' => null,
                'priority' => 15,
                'description' => 'Marge spéciale pour les tours en hélicoptère',
                'is_active' => true,
            ],
            [
                'product_type' => 'package',
                'rule_name' => 'Marge Jet Privé',
                'category' => 'private_jet',
                'margin_type' => 'percentage',
                'margin_value' => 35.00,
                'min_price' => 1000000,
                'max_price' => null,
                'priority' => 15,
                'description' => 'Marge pour les vols en jet privé',
                'is_active' => true,
            ],
            [
                'product_type' => 'flight',
                'rule_name' => 'Frais de Service Minimum',
                'category' => null,
                'margin_type' => 'fixed',
                'margin_value' => 5000,
                'min_price' => null,
                'max_price' => 50000,
                'priority' => 25,
                'description' => 'Frais de service minimum pour les billets à bas prix',
                'is_active' => true,
            ],
        ];

        foreach ($rules as $rule) {
            PricingRule::create($rule);
        }
    }
}
