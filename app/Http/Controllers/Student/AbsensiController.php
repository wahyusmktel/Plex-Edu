<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Schedule;
use App\Models\JamPelajaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $siswa = $user->siswa;
        
        if (!$siswa || !$siswa->kelas_id) {
            return back()->with('error', 'Data kelas tidak ditemukan. Silakan hubungi admin.');
        }

        $today = now()->dayOfWeekIso; // 1 (Senin) - 7 (Minggu)
        $dayNames = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
        $todayName = $dayNames[$today];

        // Fetch today's schedule
        $schedules = Schedule::with(['subject.guru', 'jam'])
            ->where('kelas_id', $siswa->kelas_id)
            ->where('hari', $todayName)
            ->get();

        $now = now()->format('H:i');
        $activeSession = null;

        foreach ($schedules as $schedule) {
            $start = $schedule->jam->jam_mulai->format('H:i');
            $end = $schedule->jam->jam_selesai->format('H:i');
            
            if ($now >= $start && $now <= $end) {
                $activeSession = $schedule;
                break;
            }
        }

        // Check if already attended for active session
        $hasAttended = false;
        if ($activeSession) {
            $hasAttended = Absensi::where('siswa_id', $siswa->id)
                ->where('subject_id', $activeSession->subject_id)
                ->whereDate('tanggal', today())
                ->exists();
        }

        // History for today
        $history = Absensi::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', today())
            ->with('subject')
            ->get();

        return view('student.absensi.index', compact('activeSession', 'hasAttended', 'history', 'todayName', 'schedules'));
    }

    public function submit(Request $request)
    {
        $user = auth()->user();
        $siswa = $user->siswa;
        $subjectId = $request->input('subject_id');

        // Validation: Must be student with class
        if (!$siswa || !$siswa->kelas_id) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        // Validation: Subject must match active session
        $today = now()->dayOfWeekIso;
        $dayNames = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
        $todayName = $dayNames[$today];

        $schedule = Schedule::with('jam')
            ->where('kelas_id', $siswa->kelas_id)
            ->where('hari', $todayName)
            ->where('subject_id', $subjectId)
            ->first();

        if (!$schedule) {
            return response()->json(['error' => 'Jadwal tidak ditemukan.'], 404);
        }

        $now = now()->format('H:i');
        $start = $schedule->jam->jam_mulai->format('H:i');
        $end = $schedule->jam->jam_selesai->format('H:i');

        if ($now < $start || $now > $end) {
            return response()->json(['error' => 'Jam pelajaran belum dimulai atau sudah selesai.'], 400);
        }

        // Validation: Already attended
        if (Absensi::where('siswa_id', $siswa->id)->where('subject_id', $subjectId)->whereDate('tanggal', today())->exists()) {
            return response()->json(['error' => 'Anda sudah melakukan absensi untuk mata pelajaran ini.'], 400);
        }

        // Record attendance
        Absensi::create([
            'school_id' => $user->school_id,
            'siswa_id' => $siswa->id,
            'subject_id' => $subjectId,
            'tanggal' => today(),
            'status' => 'H',
            'keterangan' => 'Absen mandiri via Web'
        ]);

        return response()->json(['success' => 'Berhasil mengirim kehadiran!']);
    }
}
