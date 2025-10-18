<?php
/**
 * CONTENU DES MIGRATIONS POUR CARRÉ PREMIUM
 * Ce fichier contient le code à copier dans chaque migration
 */

// ============================================
// CATEGORIES TABLE
// File: create_categories_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('name_fr');
        $table->string('name_en');
        $table->string('slug')->unique();
        $table->text('description_fr')->nullable();
        $table->text('description_en')->nullable();
        $table->string('icon')->nullable();
        $table->string('image')->nullable();
        $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('set null');
        $table->integer('order_position')->default(0);
        $table->boolean('is_active')->default(true);
        $table->timestamps();
        
        $table->index('slug');
        $table->index('parent_id');
    });
}
*/

// ============================================
// AIRLINES TABLE
// File: create_airlines_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('airlines', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('code', 10)->unique();
        $table->string('logo')->nullable();
        $table->string('country', 100)->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
        
        $table->index('code');
    });
}
*/

// ============================================
// AIRPORTS TABLE
// File: create_airports_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('airports', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('city', 100);
        $table->string('country', 100);
        $table->string('iata_code', 3)->unique();
        $table->string('icao_code', 4)->nullable();
        $table->decimal('latitude', 10, 8)->nullable();
        $table->decimal('longitude', 11, 8)->nullable();
        $table->string('timezone', 50)->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
        
        $table->index('iata_code');
        $table->index('city');
        $table->index('country');
    });
}
*/

// ============================================
// FLIGHTS TABLE
// File: create_flights_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('flights', function (Blueprint $table) {
        $table->id();
        $table->foreignId('airline_id')->constrained()->onDelete('cascade');
        $table->string('flight_number', 20);
        $table->foreignId('departure_airport_id')->constrained('airports')->onDelete('cascade');
        $table->foreignId('arrival_airport_id')->constrained('airports')->onDelete('cascade');
        $table->date('departure_date');
        $table->time('departure_time');
        $table->date('arrival_date');
        $table->time('arrival_time');
        $table->integer('duration')->comment('Duration in minutes');
        $table->string('aircraft_type', 100)->nullable();
        $table->integer('economy_seats')->default(0);
        $table->integer('business_seats')->default(0);
        $table->integer('first_class_seats')->default(0);
        $table->decimal('economy_price', 10, 2)->nullable();
        $table->decimal('business_price', 10, 2)->nullable();
        $table->decimal('first_class_price', 10, 2)->nullable();
        $table->integer('available_economy')->default(0);
        $table->integer('available_business')->default(0);
        $table->integer('available_first_class')->default(0);
        $table->enum('status', ['scheduled', 'delayed', 'cancelled', 'completed'])->default('scheduled');
        $table->boolean('is_active')->default(true);
        $table->timestamps();
        
        $table->index('flight_number');
        $table->index('departure_date');
        $table->index('status');
    });
}
*/

// ============================================
// EVENTS TABLE
// File: create_events_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('events', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->string('title_fr');
        $table->string('title_en');
        $table->string('slug')->unique();
        $table->text('description_fr')->nullable();
        $table->text('description_en')->nullable();
        $table->enum('event_type', ['sport', 'concert', 'theater', 'festival', 'other']);
        $table->string('sport_type', 100)->nullable()->comment('tennis, football, formula1, etc.');
        $table->string('venue_name');
        $table->text('venue_address')->nullable();
        $table->string('city', 100);
        $table->string('country', 100);
        $table->date('event_date');
        $table->time('event_time');
        $table->date('end_date')->nullable();
        $table->time('end_time')->nullable();
        $table->string('image')->nullable();
        $table->json('gallery')->nullable()->comment('Array of images');
        $table->string('video_url')->nullable();
        $table->string('organizer')->nullable();
        $table->decimal('min_price', 10, 2)->nullable();
        $table->decimal('max_price', 10, 2)->nullable();
        $table->integer('total_seats')->default(0);
        $table->integer('available_seats')->default(0);
        $table->boolean('is_featured')->default(false);
        $table->boolean('is_active')->default(true);
        $table->string('meta_title_fr')->nullable();
        $table->string('meta_title_en')->nullable();
        $table->text('meta_description_fr')->nullable();
        $table->text('meta_description_en')->nullable();
        $table->timestamps();
        
        $table->index('slug');
        $table->index('event_date');
        $table->index('event_type');
        $table->index('city');
    });
}
*/

