<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AppSetting extends Model
{
    protected $fillable = ['app_name', 'app_logo', 'school_registration_enabled'];

    public function getLogoUrlAttribute()
    {
        if ($this->app_logo) {
            // Check if it's already using the new path format (without 'public/')
            $path = str_replace('public/', '', $this->app_logo);
            return Storage::disk('public')->url($path);
        }
        return null; // Layout will handle fallback to icon
    }
}
