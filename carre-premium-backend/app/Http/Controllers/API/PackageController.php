<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TourPackage;
use App\Models\Category;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Get all packages with filters
     */
    public function index(Request $request)
    {
        $query = TourPackage::with(['category'])
            ->where('is_active', true);

        // Filtres
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('package_type')) {
            $query->where('package_type', $request->package_type);
        }

        if ($request->has('destination')) {
            $query->where('destination', 'like', '%' . $request->destination . '%');
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('min_duration')) {
            $query->where('duration', '>=', $request->min_duration);
        }

        if ($request->has('max_duration')) {
            $query->where('duration', '<=', $request->max_duration);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 12);
        $packages = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $packages
        ]);
    }

    /**
     * Get package by ID
     */
    public function show($id)
    {
        $package = TourPackage::with(['category'])->find($id);

        if (!$package) {
            return response()->json([
                'success' => false,
                'message' => 'Package not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $package
        ]);
    }

    /**
     * Get VIP packages
     */
    public function vip(Request $request)
    {
        $limit = $request->get('limit', 6);
        
        $packages = TourPackage::with(['category'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->whereIn('package_type', ['helicopter', 'private_jet', 'luxury'])
            ->orderBy('price', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $packages
        ]);
    }

    /**
     * Get packages by type
     */
    public function byType($type)
    {
        $packages = TourPackage::with(['category'])
            ->where('package_type', $type)
            ->where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $packages
        ]);
    }

    /**
     * Search packages
     */
    public function search(Request $request)
    {
        $query = TourPackage::with(['category'])
            ->where('is_active', true);

        if ($request->has('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('title_fr', 'like', '%' . $search . '%')
                  ->orWhere('title_en', 'like', '%' . $search . '%')
                  ->orWhere('destination', 'like', '%' . $search . '%')
                  ->orWhere('description_fr', 'like', '%' . $search . '%');
            });
        }

        $packages = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $packages,
            'count' => $packages->count()
        ]);
    }

    /**
     * Get available dates for a package
     */
    public function availableDates($id)
    {
        $package = TourPackage::find($id);
        
        if (!$package) {
            return response()->json([
                'success' => false,
                'message' => 'Package not found'
            ], 404);
        }

        $availableDates = json_decode($package->available_dates, true) ?? [];

        return response()->json([
            'success' => true,
            'data' => $availableDates
        ]);
    }

    /**
     * Check availability for specific date
     */
    public function checkAvailability($id, Request $request)
    {
        $package = TourPackage::find($id);
        
        if (!$package) {
            return response()->json([
                'success' => false,
                'message' => 'Package not found'
            ], 404);
        }

        $participants = $request->get('participants', 1);
        $date = $request->get('date');

        $available = $participants >= $package->min_participants && 
                     $participants <= $package->max_participants;

        return response()->json([
            'success' => true,
            'available' => $available,
            'min_participants' => $package->min_participants,
            'max_participants' => $package->max_participants,
            'requested_participants' => $participants
        ]);
    }
}
