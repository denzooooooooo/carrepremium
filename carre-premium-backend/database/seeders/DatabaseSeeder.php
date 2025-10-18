<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ordre important : respecter les dépendances entre tables
        $this->call([
            CurrencySeeder::class,
            AdminSeeder::class,
            CategorySeeder::class,
            SettingSeeder::class,
            // Nouveaux seeders
            ApiConfigurationsSeeder::class,
            PricingRulesSeeder::class,
            PaymentGatewaysSeeder::class,
        ]);

        // Créer un utilisateur de test
        // User::factory(10)->create();
    }
}
