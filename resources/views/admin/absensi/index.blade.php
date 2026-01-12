@extends('layouts.app')

@section('title', 'Rekap Absensi - Literasia')

@section('styles')
<style>
    .status-badge {
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        padding: 4px 10px;
        border-radius: 12px;
    }
    .status-H { background: #ecfdf5; color: #10b981; }
    .status-A { background: #fef2f2; color: #ef4444; }
    .status-S { background: #eff6ff; color: #3b82f6; }
    .status-I { background: #fffbeb; color: #f59e0b; }
</style>
@endsection

@section('content')
<div class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Rekap Absensi Siswa</h1>
            <p class="text-slate-500 font-medium mt-1">Pantau kehadiran siswa berdasarkan kelas dan periode tertentu</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('absensi.export.all') }}?format=pdf&start_date={{ $startDate }}&end_date={{ $endDate }}" class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl text-sm font-bold hover:bg-slate-50 transition-all">
                <i class="material-icons text-[20px]">print</i> Cetak Seluruh Kelas
            </a>
            @if($selectedClass)
            <div class="flex items-center gap-3">
                <a href="{{ route('absensi.export.class') }}?format=excel&kelas_id={{ $selectedClass }}&start_date={{ $startDate }}&end_date={{ $endDate }}" class="flex items-center gap-2 px-6 py-3 bg-emerald-500 text-white rounded-2xl text-sm font-bold shadow-lg shadow-emerald-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    <i class="material-icons text-[20px]">description</i> Excel Kelas Ini
                </a>
                <a href="{{ route('absensi.export.class') }}?format=pdf&kelas_id={{ $selectedClass }}&start_date={{ $startDate }}&end_date={{ $endDate }}" class="flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl text-sm font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    <i class="material-icons text-[20px]">picture_as_pdf</i> PDF Kelas Ini
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Filter Area -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8">
        <form action="{{ route('absensi.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <!-- Class Selection -->
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Kelas</label>
                <div class="relative">
                    <select name="kelas_id" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-[#ba80e8]/20 transition-all appearance-none cursor-pointer">
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>{{ $class->nama }}</option>
                        @endforeach
                    </select>
                    <i class="material-icons absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</i>
                </div>
            </div>

            <!-- Date Range -->
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-[#ba80e8]/20 transition-all">
            </div>
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-[#ba80e8]/20 transition-all">
            </div>

            <!-- Search & Actions -->
            <div class="flex gap-3">
                <div class="relative flex-grow group">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search }}" 
                        placeholder="Nama siswa..." 
                        class="w-full bg-slate-50 border-none rounded-2xl pl-12 pr-6 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-[#ba80e8]/20 transition-all outline-none"
                    >
                    <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#ba80e8] transition-colors">search</i>
                </div>
                <button type="submit" class="p-3.5 bg-slate-800 text-white rounded-2xl hover:bg-slate-900 transition-all shadow-lg">
                    <i class="material-icons">filter_list</i>
                </button>
            </div>
        </form>
    </div>

    <!-- Stats Overview (Optional, for premium feel) -->
    @if($selectedClass)
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @php
            $totalH = collect($recap)->sum('H');
            $totalA = collect($recap)->sum('A');
            $totalS = collect($recap)->sum('S');
            $totalI = collect($recap)->sum('I');
        @endphp
        <div class="bg-white rounded-[2rem] p-6 border border-slate-50 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center">
                <span class="font-black text-lg">H</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Total Hadir</p>
                <p class="text-xl font-black text-slate-800 mt-1">{{ $totalH }}</p>
            </div>
        </div>
        <div class="bg-white rounded-[2rem] p-6 border border-slate-50 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center">
                <span class="font-black text-lg">A</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Total Alfa</p>
                <p class="text-xl font-black text-slate-800 mt-1">{{ $totalA }}</p>
            </div>
        </div>
        <div class="bg-white rounded-[2rem] p-6 border border-slate-50 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center">
                <span class="font-black text-lg">S</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Total Sakit</p>
                <p class="text-xl font-black text-slate-800 mt-1">{{ $totalS }}</p>
            </div>
        </div>
        <div class="bg-white rounded-[2rem] p-6 border border-slate-50 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center">
                <span class="font-black text-lg">I</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Total Izin</p>
                <p class="text-xl font-black text-slate-800 mt-1">{{ $totalI }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Attendance Table -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Siswa & Kelas</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Hadir</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Alfa</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Sakit</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Izin</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Cetak Per Siswa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recap as $item)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-8 py-5">
                            <p class="font-black text-slate-800 tracking-tight">{{ $item['nama'] }}</p>
                            <p class="text-xs text-[#ba80e8] font-bold mt-0.5">{{ $item['kelas'] }}</p>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="text-sm font-black text-emerald-500 bg-emerald-50 px-3 py-1 rounded-lg">{{ $item['H'] }}</span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="text-sm font-black text-rose-500 bg-rose-50 px-3 py-1 rounded-lg">{{ $item['A'] }}</span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="text-sm font-black text-blue-500 bg-blue-50 px-3 py-1 rounded-lg">{{ $item['S'] }}</span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="text-sm font-black text-amber-500 bg-amber-50 px-3 py-1 rounded-lg">{{ $item['I'] }}</span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('absensi.export.student', $item['id']) }}?format=pdf&start_date={{ $startDate }}&end_date={{ $endDate }}" class="p-2 text-rose-500 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors" title="Cetak PDF">
                                    <i class="material-icons text-lg">picture_as_pdf</i>
                                </a>
                                <a href="{{ route('absensi.export.student', $item['id']) }}?format=excel&start_date={{ $startDate }}&end_date={{ $endDate }}" class="p-2 text-emerald-500 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition-colors" title="Cetak Excel">
                                    <i class="material-icons text-lg">description</i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mx-auto mb-4">
                                <i class="material-icons text-3xl">assignment_ind</i>
                            </div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Data absensi tidak ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $students->appends(request()->input())->links() }}
    </div>

</div>
@endsection
