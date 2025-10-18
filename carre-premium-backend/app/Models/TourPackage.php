<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasImageUrl;

class TourPackage extends Model
{
    use HasFactory, HasImageUrl;

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

    public function packageBookings()
    {
        return $this->hasMany(PackageBooking::class, 'package_id');
    }

    public function inventory()
    {
        return $this->hasOne(PackageInventory::class, 'package_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'item_id')->where('item_type', 'package');
    }
}