// ============================================
// EVENT_SEAT_ZONES TABLE
// File: create_event_seat_zones_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('event_seat_zones', function (Blueprint $table) {
        $table->id();
        $table->foreignId('event_id')->constrained()->onDelete('cascade');
        $table->string('zone_name_fr', 100);
        $table->string('zone_name_en', 100);
        $table->string('zone_code', 20);
        $table->decimal('price', 10, 2);
        $table->integer('total_seats');
        $table->integer('available_seats');
        $table->text('description_fr')->nullable();
        $table->text('description_en')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
        
        $table->index(['event_id', 'zone_code']);
    });
}
*/

// ============================================
// TOUR_PACKAGES TABLE
// File: create_tour_packages_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('tour_packages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->string('title_fr');
        $table->string('title_en');
        $table->string('slug')->unique();
        $table->text('description_fr')->nullable();
        $table->text('description_en')->nullable();
        $table->enum('package_type', ['helicopter', 'private_jet', 'cruise', 'safari', 'city_tour', 'adventure', 'luxury']);
        $table->string('destination');
        $table->integer('duration')->comment('Duration in days');
        $table->string('duration_text_fr', 100)->nullable();
        $table->string('duration_text_en', 100)->nullable();
        $table->string('departure_city', 100)->nullable();
        $table->decimal('price', 10, 2);
        $table->decimal('discount_price', 10, 2)->nullable();
        $table->integer('max_participants')->default(1);
        $table->integer('min_participants')->default(1);
        $table->json('included_services_fr')->nullable()->comment('Array of included services');
        $table->json('included_services_en')->nullable();
        $table->json('excluded_services_fr')->nullable();
        $table->json('excluded_services_en')->nullable();
        $table->json('itinerary_fr')->nullable()->comment('Day by day itinerary');
        $table->json('itinerary_en')->nullable();
        $table->string('image')->nullable();
        $table->json('gallery')->nullable();
        $table->string('video_url')->nullable();
        $table->json('available_dates')->nullable()->comment('Array of available dates');
        $table->boolean('is_featured')->default(false);
        $table->boolean('is_active')->default(true);
        $table->decimal('rating', 3, 2)->default(0.00);
        $table->integer('total_reviews')->default(0);
        $table->string('meta_title_fr')->nullable();
        $table->string('meta_title_en')->nullable();
        $table->text('meta_description_fr')->nullable();
        $table->text('meta_description_en')->nullable();
        $table->timestamps();
        
        $table->index('slug');
        $table->index('package_type');
        $table->index('destination');
    });
}
*/

// ============================================
// BOOKINGS TABLE
// File: create_bookings_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->string('booking_number', 50)->unique();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->enum('booking_type', ['flight', 'event', 'package']);
        $table->foreignId('flight_id')->nullable()->constrained()->onDelete('set null');
        $table->foreignId('event_id')->nullable()->constrained()->onDelete('set null');
        $table->foreignId('package_id')->nullable()->constrained('tour_packages')->onDelete('set null');
        $table->foreignId('seat_zone_id')->nullable()->constrained('event_seat_zones')->onDelete('set null');
        $table->timestamp('booking_date')->useCurrent();
        $table->date('travel_date')->nullable();
        $table->integer('number_of_passengers')->default(1);
        $table->json('passenger_details')->nullable()->comment('Array of passenger information');
        $table->string('seat_class', 50)->nullable()->comment('economy, business, first_class');
        $table->string('seat_numbers')->nullable();
        $table->decimal('total_amount', 10, 2);
        $table->string('currency', 3)->default('XOF');
        $table->decimal('discount_amount', 10, 2)->default(0.00);
        $table->decimal('tax_amount', 10, 2)->default(0.00);
        $table->decimal('final_amount', 10, 2);
        $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed', 'refunded'])->default('pending');
        $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
        $table->text('special_requests')->nullable();
        $table->text('cancellation_reason')->nullable();
        $table->timestamp('cancelled_at')->nullable();
        $table->timestamp('confirmed_at')->nullable();
        $table->timestamps();
        
        $table->index('booking_number');
        $table->index('user_id');
        $table->index('status');
        $table->index('booking_date');
    });
}
*/

// ============================================
// PAYMENTS TABLE
// File: create_payments_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('booking_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('transaction_id')->unique();
        $table->enum('payment_method', ['credit_card', 'mobile_money', 'bank_transfer', 'paypal', 'stripe']);
        $table->string('payment_provider', 100)->nullable();
        $table->decimal('amount', 10, 2);
        $table->string('currency', 3)->default('XOF');
        $table->decimal('exchange_rate', 10, 6)->default(1.000000);
        $table->decimal('amount_in_base_currency', 10, 2)->nullable();
        $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'refunded'])->default('pending');
        $table->timestamp('payment_date')->nullable();
        $table->timestamp('refund_date')->nullable();
        $table->decimal('refund_amount', 10, 2)->nullable();
        $table->json('payment_details')->nullable()->comment('Additional payment information');
        $table->text('failure_reason')->nullable();
        $table->timestamps();
        
        $table->index('transaction_id');
        $table->index('status');
        $table->index('payment_date');
    });
}
*/

