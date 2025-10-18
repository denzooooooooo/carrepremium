# 🚀 SCRIPT COMPLET POUR REMPLIR TOUTES LES MIGRATIONS

Exécutez ces commandes une par une dans le terminal :

```bash
cd carre-premium-backend

# Migration 4: Event Tickets
cat > database/migrations/2025_10_07_190357_create_event_tickets_table.php << 'EOF'
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
            $table->string('qr_code');
            $table->text('qr_data');
            $table->foreignId('seat_zone_id')->nullable()->constrained('event_seat_zones');
            $table->string('seat_number', 20)->nullable();
            $table->decimal('base_price', 10, 2);
            $table->decimal('margin_amount', 10, 2)->default(0);
            $table->decimal('final_price', 10, 2);
            $table->enum('ticket_status', ['valid', 'used', 'cancelled', 'expired'])->default('valid');
            $table->timestamp('used_at')->nullable();
            $table->string('used_by')->nullable();
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
EOF

# Migration 5: Event Inventory
cat > database/migrations/2025_10_07_190357_create_event_inventory_table.php << 'EOF'
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
EOF

# Migration 6: Package Bookings
cat > database/migrations/2025_10_07_190357_create_package_bookings_table.php << 'EOF'
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
EOF

# Migration 7: Package Inventory
cat > database/migrations/2025_10_07_190357_create_package_inventory_table.php << 'EOF'
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
EOF

# Migration 8: Payment Gateways
cat > database/migrations/2025_10_07_190358_create_payment_gateways_table.php << 'EOF'
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
            $table->string('gateway_type', 50);
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
EOF

echo "✅ Toutes les migrations ont été remplies avec succès!"
echo "Exécutez maintenant: php artisan migrate"
```

## OU UTILISEZ CE SCRIPT PHP UNIQUE:

```bash
cd carre-premium-backend
php -r "
\$migrations = [
    'event_tickets' => \"<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_tickets', function (Blueprint \\\$table) {
            \\\$table->id();
            \\\$table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            \\\$table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            \\\$table->string('ticket_number', 20)->unique();
            \\\$table->string('qr_code');
            \\\$table->text('qr_data');
            \\\$table->foreignId('seat_zone_id')->nullable()->constrained('event_seat_zones');
            \\\$table->string('seat_number', 20)->nullable();
            \\\$table->decimal('base_price', 10, 2);
            \\\$table->decimal('margin_amount', 10, 2)->default(0);
            \\\$table->decimal('final_price', 10, 2);
            \\\$table->enum('ticket_status', ['valid', 'used', 'cancelled', 'expired'])->default('valid');
            \\\$table->timestamp('used_at')->nullable();
            \\\$table->string('used_by')->nullable();
            \\\$table->string('ticket_pdf_path')->nullable();
            \\\$table->timestamps();
            
            \\\$table->index('ticket_number');
            \\\$table->index('ticket_status');
            \\\$table->index(['event_id', 'ticket_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_tickets');
    }
};
\"
];

foreach (\$migrations as \$name => \$content) {
    file_put_contents('database/migrations/2025_10_07_190357_create_'.\$name.'_table.php', \$content);
    echo \"✅ Migration \$name créée\\n\";
}
"
