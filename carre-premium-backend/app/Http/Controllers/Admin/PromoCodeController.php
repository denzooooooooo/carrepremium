<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use App\Models\PromoCodeUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromoCodeController extends Controller
{
    /**
     * Display a listing of promo codes
     */
    public function index(Request $request)
    {
        $query = PromoCode::query();

        // Filtres
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)
                      ->where('valid_from', '<=', now())
                      ->where('valid_until', '>=', now());
            } elseif ($request->status === 'expired') {
                $query->where('valid_until', '<', now());
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->has('type')) {
            $query->where('discount_type', $request->type);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('description_fr', 'like', "%{$search}%");
            });
        }

        $promoCodes = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistiques
        $stats = [
            'total' => PromoCode::count(),
            'active' => PromoCode::where('is_active', true)
                ->where('valid_from', '<=', now())
                ->where('valid_until', '>=', now())
                ->count(),
            'expired' => PromoCode::where('valid_until', '<', now())->count(),
            'total_usage' => PromoCodeUsage::count(),
            'total_discount' => PromoCodeUsage::sum('discount_amount')
        ];

        return view('admin.promo-codes.index', compact('promoCodes', 'stats'));
    }

    /**
     * Show the form for creating a new promo code
     */
    public function create()
    {
        return view('admin.promo-codes.create');
    }

    /**
     * Store a newly created promo code
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:promo_codes,code',
            'description_fr' => 'required|string',
            'description_en' => 'required|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'applicable_to' => 'required|in:all,flights,events,packages',
            'is_active' => 'boolean'
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active');

        PromoCode::create($validated);

        return redirect()->route('admin.promo-codes.index')
            ->with('success', 'Code promo créé avec succès');
    }

    /**
     * Display the specified promo code
     */
    public function show($id)
    {
        $promoCode = PromoCode::with('usage.user', 'usage.booking')->findOrFail($id);
        
        $usageStats = [
            'total_uses' => $promoCode->used_count,
            'total_discount' => $promoCode->usage->sum('discount_amount'),
            'unique_users' => $promoCode->usage->unique('user_id')->count(),
            'recent_usage' => $promoCode->usage()->latest()->take(10)->get()
        ];

        return view('admin.promo-codes.show', compact('promoCode', 'usageStats'));
    }

    /**
     * Show the form for editing the specified promo code
     */
    public function edit($id)
    {
        $promoCode = PromoCode::findOrFail($id);
        return view('admin.promo-codes.edit', compact('promoCode'));
    }

    /**
     * Update the specified promo code
     */
    public function update(Request $request, $id)
    {
        $promoCode = PromoCode::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:promo_codes,code,' . $id,
            'description_fr' => 'required|string',
            'description_en' => 'required|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'applicable_to' => 'required|in:all,flights,events,packages',
            'is_active' => 'boolean'
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active');

        $promoCode->update($validated);

        return redirect()->route('admin.promo-codes.index')
            ->with('success', 'Code promo mis à jour avec succès');
    }

    /**
     * Remove the specified promo code
     */
    public function destroy($id)
    {
        $promoCode = PromoCode::findOrFail($id);
        
        // Vérifier si le code a été utilisé
        if ($promoCode->used_count > 0) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer un code promo déjà utilisé. Désactivez-le plutôt.');
        }

        $promoCode->delete();

        return redirect()->route('admin.promo-codes.index')
            ->with('success', 'Code promo supprimé avec succès');
    }

    /**
     * Toggle promo code status
     */
    public function toggleStatus($id)
    {
        $promoCode = PromoCode::findOrFail($id);
        $promoCode->update(['is_active' => !$promoCode->is_active]);

        $status = $promoCode->is_active ? 'activé' : 'désactivé';
        return redirect()->back()->with('success', "Code promo {$status} avec succès");
    }

    /**
     * Generate random promo code
     */
    public function generateCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (PromoCode::where('code', $code)->exists());

        return response()->json(['code' => $code]);
    }

    /**
     * Export promo codes
     */
    public function export()
    {
        $promoCodes = PromoCode::with('usage')->get();
        
        $csv = "Code,Description,Type,Valeur,Utilisations,Limite,Valide du,Valide jusqu'au,Statut\n";
        
        foreach ($promoCodes as $code) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%d,%s,%s,%s,%s\n",
                $code->code,
                $code->description_fr,
                $code->discount_type,
                $code->discount_value,
                $code->used_count,
                $code->usage_limit ?? 'Illimité',
                $code->valid_from->format('d/m/Y'),
                $code->valid_until->format('d/m/Y'),
                $code->is_active ? 'Actif' : 'Inactif'
            );
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="promo-codes-' . date('Y-m-d') . '.csv"');
    }
}
