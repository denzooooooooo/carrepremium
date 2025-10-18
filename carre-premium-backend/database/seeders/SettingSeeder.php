<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Paramètres généraux
            ['setting_key' => 'site_name', 'setting_value' => 'Carré Premium', 'setting_type' => 'text', 'group_name' => 'general', 'description' => 'Nom du site', 'updated_at' => now()],
            ['setting_key' => 'site_email', 'setting_value' => 'contact@carrepremium.com', 'setting_type' => 'text', 'group_name' => 'general', 'description' => 'Email de contact', 'updated_at' => now()],
            ['setting_key' => 'site_phone', 'setting_value' => '+225 XX XX XX XX XX', 'setting_type' => 'text', 'group_name' => 'general', 'description' => 'Téléphone', 'updated_at' => now()],
            ['setting_key' => 'site_address', 'setting_value' => 'Abidjan, Côte d\'Ivoire', 'setting_type' => 'text', 'group_name' => 'general', 'description' => 'Adresse', 'updated_at' => now()],
            ['setting_key' => 'default_language', 'setting_value' => 'fr', 'setting_type' => 'text', 'group_name' => 'general', 'description' => 'Langue par défaut', 'updated_at' => now()],
            ['setting_key' => 'default_currency', 'setting_value' => 'XOF', 'setting_type' => 'text', 'group_name' => 'general', 'description' => 'Devise par défaut', 'updated_at' => now()],
            
            // Apparence
            ['setting_key' => 'theme_mode', 'setting_value' => 'light', 'setting_type' => 'text', 'group_name' => 'appearance', 'description' => 'Mode du thème (light/dark)', 'updated_at' => now()],
            ['setting_key' => 'primary_color', 'setting_value' => '#9333EA', 'setting_type' => 'text', 'group_name' => 'appearance', 'description' => 'Couleur primaire (violet)', 'updated_at' => now()],
            ['setting_key' => 'secondary_color', 'setting_value' => '#D4AF37', 'setting_type' => 'text', 'group_name' => 'appearance', 'description' => 'Couleur secondaire (doré)', 'updated_at' => now()],
            ['setting_key' => 'logo', 'setting_value' => '/images/logo.png', 'setting_type' => 'image', 'group_name' => 'appearance', 'description' => 'Logo du site', 'updated_at' => now()],
            ['setting_key' => 'favicon', 'setting_value' => '/images/favicon.ico', 'setting_type' => 'image', 'group_name' => 'appearance', 'description' => 'Favicon', 'updated_at' => now()],
            
            // Fonctionnalités
            ['setting_key' => 'enable_chatbot', 'setting_value' => 'true', 'setting_type' => 'boolean', 'group_name' => 'features', 'description' => 'Activer le chatbot', 'updated_at' => now()],
            ['setting_key' => 'enable_whatsapp', 'setting_value' => 'true', 'setting_type' => 'boolean', 'group_name' => 'features', 'description' => 'Activer WhatsApp', 'updated_at' => now()],
            ['setting_key' => 'whatsapp_number', 'setting_value' => '+225XXXXXXXXX', 'setting_type' => 'text', 'group_name' => 'features', 'description' => 'Numéro WhatsApp', 'updated_at' => now()],
            ['setting_key' => 'enable_recommendations', 'setting_value' => 'true', 'setting_type' => 'boolean', 'group_name' => 'features', 'description' => 'Activer les recommandations', 'updated_at' => now()],
            ['setting_key' => 'enable_newsletter', 'setting_value' => 'true', 'setting_type' => 'boolean', 'group_name' => 'features', 'description' => 'Activer la newsletter', 'updated_at' => now()],
            ['setting_key' => 'enable_reviews', 'setting_value' => 'true', 'setting_type' => 'boolean', 'group_name' => 'features', 'description' => 'Activer les avis', 'updated_at' => now()],
            
            // Paiement
            ['setting_key' => 'tax_rate', 'setting_value' => '0.18', 'setting_type' => 'number', 'group_name' => 'payment', 'description' => 'Taux de taxe (18%)', 'updated_at' => now()],
            ['setting_key' => 'booking_fee', 'setting_value' => '5000', 'setting_type' => 'number', 'group_name' => 'payment', 'description' => 'Frais de réservation', 'updated_at' => now()],
            ['setting_key' => 'cancellation_fee_percentage', 'setting_value' => '10', 'setting_type' => 'number', 'group_name' => 'payment', 'description' => 'Pourcentage de frais d\'annulation', 'updated_at' => now()],
            ['setting_key' => 'enable_stripe', 'setting_value' => 'true', 'setting_type' => 'boolean', 'group_name' => 'payment', 'description' => 'Activer Stripe', 'updated_at' => now()],
            ['setting_key' => 'enable_paypal', 'setting_value' => 'true', 'setting_type' => 'boolean', 'group_name' => 'payment', 'description' => 'Activer PayPal', 'updated_at' => now()],
            ['setting_key' => 'enable_mobile_money', 'setting_value' => 'true', 'setting_type' => 'boolean', 'group_name' => 'payment', 'description' => 'Activer Mobile Money', 'updated_at' => now()],
            
            // Email
            ['setting_key' => 'smtp_host', 'setting_value' => 'smtp.gmail.com', 'setting_type' => 'text', 'group_name' => 'email', 'description' => 'Serveur SMTP', 'updated_at' => now()],
            ['setting_key' => 'smtp_port', 'setting_value' => '587', 'setting_type' => 'number', 'group_name' => 'email', 'description' => 'Port SMTP', 'updated_at' => now()],
            ['setting_key' => 'smtp_encryption', 'setting_value' => 'tls', 'setting_type' => 'text', 'group_name' => 'email', 'description' => 'Encryption SMTP', 'updated_at' => now()],
            
            // Réseaux sociaux
            ['setting_key' => 'facebook_url', 'setting_value' => 'https://facebook.com/carrepremium', 'setting_type' => 'text', 'group_name' => 'social', 'description' => 'URL Facebook', 'updated_at' => now()],
            ['setting_key' => 'instagram_url', 'setting_value' => 'https://instagram.com/carrepremium', 'setting_type' => 'text', 'group_name' => 'social', 'description' => 'URL Instagram', 'updated_at' => now()],
            ['setting_key' => 'twitter_url', 'setting_value' => 'https://twitter.com/carrepremium', 'setting_type' => 'text', 'group_name' => 'social', 'description' => 'URL Twitter', 'updated_at' => now()],
            ['setting_key' => 'linkedin_url', 'setting_value' => 'https://linkedin.com/company/carrepremium', 'setting_type' => 'text', 'group_name' => 'social', 'description' => 'URL LinkedIn', 'updated_at' => now()],
            
            // SEO
            ['setting_key' => 'meta_title', 'setting_value' => 'Carré Premium - Billetterie & Voyages de Luxe', 'setting_type' => 'text', 'group_name' => 'seo', 'description' => 'Meta titre', 'updated_at' => now()],
            ['setting_key' => 'meta_description', 'setting_value' => 'Réservez vos billets d\'avion, événements sportifs et culturels, packages touristiques avec Carré Premium', 'setting_type' => 'text', 'group_name' => 'seo', 'description' => 'Meta description', 'updated_at' => now()],
            ['setting_key' => 'meta_keywords', 'setting_value' => 'billets avion, événements sportifs, concerts, packages touristiques, jet privé, hélicoptère', 'setting_type' => 'text', 'group_name' => 'seo', 'description' => 'Meta keywords', 'updated_at' => now()],
            
            // Maintenance
            ['setting_key' => 'maintenance_mode', 'setting_value' => 'false', 'setting_type' => 'boolean', 'group_name' => 'maintenance', 'description' => 'Mode maintenance', 'updated_at' => now()],
            ['setting_key' => 'maintenance_message', 'setting_value' => 'Site en maintenance. Nous revenons bientôt!', 'setting_type' => 'text', 'group_name' => 'maintenance', 'description' => 'Message de maintenance', 'updated_at' => now()],
        ];

        DB::table('settings')->insert($settings);
    }
}
