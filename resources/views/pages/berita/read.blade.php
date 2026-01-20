@extends('layouts.app')

@section('title', $berita->judul . ' - Literasia')

@section('styles')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .text-gradient {
        background: linear-gradient(to right, #ba80e8, #d90d8b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    
    <!-- Navigation & Action -->
    <div class="flex items-center justify-between">
        <a href="{{ route('berita.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white hover:bg-slate-50 text-slate-600 rounded-2xl text-sm font-bold border border-slate-100 transition-all shadow-sm">
            <i class="material-icons text-[20px]">arrow_back</i> Kembali
        </a>
    </div>

    <!-- Article Content -->
    <article class="bg-white rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
        <!-- Thumbnail Illustration/Header -->
        @if($berita->thumbnail)
        <div class="relative h-[400px] w-full overflow-hidden">
            <img src="{{ Storage::url($berita->thumbnail) }}" alt="{{ $berita->judul }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
            <div class="absolute bottom-8 left-10 right-10">
                <div class="flex flex-wrap items-center gap-4 text-white/90 text-[10px] font-black uppercase tracking-[0.2em]">
                    <span class="px-4 py-2 bg-white/20 backdrop-blur-md rounded-xl shadow-sm border border-white/10">
                        {{ \Carbon\Carbon::parse($berita->tanggal_terbit)->translatedFormat('d F Y') }}
                    </span>
                    <span class="px-4 py-2 bg-white/20 backdrop-blur-md rounded-xl shadow-sm border border-white/10">
                        {{ \Carbon\Carbon::parse($berita->jam_terbit)->format('H:i') }} WIB
                    </span>
                </div>
            </div>
        </div>
        @else
        <div class="h-40 bg-gradient-to-br from-[#ba80e8] to-[#d90d8b] opacity-10"></div>
        @endif

        <div class="p-8 md:p-14 space-y-10">
            <!-- Title & Meta -->
            <div class="space-y-6">
                <h1 class="text-4xl md:text-5xl font-black text-slate-800 leading-tight tracking-tight">
                    {{ $berita->judul }}
                </h1>
                
                <div class="flex items-center gap-4 pt-4 border-t border-slate-50">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($berita->user->name) }}&background=ba80e8&color=fff&size=100" class="w-12 h-12 rounded-2xl shadow-sm">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Ditulis Oleh</p>
                        <p class="text-sm font-bold text-slate-700 mt-1">{{ $berita->user->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="prose prose-slate prose-lg max-w-none prose-img:rounded-[2rem] prose-headings:font-black prose-headings:text-slate-800 prose-p:text-slate-600 prose-p:leading-relaxed prose-a:text-[#d90d8b] prose-a:font-bold prose-strong:text-slate-800">
                {!! $berita->deskripsi !!}
            </div>
        </div>
    </article>

    <!-- Footer Decoration -->
    <div class="text-center py-10">
        <div class="w-1.5 h-1.5 bg-slate-200 rounded-full mx-auto mb-4"></div>
        <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Akhir dari Berita</p>
    </div>
</div>
@endsection
