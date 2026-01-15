@extends('layouts.app')

@section('title', 'Dashboard - Literasia')

@section('content')
<!-- Header Section -->
<div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div class="flex items-center gap-5">
        <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-[#d90d8b]">
            <i class="material-icons text-3xl">school</i>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">UNIT PENDIDIKAN</p>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ $schoolName }}</h1>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <span class="px-4 py-2 bg-emerald-50 text-emerald-600 text-xs font-bold rounded-lg border border-emerald-100 flex items-center gap-2">
            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
            SISTEM ONLINE
        </span>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-10">
    <x-stat-card icon="book" label="E-Book" value="{{ number_format($books) }}" color="pink" />
    <x-stat-card icon="music_note" label="Audio Book" value="{{ number_format($audios) }}" color="purple" />
    <x-stat-card icon="play_circle" label="Video Book" value="{{ number_format($videos) }}" color="yellow" />
    <x-stat-card icon="people" label="Siswa" value="{{ number_format($siswaCount) }}" color="blue" />
    <x-stat-card icon="person" label="Guru" value="{{ number_format($guruCount) }}" color="red" />
    <x-stat-card icon="badge" label="Pegawai" value="{{ number_format($pegawaiCount) }}" color="cyan" />
</div>

<!-- Main Section Grid -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Chart Section -->
    <div class="lg:col-span-8 bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">Absensi Siswa</h3>
                <p class="text-sm text-slate-400 font-medium">Statistik kehadiran murid hari ini</p>
            </div>
            <span class="px-4 py-2 bg-slate-50 text-slate-500 text-sm font-bold rounded-xl border border-slate-100">
                {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}
            </span>
        </div>
        
        <div class="flex flex-col md:flex-row items-center justify-around gap-12 py-6">
            <!-- Semi-Donut Chart -->
            @php
                $strokeDashoffset = 628 - (628 * $hadirPercentage / 100);
            @endphp
            <div class="relative flex items-center justify-center w-64 h-64">
                <svg class="w-full h-full transform -rotate-90">
                    <circle cx="128" cy="128" r="100" stroke="#f1f5f9" stroke-width="24" fill="transparent" />
                    <circle cx="128" cy="128" r="100" stroke="#d90d8b" stroke-width="24" stroke-dasharray="628" stroke-dashoffset="{{ $strokeDashoffset }}" stroke-linecap="round" fill="transparent" class="transition-all duration-1000 ease-out" />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                    <span class="text-4xl font-black text-slate-800 tracking-tighter">{{ $hadirPercentage }}%</span>
                    <span class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-1">Hadir</span>
                </div>
            </div>

            <!-- Chart Legend -->
            <div class="space-y-6 w-full max-w-[240px]">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#d90d8b]"></span>
                            <span class="text-sm font-bold text-slate-600">Hadir</span>
                        </div>
                        <span class="text-sm font-black text-slate-800">{{ number_format($hadirCount) }}</span>
                    </div>
                    <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-[#d90d8b] rounded-full transition-all duration-700" style="width: {{ $hadirPercentage }}%"></div>
                    </div>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-slate-300"></span>
                            <span class="text-sm font-bold text-slate-600">Absen</span>
                        </div>
                        <span class="text-sm font-black text-slate-800">{{ number_format($absenCount) }}</span>
                    </div>
                    <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-slate-400 rounded-full transition-all duration-700" style="width: {{ 100 - $hadirPercentage }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Cards Section -->
    <div class="lg:col-span-4 space-y-6">
        <x-info-item icon="cast_for_education" label="Materi E-Learning" value="{{ number_format($eLearningCount) }}" color="yellow" />
        <x-info-item icon="account_balance" label="Bank Soal" value="{{ number_format($bankSoalCount) }}" color="blue" />
        <x-info-item icon="forum" label="Postingan Forum" value="{{ number_format($forumCount) }}" color="purple" />
        <x-info-item icon="report_problem" label="Pelanggaran Hari Ini" value="{{ number_format($pelanggaranCount) }}" color="red" />
    </div>
</div>

<!-- News & Announcements -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-10">
    <!-- Announcements -->
    <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-xl font-extrabold text-slate-800 flex items-center gap-3">
                <i class="material-icons text-yellow-500">campaign</i>
                Pengumuman Terbaru
            </h3>
            <a href="{{ route('pengumuman.index') }}" class="text-[#d90d8b] text-sm font-bold hover:underline">Lihat Semua</a>
        </div>
        <div class="space-y-4">
            @forelse($latestAnnouncements as $ann)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 group hover:bg-white hover:shadow-md transition-all">
                    <p class="text-[10px] font-black text-[#d90d8b] uppercase tracking-widest mb-1">{{ $ann->created_at->translatedFormat('d M Y') }}</p>
                    <h4 class="text-sm font-bold text-slate-800 line-clamp-1">{{ $ann->title }}</h4>
                </div>
            @empty
                <p class="text-center py-8 text-slate-400 font-medium italic">Belum ada pengumuman.</p>
            @endforelse
        </div>
    </div>

    <!-- News -->
    <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-xl font-extrabold text-slate-800 flex items-center gap-3">
                <i class="material-icons text-blue-500">article</i>
                Berita Terbaru
            </h3>
            <a href="{{ route('berita.index') }}" class="text-[#d90d8b] text-sm font-bold hover:underline">Lihat Semua</a>
        </div>
        <div class="space-y-4">
            @forelse($latestNews as $item)
                <div class="flex gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100 group hover:bg-white hover:shadow-md transition-all">
                    <img src="{{ Storage::url($item->thumbnail) }}" class="w-16 h-16 rounded-xl object-cover" alt="">
                    <div>
                        <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1">{{ $item->created_at->diffForHumans() }}</p>
                        <h4 class="text-sm font-bold text-slate-800 line-clamp-1">{{ $item->judul }}</h4>
                    </div>
                </div>
            @empty
                <p class="text-center py-8 text-slate-400 font-medium italic">Belum ada berita.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
