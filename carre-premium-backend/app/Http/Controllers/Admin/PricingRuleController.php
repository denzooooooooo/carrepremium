<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingRule;
use Illuminate\Http\Request;

class PricingRuleController extends Controller
{
    public function index()
    {
        $rules = PricingRule::orderBy('product_type')
            ->orderBy('priority', 'desc')
            ->get();

        return view('admin.pricing-rules.index', compact('rules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_type' => 'required|in:flight,event,package',
            'rule_name' => 'required|string|max:100',
            'category' => 'nullable|string|max:50',
            'margin_type' => 'required|in:percentage,fixed',
            'margin_value' => 'required|numeric|min:0',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'priority' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        $validated['priority'] = $validated['priority'] ?? 10;
        $validated['is_active'] = $validated['is_active'] ?? true;

        $rule = PricingRule::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Règle de pricing créée avec succès',
                'rule' => $rule
            ]);
        }

        return redirect()->route('admin.pricing-rules.index')
            ->with('success', 'Règle de pricing créée avec succès');
    }

    public function update(Request $request, $id)
    {
        $rule = PricingRule::findOrFail($id);

        $validated = $request->validate([
            'product_type' => 'required|in:flight,event,package',
            'rule_name' => 'required|string|max:100',
            'category' => 'nullable|string|max:50',
            'margin_type' => 'required|in:percentage,fixed',
            'margin_value' => 'required|numeric|min:0',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'priority' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        $rule->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Règle de pricing mise à jour avec succès',
                'rule' => $rule
            ]);
        }

        return redirect()->route('admin.pricing-rules.index')
            ->with('success', 'Règle de pricing mise à jour avec succès');
    }

    public function destroy(Request $request, $id)
    {
        $rule = PricingRule::findOrFail($id);
        $rule->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Règle de pricing supprimée avec succès'
            ]);
        }

        return redirect()->route('admin.pricing-rules.index')
            ->with('success', 'Règle de pricing supprimée avec succès');
    }

    public function toggleStatus(Request $request, $id)
    {
        $rule = PricingRule::findOrFail($id);
        $rule->is_active = !$rule->is_active;
        $rule->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès',
                'is_active' => $rule->is_active
            ]);
        }

        return redirect()->route('admin.pricing-rules.index')
            ->with('success', 'Statut mis à jour avec succès');
    }
}
