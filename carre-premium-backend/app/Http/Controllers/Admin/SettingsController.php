<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settingsData = Setting::all();
        $settings = [];
        
        foreach ($settingsData as $setting) {
            $settings[$setting->setting_key] = $setting->setting_value;
        }
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['setting_key' => $key],
                [
                    'setting_value' => is_bool($value) ? ($value ? 'true' : 'false') : $value,
                    'setting_type' => 'text'
                ]
            );
        }
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Paramètres mis à jour avec succès');
    }
}
