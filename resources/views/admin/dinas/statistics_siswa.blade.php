@extends('layouts.app')

@section('title', 'Statistik Siswa - Literasia')

@section('content')
<div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div class="flex items-center gap-5">
        <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg shadow-blue-100 flex items-center justify-center text-white">
            <i class="material-icons text-3xl">analytics</i>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Monitoring Siswa</p>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Statistik Siswa Nasional</h1>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Gender Distribution -->
    <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
        <h3 class="text-lg font-extrabold text-slate-800 mb-6">Distribusi Jenis Kelamin</h3>
        <div class="space-y-6">
            @foreach($genderStats as $stat)
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-slate-600">{{ $stat->jenis_kelamin }}</span>
                        <span class="text-sm font-black text-slate-800">{{ number_format($stat->total) }} Siswa</span>
                    </div>
                    <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 rounded-full" style="width: {{ $totalSiswa > 0 ? ($stat->total / $totalSiswa) * 100 : 0 }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Growth -->
    <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
        <h3 class="text-lg font-extrabold text-slate-800 mb-6">Pertumbuhan Registrasi Sekolah</h3>
        <div class="space-y-4">
            @foreach($schoolGrowth as $growth)
                <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">
                        +{{ $growth->total }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($growth->month)->translatedFormat('F Y') }}</p>
                        <p class="text-xs text-slate-400 font-medium">Sekolah baru terdaftar</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="mt-8 bg-blue-600 rounded-[2rem] p-10 text-white relative overflow-hidden">
    <div class="relative z-10">
        <h2 class="text-3xl font-black mb-2">Total Seluruh Siswa</h2>
        <p class="text-5xl font-black tracking-tighter">{{ number_format($totalSiswa) }}</p>
        <p class="mt-4 opacity-80 font-medium">Data terintegrasi dari seluruh unit pendidikan LITERASIA.</p>
    </div>
    <i class="material-icons absolute -right-10 -bottom-10 text-[200px] opacity-10 rotate-12">groups</i>
</div>
@endsection