// ============================================
// CART TABLE
// File: create_cart_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('cart', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
        $table->string('session_id');
        $table->enum('item_type', ['flight', 'event', 'package']);
        $table->unsignedBigInteger('item_id');
        $table->integer('quantity')->default(1);
        $table->string('seat_class', 50)->nullable();
        $table->foreignId('seat_zone_id')->nullable()->constrained('event_seat_zones')->onDelete('set null');
        $table->date('travel_date')->nullable();
        $table->integer('passenger_count')->default(1);
        $table->decimal('price', 10, 2);
        $table->json('options')->nullable()->comment('Additional options selected');
        $table->timestamps();
        
        $table->index('user_id');
        $table->index('session_id');
    });
}
*/

// ============================================
// FAVORITES TABLE
// File: create_favorites_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('favorites', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->enum('item_type', ['flight', 'event', 'package']);
        $table->unsignedBigInteger('item_id');
        $table->timestamps();
        
        $table->unique(['user_id', 'item_type', 'item_id']);
        $table->index('user_id');
    });
}
*/

// ============================================
// REVIEWS TABLE
// File: create_reviews_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('reviews', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('booking_id')->constrained()->onDelete('cascade');
        $table->enum('item_type', ['flight', 'event', 'package']);
        $table->unsignedBigInteger('item_id');
        $table->integer('rating')->unsigned();
        $table->string('title')->nullable();
        $table->text('comment')->nullable();
        $table->text('pros')->nullable();
        $table->text('cons')->nullable();
        $table->boolean('is_verified')->default(false);
        $table->boolean('is_approved')->default(false);
        $table->text('admin_response')->nullable();
        $table->integer('helpful_count')->default(0);
        $table->timestamps();
        
        $table->index(['item_type', 'item_id']);
        $table->index('rating');
        $table->index('is_approved');
    });
}
*/

// ============================================
// CURRENCIES TABLE
// File: create_currencies_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('currencies', function (Blueprint $table) {
        $table->id();
        $table->string('code', 3)->unique();
        $table->string('name', 100);
        $table->string('symbol', 10);
        $table->decimal('exchange_rate', 10, 6)->default(1.000000);
        $table->boolean('is_active')->default(true);
        $table->boolean('is_default')->default(false);
        $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        
        $table->index('code');
    });
}
*/

// ============================================
// CHAT_MESSAGES TABLE
// File: create_chat_messages_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('chat_messages', function (Blueprint $table) {
        $table->id();
        $table->string('conversation_id', 100);
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
        $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null');
        $table->text('message');
        $table->enum('message_type', ['text', 'image', 'file', 'system'])->default('text');
        $table->string('attachment_url')->nullable();
        $table->boolean('is_read')->default(false);
        $table->timestamp('read_at')->nullable();
        $table->timestamps();
        
        $table->index('conversation_id');
        $table->index('created_at');
    });
}
*/

// ============================================
// CHATBOT_CONVERSATIONS TABLE
// File: create_chatbot_conversations_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('chatbot_conversations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
        $table->string('session_id');
        $table->text('user_message');
        $table->text('bot_response');
        $table->string('intent', 100)->nullable();
        $table->decimal('confidence', 5, 4)->nullable();
        $table->json('context')->nullable();
        $table->timestamps();
        
        $table->index('session_id');
        $table->index('user_id');
    });
}
*/

// ============================================
// NOTIFICATIONS TABLE
// File: create_notifications_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('notifications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('type', 100);
        $table->string('title_fr');
        $table->string('title_en');
        $table->text('message_fr');
        $table->text('message_en');
        $table->json('data')->nullable();
        $table->boolean('is_read')->default(false);
        $table->timestamp('read_at')->nullable();
        $table->timestamps();
        
        $table->index('user_id');
        $table->index('is_read');
    });
}
*/

// ============================================
// NEWSLETTER_SUBSCRIBERS TABLE
// File: create_newsletter_subscribers_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('newsletter_subscribers', function (Blueprint $table) {
        $table->id();
        $table->string('email')->unique();
        $table->string('name')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamp('subscribed_at')->useCurrent();
        $table->timestamp('unsubscribed_at')->nullable();
        
        $table->index('email');
    });
}
*/

