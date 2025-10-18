<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Jean',
                'last_name' => 'Kouassi',
                'email' => 'jean.kouassi@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+225 07 12 34 56 78',
                'country' => 'Côte d\'Ivoire',
                'city' => 'Abidjan',
                'address' => 'Cocody, Riviera 3',
                'is_active' => true,
                'loyalty_points' => 150,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Marie',
                'last_name' => 'Diallo',
                'email' => 'marie.diallo@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+225 05 98 76 54 32',
                'country' => 'Côte d\'Ivoire',
                'city' => 'Abidjan',
                'address' => 'Plateau, Avenue Chardy',
                'is_active' => true,
                'loyalty_points' => 320,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Pierre',
                'last_name' => 'Martin',
                'email' => 'pierre.martin@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+33 6 12 34 56 78',
                'country' => 'France',
                'city' => 'Paris',
                'address' => '15 Rue de la Paix, 75002 Paris',
                'is_active' => true,
                'loyalty_points' => 580,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Fatou',
                'last_name' => 'Sow',
                'email' => 'fatou.sow@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+221 77 123 45 67',
                'country' => 'Sénégal',
                'city' => 'Dakar',
                'address' => 'Almadies, Dakar',
                'is_active' => true,
                'loyalty_points' => 95,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Amadou',
                'last_name' => 'Traoré',
                'email' => 'amadou.traore@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+225 01 23 45 67 89',
                'country' => 'Côte d\'Ivoire',
                'city' => 'Yamoussoukro',
                'address' => 'Quartier Habitat',
                'is_active' => false,
                'loyalty_points' => 45,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Sophie',
                'last_name' => 'Dubois',
                'email' => 'sophie.dubois@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+33 7 98 76 54 32',
                'country' => 'France',
                'city' => 'Lyon',
                'address' => '25 Rue de la République, 69002 Lyon',
                'is_active' => true,
                'loyalty_points' => 210,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Koffi',
                'last_name' => 'Yao',
                'email' => 'koffi.yao@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+225 07 55 66 77 88',
                'country' => 'Côte d\'Ivoire',
                'city' => 'Abidjan',
                'address' => 'Marcory, Zone 4',
                'is_active' => true,
                'loyalty_points' => 425,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Aïcha',
                'last_name' => 'Bamba',
                'email' => 'aicha.bamba@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+225 05 11 22 33 44',
                'country' => 'Côte d\'Ivoire',
                'city' => 'Bouaké',
                'address' => 'Dar Es Salam',
                'is_active' => true,
                'loyalty_points' => 175,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Thomas',
                'last_name' => 'Bernard',
                'email' => 'thomas.bernard@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+33 6 45 67 89 01',
                'country' => 'France',
                'city' => 'Marseille',
                'address' => '10 La Canebière, 13001 Marseille',
                'is_active' => true,
                'loyalty_points' => 890,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Awa',
                'last_name' => 'Cissé',
                'email' => 'awa.cisse@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+225 07 99 88 77 66',
                'country' => 'Côte d\'Ivoire',
                'city' => 'Abidjan',
                'address' => 'Yopougon, Niangon',
                'is_active' => true,
                'loyalty_points' => 65,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        $this->command->info('✅ 10 utilisateurs de test créés avec succès!');
        $this->command->info('📧 Email: jean.kouassi@example.com');
        $this->command->info('🔑 Mot de passe: password123');
    }
}
