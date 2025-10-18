<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants fournis sont incorrects.'],
            ]);
        }

        if (!$admin->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Votre compte est désactivé. Contactez l\'administrateur.'],
            ]);
        }

        // Update last login
        $admin->update(['last_login' => now()]);

        // Login the admin
        Auth::guard('admin')->login($admin, $request->filled('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * Show admin profile
     */
    public function profile()
    {
        $admin = Auth::guard('admin')->user();
        $recentActivities = ActivityLog::where('admin_id', $admin->id)
            ->latest()
            ->take(10)
            ->get();
        
        return view('admin.profile', compact('admin', 'recentActivities'));
    }

    /**
     * Update admin profile
     */
    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($admin->avatar) {
                Storage::disk('public')->delete($admin->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $admin->update($validated);

        return redirect()->route('admin.profile')
            ->with('success', 'Profil mis à jour avec succès');
    }

    /**
     * Update admin password
     */
    public function updatePassword(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect']);
        }

        $admin->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('admin.profile')
            ->with('success', 'Mot de passe changé avec succès');
    }

    /**
     * Show notifications
     */
    public function notifications()
    {
        $admin = Auth::guard('admin')->user();
        
        // For now, we'll create a dummy notification system
        // In production, you'd fetch from the notifications table
        $notifications = collect([
            (object)[
                'id' => 1,
                'title_fr' => 'Nouvelle réservation',
                'message_fr' => 'Une nouvelle réservation a été effectuée',
                'type' => 'booking',
                'is_read' => false,
                'created_at' => now()->subMinutes(5),
            ],
            (object)[
                'id' => 2,
                'title_fr' => 'Nouveau utilisateur',
                'message_fr' => 'Un nouvel utilisateur s\'est inscrit',
                'type' => 'user',
                'is_read' => false,
                'created_at' => now()->subHours(2),
            ],
            (object)[
                'id' => 3,
                'title_fr' => 'Paiement reçu',
                'message_fr' => 'Un paiement de 150,000 XOF a été reçu',
                'type' => 'payment',
                'is_read' => true,
                'created_at' => now()->subDays(1),
            ],
        ]);

        return view('admin.notifications', compact('notifications'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        // In production, update the notification in database
        return back()->with('success', 'Notification marquée comme lue');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        // In production, update all notifications in database
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }
}
