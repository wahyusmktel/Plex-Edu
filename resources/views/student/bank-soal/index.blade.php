@extends('layouts.app')

@section('title', 'Bank Soal Publik - Literasia')

@section('content')
<div class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Bank Soal Publik</h1>
            <p class="text-slate-500 font-medium mt-1">Kumpulan bank soal yang bisa kamu gunakan untuk belajar mandiri</p>
        </div>
        <div class="px-6 py-3 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center">
                <i class="material-icons">inventory_2</i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">TOTAL</p>
                <p class="text-sm font-bold text-slate-700 mt-1">{{ $bankSoal->count() }} Bank Soal</p>
            </div>
        </div>
    </div>

    <!-- Bank Soal Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($bankSoal as $item)
        <a href="{{ route('student.bank-soal.show', $item->id) }}" class="group bg-white rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:border-orange-100 hover:-translate-y-1 transition-all p-8 flex flex-col justify-between overflow-hidden relative">
            <!-- Decorative Accent -->
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-orange-50 to-transparent rounded-bl-full opacity-50"></div>
            
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="material-icons text-2xl">menu_book</i>
                    </div>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-500 text-[10px] font-black rounded-full uppercase tracking-widest">PUBLIK</span>
                </div>
                
                <h3 class="text-lg font-black text-slate-800 tracking-tight leading-snug group-hover:text-orange-600 transition-colors line-clamp-2">{{ $item->title }}</h3>
                
                <div class="mt-4 flex flex-col gap-2">
                    <div class="flex items-center gap-2 text-slate-400">
                        <i class="material-icons text-sm">subject</i>
                        <span class="text-xs font-bold">{{ $item->subject->nama_pelajaran ?? 'Mata Pelajaran' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-400">
                        <i class="material-icons text-sm">person</i>
                        <span class="text-xs font-bold">{{ $item->teacher->nama ?? 'Guru' }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="material-icons text-sm text-slate-300">format_list_bulleted</i>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $item->questions->count() }} Soal</span>
                </div>
                <div class="w-8 h-8 rounded-full bg-slate-50 text-slate-300 flex items-center justify-center group-hover:bg-orange-500 group-hover:text-white transition-all">
                    <i class="material-icons text-sm">arrow_forward</i>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-100 flex flex-col items-center text-center">
            <div class="w-20 h-20 bg-slate-50 text-slate-200 rounded-[2rem] flex items-center justify-center mb-6">
                <i class="material-icons text-4xl">inventory_2</i>
            </div>
            <h4 class="text-lg font-black text-slate-400 uppercase tracking-widest">Bank Soal Belum Tersedia</h4>
            <p class="text-slate-400 text-sm mt-2">Belum ada bank soal yang dipublikasikan untuk saat ini.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
