@extends('layouts.app')

@section('title', $pengumuman->judul . ' - Literasia')

@section('styles')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
</style>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    
    <!-- Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('pengumuman.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white hover:bg-slate-50 text-slate-600 rounded-2xl text-sm font-bold border border-slate-100 transition-all shadow-sm">
            <i class="material-icons text-[20px]">arrow_back</i> Kembali
        </a>
    </div>

    <!-- Announcement Content -->
    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden relative">
        <!-- Top Status Bar -->
        @php
            $today = \Carbon\Carbon::today();
            $start = \Carbon\Carbon::parse($pengumuman->tanggal_terbit);
            $end = $pengumuman->tanggal_berakhir ? \Carbon\Carbon::parse($pengumuman->tanggal_berakhir) : null;
            
            $status = 'Aktif';
            $statusColor = 'bg-emerald-500';
            
            if ($pengumuman->is_permanen) {
                $status = 'Permanen';
                $statusColor = 'bg-indigo-500';
            } elseif ($today->lt($start)) {
                $status = 'Akan Datang';
                $statusColor = 'bg-blue-500';
            } elseif ($end && $today->gt($end)) {
                $status = 'Kedaluwarsa';
                $statusColor = 'bg-slate-400';
            }
        @endphp
        
        <div class="h-3 w-full {{ $statusColor }}"></div>

        <div class="p-8 md:p-14 space-y-10">
            <!-- Header -->
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-white {{ $statusColor }} shadow-sm">
                        {{ $status }}
                    </span>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">
                        Diterbitkan: {{ \Carbon\Carbon::parse($pengumuman->tanggal_terbit)->translatedFormat('d M Y') }}
                    </p>
                </div>

                <h1 class="text-4xl md:text-5xl font-black text-slate-800 leading-tight tracking-tight">
                    {{ $pengumuman->judul }}
                </h1>
                
                <div class="flex items-center gap-4 pt-4 border-t border-slate-50">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-[#ba80e8] border border-slate-100">
                        <i class="material-icons">campaign</i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Dari</p>
                        <p class="text-sm font-bold text-slate-700 mt-1">{{ $pengumuman->user->name ?? 'Administrator' }}</p>
                    </div>
                </div>
            </div>

            <!-- Announcement Message -->
            <div class="bg-slate-50 rounded-[2.5rem] p-8 md:p-10 border border-slate-100">
                <div class="prose prose-slate prose-lg max-w-none prose-headings:font-black prose-headings:text-slate-800 prose-p:text-slate-600 prose-p:leading-relaxed prose-strong:text-slate-800">
                    {!! $pengumuman->pesan !!}
                </div>
            </div>

            <!-- Footer Details -->
            @if(!$pengumuman->is_permanen && $pengumuman->tanggal_berakhir)
            <div class="pt-6 border-t border-slate-100 flex items-center gap-3 text-slate-400">
                <i class="material-icons text-sm">event_busy</i>
                <p class="text-[11px] font-bold uppercase tracking-widest">Berlaku sampai dengan {{ \Carbon\Carbon::parse($pengumuman->tanggal_berakhir)->translatedFormat('d F Y') }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Footer Decoration -->
    <div class="text-center py-10 text-slate-300">
        <i class="material-icons opacity-20 text-4xl">campaign</i>
    </div>
</div>
@endsection
