<?php
/**
 * MIGRATIONS AVANCÉES POUR SYSTÈME COMPLET
 * Carré Premium - Intégration Amadeus & Gestion Complète
 * 
 * Ce fichier contient toutes les migrations pour les nouvelles tables
 * Copiez le contenu de chaque migration dans le fichier correspondant
 */

// ============================================
// 1. API CONFIGURATIONS
// ============================================
/*
Fichier: database/migrations/2025_10_07_190356_create_api_configurations_table.php
*/
$api_configurations_migration = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 50); // 'amadeus', 'sabre', etc.
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->string('endpoint_url');
            $table->boolean('is_production')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('additional_config')->nullable();
            $table->timestamps();
            
            $table->index('provider');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_configurations');
    }
};
PHP;

// ============================================
// 2. FLIGHT BOOKINGS
// ============================================
/*
Fichier: database/migrations/2025_10_07_190356_create_flight_bookings_table.php
*/
$flight_bookings_migration = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('pnr', 10); // Passenger Name Record
            $table->string('eticket_number', 20)->nullable();
            $table->string('amadeus_booking_ref', 50)->nullable();
            $table->json('flight_segments'); // Détails des segments
            $table->decimal('base_price', 10, 2);
            $table->decimal('taxes', 10, 2)->default(0);
            $table->decimal('margin_amount', 10, 2)->default(0);
            $table->decimal('margin_percentage', 5, 2)->default(0);
            $table->decimal('final_price', 10, 2);
            $table->enum('ticket_status', ['issued', 'cancelled', 'refunded', 'pending'])->default('pending');
            $table->string('ticket_pdf_path')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            
            $table->index('pnr');
            $table->index('eticket_number');
            $table->index('ticket_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_bookings');
    }
};
PHP;

// ============================================
// 3. PRICING RULES
// ============================================
/*
Fichier: database/migrations/2025_10_07_190357_create_pricing_rules_table.php
*/
$pricing_rules_migration = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->enum('product_type', ['flight', 'event', 'package']);
            $table->string('rule_name', 100);
            $table->string('category')->nullable(); // 'domestic', 'international', 'vip', etc.
            $table->enum('margin_type', ['percentage', 'fixed']);
            $table->decimal('margin_value', 10, 2);
            $table->decimal('min_price', 10, 2)->nullable();
            $table->decimal('max_price', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['product_type', 'is_active']);
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_rules');
    }
};
PHP;

// ============================================
// 4. EVENT TICKETS
// ============================================
/*
Fichier: database/migrations/2025_10_07_190357_create_event_tickets_table.php
*/
$event_tickets_migration = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('ticket_number', 20)->unique();
            $table->string('qr_code'); // Chemin vers l'image QR
            $table->text('qr_data'); // Données encodées dans le QR
            $table->foreignId('seat_zone_id')->nullable()->constrained('event_seat_zones');
            $table->string('seat_number', 20)->nullable();
            $table->decimal('base_price', 10, 2);
            $table->decimal('margin_amount', 10, 2)->default(0);
            $table->decimal('final_price', 10, 2);
            $table->enum('ticket_status', ['valid', 'used', 'cancelled', 'expired'])->default('valid');
            $table->timestamp('used_at')->nullable();
            $table->string('used_by')->nullable(); // Qui a scanné le ticket
            $table->string('ticket_pdf_path')->nullable();
            $table->timestamps();
            
            $table->index('ticket_number');
            $table->index('ticket_status');
            $table->index(['event_id', 'ticket_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_tickets');
    }
};
PHP;

// ============================================
// 5. EVENT INVENTORY
// ============================================
/*
Fichier: database/migrations/2025_10_07_190357_create_event_inventory_table.php
*/
$event_inventory_migration = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('seat_zone_id')->nullable()->constrained('event_seat_zones');
            $table->integer('total_tickets');
            $table->integer('sold_tickets')->default(0);
            $table->integer('reserved_tickets')->default(0);
            $table->integer('available_tickets');
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate();
            $table->timestamps();
            
            $table->index(['event_id', 'seat_zone_id']);
            $table->index('available_tickets');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_inventory');
    }
};
PHP;

