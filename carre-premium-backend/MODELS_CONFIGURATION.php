<?php
/**
 * Configuration complète de tous les modèles Eloquent
 * Ce fichier contient le code pour tous les modèles restants
 */

// ============================================
// Event Model
// ============================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'title_fr', 'title_en', 'slug', 'description_fr', 'description_en',
        'event_type', 'sport_type', 'venue_name', 'venue_address', 'city', 'country',
        'event_date', 'event_time', 'end_date', 'end_time', 'image', 'gallery', 'video_url',
        'organizer', 'min_price', 'max_price', 'total_seats', 'available_seats',
        'is_featured', 'is_active', 'meta_title_fr', 'meta_title_en', 'meta_description_fr', 'meta_description_en',
    ];

    protected $casts = [
        'event_date' => 'date',
        'end_date' => 'date',
        'gallery' => 'array',
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function seatZones()
    {
        return $this->hasMany(EventSeatZone::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'item_id')->where('item_type', 'event');
    }
}

// ============================================
// TourPackage Model
// ============================================
class TourPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'title_fr', 'title_en', 'slug', 'description_fr', 'description_en',
        'package_type', 'destination', 'duration', 'duration_text_fr', 'duration_text_en',
        'departure_city', 'price', 'discount_price', 'max_participants', 'min_participants',
        'included_services_fr', 'included_services_en', 'excluded_services_fr', 'excluded_services_en',
        'itinerary_fr', 'itinerary_en', 'image', 'gallery', 'video_url', 'available_dates',
        'is_featured', 'is_active', 'rating', 'total_reviews',
        'meta_title_fr', 'meta_title_en', 'meta_description_fr', 'meta_description_en',
    ];

    protected $casts = [
        'included_services_fr' => 'array',
        'included_services_en' => 'array',
        'excluded_services_fr' => 'array',
        'excluded_services_en' => 'array',
        'itinerary_fr' => 'array',
        'itinerary_en' => 'array',
        'gallery' => 'array',
        'available_dates' => 'array',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'package_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'item_id')->where('item_type', 'package');
    }
}

// ============================================
// Payment Model
// ============================================
class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 'user_id', 'transaction_id', 'payment_method', 'payment_provider',
        'amount', 'currency', 'exchange_rate', 'amount_in_base_currency', 'status',
        'payment_date', 'refund_date', 'refund_amount', 'payment_details', 'failure_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'exchange_rate' => 'decimal:6',
        'amount_in_base_currency' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'payment_details' => 'array',
        'payment_date' => 'datetime',
        'refund_date' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

// ============================================
// Review Model
// ============================================
class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'booking_id', 'item_type', 'item_id', 'rating', 'title',
        'comment', 'pros', 'cons', 'is_verified', 'is_approved', 'admin_response', 'helpful_count',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

// ============================================
// Category Model
// ============================================
class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_fr', 'name_en', 'slug', 'description_fr', 'description_en',
        'icon', 'image', 'parent_id', 'order_position', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function packages()
    {
        return $this->hasMany(TourPackage::class);
    }
}

// ============================================
// Carousel Model
// ============================================
class Carousel extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_fr', 'title_en', 'subtitle_fr', 'subtitle_en', 'image', 'mobile_image',
        'video_url', 'link_url', 'button_text_fr', 'button_text_en', 'order_position',
        'is_active', 'start_date', 'end_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}

// ============================================
// Setting Model
// ============================================
class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_key', 'setting_value', 'setting_type', 'group_name', 'description',
    ];

    public $timestamps = false;

    protected $casts = [
        'updated_at' => 'datetime',
    ];
}

// ============================================
// Airline Model
// ============================================
class Airline extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'logo', 'country', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function flights()
    {
        return $this->hasMany(Flight::class);
    }
}

// ============================================
// Airport Model
// ============================================
class Airport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'city', 'country', 'iata_code', 'icao_code',
        'latitude', 'longitude', 'timezone', 'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    public function departureFlights()
    {
        return $this->hasMany(Flight::class, 'departure_airport_id');
    }

    public function arrivalFlights()
    {
        return $this->hasMany(Flight::class, 'arrival_airport_id');
    }
}

// ============================================
// EventSeatZone Model
// ============================================
class EventSeatZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'zone_name_fr', 'zone_name_en', 'zone_code', 'price',
        'total_seats', 'available_seats', 'description_fr', 'description_en', 'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'seat_zone_id');
    }
}

// ============================================
// Currency Model
// ============================================
class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'symbol', 'exchange_rate', 'is_active', 'is_default',
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:6',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public $timestamps = false;

    protected $casts = [
        'updated_at' => 'datetime',
    ];
}

// ============================================
// ActivityLog Model
// ============================================
class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id', 'action', 'model_type', 'model_id', 'description',
        'ip_address', 'user_agent', 'changes',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}

// Autres modèles simples...
class Cart extends Model { use HasFactory; }
class Favorite extends Model { use HasFactory; }
class ChatMessage extends Model { use HasFactory; }
class ChatbotConversation extends Model { use HasFactory; }
class Notification extends Model { use HasFactory; }
class NewsletterSubscriber extends Model { use HasFactory; }
class Page extends Model { use HasFactory; }
class UserPreference extends Model { use HasFactory; }
class PromoCode extends Model { use HasFactory; }
class PromoCodeUsage extends Model { use HasFactory; }
