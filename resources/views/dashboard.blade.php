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
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Sekolah Literasia Edutekno Digital</h1>
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
    <x-stat-card icon="book" label="E-Book" value="1,921" color="pink" />
    <x-stat-card icon="music_note" label="Audio Book" value="733" color="purple" />
    <x-stat-card icon="play_circle" label="Video Book" value="745" color="yellow" />
    <x-stat-card icon="people" label="Siswa" value="3,120" color="blue" />
    <x-stat-card icon="person" label="Guru" value="142" color="red" />
    <x-stat-card icon="badge" label="Pegawai" value="56" color="cyan" />
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
            <select class="bg-slate-50 border border-slate-100 text-slate-500 text-sm font-bold rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-pink-100 transition-all cursor-pointer">
                <option>Minggu Ini</option>
                <option>Bulan Ini</option>
                <option>Tahun Ini</option>
            </select>
        </div>
        
        <div class="flex flex-col md:flex-row items-center justify-around gap-12 py-6">
            <!-- Semi-Donut Placeholder (Tailwind/SVG) -->
            <div class="relative flex items-center justify-center w-64 h-64">
                <svg class="w-full h-full transform -rotate-90">
                    <circle cx="128" cy="128" r="100" stroke="#f1f5f9" stroke-width="24" fill="transparent" />
                    <circle cx="128" cy="128" r="100" stroke="#d90d8b" stroke-width="24" stroke-dasharray="628" stroke-dashoffset="125" stroke-linecap="round" fill="transparent" class="transition-all duration-1000 ease-out" />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                    <span class="text-4xl font-black text-slate-800 tracking-tighter">80%</span>
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
                        <span class="text-sm font-black text-slate-800">2,496</span>
                    </div>
                    <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                        <div class="w-[80%] h-full bg-[#d90d8b] rounded-full"></div>
                    </div>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-slate-300"></span>
                            <span class="text-sm font-bold text-slate-600">Absen</span>
                        </div>
                        <span class="text-sm font-black text-slate-800">624</span>
                    </div>
                    <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                        <div class="w-[20%] h-full bg-slate-400 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Cards Section -->
    <div class="lg:col-span-4 space-y-6">
        <x-info-item icon="cast_for_education" label="Materi E-Learning" value="128" color="yellow" />
        <x-info-item icon="account_balance" label="Bank Soal" value="45" color="blue" />
        <x-info-item icon="forum" label="Postingan Forum" value="12" color="purple" />
        <x-info-item icon="report_problem" label="Pelanggaran" value="3" color="red" />
    </div>
</div>
@endsection
