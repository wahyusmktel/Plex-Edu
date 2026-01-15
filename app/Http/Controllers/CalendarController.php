<?php

namespace App\Http\Controllers;

use App\Models\KalenderEvent;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        return view('admin.calendar.index');
    }

    public function getEvents(Request $request)
    {
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);

        $events = KalenderEvent::whereBetween('start_date', [$start, $end])
            ->orWhereBetween('end_date', [$start, $end])
            ->get();

        $formattedEvents = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_date->toIso8601String(),
                'end' => $event->end_date->toIso8601String(),
                'description' => $event->description,
                'category' => $event->category,
                'backgroundColor' => $this->getCategoryColor($event->category),
                'borderColor' => $this->getCategoryColor($event->category),
                'textColor' => '#ffffff',
            ];
        });

        return response()->json($formattedEvents);
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'dinas'])) {
            return response()->json(['error' => 'Akses dilarang.'], 403);
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        $event = KalenderEvent::create([
            'title' => $request->title,
            'category' => $request->category,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'color' => $this->getCategoryColor($request->category),
        ]);

        return response()->json(['success' => 'Acara berhasil ditambahkan!', 'event' => $event]);
    }

    public function show($id)
    {
        return response()->json(KalenderEvent::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'dinas'])) {
            return response()->json(['error' => 'Akses dilarang.'], 403);
        }
        $event = KalenderEvent::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        $event->update([
            'title' => $request->title,
            'category' => $request->category,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'color' => $this->getCategoryColor($request->category),
        ]);

        return response()->json(['success' => 'Acara berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'dinas'])) {
            return response()->json(['error' => 'Akses dilarang.'], 403);
        }
        KalenderEvent::findOrFail($id)->delete();
        return response()->json(['success' => 'Acara berhasil dihapus.']);
    }

    private function getCategoryColor($category)
    {
        $colors = [
            'event' => '#ba80e8',   // Purple
            'holiday' => '#f43f5e', // Rose/Red
            'exam' => '#0ea5e9',    // Blue
            'other' => '#64748b',   // Slate
        ];

        return $colors[$category] ?? '#ba80e8';
    }
}
