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
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
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
        $absenCount = $totalSiswa - $hadirCount;
        $hadirPercentage = $totalSiswa > 0 ? round(($hadirCount / $totalSiswa) * 100) : 0;

        // Info items
        $eLearningCount = ELearning::count();
        $bankSoalCount = BankSoal::count();
        $forumCount = ForumTopic::count();
        $pelanggaranCount = PelanggaranSiswa::whereDate('created_at', $today)->count();

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
            'pelanggaranCount'
        ));
    }
}
