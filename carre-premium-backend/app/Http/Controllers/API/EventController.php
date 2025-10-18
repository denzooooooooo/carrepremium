<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventSeatZone;
use App\Models\Category;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Get all events with filters
     */
    public function index(Request $request)
    {
        $query = Event::with(['category', 'seatZones'])
            ->where('is_active', true)
            ->where('event_date', '>=', now());

        // Filtres
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->has('sport_type')) {
            $query->where('sport_type', $request->sport_type);
        }

        if ($request->has('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->has('date_from')) {
            $query->whereDate('event_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('event_date', '<=', $request->date_to);
        }

        if ($request->has('max_price')) {
            $query->where('min_price', '<=', $request->max_price);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'event_date');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 12);
        $events = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Get event by ID
     */
    public function show($id)
    {
        $event = Event::with(['category', 'seatZones' => function($query) {
            $query->where('is_active', true);
        }])->find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $event
        ]);
    }

    /**
     * Get upcoming events
     */
    public function upcoming(Request $request)
    {
        $limit = $request->get('limit', 6);
        
        $events = Event::with(['category'])
            ->where('is_active', true)
            ->where('event_date', '>=', now())
            ->where('is_featured', true)
            ->orderBy('event_date', 'asc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Get events by category
     */
    public function byCategory($categoryId)
    {
        $events = Event::with(['category'])
            ->where('category_id', $categoryId)
            ->where('is_active', true)
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Get event categories
     */
    public function categories()
    {
        $categories = Category::where('parent_id', '!=', null)
            ->where('is_active', true)
            ->orderBy('order_position', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Search events
     */
    public function search(Request $request)
    {
        $query = Event::with(['category'])
            ->where('is_active', true)
            ->where('event_date', '>=', now());

        if ($request->has('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('title_fr', 'like', '%' . $search . '%')
                  ->orWhere('title_en', 'like', '%' . $search . '%')
                  ->orWhere('venue_name', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        $events = $query->orderBy('event_date', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $events,
            'count' => $events->count()
        ]);
    }

    /**
     * Check ticket availability
     */
    public function checkAvailability($id, Request $request)
    {
        $event = Event::find($id);
        
        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        $quantity = $request->get('quantity', 1);
        $zoneId = $request->get('zone_id');

        if ($zoneId) {
            $zone = EventSeatZone::find($zoneId);
            $available = $zone && $zone->available_seats >= $quantity;
            $seatsAvailable = $zone ? $zone->available_seats : 0;
        } else {
            $available = $event->available_seats >= $quantity;
            $seatsAvailable = $event->available_seats;
        }

        return response()->json([
            'success' => true,
            'available' => $available,
            'seats_available' => $seatsAvailable,
            'seats_requested' => $quantity
        ]);
    }
}
