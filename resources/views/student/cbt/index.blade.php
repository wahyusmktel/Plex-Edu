@extends('layouts.app')

@section('title', 'CBT - Literasia')

@section('content')
<div class="space-y-8" x-data="{ openToken: false }">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Computer Based Test</h1>
            <p class="text-slate-500 font-medium mt-1">Daftar ujian yang tersedia untuk Anda kerjakan semester ini</p>
        </div>
        <button @click="openToken = true" class="px-8 py-3 bg-slate-800 text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg hover:bg-slate-700 transition-all flex items-center gap-2">
            <i class="material-icons text-sm">vpn_key</i>
            Masukkan Token
        </button>
    </div>

    @if(session('info'))
    <div class="p-6 bg-blue-50 border border-blue-100 rounded-[1.5rem] flex items-center gap-4 text-blue-600">
        <i class="material-icons">info</i>
        <p class="text-sm font-bold">{{ session('info') }}</p>
    </div>
    @endif

    @if($errors->any())
    <div class="p-6 bg-rose-50 border border-rose-100 rounded-[1.5rem] flex items-center gap-4 text-rose-600">
        <i class="material-icons">error_outline</i>
        <p class="text-sm font-bold">{{ $errors->first() }}</p>
    </div>
    @endif

    <!-- CBT Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($cbtList as $item)
        @php
            $status = $item->status; // upcoming, completed, ongoing
            $userSession = $item->sessions->first();
            $isFinished = $userSession && $userSession->status == 'completed';
            $isOngoing = $userSession && $userSession->status == 'ongoing';
        @endphp
        <div class="group bg-white rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all p-8 flex flex-col justify-between relative overflow-hidden">
            <!-- Status Badge -->
            <div class="absolute top-6 right-6">
                @if($isFinished)
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-500 text-[9px] font-black rounded-full uppercase tracking-widest">SELESAI</span>
                @elseif($status == 'ongoing')
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-500 text-[9px] font-black rounded-full uppercase tracking-widest animate-pulse">BERLANGSUNG</span>
                @elseif($status == 'upcoming')
                    <span class="px-3 py-1 bg-amber-50 text-amber-500 text-[9px] font-black rounded-full uppercase tracking-widest">AKAN DATANG</span>
                @else
                    <span class="px-3 py-1 bg-slate-50 text-slate-400 text-[9px] font-black rounded-full uppercase tracking-widest">BERAKHIR</span>
                @endif
            </div>

            <div>
                <div class="w-14 h-14 rounded-2xl bg-slate-50 text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-500 flex items-center justify-center transition-all mb-6">
                    <i class="material-icons text-2xl">quiz</i>
                </div>
                
                <h3 class="text-lg font-black text-slate-800 tracking-tight leading-snug group-hover:text-indigo-600 transition-colors line-clamp-2">{{ $item->nama_cbt }}</h3>
                <p class="text-xs text-slate-400 font-bold mt-2 uppercase tracking-widest">{{ $item->subject->nama_pelajaran ?? 'Mata Pelajaran' }}</p>
                
                <div class="mt-6 flex flex-col gap-3">
                    <div class="flex items-center gap-2 text-slate-400">
                        <i class="material-icons text-sm">calendar_today</i>
                        <span class="text-[10px] font-black uppercase tracking-widest">{{ $item->tanggal->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-400">
                        <i class="material-icons text-sm">schedule</i>
                        <span class="text-[10px] font-black uppercase tracking-widest">{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-10">
                @if($isFinished)
                    <a href="{{ route('test.result', $userSession->id) }}" class="w-full py-4 bg-emerald-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-100 flex items-center justify-center gap-2 hover:bg-emerald-600 transition-all">
                        LIHAT HASIL <i class="material-icons text-sm">analytics</i>
                    </a>
                @elseif($status == 'ongoing')
                    <button @click="openToken = true" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-100 flex items-center justify-center gap-2 hover:bg-indigo-700 transition-all">
                        @if($isOngoing) LANJUTKAN UJIAN @else MULAI KERJAKAN @endif <i class="material-icons text-sm">vpn_key</i>
                    </button>
                @elseif($status == 'upcoming')
                    <button disabled class="w-full py-4 bg-slate-50 text-slate-300 rounded-2xl text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-2 cursor-not-allowed">
                        BELUM DIMULAI <i class="material-icons text-sm">lock</i>
                    </button>
                @else
                    <button disabled class="w-full py-4 bg-slate-50 text-slate-300 rounded-2xl text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-2 cursor-not-allowed">
                        SUDAH BERAKHIR <i class="material-icons text-sm">history</i>
                    </button>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-100 flex flex-col items-center text-center">
            <div class="w-20 h-20 bg-slate-50 text-slate-200 rounded-[2rem] flex items-center justify-center mb-6">
                <i class="material-icons text-4xl">quiz</i>
            </div>
            <h4 class="text-lg font-black text-slate-400 uppercase tracking-widest">Belum Ada Ujian</h4>
            <p class="text-slate-400 text-sm mt-2">Tidak ada daftar ujian CBT yang dijadwalkan untuk saat ini.</p>
        </div>
        @endforelse
    </div>

    <!-- Token Entry Modal -->
    <div x-show="openToken" 
         x-cloak
         class="fixed inset-0 z-[9999] flex items-center justify-center p-6"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openToken = false"></div>
        
        <div class="relative w-full max-w-lg bg-white rounded-[3rem] shadow-2xl p-10 md:p-16 space-y-10"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8"
             x-transition:enter-end="opacity-100 translate-y-0">
            
            <button @click="openToken = false" class="absolute top-8 right-10 text-slate-400 hover:text-slate-600">
                <i class="material-icons">close</i>
            </button>

            <!-- Icon & Header -->
            <div class="text-center space-y-4">
                <div class="w-24 h-24 bg-gradient-to-br from-[#ba80e8] to-[#d90d8b] rounded-[2rem] flex items-center justify-center text-white mx-auto shadow-xl shadow-pink-100 mb-6">
                    <i class="material-icons text-5xl">vpn_key</i>
                </div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase leading-none">Akses Ujian</h1>
                <p class="text-slate-400 font-medium">Masukkan 5 digit token akses ujian</p>
            </div>

            <!-- Token Form -->
            <form action="{{ route('test.join') }}" method="POST" class="space-y-8">
                @csrf
                <div class="relative group">
                    <input 
                        type="text" 
                        name="token" 
                        maxlength="5"
                        placeholder="EXAM..." 
                        class="w-full text-center px-6 py-8 bg-slate-50 border-4 border-transparent rounded-[2rem] focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-black text-4xl text-slate-700 tracking-[0.5em] uppercase placeholder:text-slate-200"
                        required
                    >
                </div>

                <button type="submit" class="w-full py-6 bg-slate-800 text-white rounded-[2rem] text-sm font-black shadow-xl hover:bg-slate-700 transition-all uppercase tracking-[0.2em] flex items-center justify-center gap-3">
                    MULAI UJIAN <i class="material-icons text-xl">arrow_forward</i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
