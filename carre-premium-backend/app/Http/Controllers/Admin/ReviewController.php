<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'booking'])
            ->orderBy('created_at', 'desc');

        // Filtres
        if ($request->has('status')) {
            if ($request->status === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->status === 'approved') {
                $query->where('is_approved', true);
            }
        }

        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('comment', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        $reviews = $query->paginate(20);

        // Statistiques
        $stats = [
            'total' => Review::count(),
            'pending' => Review::where('is_approved', false)->count(),
            'approved' => Review::where('is_approved', true)->count(),
            'average_rating' => Review::where('is_approved', true)->avg('rating')
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Show review details
     */
    public function show($id)
    {
        $review = Review::with(['user', 'booking'])->findOrFail($id);
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Approve review
     */
    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => true]);

        return redirect()->back()->with('success', 'Avis approuvé avec succès');
    }

    /**
     * Reject review
     */
    public function reject($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => false]);

        return redirect()->back()->with('success', 'Avis rejeté');
    }

    /**
     * Add admin response
     */
    public function respond(Request $request, $id)
    {
        $request->validate([
            'admin_response' => 'required|string|max:1000'
        ]);

        $review = Review::findOrFail($id);
        $review->update([
            'admin_response' => $request->admin_response
        ]);

        return redirect()->back()->with('success', 'Réponse ajoutée avec succès');
    }

    /**
     * Delete review
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Avis supprimé avec succès');
    }

    /**
     * Bulk approve
     */
    public function bulkApprove(Request $request)
    {
        $ids = $request->input('ids', []);
        Review::whereIn('id', $ids)->update(['is_approved' => true]);

        return redirect()->back()->with('success', count($ids) . ' avis approuvés');
    }

    /**
     * Bulk delete
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        Review::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', count($ids) . ' avis supprimés');
    }
}
