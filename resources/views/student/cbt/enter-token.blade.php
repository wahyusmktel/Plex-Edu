@extends('layouts.app')

@section('title', 'Masuk Ujian CBT - Literasia')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="w-full max-w-lg bg-white rounded-[3rem] border border-slate-100 shadow-2xl p-10 md:p-16 space-y-10">
        
        <!-- Icon & Header -->
        <div class="text-center space-y-4">
            <div class="w-24 h-24 bg-gradient-to-br from-[#ba80e8] to-[#d90d8b] rounded-[2rem] flex items-center justify-center text-white mx-auto shadow-xl shadow-pink-100 mb-6">
                <i class="material-icons text-5xl">vpn_key</i>
            </div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase">Akses Ujian CBT</h1>
            <p class="text-slate-400 font-medium">Masukkan 5 digit token akses untuk memulai ujian Anda.</p>
        </div>

        @if(session('info'))
        <div class="p-6 bg-blue-50 border border-blue-100 rounded-[1.5rem] flex items-center gap-4 text-blue-600">
            <i class="material-icons">info</i>
            <p class="text-sm font-bold">{{ session('info') }}</p>
        </div>
        @endif

        @if($errors->any())
        <div class="p-6 bg-rose-50 border border-rose-100 rounded-[1.5rem] flex items-center gap-4 text-rose-600 animate-shake">
            <i class="material-icons">error_outline</i>
            <p class="text-sm font-bold">{{ $errors->first() }}</p>
        </div>
        @endif

        <!-- Token Form -->
        <form action="{{ route('test.join') }}" method="POST" class="space-y-8">
            @csrf
            <div class="relative group">
                <input 
                    type="text" 
                    name="token" 
                    maxlength="5"
                    placeholder="E X A M 1" 
                    class="w-full text-center px-6 py-8 bg-slate-50 border-4 border-transparent rounded-[2rem] focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-black text-4xl text-slate-700 tracking-[0.5em] uppercase placeholder:text-slate-200"
                    required
                    autofocus
                >
                <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-32 h-1 bg-[#ba80e8] rounded-full opacity-0 group-focus-within:opacity-100 transition-opacity"></div>
            </div>

            <button type="submit" class="w-full py-6 bg-slate-800 text-white rounded-[2rem] text-sm font-black shadow-xl hover:bg-slate-700 hover:scale-[1.02] active:scale-[0.98] transition-all uppercase tracking-[0.2em] flex items-center justify-center gap-3">
                MULAI UJIAN SEKARANG <i class="material-icons text-xl">arrow_forward</i>
            </button>
        </form>

        <div class="pt-6 border-t border-slate-50 text-center">
            <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest leading-relaxed">
                PASTIKAN KONEKSI INTERNET STABIL SEBELUM MEMULAI.<br>
                SISTEM AKAN MENCATAT WAKTU MULAI DAN SELESAI ANDA SECARA OTOMATIS.
            </p>
        </div>
    </div>
</div>

<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }
    .animate-shake {
        animation: shake 0.5s ease-in-out;
    }
</style>
@endsection
