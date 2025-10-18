<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $gateways = PaymentGateway::orderBy('gateway_name')->get();
        return view('admin.payment-gateways.index', compact('gateways'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gateway_name' => 'required|string|max:50',
            'gateway_type' => 'required|in:card,mobile_money,bank_transfer,paypal,stripe',
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
            'merchant_id' => 'nullable|string|max:100',
            'webhook_url' => 'nullable|url',
            'supported_currencies' => 'nullable|json',
            'transaction_fee_percentage' => 'nullable|numeric|min:0|max:100',
            'transaction_fee_fixed' => 'nullable|numeric|min:0',
            'configuration' => 'nullable|json',
            'is_active' => 'nullable|boolean'
        ]);

        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['transaction_fee_percentage'] = $validated['transaction_fee_percentage'] ?? 0;
        $validated['transaction_fee_fixed'] = $validated['transaction_fee_fixed'] ?? 0;

        $gateway = PaymentGateway::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Passerelle de paiement créée avec succès',
                'gateway' => $gateway
            ]);
        }

        return redirect()->route('admin.payment-gateways.index')
            ->with('success', 'Passerelle de paiement créée avec succès');
    }

    public function update(Request $request, $id)
    {
        $gateway = PaymentGateway::findOrFail($id);

        $validated = $request->validate([
            'gateway_name' => 'required|string|max:50',
            'gateway_type' => 'required|in:card,mobile_money,bank_transfer,paypal,stripe',
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
            'merchant_id' => 'nullable|string|max:100',
            'webhook_url' => 'nullable|url',
            'supported_currencies' => 'nullable|json',
            'transaction_fee_percentage' => 'nullable|numeric|min:0|max:100',
            'transaction_fee_fixed' => 'nullable|numeric|min:0',
            'configuration' => 'nullable|json',
            'is_active' => 'nullable|boolean'
        ]);

        $gateway->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Passerelle de paiement mise à jour avec succès',
                'gateway' => $gateway
            ]);
        }

        return redirect()->route('admin.payment-gateways.index')
            ->with('success', 'Passerelle de paiement mise à jour avec succès');
    }

    public function destroy(Request $request, $id)
    {
        $gateway = PaymentGateway::findOrFail($id);
        $gateway->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Passerelle de paiement supprimée avec succès'
            ]);
        }

        return redirect()->route('admin.payment-gateways.index')
            ->with('success', 'Passerelle de paiement supprimée avec succès');
    }

    public function toggleStatus(Request $request, $id)
    {
        $gateway = PaymentGateway::findOrFail($id);
        $gateway->is_active = !$gateway->is_active;
        $gateway->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès',
                'is_active' => $gateway->is_active
            ]);
        }

        return redirect()->route('admin.payment-gateways.index')
            ->with('success', 'Statut mis à jour avec succès');
    }
}
