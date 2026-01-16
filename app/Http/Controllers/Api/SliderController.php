<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        
        $sliders = Slider::where(function($query) use ($today) {
            $query->where('is_permanen', true)
                  ->orWhere(function($q) use ($today) {
                      $q->where('waktu_mulai', '<=', $today)
                        ->where(function($qq) use ($today) {
                            $qq->whereNull('waktu_selesai')
                               ->orWhere('waktu_selesai', '>=', $today);
                        });
                  });
        })
        ->orderBy('created_at', 'desc')
        ->get();

        $sliders->transform(function ($slider) {
            $slider->gambar_url = url('api/sliders/image/' . basename($slider->gambar));
            return $slider;
        });

        return response()->json([
            'status' => 'success',
            'data' => $sliders
        ]);
    }

    public function showImage($filename)
    {
        $path = storage_path('app/public/sliders/' . $filename);
        
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
        ]);
    }
}
