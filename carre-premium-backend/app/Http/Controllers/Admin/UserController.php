<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of users with advanced filters and statistics
     */
    public function index(Request $request)
    {
        $query = User::withCount(['bookings', 'reviews'])
            ->withSum('bookings as total_spent', 'final_amount');

        // Recherche avancée
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filtre par pays
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        // Filtre par date d'inscription
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filtre par points de fidélité
        if ($request->filled('points_min')) {
            $query->where('loyalty_points', '>=', $request->points_min);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate($request->get('per_page', 15));

        // Statistiques détaillées
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'new_today' => User::whereDate('created_at', today())->count(),
            'new_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'new_month' => User::whereMonth('created_at', now()->month)->count(),
            'total_points' => User::sum('loyalty_points'),
            'with_bookings' => User::has('bookings')->count(),
            'top_spenders' => User::withSum('bookings as total_spent', 'final_amount')
                ->orderBy('total_spent', 'desc')
                ->take(5)
                ->get(),
            'by_country' => User::select('country', DB::raw('count(*) as count'))
                ->groupBy('country')
                ->orderBy('count', 'desc')
                ->take(5)
                ->get(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;

        $user = User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès');
    }

    /**
     * Display the specified user with complete profile
     */
    public function show(string $id)
    {
        $user = User::withCount(['bookings', 'reviews', 'favorites'])
            ->withSum('bookings as total_spent', 'final_amount')
            ->findOrFail($id);
        
        // Réservations de l'utilisateur
        $bookings = $user->bookings()
            ->with(['flight', 'event', 'package'])
            ->latest()
            ->paginate(10);
        
        // Avis de l'utilisateur
        $reviews = $user->reviews()->latest()->take(5)->get();

        // Statistiques utilisateur
        $userStats = [
            'total_bookings' => $user->bookings()->count(),
            'confirmed_bookings' => $user->bookings()->where('status', 'confirmed')->count(),
            'cancelled_bookings' => $user->bookings()->where('status', 'cancelled')->count(),
            'total_spent' => $user->bookings()->where('payment_status', 'paid')->sum('final_amount'),
            'loyalty_points' => $user->loyalty_points,
            'average_rating' => round($user->reviews()->avg('rating'), 1),
            'favorite_destination' => $user->bookings()
                ->select('destination', DB::raw('count(*) as count'))
                ->groupBy('destination')
                ->orderBy('count', 'desc')
                ->first(),
            'last_booking' => $user->bookings()->latest()->first(),
            'member_since' => $user->created_at->diffForHumans(),
        ];

        return view('admin.users.show', compact('user', 'bookings', 'reviews', 'userStats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        
        // Si c'est une requête AJAX, retourner JSON
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json($user);
        }
        
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
            'loyalty_points' => 'nullable|integer|min:0',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(string $id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'success' => true,
            'is_active' => $user->is_active,
            'message' => 'Statut mis à jour avec succès'
        ]);
    }

    /**
     * Add loyalty points to user
     */
    public function addPoints(Request $request, string $id)
    {
        $validated = $request->validate([
            'points' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255'
        ]);

        $user = User::findOrFail($id);
        $user->loyalty_points += $validated['points'];
        $user->save();

        // TODO: Log l'ajout de points
        
        return response()->json([
            'success' => true,
            'new_points' => $user->loyalty_points,
            'message' => $validated['points'] . ' points ajoutés avec succès'
        ]);
    }

    /**
     * Send email to user
     */
    public function sendEmail(Request $request, string $id)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        $user = User::findOrFail($id);

        // TODO: Envoyer l'email
        // Mail::to($user->email)->send(new CustomEmail($validated));

        return response()->json([
            'success' => true,
            'message' => 'Email envoyé avec succès'
        ]);
    }

    /**
     * Export users to CSV
     */
    public function export(Request $request)
    {
        $query = User::query();

        // Appliquer filtres
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        $users = $query->get();

        $filename = 'utilisateurs_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'ID',
                'Prénom',
                'Nom',
                'Email',
                'Téléphone',
                'Pays',
                'Points Fidélité',
                'Statut',
                'Date Inscription'
            ]);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->first_name,
                    $user->last_name,
                    $user->email,
                    $user->phone,
                    $user->country,
                    $user->loyalty_points,
                    $user->is_active ? 'Actif' : 'Inactif',
                    $user->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
