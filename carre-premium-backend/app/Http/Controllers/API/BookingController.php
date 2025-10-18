<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\Event;
use App\Models\TourPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Create a new booking
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_type' => 'required|in:flight,event,package',
            'item_id' => 'required|integer',
            'travel_date' => 'required|date',
            'number_of_passengers' => 'required|integer|min:1',
            'passenger_details' => 'required|array',
            'seat_class' => 'nullable|string',
            'seat_numbers' => 'nullable|string',
            'seat_zone_id' => 'nullable|integer',
            'user_email' => 'required|email',
            'user_phone' => 'required|string',
            'special_requests' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Generate unique booking number
        $bookingNumber = 'CP-' . strtoupper(Str::random(8));

        // Calculate amounts
        $totalAmount = $this->calculateAmount($request);
        $taxAmount = $totalAmount * 0.18; // 18% tax
        $finalAmount = $totalAmount + $taxAmount;

        // Create booking
        $booking = Booking::create([
            'booking_number' => $bookingNumber,
            'user_id' => auth()->id() ?? null,
            'booking_type' => $request->booking_type,
            'flight_id' => $request->booking_type === 'flight' ? $request->item_id : null,
            'event_id' => $request->booking_type === 'event' ? $request->item_id : null,
            'package_id' => $request->booking_type === 'package' ? $request->item_id : null,
            'seat_zone_id' => $request->seat_zone_id,
            'travel_date' => $request->travel_date,
            'number_of_passengers' => $request->number_of_passengers,
            'passenger_details' => json_encode($request->passenger_details),
            'seat_class' => $request->seat_class,
            'seat_numbers' => $request->seat_numbers,
            'total_amount' => $totalAmount,
            'currency' => 'XOF',
            'tax_amount' => $taxAmount,
            'final_amount' => $finalAmount,
            'status' => 'pending',
            'payment_status' => 'pending',
            'special_requests' => $request->special_requests
        ]);

        // TODO: Send confirmation email
        // TODO: Update inventory/availability

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'data' => $booking,
            'booking_number' => $bookingNumber
        ], 201);
    }

    /**
     * Get booking by booking number
     */
    public function getByNumber($bookingNumber)
    {
        $booking = Booking::where('booking_number', $bookingNumber)
            ->with(['flight', 'event', 'package', 'user'])
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $booking
        ]);
    }

    /**
     * Get user's bookings (authenticated)
     */
    public function myBookings(Request $request)
    {
        $bookings = Booking::where('user_id', $request->user()->id)
            ->with(['flight', 'event', 'package'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }

    /**
     * Get booking details (authenticated)
     */
    public function show(Request $request, $id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->with(['flight', 'event', 'package'])
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $booking
        ]);
    }

    /**
     * Cancel booking (authenticated)
     */
    public function cancel(Request $request, $id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        if ($booking->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Booking already cancelled'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'cancellation_reason' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_at' => now()
        ]);

        // TODO: Process refund if applicable
        // TODO: Update inventory/availability
        // TODO: Send cancellation email

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully',
            'data' => $booking
        ]);
    }

    /**
     * Calculate booking amount
     */
    private function calculateAmount(Request $request)
    {
        $amount = 0;

        switch ($request->booking_type) {
            case 'flight':
                $flight = Flight::find($request->item_id);
                if ($flight) {
                    $seatClass = $request->seat_class ?? 'economy';
                    $pricePerPerson = $flight->{$seatClass . '_price'};
                    $amount = $pricePerPerson * $request->number_of_passengers;
                }
                break;

            case 'event':
                $event = Event::find($request->item_id);
                if ($event) {
                    // If seat zone specified, use zone price
                    if ($request->seat_zone_id) {
                        $zone = $event->seatZones()->find($request->seat_zone_id);
                        $pricePerPerson = $zone ? $zone->price : $event->min_price;
                    } else {
                        $pricePerPerson = $event->min_price;
                    }
                    $amount = $pricePerPerson * $request->number_of_passengers;
                }
                break;

            case 'package':
                $package = TourPackage::find($request->item_id);
                if ($package) {
                    $pricePerPerson = $package->discount_price ?? $package->price;
                    $amount = $pricePerPerson * $request->number_of_passengers;
                }
                break;
        }

        return $amount;
    }
}
