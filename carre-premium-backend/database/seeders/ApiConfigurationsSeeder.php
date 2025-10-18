<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApiConfiguration;

class ApiConfigurationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            [
                'provider' => 'amadeus',
                'api_key' => env('AMADEUS_CLIENT_ID', 'your_amadeus_client_id'),
                'api_secret' => env('AMADEUS_CLIENT_SECRET', 'your_amadeus_client_secret'),
                'endpoint_url' => env('AMADEUS_ENVIRONMENT', 'test') === 'production' 
                    ? 'https://api.amadeus.com' 
                    : 'https://test.api.amadeus.com',
                'is_production' => env('AMADEUS_ENVIRONMENT', 'test') === 'production',
                'is_active' => true,
                'additional_config' => json_encode([
                    'version' => 'v1',
                    'timeout' => 30,
                    'grant_type' => 'client_credentials',
                    'endpoints' => [
                        'token' => '/v1/security/oauth2/token',
                        'flight_search' => '/v2/shopping/flight-offers',
                        'flight_price' => '/v1/shopping/flight-offers/pricing',
                        'flight_booking' => '/v1/booking/flight-orders',
                    ],
                ]),
            ],
            [
                'provider' => 'sabre',
                'api_key' => env('SABRE_CLIENT_ID', ''),
                'api_secret' => env('SABRE_CLIENT_SECRET', ''),
                'endpoint_url' => env('SABRE_ENVIRONMENT', 'test') === 'production'
                    ? 'https://api.sabre.com'
                    : 'https://api-crt.cert.sabre.com',
                'is_production' => env('SABRE_ENVIRONMENT', 'test') === 'production',
                'is_active' => false,
                'additional_config' => json_encode([
                    'version' => 'v1',
                    'timeout' => 30,
                    'pcc' => env('SABRE_PCC', ''),
                ]),
            ],
            [
                'provider' => 'skyscanner',
                'api_key' => env('SKYSCANNER_API_KEY', ''),
                'api_secret' => null,
                'endpoint_url' => 'https://partners.api.skyscanner.net',
                'is_production' => true,
                'is_active' => false,
                'additional_config' => json_encode([
                    'version' => 'v1',
                    'timeout' => 30,
                    'market' => 'CI',
                    'currency' => 'XOF',
                    'locale' => 'fr-FR',
                ]),
            ],
        ];

        foreach ($configs as $config) {
            ApiConfiguration::create($config);
        }
    }
}
