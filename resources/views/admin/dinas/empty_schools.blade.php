@extends('layouts.app')

@section('title', 'Sekolah Tanpa Siswa - Literasia')

@section('content')
<div class="space-y-8">
    
    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 shadow-lg shadow-amber-100 flex items-center justify-center text-white">
                    <i class="material-icons text-2xl">warning</i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-800 tracking-tight">Sekolah Tanpa Siswa</h1>
                    <p class="text-slate-500 font-medium text-sm">Daftar sekolah yang belum memiliki data siswa.</p>
                </div>
            </div>
        </div>
        
        <div class="flex gap-3">
            <a href="{{ route('dinas.siswa') }}" class="flex items-center gap-2 px-6 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
                <i class="material-icons text-[20px]">arrow_back</i> Kembali
            </a>
        </div>
    </div>

    <!-- Stats & Filter Card -->
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6">
        <div class="flex flex-col lg:flex-row items-end gap-6">
            <!-- Jenjang Filter -->
            <div class="w-full lg:w-1/4">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Filter Jenjang</label>
                <form action="{{ route('dinas.siswa.empty-schools') }}" method="GET" class="flex gap-2" id="filterForm">
                    <select 
                        name="jenjang" 
                        onchange="document.getElementById('filterForm').submit()"
                        class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-amber-100 transition-all"
                    >
                        <option value="">Semua Jenjang</option>
                        <option value="sd" {{ ($selectedJenjang ?? '') == 'sd' ? 'selected' : '' }}>SD</option>
                        <option value="smp" {{ ($selectedJenjang ?? '') == 'smp' ? 'selected' : '' }}>SMP</option>
                        <option value="sma_smk" {{ ($selectedJenjang ?? '') == 'sma_smk' ? 'selected' : '' }}>SMA/SMK</option>
                    </select>
                </form>
            </div>

            <!-- Search -->
            <div class="w-full lg:w-2/4">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Cari Sekolah</label>
                <form action="{{ route('dinas.siswa.empty-schools') }}" method="GET" class="flex gap-2">
                    @if($selectedJenjang)
                    <input type="hidden" name="jenjang" value="{{ $selectedJenjang }}">
                    @endif
                    <div class="relative flex-grow">
                        <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-300">search</i>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ $search ?? '' }}"
                            placeholder="Cari nama sekolah atau NPSN..." 
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl pl-12 pr-6 py-4 text-sm font-bold text-slate-700 placeholder-slate-300 outline-none focus:ring-2 focus:ring-amber-100"
                        >
                    </div>
                    <button type="submit" class="px-6 py-4 bg-slate-800 text-white rounded-2xl text-sm font-bold hover:bg-slate-900 transition-all">
                        Cari
                    </button>
                </form>
            </div>
            
            <div class="flex-grow w-full lg:w-1/4">
                <div class="p-4 rounded-3xl bg-amber-50 border border-amber-100">
                    <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest leading-none mb-2">Total Tanpa Siswa</p>
                    <p class="text-xl font-black text-slate-800 leading-none">{{ $totalEmptySchools }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Schools Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($schools as $school)
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 hover:shadow-md transition-all group">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500 group-hover:scale-110 transition-transform">
                    <i class="material-icons text-xl">school</i>
                </div>
                <span class="px-3 py-1 bg-slate-50 border border-slate-100 rounded-xl text-[10px] font-black text-slate-400 uppercase">
                    {{ strtoupper($school->jenjang == 'sma_smk' ? 'SMA/SMK' : $school->jenjang) }}
                </span>
            </div>
            
            <h3 class="text-sm font-black text-slate-800 leading-tight mb-1 line-clamp-2">{{ $school->nama_sekolah }}</h3>
            <p class="text-[11px] text-slate-400 font-bold mb-4">NPSN: {{ $school->npsn }}</p>
            
            <div class="flex items-center gap-2 text-[10px] text-slate-400 font-medium mb-4">
                <i class="material-icons text-[14px]">location_on</i>
                <span class="line-clamp-1">{{ $school->kabupaten_kota ?? $school->kecamatan ?? '-' }}</span>
            </div>
            
            <a 
                href="{{ route('dinas.siswa', ['school_id' => $school->id]) }}" 
                class="flex items-center justify-center gap-2 w-full py-3 bg-amber-50 border border-amber-100 rounded-xl text-xs font-bold text-amber-600 hover:bg-amber-100 transition-all"
            >
                <i class="material-icons text-[16px]">file_upload</i> Import Siswa
            </a>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-20 text-center">
                <div class="w-24 h-24 bg-emerald-50 rounded-3xl flex items-center justify-center text-emerald-400 mx-auto mb-6">
                    <i class="material-icons text-6xl">check_circle</i>
                </div>
                <h2 class="text-2xl font-black text-slate-800">Semua Sekolah Sudah Memiliki Siswa!</h2>
                <p class="text-slate-400 font-medium max-w-md mx-auto mt-2">Tidak ada sekolah yang belum memiliki data siswa.</p>
            </div>
        </div>
        @endforelse
    </div>

    @if($schools->hasPages())
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm px-8 py-6">
        {{ $schools->links() }}
    </div>
    @endif

</div>
@endsection
