@extends('layouts.app')

@section('title', 'Dinas Dashboard - Literasia')

@section('content')
<!-- Header Section -->
<div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div class="flex items-center gap-5">
        <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br from-[#ba80e8] to-[#d90d8b] shadow-lg shadow-pink-100 flex items-center justify-center text-white">
            <i class="material-icons text-3xl">account_balance</i>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Dinas Pendidikan</p>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Pusat Data Pendidikan LITERASIA</h1>
        </div>
    </div>
</div>

<!-- Central Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <x-stat-card icon="business" label="Total Sekolah" value="{{ number_format($totalSchools) }}" color="blue" />
    <x-stat-card icon="pending_actions" label="Menunggu Persetujuan" value="{{ number_format($pendingSchools) }}" color="yellow" />
    <x-stat-card icon="verified" label="Sekolah Aktif" value="{{ number_format($activeSchools) }}" color="emerald" />
    <x-stat-card icon="groups" label="Total Siswa Nasional" value="{{ number_format($totalSiswaAcrossSchools) }}" color="pink" />
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Registrasi Terbaru -->
    <div class="lg:col-span-8 bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">Registrasi Sekolah Terbaru</h3>
                <p class="text-sm text-slate-400 font-medium">Monitoring pendaftaran sekolah baru</p>
            </div>
            <a href="{{ route('dinas.index') }}" class="px-6 py-2.5 bg-slate-50 text-[#d90d8b] text-sm font-bold rounded-xl border border-slate-100 hover:bg-pink-50 transition-all">
                Lihat Semua
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">
                        <th class="pb-4 px-2">Sekolah</th>
                        <th class="pb-4 px-2">NPSN</th>
                        <th class="pb-4 px-2">Wilayah</th>
                        <th class="pb-4 px-2">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($latestRegistrations as $school)
                    <tr class="group hover:bg-slate-50/50 transition-all">
                        <td class="py-4 px-2">
                            <p class="font-bold text-slate-700 text-sm">{{ $school->nama_sekolah }}</p>
                            <p class="text-[10px] font-medium text-slate-400">Terdaftar {{ $school->created_at->diffForHumans() }}</p>
                        </td>
                        <td class="py-4 px-2 font-mono text-xs font-bold text-slate-500">{{ $school->npsn }}</td>
                        <td class="py-4 px-2 text-xs font-bold text-slate-600">{{ $school->kabupaten_kota }}, {{ $school->provinsi }}</td>
                        <td class="py-4 px-2">
                            @if($school->status === 'pending')
                                <span class="px-3 py-1 bg-yellow-50 text-yellow-600 text-[10px] font-black rounded-full uppercase border border-yellow-100">Pending</span>
                            @elseif($school->status === 'approved')
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-full uppercase border border-emerald-100">Approved</span>
                            @else
                                <span class="px-3 py-1 bg-rose-50 text-rose-600 text-[10px] font-black rounded-full uppercase border border-rose-100">Rejected</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-slate-400 font-bold italic">Belum ada registrasi baru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Stats Summary -->
    <div class="lg:col-span-4 space-y-6">
        <div class="bg-gradient-to-br from-[#ba80e8] to-[#d90d8b] rounded-[2rem] p-8 text-white shadow-lg shadow-pink-100">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center">
                    <i class="material-icons text-white">person</i>
                </div>
                <div>
                    <h4 class="text-sm font-bold opacity-80">Total Guru Nasional</h4>
                    <p class="text-3xl font-black">{{ number_format($totalGuruAcrossSchools) }}</p>
                </div>
            </div>
            <div class="h-1 w-full bg-white/20 rounded-full overflow-hidden">
                <div class="h-full bg-white rounded-full w-3/4"></div>
            </div>
            <p class="text-[10px] font-bold mt-4 opacity-70 tracking-widest uppercase italic">Updated in real-time</p>
        </div>

        <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
            <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6 border-b border-slate-50 pb-4">Panduan Dinas</h4>
            <div class="space-y-4">
                <div class="flex gap-4 group cursor-pointer">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex flex-shrink-0 items-center justify-center group-hover:bg-blue-500 group-hover:text-white transition-all">
                        <i class="material-icons text-xl">help_outline</i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-700 leading-snug">Cara Approval Sekolah Baru</p>
                    </div>
                </div>
                <div class="flex gap-4 group cursor-pointer">
                    <div class="w-10 h-10 rounded-xl bg-yellow-50 text-yellow-500 flex flex-shrink-0 items-center justify-center group-hover:bg-yellow-500 group-hover:text-white transition-all">
                        <i class="material-icons text-xl">summarize</i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-700 leading-snug">Download Laporan Bulanan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