// ============================================
// PAGES TABLE
// File: create_pages_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('pages', function (Blueprint $table) {
        $table->id();
        $table->string('title_fr');
        $table->string('title_en');
        $table->string('slug')->unique();
        $table->longText('content_fr')->nullable();
        $table->longText('content_en')->nullable();
        $table->enum('page_type', ['about', 'terms', 'privacy', 'faq', 'contact', 'custom']);
        $table->string('meta_title_fr')->nullable();
        $table->string('meta_title_en')->nullable();
        $table->text('meta_description_fr')->nullable();
        $table->text('meta_description_en')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
        
        $table->index('slug');
        $table->index('page_type');
    });
}
*/

// ============================================
// CAROUSELS TABLE
// File: create_carousels_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('carousels', function (Blueprint $table) {
        $table->id();
        $table->string('title_fr');
        $table->string('title_en');
        $table->text('subtitle_fr')->nullable();
        $table->text('subtitle_en')->nullable();
        $table->string('image');
        $table->string('mobile_image')->nullable();
        $table->string('video_url')->nullable();
        $table->string('link_url')->nullable();
        $table->string('button_text_fr', 100)->nullable();
        $table->string('button_text_en', 100)->nullable();
        $table->integer('order_position')->default(0);
        $table->boolean('is_active')->default(true);
        $table->timestamp('start_date')->nullable();
        $table->timestamp('end_date')->nullable();
        $table->timestamps();
        
        $table->index('order_position');
        $table->index('is_active');
    });
}
*/

// ============================================
// SETTINGS TABLE
// File: create_settings_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('settings', function (Blueprint $table) {
        $table->id();
        $table->string('setting_key', 100)->unique();
        $table->text('setting_value')->nullable();
        $table->enum('setting_type', ['text', 'number', 'boolean', 'json', 'image'])->default('text');
        $table->string('group_name', 100)->nullable();
        $table->text('description')->nullable();
        $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        
        $table->index('setting_key');
        $table->index('group_name');
    });
}
*/

// ============================================
// ACTIVITY_LOGS TABLE
// File: create_activity_logs_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('activity_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('admin_id')->constrained()->onDelete('cascade');
        $table->string('action', 100);
        $table->string('model_type', 100)->nullable();
        $table->unsignedBigInteger('model_id')->nullable();
        $table->text('description')->nullable();
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->json('changes')->nullable();
        $table->timestamps();
        
        $table->index('admin_id');
        $table->index('action');
        $table->index('created_at');
    });
}
*/

// ============================================
// USER_PREFERENCES TABLE
// File: create_user_preferences_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('user_preferences', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
        $table->json('preferred_destinations')->nullable();
        $table->json('preferred_event_types')->nullable();
        $table->decimal('budget_range_min', 10, 2)->nullable();
        $table->decimal('budget_range_max', 10, 2)->nullable();
        $table->string('travel_frequency', 50)->nullable();
        $table->json('interests')->nullable();
        $table->timestamps();
    });
}
*/

// ============================================
// PROMO_CODES TABLE
// File: create_promo_codes_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('promo_codes', function (Blueprint $table) {
        $table->id();
        $table->string('code', 50)->unique();
        $table->text('description_fr')->nullable();
        $table->text('description_en')->nullable();
        $table->enum('discount_type', ['percentage', 'fixed']);
        $table->decimal('discount_value', 10, 2);
        $table->decimal('min_purchase_amount', 10, 2)->nullable();
        $table->decimal('max_discount_amount', 10, 2)->nullable();
        $table->integer('usage_limit')->nullable();
        $table->integer('used_count')->default(0);
        $table->timestamp('valid_from');
        $table->timestamp('valid_until');
        $table->enum('applicable_to', ['all', 'flights', 'events', 'packages'])->default('all');
        $table->boolean('is_active')->default(true);
        $table->timestamps();
        
        $table->index('code');
        $table->index(['valid_from', 'valid_until']);
    });
}
*/

// ============================================
// PROMO_CODE_USAGE TABLE
// File: create_promo_code_usage_table.php
// ============================================
/*
public function up(): void
{
    Schema::create('promo_code_usage', function (Blueprint $table) {
        $table->id();
        $table->foreignId('promo_code_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('booking_id')->constrained()->onDelete('cascade');
        $table->decimal('discount_amount', 10, 2);
        $table->timestamp('used_at')->useCurrent();
        
        $table->index('promo_code_id');
        $table->index('user_id');
    });
}
*/
