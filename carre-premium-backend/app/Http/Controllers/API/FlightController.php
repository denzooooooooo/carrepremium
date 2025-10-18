<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Airline;
use App\Models\Airport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlightController extends Controller
{
    /**
     * Get all flights with filters
     */
    public function index(Request $request)
    {
        $query = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
            ->where('is_active', true)
            ->where('status', 'scheduled');

        // Filtres
        if ($request->has('from')) {
            $query->whereHas('departureAirport', function($q) use ($request) {
                $q->where('city', 'like', '%' . $request->from . '%')
                  ->orWhere('iata_code', $request->from);
            });
        }

        if ($request->has('to')) {
            $query->whereHas('arrivalAirport', function($q) use ($request) {
                $q->where('city', 'like', '%' . $request->to . '%')
                  ->orWhere('iata_code', $request->to);
            });
        }

        if ($request->has('date')) {
            $query->whereDate('departure_date', $request->date);
        }

        if ($request->has('class')) {
            $class = $request->class;
            $query->where("available_{$class}", '>', 0);
        }

        if ($request->has('max_price')) {
            $query->where('economy_price', '<=', $request->max_price);
        }

        if ($request->has('airline')) {
            $query->where('airline_id', $request->airline);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'departure_date');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 12);
        $flights = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $flights,
            'message' => 'Flights retrieved successfully'
        ]);
    }

    /**
     * Get flight by ID
     */
    public function show($id)
    {
        $flight = Flight::with([
            'airline', 
            'departureAirport', 
            'arrivalAirport'
        ])->find($id);

        if (!$flight) {
            return response()->json([
                'success' => false,
                'message' => 'Flight not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $flight,
            'message' => 'Flight retrieved successfully'
        ]);
    }

    /**
     * Search flights with advanced filters
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from' => 'required|string',
            'to' => 'required|string',
            'departure_date' => 'required|date',
            'return_date' => 'nullable|date|after:departure_date',
            'passengers' => 'nullable|integer|min:1|max:9',
            'class' => 'nullable|in:economy,business,first'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $query = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
            ->where('is_active', true)
            ->where('status', 'scheduled');

        // Recherche par ville ou code aéroport
        $query->whereHas('departureAirport', function($q) use ($request) {
            $q->where('city', 'like', '%' . $request->from . '%')
              ->orWhere('iata_code', strtoupper($request->from));
        });

        $query->whereHas('arrivalAirport', function($q) use ($request) {
            $q->where('city', 'like', '%' . $request->to . '%')
              ->orWhere('iata_code', strtoupper($request->to));
        });

        $query->whereDate('departure_date', $request->departure_date);

        // Vérifier disponibilité selon classe et nombre de passagers
        $class = $request->get('class', 'economy');
        $passengers = $request->get('passengers', 1);
        $query->where("available_{$class}", '>=', $passengers);

        $flights = $query->orderBy('departure_time', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $flights,
            'count' => $flights->count(),
            'message' => 'Search completed successfully'
        ]);
    }

    /**
     * Get popular flights
     */
    public function popular(Request $request)
    {
        $limit = $request->get('limit', 6);
        
        $flights = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
            ->where('is_active', true)
            ->where('status', 'scheduled')
            ->where('departure_date', '>=', now())
            ->orderBy('departure_date', 'asc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $flights
        ]);
    }

    /**
     * Get available airlines
     */
    public function airlines()
    {
        $airlines = Airline::where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $airlines
        ]);
    }

    /**
     * Get available airports
     */
    public function airports(Request $request)
    {
        $query = Airport::where('is_active', true);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('city', 'like', '%' . $search . '%')
                  ->orWhere('name', 'like', '%' . $search . '%')
                  ->orWhere('iata_code', 'like', '%' . $search . '%');
            });
        }

        $airports = $query->orderBy('city', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $airports
        ]);
    }

    /**
     * Check seat availability
     */
    public function checkAvailability(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'class' => 'required|in:economy,business,first',
            'passengers' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $flight = Flight::find($id);
        
        if (!$flight) {
            return response()->json([
                'success' => false,
                'message' => 'Flight not found'
            ], 404);
        }

        $class = $request->class;
        $passengers = $request->passengers;
        $available = $flight->{"available_{$class}"};

        return response()->json([
            'success' => true,
            'available' => $available >= $passengers,
            'seats_available' => $available,
            'seats_requested' => $passengers
        ]);
    }
}
