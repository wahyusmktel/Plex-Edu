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
            $totalGuruAcrossSchools = User::withoutGlobalScopes()->where('role', 'guru')->count();
            
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
        $guruCount = User::where('role', 'guru')->count();
        $pegawaiCount = User::whereIn('role', ['pegawai', 'admin', 'staff'])->count();

        // Today's attendance
        $today = Carbon::today();
        $totalSiswa = $siswaCount > 0 ? $siswaCount : 1; // Prevent division by zero
        $hadirCount = Absensi::whereDate('tanggal', $today)->where('status', 'hadir')->count();
        $absenCount = max(0, $totalSiswa - $hadirCount);
        $hadirPercentage = $totalSiswa > 0 ? round(($hadirCount / $totalSiswa) * 100) : 0;

        // Info items
        $eLearningCount = ELearning::count();
        $bankSoalCount = BankSoal::count();
        $forumCount = ForumTopic::count();
        $pelanggaranCount = PelanggaranSiswa::whereDate('created_at', $today)->count();
        
        // Latest News & Announcements (Scoped to school)
        $latestNews = Berita::latest()->take(5)->get();
        $latestAnnouncements = Pengumuman::latest()->take(5)->get();

        $schoolName = $user->school->nama_sekolah ?? 'Sekolah Literasia';

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
            'schoolName'
        ));
    }
}
