@extends('layouts.app_guest')

@section('title', $berita->judul . ' - ' . $app_settings->app_name)

@section('content')
<article class="pt-32 pb-20 px-6 min-h-screen relative overflow-hidden">
    <!-- Decorations -->
    <div class="absolute top-0 right-0 -mr-40 -mt-20 w-[600px] h-[600px] bg-pink-100 rounded-full blur-[100px] opacity-40 -z-10"></div>
    <div class="absolute bottom-0 left-0 -ml-40 -mb-20 w-[500px] h-[500px] bg-purple-100 rounded-full blur-[100px] opacity-40 -z-10"></div>

    <div class="max-w-4xl mx-auto relative z-10">
        <!-- Breadcrumb & Tag -->
        <div class="flex flex-wrap items-center gap-4 mb-8">
            <a href="{{ url('/') }}" class="text-sm font-bold text-slate-400 hover:text-pink-600 transition-colors flex items-center gap-1">
                <i class="material-icons text-lg">home</i> Beranda
            </a>
            <i class="material-icons text-slate-300 text-sm">chevron_right</i>
            <span class="text-xs font-black text-pink-600 uppercase tracking-widest bg-pink-50 px-3 py-1.5 rounded-lg border border-pink-100">
                Informasi Sekolah
            </span>
        </div>

        <!-- Title -->
        <h1 class="text-4xl lg:text-6xl font-black text-slate-800 leading-[1.1] mb-8 font-outfit">
            {{ $berita->judul }}
        </h1>

        <!-- Meta Info -->
        <div class="flex flex-wrap items-center gap-6 mb-12 py-6 border-y border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-main flex items-center justify-center text-white shadow-lg">
                    <i class="material-icons text-xl">person</i>
                </div>
                <div class="leading-none">
                    <p class="text-[10px] uppercase font-black text-slate-400 tracking-widest mb-1">Penulis</p>
                    <p class="text-sm font-bold text-slate-700">Administrator</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                    <i class="material-icons text-xl">calendar_today</i>
                </div>
                <div class="leading-none">
                    <p class="text-[10px] uppercase font-black text-slate-400 tracking-widest mb-1">Diterbitkan</p>
                    <p class="text-sm font-bold text-slate-700">{{ date('d F Y', strtotime($berita->tanggal_terbit)) }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                    <i class="material-icons text-xl">schedule</i>
                </div>
                <div class="leading-none">
                    <p class="text-[10px] uppercase font-black text-slate-400 tracking-widest mb-1">Waktu</p>
                    <p class="text-sm font-bold text-slate-700">{{ $berita->jam_terbit }} WIB</p>
                </div>
            </div>
        </div>

        <!-- Featured Image -->
        @if($berita->thumbnail)
            <div class="mb-12 rounded-[2.5rem] overflow-hidden shadow-2xl shadow-slate-200 border-4 border-white">
                <img src="{{ asset('storage/'.$berita->thumbnail) }}" alt="{{ $berita->judul }}" class="w-full h-auto object-cover max-h-[500px]">
            </div>
        @endif

        <!-- Content -->
        <div class="bg-white rounded-[2.5rem] p-8 lg:p-12 shadow-sm border border-slate-100 prose prose-slate max-w-none">
            {!! $berita->deskripsi !!}
        </div>

        <!-- Footer Actions -->
        <div class="mt-16 pt-12 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-3 px-8 py-4 glossy-card rounded-[1.5rem] text-sm font-black text-slate-600 hover:bg-white transition-all group">
                <i class="material-icons text-pink-600 group-hover:-translate-x-1 transition-transform">west</i> Kembali ke Home
            </a>
            
            <div class="flex items-center gap-4">
                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Bagikan Berita:</span>
                <div class="flex gap-2">
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($berita->judul . ' - ' . url()->current()) }}" target="_blank" class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-pink-600 hover:text-white transition-all">
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-blue-600 hover:text-white transition-all">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($berita->judul) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-black hover:text-white transition-all">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</article>

@push('styles')
<style>
    .bg-gradient-main {
        background: linear-gradient(135deg, #d90d8b 0%, #ba80e8 100%);
    }
    .glossy-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
    }
    .prose h2, .prose h3 { font-family: 'Outfit', sans-serif; font-weight: 800; color: #1e293b; margin-top: 2.5rem; }
    .prose p { color: #475569; line-height: 1.8; font-weight: 500; font-size: 1.1rem; margin-bottom: 1.5rem; }
</style>
@endpush
@endsection