// ============================================
// 6. PACKAGE BOOKINGS
// ============================================
/*
Fichier: database/migrations/2025_10_07_190357_create_package_bookings_table.php
*/
$package_bookings_migration = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('tour_packages')->onDelete('cascade');
            $table->string('confirmation_number', 20)->unique();
            $table->date('travel_date');
            $table->integer('participants_count');
            $table->json('participants_details');
            $table->decimal('base_price', 10, 2);
            $table->decimal('margin_amount', 10, 2)->default(0);
            $table->decimal('final_price', 10, 2);
            $table->string('voucher_pdf_path')->nullable();
            $table->string('itinerary_pdf_path')->nullable();
            $table->enum('status', ['confirmed', 'pending', 'cancelled', 'completed'])->default('pending');
            $table->text('special_requests')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
            
            $table->index('confirmation_number');
            $table->index('status');
            $table->index('travel_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_bookings');
    }
};
PHP;

// ============================================
// 7. PACKAGE INVENTORY
// ============================================
/*
Fichier: database/migrations/2025_10_07_190357_create_package_inventory_table.php
*/
$package_inventory_migration = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('tour_packages')->onDelete('cascade');
            $table->date('available_date');
            $table->integer('max_participants');
            $table->integer('booked_participants')->default(0);
            $table->integer('available_spots');
            $table->decimal('price_override', 10, 2)->nullable();
            $table->boolean('is_available')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['package_id', 'available_date']);
            $table->index('available_date');
            $table->index('is_available');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_inventory');
    }
};
PHP;

// ============================================
// 8. PAYMENT GATEWAYS
// ============================================
/*
Fichier: database/migrations/2025_10_07_190358_create_payment_gateways_table.php
*/
$payment_gateways_migration = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('gateway_name', 50);
            $table->string('gateway_type', 50); // 'card', 'mobile_money', 'bank_transfer'
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->string('merchant_id', 100)->nullable();
            $table->string('webhook_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('supported_currencies')->nullable();
            $table->decimal('transaction_fee_percentage', 5, 2)->default(0);
            $table->decimal('transaction_fee_fixed', 10, 2)->default(0);
            $table->json('configuration')->nullable();
            $table->timestamps();
            
            $table->index('gateway_name');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
PHP;

// ============================================
// INSTRUCTIONS D'UTILISATION
// ============================================
echo "
╔══════════════════════════════════════════════════════════════════╗
║  MIGRATIONS AVANCÉES - CARRÉ PREMIUM                             ║
╚══════════════════════════════════════════════════════════════════╝

📋 INSTRUCTIONS:

1. Copiez le contenu de chaque migration ci-dessus dans le fichier correspondant
2. Les fichiers sont dans: database/migrations/
3. Exécutez: php artisan migrate

📁 FICHIERS À MODIFIER:

✅ 2025_10_07_190356_create_api_configurations_table.php
✅ 2025_10_07_190356_create_flight_bookings_table.php
✅ 2025_10_07_190357_create_pricing_rules_table.php
✅ 2025_10_07_190357_create_event_tickets_table.php
✅ 2025_10_07_190357_create_event_inventory_table.php
✅ 2025_10_07_190357_create_package_bookings_table.php
✅ 2025_10_07_190357_create_package_inventory_table.php
✅ 2025_10_07_190358_create_payment_gateways_table.php

🎯 PROCHAINES ÉTAPES:

1. Implémenter les migrations
2. Créer les modèles correspondants
3. Créer les services (AmadeusService, PricingService, etc.)
4. Créer les contrôleurs admin pour gérer ces entités
5. Créer les vues admin

";
?>
