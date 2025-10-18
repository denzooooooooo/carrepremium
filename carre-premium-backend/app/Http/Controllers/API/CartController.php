<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Flight;
use App\Models\Event;
use App\Models\TourPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_type' => 'required|in:flight,event,package',
            'item_id' => 'required|integer',
            'quantity' => 'nullable|integer|min:1',
            'travel_date' => 'nullable|date',
            'passenger_count' => 'nullable|integer|min:1',
            'seat_class' => 'nullable|string',
            'seat_zone_id' => 'nullable|integer',
            'options' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Get or create session ID
        $sessionId = $request->session_id ?? Str::uuid()->toString();
        $userId = auth()->check() ? auth()->id() : null;

        // Get item and calculate price
        $itemType = $request->item_type;
        $itemId = $request->item_id;
        $price = 0;

        switch ($itemType) {
            case 'flight':
                $flight = Flight::find($itemId);
                if (!$flight) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Flight not found'
                    ], 404);
                }
                $seatClass = $request->seat_class ?? 'economy';
                $price = $flight->{$seatClass . '_price'};
                break;

            case 'event':
                $event = Event::find($itemId);
                if (!$event) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Event not found'
                    ], 404);
                }
                $price = $event->min_price;
                break;

            case 'package':
                $package = TourPackage::find($itemId);
                if (!$package) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Package not found'
                    ], 404);
                }
                $price = $package->discount_price ?? $package->price;
                break;
        }

        // Create cart item
        $cartItem = Cart::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'item_type' => $itemType,
            'item_id' => $itemId,
            'quantity' => $request->quantity ?? 1,
            'seat_class' => $request->seat_class,
            'seat_zone_id' => $request->seat_zone_id,
            'travel_date' => $request->travel_date,
            'passenger_count' => $request->passenger_count ?? 1,
            'price' => $price,
            'options' => json_encode($request->options ?? [])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart',
            'data' => $cartItem,
            'session_id' => $sessionId
        ]);
    }

    /**
     * Get cart items by session ID
     */
    public function get($sessionId)
    {
        $cartItems = Cart::where('session_id', $sessionId)
            ->with(['flight', 'event', 'package'])
            ->get();

        $total = $cartItems->sum(function($item) {
            return $item->price * $item->quantity * $item->passenger_count;
        });

        return response()->json([
            'success' => true,
            'data' => $cartItems,
            'total' => $total,
            'count' => $cartItems->count()
        ]);
    }

    /**
     * Update cart item
     */
    public function update(Request $request, $id)
    {
        $cartItem = Cart::find($id);

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'quantity' => 'nullable|integer|min:1',
            'passenger_count' => 'nullable|integer|min:1',
            'seat_class' => 'nullable|string',
            'travel_date' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $cartItem->update($request->only([
            'quantity',
            'passenger_count',
            'seat_class',
            'travel_date'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Cart item updated',
            'data' => $cartItem
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        $cartItem = Cart::find($id);

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart'
        ]);
    }

    /**
     * Clear entire cart by session ID
     */
    public function clear($sessionId)
    {
        Cart::where('session_id', $sessionId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared'
        ]);
    }
}
