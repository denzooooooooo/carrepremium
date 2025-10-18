<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentGateway;

class PaymentGatewaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gateways = [
            [
                'gateway_name' => 'Stripe',
                'gateway_type' => 'card',
                'is_active' => true,
                'supported_currencies' => json_encode(['EUR', 'USD', 'XOF']),
                'transaction_fee_percentage' => 2.90,
                'transaction_fee_fixed' => 500,
                'api_key' => env('STRIPE_KEY', ''),
                'api_secret' => env('STRIPE_SECRET', ''),
                'webhook_url' => env('APP_URL') . '/webhooks/stripe',
                'configuration' => json_encode([
                    'publishable_key' => env('STRIPE_PUBLISHABLE_KEY', ''),
                    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET', ''),
                ]),
            ],
            [
                'gateway_name' => 'PayPal',
                'gateway_type' => 'card',
                'is_active' => false,
                'supported_currencies' => json_encode(['EUR', 'USD']),
                'transaction_fee_percentage' => 3.40,
                'transaction_fee_fixed' => 0,
                'api_key' => env('PAYPAL_CLIENT_ID', ''),
                'api_secret' => env('PAYPAL_SECRET', ''),
                'webhook_url' => env('APP_URL') . '/webhooks/paypal',
                'configuration' => json_encode([
                    'mode' => env('PAYPAL_MODE', 'sandbox'),
                ]),
            ],
            [
                'gateway_name' => 'Orange Money',
                'gateway_type' => 'mobile_money',
                'is_active' => true,
                'supported_currencies' => json_encode(['XOF']),
                'transaction_fee_percentage' => 1.50,
                'transaction_fee_fixed' => 0,
                'api_key' => env('ORANGE_MONEY_KEY', ''),
                'api_secret' => env('ORANGE_MONEY_SECRET', ''),
                'merchant_id' => env('ORANGE_MONEY_MERCHANT_ID', ''),
                'webhook_url' => env('APP_URL') . '/webhooks/orange-money',
                'configuration' => json_encode([
                    'country' => 'CI',
                    'currency' => 'XOF',
                ]),
            ],
            [
                'gateway_name' => 'MTN Mobile Money',
                'gateway_type' => 'mobile_money',
                'is_active' => true,
                'supported_currencies' => json_encode(['XOF']),
                'transaction_fee_percentage' => 1.50,
                'transaction_fee_fixed' => 0,
                'api_key' => env('MTN_MOMO_KEY', ''),
                'api_secret' => env('MTN_MOMO_SECRET', ''),
                'merchant_id' => env('MTN_MOMO_MERCHANT_ID', ''),
                'webhook_url' => env('APP_URL') . '/webhooks/mtn-momo',
                'configuration' => json_encode([
                    'country' => 'CI',
                    'currency' => 'XOF',
                ]),
            ],
            [
                'gateway_name' => 'Wave',
                'gateway_type' => 'mobile_money',
                'is_active' => true,
                'supported_currencies' => json_encode(['XOF']),
                'transaction_fee_percentage' => 1.00,
                'transaction_fee_fixed' => 0,
                'api_key' => env('WAVE_KEY', ''),
                'api_secret' => env('WAVE_SECRET', ''),
                'merchant_id' => env('WAVE_MERCHANT_ID', ''),
                'webhook_url' => env('APP_URL') . '/webhooks/wave',
                'configuration' => json_encode([
                    'country' => 'CI',
                    'currency' => 'XOF',
                ]),
            ],
            [
                'gateway_name' => 'Virement Bancaire',
                'gateway_type' => 'bank_transfer',
                'is_active' => true,
                'supported_currencies' => json_encode(['XOF', 'EUR', 'USD']),
                'transaction_fee_percentage' => 0,
                'transaction_fee_fixed' => 0,
                'configuration' => json_encode([
                    'bank_name' => 'Banque Atlantique',
                    'account_number' => 'CI00 0000 0000 0000 0000 0000',
                    'swift_code' => 'BIATCIAB',
                    'iban' => 'CI00 0000 0000 0000 0000 0000',
                ]),
            ],
        ];

        foreach ($gateways as $gateway) {
            PaymentGateway::create($gateway);
        }
    }
}
