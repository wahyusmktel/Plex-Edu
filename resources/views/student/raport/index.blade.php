@extends('layouts.app')

@section('title', 'E-Raport Saya - Literasia')

@section('content')
<div class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">E-Raport Digital</h1>
            <p class="text-slate-500 font-medium mt-1">Arsip laporan hasil belajar digital Anda</p>
        </div>
        <div class="px-6 py-3 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center">
                <i class="material-icons">folder_shared</i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">TOTAL ARSIP</p>
                <p class="text-sm font-bold text-slate-700 mt-1">{{ $raports->count() }} Dokumen</p>
            </div>
        </div>
    </div>

    <!-- Raport List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($raports as $raport)
        <div class="group bg-white rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all p-10 flex flex-col justify-between overflow-hidden relative">
            <!-- Decorative Icon Background -->
            <i class="material-icons absolute -right-4 -bottom-4 text-9xl text-slate-50 opacity-10 group-hover:text-blue-50 transition-colors">description</i>

            <div>
                <div class="flex items-start justify-between mb-8">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="material-icons text-3xl">picture_as_pdf</i>
                    </div>
                    <span class="px-4 py-1.5 bg-slate-50 text-slate-400 text-[10px] font-black rounded-full uppercase tracking-widest transition-all group-hover:bg-blue-500 group-hover:text-white">
                        PDF
                    </span>
                </div>
                
                <h3 class="text-xl font-black text-slate-800 tracking-tight leading-snug group-hover:text-blue-600 transition-colors">Semester {{ $raport->semester }}</h3>
                <p class="text-xs text-slate-400 font-bold mt-2 uppercase tracking-widest">Tahun Pelajaran {{ $raport->tahun_pelajaran }}</p>
                
                <div class="mt-8 space-y-3">
                    <div class="flex items-center gap-3 text-slate-400">
                        <i class="material-icons text-sm">history_edu</i>
                        <span class="text-xs font-bold leading-none">{{ $raport->file_name }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-12">
                <a href="{{ asset('storage/' . $raport->file_path) }}" target="_blank" class="w-full py-5 bg-slate-800 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg hover:bg-blue-600 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                    DOWNLOAD RAPORT <i class="material-icons text-lg">download</i>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-100 flex flex-col items-center text-center">
            <div class="w-20 h-20 bg-slate-50 text-slate-200 rounded-[2rem] flex items-center justify-center mb-6">
                <i class="material-icons text-4xl">folder_off</i>
            </div>
            <h4 class="text-lg font-black text-slate-400 uppercase tracking-widest">Arsip Belum Tersedia</h4>
            <p class="text-slate-400 text-sm mt-2">Belum ada file E-Raport yang diunggah untuk akun Anda.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
