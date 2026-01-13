@extends('layouts.app')

@section('title', 'E-Learning - Literasia')

@section('content')
<div x-data="{ createModalOpen: false }">
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-[#ba80e8]">
                <i class="material-icons text-3xl">cast_for_education</i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">PROGRAM PENDIDIKAN</p>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Manajemen E-Learning</h1>
            </div>
        </div>
        @if(auth()->user()->role === 'guru' || auth()->user()->role === 'admin')
        <button 
            @click="createModalOpen = true"
            class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white text-sm font-bold rounded-2xl shadow-lg shadow-pink-100 hover:scale-[1.02] transition-all cursor-pointer"
        >
            <i class="material-icons text-lg">add</i>
            BUAT E-LEARNING
        </button>
        @endif
    </div>

    <!-- ELearning Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        @forelse($elearnings as $course)
        <div class="group bg-white rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 overflow-hidden">
            <div class="relative h-48 bg-slate-100 overflow-hidden">
                @if($course->thumbnail)
                <img src="{{ asset('storage/' . $course->thumbnail) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="Thumbnail">
                @else
                <div class="w-full h-full flex items-center justify-center text-slate-200">
                    <i class="material-icons text-6xl">image</i>
                </div>
                @endif
                <div class="absolute top-4 left-4">
                    <span class="px-3 py-1 bg-white/90 backdrop-blur text-[#d90d8b] text-[10px] font-black rounded-full shadow-sm uppercase tracking-widest">
                        {{ $course->subject->nama_pelajaran }}
                    </span>
                </div>
            </div>
            
            <div class="p-8">
                <h3 class="text-lg font-extrabold text-slate-800 mb-2 line-clamp-1">{{ $course->title }}</h3>
                <p class="text-sm text-slate-500 font-medium mb-6 line-clamp-2 leading-relaxed">
                    {{ $course->description ?? 'Kurikulum digital interaktif untuk mata pelajaran ini.' }}
                </p>

                <div class="flex items-center justify-between">
                    <div class="flex -space-x-2">
                        <div class="w-8 h-8 rounded-full border-2 border-white bg-indigo-50 flex items-center justify-center text-indigo-400 text-[10px] font-black uppercase shadow-sm">
                            {{ count($course->chapters) }}
                        </div>
                        <span class="ps-10 text-[10px] font-bold text-slate-400 uppercase tracking-widest content-center">BAB / MATERI</span>
                    </div>

                    @if(auth()->user()->role === 'siswa')
                    <div class="text-end">
                        <p class="text-[9px] font-black text-[#d90d8b] uppercase tracking-widest mb-1">{{ $course->progress_percentage }}% SELESAI</p>
                        <div class="w-24 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] transition-all duration-1000" style="width: {{ $course->progress_percentage }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>

                @if(auth()->user()->role === 'guru' || auth()->user()->role === 'admin')
                <div class="mt-8 pt-6 border-t border-slate-50 flex gap-3">
                    <a href="{{ route('elearning.show', $course->id) }}" class="flex-1 py-3 bg-slate-50 text-slate-600 text-xs font-bold rounded-xl text-center hover:bg-indigo-50 hover:text-indigo-500 transition-colors">
                        KELOLA KONTEN
                    </a>
                    <form action="{{ route('elearning.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Hapus e-learning ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-3 bg-red-50 text-red-400 rounded-xl hover:bg-red-500 hover:text-white transition-all cursor-pointer">
                            <i class="material-icons text-sm">delete_outline</i>
                        </button>
                    </form>
                </div>
                @else
                <div class="mt-8 pt-6 border-t border-slate-50">
                    <a href="{{ route('elearning.show', $course->id) }}" class="block w-full py-3 bg-indigo-50 text-indigo-500 text-xs font-black rounded-xl text-center hover:bg-indigo-500 hover:text-white transition-all uppercase tracking-widest">
                        BUKA KURSUS
                    </a>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center flex flex-col items-center">
            <div class="w-24 h-24 rounded-3xl bg-slate-50 flex items-center justify-center text-slate-200 mb-6 border border-dashed border-slate-200">
                <i class="material-icons text-5xl">cast_for_education</i>
            </div>
            <h3 class="text-xl font-extrabold text-slate-800 mb-2">Belum Ada E-Learning</h3>
            <p class="text-slate-400 font-medium">Mulailah menyusun kurikulum digital Anda sekarang.</p>
        </div>
        @endforelse
    </div>

    <!-- Create ELearning Modal -->
    <template x-if="true">
    <div 
        x-show="createModalOpen" 
        x-cloak
        class="fixed inset-0 z-[60] flex items-center justify-center p-4"
    >
        <div 
            x-show="createModalOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            @click="createModalOpen = false"
            class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"
        ></div>

        <div 
            x-show="createModalOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            class="relative w-full max-w-lg bg-white rounded-[2.5rem] shadow-2xl p-10 overflow-hidden"
        >
            <div class="absolute top-0 right-0 p-8">
                <button @click="createModalOpen = false" class="text-slate-300 hover:text-slate-500 transition-colors">
                    <i class="material-icons">close</i>
                </button>
            </div>

            <div class="mb-8">
                <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center mb-6">
                    <i class="material-icons text-3xl">add_to_photos</i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight">Buat E-Learning</h3>
                <p class="text-slate-400 font-medium">Lengkapi detail untuk memulai kursus digital baru.</p>
            </div>

            <form action="{{ route('elearning.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ms-1">MATA PELAJARAN</label>
                        <select 
                            name="subject_id" 
                            required 
                            class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-indigo-50 focus:bg-white transition-all appearance-none"
                        >
                            <option value="">Pilih Mata Pelajaran...</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->nama_pelajaran }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ms-1">JUDUL E-LEARNING</label>
                        <input 
                            type="text" 
                            name="title" 
                            required 
                            placeholder="Contoh: Pemrograman Dasar X TKJ"
                            class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold placeholder:text-slate-300 focus:outline-none focus:ring-4 focus:ring-indigo-50 focus:bg-white transition-all"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ms-1">THUMBNAIL (OPSIONAL)</label>
                        <input 
                            type="file" 
                            name="thumbnail" 
                            class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-400 font-bold focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-[#d90d8b] file:text-white hover:file:bg-[#ba80e8] transition-all"
                        >
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <button 
                        type="button" 
                        @click="createModalOpen = false"
                        class="flex-1 py-4 bg-slate-50 text-slate-400 font-bold rounded-2xl hover:bg-slate-100 transition-colors cursor-pointer"
                    >
                        BATAL
                    </button>
                    <button 
                        type="submit"
                        class="flex-[2] py-4 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white font-bold rounded-2xl shadow-lg shadow-pink-100 hover:scale-[1.02] transition-all cursor-pointer"
                    >
                        SIMPAN KURSUS
                    </button>
                </div>
            </form>
        </div>
    </div>
    </template>
</div>
@endsection
