<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Schedule;
use App\Models\Siswa;
use App\Models\CbtSession;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StudentAttendanceController extends Controller
{
    public function getTodaySchedule(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'siswa') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $siswa = Siswa::where('user_id', $user->id)->first();
        if (!$siswa) {
            return response()->json(['status' => 'error', 'message' => 'Siswa record not found'], 404);
        }

        $today = Carbon::now('Asia/Jakarta');
        $dayName = $this->translateDay($today->format('l'));

        $schedules = Schedule::with(['subject', 'jam'])
            ->where('kelas_id', $siswa->kelas_id)
            ->where('hari', $dayName)
            ->get();

        // Check attendance status for each schedule
        $schedules->transform(function ($item) use ($siswa, $today) {
            $attendance = Absensi::where('siswa_id', $siswa->id)
                ->where('subject_id', $item->subject_id)
                ->whereDate('tanggal', $today->toDateString())
                ->first();
            
            $statusMap = [
                'H' => 'Hadir',
                'A' => 'Alfa',
                'S' => 'Sakit',
                'I' => 'Izin',
            ];
            $item->attendance_status = $attendance ? ($statusMap[$attendance->status] ?? $attendance->status) : null;
            
            // Check if it's currently session time
            $now = Carbon::now('Asia/Jakarta')->format('H:i');
            $startTime = Carbon::parse($item->jam->jam_mulai)->format('H:i');
            $endTime = Carbon::parse($item->jam->jam_selesai)->format('H:i');
            
            $item->is_current_session = ($now >= $startTime && $now <= $endTime);
            $item->can_attend = $item->is_current_session; // Allow even if already attended (for edit)

            return $item;
        });

        return response()->json([
            'status' => 'success',
            'server_time' => $today->format('H:i:s'),
            'server_day' => $dayName,
            'data' => $schedules
        ]);
    }

    public function getAllSubjects(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'siswa') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $siswa = Siswa::where('user_id', $user->id)->first();
        if (!$siswa) {
            return response()->json(['status' => 'error', 'message' => 'Siswa record not found'], 404);
        }

        $schedules = Schedule::with(['subject.guru', 'jam'])
            ->where('kelas_id', $siswa->kelas_id)
            ->get();
            
        $subjects = $schedules->groupBy('subject_id')->map(function ($items) {
            $first = $items->first();
            return [
                'id' => $first->subject->id,
                'nama_pelajaran' => $first->subject->nama_pelajaran,
                'kode_pelajaran' => $first->subject->kode_pelajaran,
                'guru' => $first->subject->guru ? $first->subject->guru->nama : 'Belum ditentukan',
                'schedules' => $items->map(function ($item) {
                    return [
                        'hari' => $item->hari,
                        'jam_mulai' => $item->jam->jam_mulai,
                        'jam_selesai' => $item->jam->jam_selesai,
                    ];
                }),
            ];
        })->values();

        return response()->json([
            'status' => 'success',
            'data' => $subjects
        ]);
    }

    public function getAllSchedules(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'siswa') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $siswa = Siswa::where('user_id', $user->id)->first();
        if (!$siswa) {
            return response()->json(['status' => 'error', 'message' => 'Siswa record not found'], 404);
        }

        $schedules = Schedule::with(['subject.guru', 'jam'])
            ->where('kelas_id', $siswa->kelas_id)
            ->get();

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        $grouped = collect($days)->mapWithKeys(function ($day) {
            return [$day => []];
        });

        foreach ($schedules as $schedule) {
            if (isset($grouped[$schedule->hari])) {
                $grouped[$schedule->hari] = array_merge($grouped[$schedule->hari], [[
                    'subject_id' => $schedule->subject->id,
                    'subject_name' => $schedule->subject->nama_pelajaran,
                    'guru_name' => $schedule->subject->guru ? $schedule->subject->guru->nama : 'Belum ditentukan',
                    'jam_mulai' => $schedule->jam->jam_mulai,
                    'jam_selesai' => $schedule->jam->jam_selesai,
                ]]);
            }
        }

        // Sort each day by jam_mulai
        $grouped = $grouped->map(function ($items) {
            return collect($items)->sortBy('jam_mulai')->values();
        });

        return response()->json([
            'status' => 'success',
            'data' => $grouped
        ]);
    }

    public function getGrades(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'siswa') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $siswa = Siswa::where('user_id', $user->id)->first();
        if (!$siswa) {
            return response()->json(['status' => 'error', 'message' => 'Siswa record not found'], 404);
        }

        $sessions = CbtSession::with(['cbt.subject'])
            ->where('siswa_id', $siswa->id)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

        $grades = $sessions->map(function ($session) {
            return [
                'id' => $session->id,
                'cbt_name' => $session->cbt->nama_cbt,
                'subject_name' => $session->cbt->subject->nama_pelajaran,
                'skor' => $session->skor,
                'skor_maksimal' => $session->cbt->skor_maksimal,
                'tanggal' => $session->cbt->tanggal->format('Y-m-d'),
                'created_at' => $session->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $grades
        ]);
    }

    public function submitAttendance(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'status' => 'required|in:H,A,S,I',
        ]);

        $user = $request->user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        if (!$siswa) {
            return response()->json(['status' => 'error', 'message' => 'Data siswa tidak ditemukan'], 404);
        }
        
        $today = Carbon::now('Asia/Jakarta');
        $dayName = $this->translateDay($today->format('l'));

        // Verify schedule exists for today at this time
        $schedule = Schedule::with('jam')
            ->where('kelas_id', $siswa->kelas_id)
            ->where('subject_id', $request->subject_id)
            ->where('hari', $dayName)
            ->first();

        if (!$schedule) {
            return response()->json(['status' => 'error', 'message' => 'Jadwal tidak ditemukan hari ini'], 404);
        }

        // Check time window
        $now = Carbon::now('Asia/Jakarta')->format('H:i');
        $startTime = Carbon::parse($schedule->jam->jam_mulai)->format('H:i');
        $endTime = Carbon::parse($schedule->jam->jam_selesai)->format('H:i');

        if ($now < $startTime || $now > $endTime) {
            return response()->json([
                'status' => 'error', 
                'message' => "Bukan jam pelajaran ini (Jam: $now, Jadwal: $startTime - $endTime, Hari: $dayName)",
            ], 400);
        }

        // Create or Update attendance
        $absensi = Absensi::updateOrCreate(
            [
                'siswa_id' => $siswa->id,
                'subject_id' => $request->subject_id,
                'tanggal' => $today->toDateString(),
            ],
            [
                'school_id' => $siswa->school_id,
                'status' => $request->status, // Store H, A, S, I
                'keterangan' => 'Absensi Mandiri via App',
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Absensi berhasil dikirim',
            'data' => $absensi
        ]);
    }

    private function translateDay($day)
    {
        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        return $days[$day] ?? $day;
    }
}
