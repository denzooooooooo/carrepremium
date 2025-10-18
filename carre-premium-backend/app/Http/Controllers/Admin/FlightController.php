<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Airline;
use App\Models\Airport;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    /**
     * Display a listing of flights
     */
    public function index(Request $request)
    {
        $query = Flight::with(['airline', 'departureAirport', 'arrivalAirport']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('flight_number', 'like', "%{$search}%")
                  ->orWhereHas('airline', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by airline
        if ($request->filled('airline')) {
            $query->where('airline_id', $request->airline);
        }

        // Filter by departure airport
        if ($request->filled('departure')) {
            $query->where('departure_airport_id', $request->departure);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $flights = $query->orderBy('departure_date', 'desc')->paginate(15);

        // Get airlines and airports for filters
        $airlines = Airline::where('is_active', true)->orderBy('name')->get();
        $airports = Airport::where('is_active', true)->orderBy('city')->get();

        // Calculate statistics
        $stats = [
            'total' => Flight::count(),
            'active' => Flight::where('is_active', true)->count(),
            'today' => Flight::whereDate('departure_date', today())->count(),
            'airlines' => Airline::count(),
        ];

        return view('admin.flights.index', compact('flights', 'stats', 'airlines', 'airports'));
    }

    /**
     * Show the form for creating a new flight
     */
    public function create()
    {
        $airlines = Airline::where('is_active', true)->orderBy('name')->get();
        $airports = Airport::where('is_active', true)->orderBy('city')->get();
        return view('admin.flights.create', compact('airlines', 'airports'));
    }

    /**
     * Store a newly created flight
     */
    public function store(Request $request, ImageUploadService $imageService)
    {
        $validated = $request->validate([
            'flight_number' => 'required|string|max:20',
            'airline_id' => 'required|exists:airlines,id',
            'departure_airport_id' => 'required|exists:airports,id',
            'arrival_airport_id' => 'required|exists:airports,id|different:departure_airport_id',
            'departure_date' => 'required|date',
            'departure_time' => 'required',
            'arrival_date' => 'required|date|after_or_equal:departure_date',
            'arrival_time' => 'required',
            'duration' => 'required|integer|min:1',
            'aircraft_type' => 'nullable|string|max:100',
            'economy_seats' => 'required|integer|min:0',
            'economy_price' => 'required|numeric|min:0',
            'business_seats' => 'nullable|integer|min:0',
            'business_price' => 'nullable|numeric|min:0',
            'first_class_seats' => 'nullable|integer|min:0',
            'first_class_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Upload image if provided
        if ($request->hasFile('image')) {
            $validated['image'] = $imageService->uploadImage($request->file('image'), 'flights');
        }

        // Set available seats equal to total seats initially
        $validated['available_economy'] = $validated['economy_seats'];
        $validated['available_business'] = $validated['business_seats'] ?? 0;
        $validated['available_first_class'] = $validated['first_class_seats'] ?? 0;
        $validated['status'] = 'scheduled';
        $validated['is_active'] = true;

        Flight::create($validated);

        return redirect()->route('admin.flights.index')
            ->with('success', 'Vol créé avec succès');
    }

    /**
     * Display the specified flight
     */
    public function show(string $id)
    {
        $flight = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'bookings'])->findOrFail($id);
        return view('admin.flights.show', compact('flight'));
    }

    /**
     * Show the form for editing the specified flight
     */
    public function edit(string $id)
    {
        $flight = Flight::findOrFail($id);
        $airlines = Airline::where('is_active', true)->orderBy('name')->get();
        $airports = Airport::where('is_active', true)->orderBy('city')->get();

        if (request()->ajax()) {
            return response()->json($flight);
        }

        return view('admin.flights.edit', compact('flight', 'airlines', 'airports'));
    }

    /**
     * Update the specified flight
     */
    public function update(Request $request, string $id, ImageUploadService $imageService)
    {
        $flight = Flight::findOrFail($id);

        $validated = $request->validate([
            'flight_number' => 'required|string|max:20',
            'airline_id' => 'required|exists:airlines,id',
            'departure_airport_id' => 'required|exists:airports,id',
            'arrival_airport_id' => 'required|exists:airports,id|different:departure_airport_id',
            'departure_date' => 'required|date',
            'departure_time' => 'required',
            'arrival_date' => 'required|date|after_or_equal:departure_date',
            'arrival_time' => 'required',
            'duration' => 'required|integer|min:1',
            'aircraft_type' => 'nullable|string|max:100',
            'economy_seats' => 'required|integer|min:0',
            'economy_price' => 'required|numeric|min:0',
            'business_seats' => 'nullable|integer|min:0',
            'business_price' => 'nullable|numeric|min:0',
            'first_class_seats' => 'nullable|integer|min:0',
            'first_class_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Upload new image if provided
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($flight->image) {
                $imageService->deleteImage($flight->image);
            }
            $validated['image'] = $imageService->uploadImage($request->file('image'), 'flights');
        }

        $flight->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Vol mis à jour avec succès'
            ]);
        }

        return redirect()->route('admin.flights.index')
            ->with('success', 'Vol mis à jour avec succès');
    }

    /**
     * Remove the specified flight
     */
    public function destroy(string $id, ImageUploadService $imageService)
    {
        $flight = Flight::findOrFail($id);
        
        // Check if flight has bookings
        if ($flight->bookings()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer ce vol car il a des réservations associées');
        }

        // Delete image if exists
        if ($flight->image) {
            $imageService->deleteImage($flight->image);
        }

        $flight->delete();

        return redirect()->route('admin.flights.index')
            ->with('success', 'Vol supprimé avec succès');
    }

    /**
     * Toggle flight active status
     */
    public function toggleStatus(string $id)
    {
        $flight = Flight::findOrFail($id);
        $flight->is_active = !$flight->is_active;
        $flight->save();

        return response()->json([
            'success' => true,
            'is_active' => $flight->is_active,
            'message' => $flight->is_active ? 'Vol activé' : 'Vol désactivé'
        ]);
    }
}
