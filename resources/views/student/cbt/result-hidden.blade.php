@extends('layouts.app')

@section('title', 'Ujian Selesai - ' . $session->cbt->nama_cbt)

@section('content')
<div class="max-w-2xl mx-auto space-y-10 py-20">
    
    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-2xl p-10 md:p-16 text-center">
        <div class="w-24 h-24 bg-emerald-50 rounded-[2rem] flex items-center justify-center text-emerald-500 mx-auto mb-8">
            <i class="material-icons text-6xl">check_circle</i>
        </div>
        
        <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase mb-4">Ujian Telah Selesai</h1>
        <p class="text-slate-500 font-medium text-lg mb-2">{{ $session->cbt->nama_cbt }}</p>
        <p class="text-slate-400 font-medium">{{ $session->cbt->subject->nama_pelajaran ?? 'Assessment' }}</p>

        <div class="my-10 py-8 border-y border-slate-100">
            <div class="w-20 h-20 bg-slate-50 rounded-[1.5rem] flex items-center justify-center text-slate-300 mx-auto mb-4">
                <i class="material-icons text-5xl">visibility_off</i>
            </div>
            <p class="text-slate-400 font-bold text-sm leading-relaxed max-w-md mx-auto">
                Hasil ujian Anda telah tercatat di sistem. <br>
                Namun, guru memilih untuk tidak menampilkan hasil secara langsung. <br>
                Silakan hubungi guru untuk informasi lebih lanjut.
            </p>
        </div>

        <a href="{{ route('test.index') }}" class="inline-flex items-center gap-3 px-10 py-5 bg-slate-800 text-white rounded-2xl text-sm font-black uppercase tracking-widest shadow-xl hover:bg-slate-700 transition-all">
            <i class="material-icons">home</i> KEMBALI KE HALAMAN UTAMA
        </a>
    </div>

</div>
@endsection
