<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Currency;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Get public settings (accessible without authentication)
     */
    public function public()
    {
        $publicSettings = Setting::whereIn('setting_key', [
            'site_name',
            'site_email',
            'site_phone',
            'default_language',
            'default_currency',
            'theme_mode',
            'primary_color',
            'secondary_color',
            'enable_chatbot',
            'enable_whatsapp',
            'whatsapp_number',
            'enable_recommendations'
        ])->get()->pluck('setting_value', 'setting_key');

        return response()->json([
            'success' => true,
            'data' => $publicSettings
        ]);
    }

    /**
     * Get all active currencies
     */
    public function currencies()
    {
        $currencies = Currency::where('is_active', true)
            ->orderBy('is_default', 'desc')
            ->orderBy('code', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $currencies
        ]);
    }

    /**
     * Get specific setting by key
     */
    public function getSetting($key)
    {
        $setting = Setting::where('setting_key', $key)->first();

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'key' => $setting->setting_key,
                'value' => $setting->setting_value,
                'type' => $setting->setting_type
            ]
        ]);
    }
}
