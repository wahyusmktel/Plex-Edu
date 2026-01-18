<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KalenderEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KalenderApiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $events = KalenderEvent::withoutGlobalScope('school')
            ->where(function($query) use ($user) {
                $query->where('school_id', $user->school_id)
                      ->orWhereNull('school_id');
            })
            ->orderBy('start_date', 'asc')
            ->get()
            ->map(function($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'category' => $event->category,
                    'start_date' => $event->start_date->isoFormat('YYYY-MM-DD HH:mm:ss'),
                    'end_date' => $event->end_date->isoFormat('YYYY-MM-DD HH:mm:ss'),
                    'description' => $event->description,
                    'color' => $event->color ?? '#3b82f6',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }
}
