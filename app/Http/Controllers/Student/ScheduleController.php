<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $siswa = $user->siswa;

        if (!$siswa || !$siswa->kelas_id) {
            return back()->with('error', 'Data kelas tidak ditemukan.');
        }

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $schedules = Schedule::with(['subject.guru', 'jam'])
            ->where('kelas_id', $siswa->kelas_id)
            ->get()
            ->groupBy('hari');

        // Sort schedules within each day by start time (via jam relationship)
        foreach ($schedules as $day => $items) {
            $schedules[$day] = $items->sortBy(function($item) {
                return $item->jam->jam_mulai;
            });
        }

        return view('student.schedule.index', compact('schedules', 'days', 'siswa'));
    }
}
