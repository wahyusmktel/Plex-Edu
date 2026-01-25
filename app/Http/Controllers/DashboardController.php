<?php

namespace App\Http\Controllers;

use App\Models\LibraryItem;
use App\Models\Siswa;
use App\Models\User;
use App\Models\ELearning;
use App\Models\BankSoal;
use App\Models\Forum;
use App\Models\ForumTopic;
use App\Models\PelanggaranSiswa;
use App\Models\Absensi;
use App\Models\Berita;
use App\Models\LibraryLoan;
use App\Models\Pengumuman;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'dinas') {
            // Dinas Dashboard Statistics
            $totalSchools = \App\Models\School::count();
            $pendingSchools = \App\Models\School::where('status', 'pending')->count();
            $activeSchools = \App\Models\School::where('status', 'approved')->where('is_active', true)->count();
            
            // Aggregated counts across all schools
            // We use withoutGlobalScopes() to get total numbers across all tenants
            $totalSiswaAcrossSchools = Siswa::withoutGlobalScopes()->count();
            $totalGuruAcrossSchools = \App\Models\Fungsionaris::withoutGlobalScopes()
                ->where('jabatan', 'guru')
                ->whereNotNull('nik')
                ->where('nik', '!=', '')
                ->distinct()
                ->count('nik');
            
            // Latest registrations for quick look
            $latestRegistrations = \App\Models\School::latest()->take(5)->get();

            // All schools with coordinates for the map
            $schoolsWithLocation = \App\Models\School::whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get(['id', 'nama_sekolah', 'latitude', 'longitude', 'jenjang', 'alamat']);

            return view('admin.dinas.dashboard', compact(
                'totalSchools',
                'pendingSchools',
                'activeSchools',
                'totalSiswaAcrossSchools',
                'totalGuruAcrossSchools',
                'latestRegistrations',
                'schoolsWithLocation'
            ));
        }

        // Regular School Dashboard stats (already scoped via BelongsToSchool)
        // Library stats
        $books = LibraryItem::where('category', 'book')->count();
        $audios = LibraryItem::where('category', 'audio')->count();
        $videos = LibraryItem::where('category', 'video')->count();

        // User stats
        $siswaCount = Siswa::count();
        $guruCount = \App\Models\Fungsionaris::where('jabatan', 'guru')->count();
        $pegawaiCount = \App\Models\Fungsionaris::where('jabatan', 'pegawai')->count();

        // Today's attendance
        $today = Carbon::today();
        
        if ($user->role === 'siswa' && $user->siswa) {
            $siswaIdsInClass = Siswa::where('kelas_id', $user->siswa->kelas_id)->pluck('id');
            $currentTotalSiswa = $siswaIdsInClass->count();
            $hadirCount = Absensi::whereIn('siswa_id', $siswaIdsInClass)
                ->whereDate('tanggal', $today)
                ->whereIn('status', ['H', 'hadir'])
                ->distinct('siswa_id')
                ->count();
        } else {
            $currentTotalSiswa = $siswaCount;
            $hadirCount = Absensi::whereDate('tanggal', $today)
                ->whereIn('status', ['H', 'hadir'])
                ->distinct('siswa_id')
                ->count();
        }

        $denominator = $currentTotalSiswa > 0 ? $currentTotalSiswa : 1;
        $absenCount = max(0, $currentTotalSiswa - $hadirCount);
        $hadirPercentage = round(($hadirCount / $denominator) * 100);

        // Info items
        $eLearningCount = ELearning::count();
        $bankSoalCount = BankSoal::count();
        $forumCount = ForumTopic::count();
        $pelanggaranCount = PelanggaranSiswa::whereDate('created_at', $today)->count();
        
        // Latest News & Announcements (Scoped to school)
        $latestNews = Berita::latest()->take(5)->get();
        $latestAnnouncements = Pengumuman::latest()->take(5)->get();

        $schoolName = $user->school->nama_sekolah ?? 'Sekolah Literasia';

        $todaySchedule = collect();
        if ($user->role === 'siswa' && $user->siswa) {
            $days = [
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu'
            ];
            $dayName = $days[Carbon::now()->format('l')];
            
            $todaySchedule = \App\Models\Schedule::with(['subject.guru', 'jam'])
                ->where('kelas_id', $user->siswa->kelas_id)
                ->where('hari', $dayName)
                ->get()
                ->sortBy(function($item) {
                    return $item->jam->jam_mulai;
                });
        }

        return view('dashboard', compact(
            'books',
            'audios',
            'videos',
            'siswaCount',
            'guruCount',
            'pegawaiCount',
            'hadirCount',
            'absenCount',
            'hadirPercentage',
            'eLearningCount',
            'bankSoalCount',
            'forumCount',
            'pelanggaranCount',
            'latestNews',
            'latestAnnouncements',
            'schoolName',
            'todaySchedule'
        ));
    }
}
