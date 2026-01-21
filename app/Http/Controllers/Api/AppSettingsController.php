<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppSettingsController extends Controller
{
    /**
     * Get Application Settings
     * 
     * Retrieve public application settings like branding and feature toggles.
     * 
     * @response {
     *  "status": "success",
     *  "data": {
     *    "app_name": "Literasia Edutekno Digital",
     *    "app_logo": "settings/logo.png",
     *    "school_registration_enabled": true
     *  }
     * }
     */
    public function index()
    {
        $settings = AppSetting::first() ?? new AppSetting([
            'app_name' => 'Literasia Edutekno Digital',
            'school_registration_enabled' => true
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'app_name' => $settings->app_name,
                'app_logo' => $settings->logo_url ? asset('storage/' . $settings->app_logo) : null,
                'school_registration_enabled' => (bool) $settings->school_registration_enabled,
            ]
        ]);
    }

    /**
     * Update Application Settings
     * 
     * Update global application settings (Dinas Only)
     */
    public function update(Request $request)
    {
        if (auth()->user()->role !== 'dinas') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only Dinas role can update app settings.'
            ], 403);
        }

        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|max:2048',
            'school_registration_enabled' => 'nullable|boolean'
        ]);

        $settings = AppSetting::first() ?? new AppSetting();

        $settings->app_name = $request->app_name;
        
        if ($request->has('school_registration_enabled')) {
            $settings->school_registration_enabled = $request->school_registration_enabled;
        }

        if ($request->hasFile('app_logo')) {
            // Delete old logo if exists
            if ($settings->app_logo) {
                Storage::disk('public')->delete($settings->app_logo);
            }
            $path = $request->file('app_logo')->store('settings', 'public');
            $settings->app_logo = $path;
        }

        $settings->save();

        return response()->json([
            'status' => 'success',
            'message' => 'App settings updated successfully',
            'data' => [
                'app_name' => $settings->app_name,
                'app_logo' => asset('storage/' . $settings->app_logo),
                'school_registration_enabled' => (bool) $settings->school_registration_enabled,
            ]
        ]);
    }
}
