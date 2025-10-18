<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use Illuminate\Http\Request;

class CarouselController extends Controller
{
    /**
     * Get active carousels for homepage
     */
    public function active()
    {
        $carousels = Carousel::where('is_active', true)
            ->where(function($query) {
                $query->whereNull('start_date')
                      ->orWhere('start_date', '<=', now());
            })
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            })
            ->orderBy('order_position', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $carousels
        ]);
    }
}
