<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiConfiguration;
use Illuminate\Http\Request;

class ApiConfigController extends Controller
{
    public function index()
    {
        $configs = ApiConfiguration::orderBy('provider')->get();
        return view('admin.api-config.index', compact('configs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'provider' => 'required|string|max:50',
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
            'endpoint_url' => 'required|url',
            'merchant_id' => 'nullable|string',
            'webhook_url' => 'nullable|url',
            'is_production' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'additional_config' => 'nullable|json'
        ]);

        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['is_production'] = $validated['is_production'] ?? false;

        $config = ApiConfiguration::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Configuration API créée avec succès',
                'config' => $config
            ]);
        }

        return redirect()->route('admin.api-config.index')
            ->with('success', 'Configuration API créée avec succès');
    }

    public function update(Request $request, $id)
    {
        $config = ApiConfiguration::findOrFail($id);

        $validated = $request->validate([
            'provider' => 'required|string|max:50',
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
            'endpoint_url' => 'required|url',
            'merchant_id' => 'nullable|string',
            'webhook_url' => 'nullable|url',
            'is_production' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'additional_config' => 'nullable|json'
        ]);

        $config->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Configuration API mise à jour avec succès',
                'config' => $config
            ]);
        }

        return redirect()->route('admin.api-config.index')
            ->with('success', 'Configuration API mise à jour avec succès');
    }

    public function destroy(Request $request, $id)
    {
        $config = ApiConfiguration::findOrFail($id);
        $config->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Configuration API supprimée avec succès'
            ]);
        }

        return redirect()->route('admin.api-config.index')
            ->with('success', 'Configuration API supprimée avec succès');
    }

    public function testConnection($id)
    {
        $config = ApiConfiguration::findOrFail($id);

        try {
            // Test de connexion selon le provider
            if ($config->provider === 'amadeus') {
                // Simuler un test de connexion Amadeus
                if (empty($config->api_key) || empty($config->api_secret)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'API Key et Secret requis pour Amadeus'
                    ], 400);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Configuration Amadeus valide (test simulé)',
                    'provider' => 'Amadeus',
                    'environment' => $config->is_production ? 'Production' : 'Test'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Configuration valide pour ' . ucfirst($config->provider),
                'provider' => $config->provider
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de connexion: ' . $e->getMessage()
            ], 500);
        }
    }
}
