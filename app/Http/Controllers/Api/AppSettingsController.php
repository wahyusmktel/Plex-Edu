<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

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
                'app_logo' => $settings->app_logo,
                'school_registration_enabled' => (bool) $settings->school_registration_enabled,
            ]
        ]);
    }
}
