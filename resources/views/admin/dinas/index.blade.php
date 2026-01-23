@extends('layouts.app')

@section('content')
<div class="px-6 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen <span class="text-pink-600">Sekolah</span></h1>
            <p class="text-slate-500 font-medium mt-1">Monitor, setujui, dan kelola seluruh instansi pendidikan dalam sistem.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="text-right">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Sekolah</p>
                    <p class="text-lg font-black text-slate-800 leading-none">{{ $totalCount }}</p>
                </div>
                <div class="w-10 h-10 bg-pink-50 rounded-xl flex items-center justify-center">
                    <i class="material-icons text-pink-600">school</i>
                </div>
            </div>
            <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="text-right">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Menunggu</p>
                    <p class="text-lg font-black text-amber-600 leading-none">{{ $pendingCount }}</p>
                </div>
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                    <i class="material-icons text-amber-600">pending_actions</i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-sm mb-10">
        <form action="{{ route('dinas.index') }}" method="GET" class="space-y-6">
            <input type="hidden" name="status" value="{{ $status }}">
            
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Search -->
                <div class="space-y-2 lg:col-span-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Cari Sekolah</label>
                    <div class="relative">
                        <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-300">search</i>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Nama Sekolah atau NPSN..." 
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl pl-12 pr-6 py-3.5 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all outline-none"
                        >
                    </div>
                </div>

                <!-- Jenjang -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Jenjang</label>
                    <select name="jenjang" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-3.5 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all outline-none appearance-none cursor-pointer">
                        <option value="">Semua Jenjang</option>
                        <option value="sd" {{ request('jenjang') == 'sd' ? 'selected' : '' }}>SD</option>
                        <option value="smp" {{ request('jenjang') == 'smp' ? 'selected' : '' }}>SMP</option>
                        <option value="sma_smk" {{ request('jenjang') == 'sma_smk' ? 'selected' : '' }}>SMA/SMK</option>
                    </select>
                </div>

                <!-- Kabupaten -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Wilayah (Kab/Kota)</label>
                    <select name="kabupaten" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-3.5 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all outline-none appearance-none cursor-pointer">
                        <option value="">Semua Wilayah</option>
                        @foreach($kabupatens as $kab)
                            <option value="{{ $kab }}" {{ request('kabupaten') == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-slate-50">
                <div class="flex p-1.5 bg-slate-100 rounded-2xl gap-1">
                    <a href="{{ route('dinas.index', array_merge(request()->except('status'), ['status' => ''])) }}" class="{{ !$status ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:bg-slate-50' }} px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Semua</a>
                    <a href="{{ route('dinas.index', array_merge(request()->except('status'), ['status' => 'pending'])) }}" class="{{ $status === 'pending' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:bg-slate-50' }} px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Menunggu ({{ $pendingCount }})</a>
                    <a href="{{ route('dinas.index', array_merge(request()->except('status'), ['status' => 'approved'])) }}" class="{{ $status === 'approved' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:bg-slate-50' }} px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Disetujui</a>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('dinas.index') }}" class="flex items-center gap-2 px-6 py-3 bg-slate-50 text-slate-400 rounded-2xl font-bold text-sm hover:bg-slate-100 hover:text-slate-600 transition-all">
                        <i class="material-icons text-[20px]">refresh</i> Reset
                    </a>
                    <button type="submit" class="flex items-center gap-2 px-8 py-3 bg-pink-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-pink-100 hover:bg-pink-700 transition-all">
                        <i class="material-icons text-[20px]">filter_alt</i> Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- School Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($schools as $school)
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 flex flex-col group hover:shadow-xl hover:shadow-slate-200 transition-all duration-500">
                <div class="flex items-start justify-between mb-8">
                    <div class="w-16 h-16 bg-slate-50 rounded-[1.5rem] flex items-center justify-center text-slate-400 group-hover:bg-pink-50 group-hover:text-pink-600 transition-colors">
                        <i class="material-icons text-3xl">account_balance</i>
                    </div>
                    <div>
                        @if($school->status == 'pending')
                            <span class="px-4 py-2 bg-amber-50 text-amber-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-amber-100 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span> Menunggu
                            </span>
                        @elseif($school->status == 'approved')
                            <span class="px-4 py-2 bg-green-50 text-green-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-green-100 flex items-center gap-2">
                                <i class="material-icons text-[14px]">check_circle</i> Disetujui
                            </span>
                        @else
                            <span class="px-4 py-2 bg-rose-50 text-rose-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-rose-100 flex items-center gap-2">
                                <i class="material-icons text-[14px]">cancel</i> Ditolak
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex-grow">
                    <h3 class="text-xl font-black text-slate-800 mb-2 leading-tight">{{ $school->nama_sekolah }}</h3>
                    <p class="text-sm font-bold text-slate-400 mb-6 flex items-center gap-1"><i class="material-icons text-base">numbers</i> {{ $school->npsn }}</p>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center gap-3">
                            <i class="material-icons text-slate-300">location_on</i>
                            <p class="text-sm font-medium text-slate-500 line-clamp-1">{{ $school->desa_kelurahan }}, {{ $school->kecamatan }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="material-icons text-slate-300">map</i>
                            <p class="text-sm font-medium text-slate-500">{{ $school->kabupaten_kota }}, {{ $school->provinsi }}</p>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-50 flex items-center justify-between gap-4">
                    @if($school->status == 'pending')
                        <form action="{{ route('dinas.approve', $school->id) }}" method="POST" class="flex-grow">
                            @csrf
                            <button type="submit" class="w-full py-4 bg-green-600 text-white rounded-2xl text-xs font-black shadow-lg shadow-green-100 hover:scale-[1.02] transition-transform flex items-center justify-center gap-2">
                                <i class="material-icons text-base">check</i> SETUJUI
                            </button>
                        </form>
                        <form action="{{ route('dinas.reject', $school->id) }}" method="POST" class="flex-grow">
                            @csrf
                            <button type="submit" class="w-full py-4 bg-white text-rose-600 border border-rose-100 rounded-2xl text-xs font-black hover:bg-rose-50 transition-colors flex items-center justify-center gap-2">
                                <i class="material-icons text-base">close</i> TOLAK
                            </button>
                        </form>
                    @else
                        <form action="{{ route('dinas.toggle', $school->id) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" @class([
                                'w-full py-4 rounded-2xl text-xs font-black transition-all flex items-center justify-center gap-2',
                                'bg-slate-100 text-slate-600 hover:bg-slate-200' => $school->is_active,
                                'bg-indigo-600 text-white shadow-lg shadow-indigo-100' => !$school->is_active
                            ])>
                                <i class="material-icons text-base">{{ $school->is_active ? 'block' : 'undo' }}</i>
                                {{ $school->is_active ? 'NONAKTIFKAN' : 'AKTIFKAN KEMBALI' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if($schools->hasPages())
        <div class="mt-12">
            {{ $schools->links() }}
        </div>
    @endif
</div>
@endsection
