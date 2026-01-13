@extends('layouts.app')

@section('title', 'Arsip Publik - Bank Soal')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">
                <a href="{{ route('bank-soal.index') }}" class="hover:text-indigo-600 transition-colors">BANK SOAL</a>
                <i class="material-icons text-xs">chevron_right</i>
                <span class="text-slate-300">ARSIP PUBLIK</span>
            </nav>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight">ARSIP <span class="text-indigo-600">PUBLIK</span></h1>
            <p class="text-slate-500 font-medium mt-1">Kumpulan soal yang dibagikan oleh guru-guru lain.</p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm mb-8">
        <form action="{{ route('bank-soal.archive') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Mata Pelajaran</label>
                <select name="subject_id" onchange="this.form.submit()" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                    <option value="">Semua Pelajaran</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->nama_pelajaran }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tingkat Kelas</label>
                <select name="level" onchange="this.form.submit()" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                    <option value="">Semua Tingkat</option>
                    <option value="X" {{ request('level') == 'X' ? 'selected' : '' }}>Kelas X</option>
                    <option value="XI" {{ request('level') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                    <option value="XII" {{ request('level') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                </select>
            </div>
            <div class="col-span-2 flex items-end">
                <a href="{{ route('bank-soal.archive') }}" class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-rose-500 transition-colors">
                    RESET FILTER
                </a>
            </div>
        </form>
    </div>

    <!-- Grid List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($bankSoals as $bank)
        <div class="group bg-white rounded-[2.5rem] border border-slate-100 p-8 hover:border-indigo-100 hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-500 flex flex-col relative overflow-hidden">
            <!-- Subject Icon -->
            <div class="w-16 h-16 rounded-[1.5rem] bg-indigo-50 text-indigo-500 flex items-center justify-center mb-6">
                <i class="material-icons text-3xl">public</i>
            </div>

            <div class="flex-grow">
                <p class="text-[10px] font-black text-[#ba80e8] uppercase tracking-[0.2em] mb-2">{{ $bank->subject->nama_pelajaran }} • KELAS {{ $bank->level }}</p>
                <h3 class="text-xl font-black text-slate-800 leading-tight mb-4 group-hover:text-indigo-600 transition-colors line-clamp-2 min-h-[3.5rem]">{{ $bank->title }}</h3>
                
                <div class="space-y-4">
                    <div class="p-4 bg-slate-50 rounded-2xl flex items-center justify-between">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">GURU PENYUSUN</p>
                            <p class="text-xs font-black text-slate-700">{{ $bank->teacher->nama_lengkap ?? 'Administrator' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 py-4 border-t border-slate-50">
                        <div class="text-center bg-white border border-slate-100 rounded-2xl px-4 py-2">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">SOAL</p>
                            <p class="text-lg font-black text-slate-800">{{ count($bank->questions) }}</p>
                        </div>
                        <div class="text-start">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">TERBIT PADA</p>
                            <p class="text-xs font-bold text-slate-600">{{ $bank->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <a href="{{ route('bank-soal.show', $bank->id) }}" class="w-full flex items-center justify-center gap-2 py-4 bg-slate-900 text-white text-[10px] font-black rounded-2xl hover:bg-indigo-600 transition-all uppercase tracking-widest">
                    LIHAT MATERI SOAL
                    <i class="material-icons text-sm">visibility</i>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center bg-white rounded-[2.5rem] border border-slate-100">
            <div class="w-24 h-24 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="material-icons text-5xl">inventory_2</i>
            </div>
            <h3 class="text-xl font-black text-slate-800 mb-2">Belum ada Arsip Publik</h3>
            <p class="text-slate-500 font-medium">Jadilah yang pertama membagikan bank soal secara publik!</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12">
        {{ $bankSoals->links() }}
    </div>
</div>
@endsection
