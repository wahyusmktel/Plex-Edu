@extends('layouts.app')

@section('title', 'Bank Soal')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight">BANK <span class="text-[#d90d8b]">SOAL</span></h1>
            <p class="text-slate-500 font-medium mt-1">Kelola koleksi soal Anda dan bagikan ke guru lain.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('bank-soal.archive') }}" class="flex items-center gap-2 px-6 py-3 bg-white text-slate-600 font-black rounded-2xl border border-slate-100 shadow-sm hover:border-indigo-100 hover:text-indigo-600 transition-all text-xs uppercase tracking-widest">
                <i class="material-icons text-lg">archive</i>
                ARSIP PUBLIK
            </a>
            <button x-data @click="$dispatch('open-modal', 'create-bank')" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white font-black rounded-2xl shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-95 transition-all text-xs uppercase tracking-widest cursor-pointer">
                <i class="material-icons text-lg">add_circle</i>
                BANK SOAL BARU
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm mb-8">
        <form action="{{ route('bank-soal.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tingkat Kelas</label>
                <select name="level" onchange="this.form.submit()" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                    <option value="">Semua Tingkat</option>
                    @foreach($levels as $lvl)
                        <option value="{{ $lvl }}" {{ request('level') == $lvl ? 'selected' : '' }}>Kelas {{ $lvl }}</option>
                    @endforeach
                </select>
            </div>
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
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Status</label>
                <select name="status" onchange="this.form.submit()" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                    <option value="">Semua Status</option>
                    <option value="private" {{ request('status') == 'private' ? 'selected' : '' }}>Privat</option>
                    <option value="public" {{ request('status') == 'public' ? 'selected' : '' }}>Publik</option>
                </select>
            </div>
            <div class="flex items-end">
                <a href="{{ route('bank-soal.index') }}" class="w-full text-center px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-rose-500 transition-colors">
                    RESET FILTER
                </a>
            </div>
        </form>
    </div>

    <!-- Grid List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($bankSoals as $bank)
        <div class="group bg-white rounded-[2.5rem] border border-slate-100 p-8 hover:border-indigo-100 hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-500 flex flex-col relative overflow-hidden">
            <!-- Status Badge -->
            <div class="absolute top-6 right-6">
                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest 
                    {{ $bank->status === 'public' ? 'bg-emerald-50 text-emerald-500' : 'bg-slate-50 text-slate-400' }}">
                    {{ $bank->status === 'public' ? 'PUBLIK' : 'PRIVAT' }}
                </span>
            </div>

            <!-- Icon & Subject -->
            <div class="w-16 h-16 rounded-[1.5rem] bg-slate-50 text-slate-400 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-all duration-500">
                <i class="material-icons text-3xl">collections_bookmark</i>
            </div>

            <div class="flex-grow">
                <p class="text-[10px] font-black text-[#ba80e8] uppercase tracking-[0.2em] mb-2">{{ $bank->subject->nama_pelajaran }} • KELAS {{ $bank->level }}</p>
                <h3 class="text-xl font-black text-slate-800 leading-tight mb-4 group-hover:text-indigo-600 transition-colors">{{ $bank->title }}</h3>
                
                <div class="flex items-center gap-4 py-4 border-t border-slate-50 mt-4">
                    <div class="text-center bg-slate-50 rounded-2xl px-4 py-2">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">SOAL</p>
                        <p class="text-lg font-black text-slate-800">{{ count($bank->questions) }}</p>
                    </div>
                    <div class="text-start">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">DIPERBARUI</p>
                        <p class="text-xs font-bold text-slate-600">{{ $bank->updated_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 mt-8">
                <a href="{{ route('bank-soal.show', $bank->id) }}" class="flex-grow flex items-center justify-center gap-2 py-4 bg-slate-900 text-white text-[10px] font-black rounded-2xl hover:bg-slate-800 transition-all uppercase tracking-widest">
                    KELOLA SOAL
                    <i class="material-icons text-sm">arrow_forward</i>
                </a>
                <button x-data @click="$dispatch('open-modal', 'edit-bank-{{ $bank->id }}')" class="w-12 h-12 flex items-center justify-center bg-slate-50 text-slate-400 rounded-2xl hover:bg-indigo-50 hover:text-indigo-500 transition-all">
                    <i class="material-icons text-xl">settings</i>
                </button>
            </div>
        </div>

        <!-- Edit Modal -->
        <x-modal name="edit-bank-{{ $bank->id }}" title="Pengaturan Bank Soal">
            <form action="{{ route('bank-soal.update', $bank->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Judul Bank Soal</label>
                    <input type="text" name="title" value="{{ $bank->title }}" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Tingkat</label>
                        <select name="level" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            @foreach($levels as $lvl)
                                <option value="{{ $lvl }}" {{ $bank->level == $lvl ? 'selected' : '' }}>Kelas {{ $lvl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Status</label>
                        <select name="status" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            <option value="private" {{ $bank->status == 'private' ? 'selected' : '' }}>Privat</option>
                            <option value="public" {{ $bank->status == 'public' ? 'selected' : '' }}>Publik</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" class="flex-grow py-4 bg-slate-900 text-white text-[10px] font-black rounded-2xl uppercase tracking-widest hover:bg-slate-800 transition-all">
                        SIMPAN PERUBAHAN
                    </button>
                    <button type="button" @click="$dispatch('open-modal', 'delete-bank-{{ $bank->id }}')" class="w-14 h-14 flex items-center justify-center bg-rose-50 text-rose-500 rounded-2xl hover:bg-rose-500 hover:text-white transition-all">
                        <i class="material-icons">delete_outline</i>
                    </button>
                </div>
            </form>
        </x-modal>

        <!-- Delete Modal -->
        <x-modal name="delete-bank-{{ $bank->id }}" title="Hapus Bank Soal">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="material-icons text-4xl">warning</i>
                </div>
                <h3 class="text-xl font-black text-slate-800 mb-2">Hapus Bank Soal?</h3>
                <p class="text-slate-500 font-medium mb-8">Tindakan ini akan menghapus seluruh soal di dalam bank soal ini secara permanen.</p>
                <div class="flex items-center gap-4">
                    <button @click="$dispatch('close-modal', 'delete-bank-{{ $bank->id }}')" class="flex-grow py-4 bg-slate-100 text-slate-600 text-[10px] font-black rounded-2xl uppercase tracking-widest hover:bg-slate-200 transition-all">
                        BATAL
                    </button>
                    <form action="{{ route('bank-soal.destroy', $bank->id) }}" method="POST" class="flex-grow">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-4 bg-rose-500 text-white text-[10px] font-black rounded-2xl uppercase tracking-widest hover:bg-rose-600 transition-all shadow-lg shadow-rose-200">
                            YA, HAPUS
                        </button>
                    </form>
                </div>
            </div>
        </x-modal>

        @empty
        <div class="col-span-full py-20 text-center bg-white rounded-[2.5rem] border border-slate-100">
            <div class="w-24 h-24 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="material-icons text-5xl">folder_off</i>
            </div>
            <h3 class="text-xl font-black text-slate-800 mb-2">Belum ada Bank Soal</h3>
            <p class="text-slate-500 font-medium mb-8">Mulai dengan membuat bank soal pertama Anda.</p>
            <button x-data @click="$dispatch('open-modal', 'create-bank')" class="inline-flex items-center gap-2 px-8 py-4 bg-slate-900 text-white font-black rounded-2xl hover:bg-slate-800 transition-all text-xs uppercase tracking-widest cursor-pointer">
                BUAT SEKARANG
            </button>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12">
        {{ $bankSoals->links() }}
    </div>
</div>

<!-- Create Modal -->
<x-modal name="create-bank" title="Bank Soal Baru">
    <form action="{{ route('bank-soal.store') }}" method="POST" class="p-6 space-y-6">
        @csrf
        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Judul Bank Soal</label>
            <input type="text" name="title" required placeholder="Contoh: Soal UTS Matematika Ganjil" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 transition-all">
        </div>
        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Mata Pelajaran</label>
            <select name="subject_id" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->nama_pelajaran }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Tingkat</label>
                <select name="level" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                    @foreach($levels as $lvl)
                        <option value="{{ $lvl }}">Kelas {{ $lvl }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Status Akses</label>
                <select name="status" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                    <option value="private">Privat (Hanya Anda)</option>
                    <option value="public">Publik (Bisa dilihat guru lain)</option>
                </select>
            </div>
        </div>
        <div class="pt-4">
            <button type="submit" class="w-full py-5 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white text-[10px] font-black rounded-2xl uppercase tracking-widest hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-pink-100">
                BUAT BANK SOAL
            </button>
        </div>
    </form>
</x-modal>
@endsection
