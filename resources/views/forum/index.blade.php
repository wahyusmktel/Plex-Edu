@extends('layouts.app')

@section('title', 'Forum Diskusi - Literasia')

@section('content')
<div x-data="{ createModalOpen: false }">
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-[#d90d8b]">
                <i class="material-icons text-3xl">forum</i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">KOMUNITAS</p>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Forum Diskusi</h1>
            </div>
        </div>
        @if(auth()->user()->role === 'guru' || auth()->user()->role === 'admin')
        <button 
            @click="createModalOpen = true"
            class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white text-sm font-bold rounded-2xl shadow-lg shadow-pink-100 hover:scale-[1.02] transition-all cursor-pointer"
        >
            <i class="material-icons text-lg">add</i>
            TAMBAH FORUM
        </button>
        @endif
    </div>

    <!-- Forum Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        @forelse($forums as $forum)
        <a href="{{ route('forum.show', $forum->id) }}" class="group block bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300">
            <div class="flex items-start justify-between mb-6">
                <div class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center text-[#ba80e8] group-hover:bg-[#ba80e8] group-hover:text-white transition-colors duration-300">
                    <i class="material-icons text-2xl">chat_bubble_outline</i>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-xl font-black text-slate-800">{{ $forum->topics_count }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Topik</span>
                </div>
            </div>
            
            <h3 class="text-lg font-extrabold text-slate-800 mb-2 group-hover:text-[#d90d8b] transition-colors line-clamp-1">{{ $forum->title }}</h3>
            <p class="text-sm text-slate-500 font-medium mb-6 line-clamp-2 leading-relaxed">
                {{ $forum->description ?? 'Tidak ada deskripsi untuk forum ini.' }}
            </p>

            <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                        <i class="material-icons text-sm">person</i>
                    </div>
                    <span class="text-xs font-bold text-slate-500">{{ $forum->creator->name }}</span>
                </div>
                <i class="material-icons text-slate-300 group-hover:translate-x-1 transition-transform">arrow_forward</i>
            </div>
        </a>
        @empty
        <div class="col-span-full py-20 text-center flex flex-col items-center">
            <div class="w-24 h-24 rounded-3xl bg-slate-50 flex items-center justify-center text-slate-200 mb-6">
                <i class="material-icons text-5xl">forum</i>
            </div>
            <h3 class="text-xl font-extrabold text-slate-800 mb-2">Belum Ada Forum</h3>
            <p class="text-slate-400 font-medium">Mulailah dengan membuat forum diskusi baru hari ini.</p>
        </div>
        @endforelse
    </div>

    <!-- Create Forum Modal -->
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
                <div class="w-16 h-16 rounded-2xl bg-pink-50 text-[#d90d8b] flex items-center justify-center mb-6">
                    <i class="material-icons text-3xl">add_comment</i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight">Buat Forum Baru</h3>
                <p class="text-slate-400 font-medium">Tentukan nama dan tujuan diskusi komunitas Anda.</p>
            </div>

            <form action="{{ route('forum.store') }}" method="POST" x-data="{ visibility: 'school' }">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ms-1">JUDUL FORUM</label>
                        <input 
                            type="text" 
                            name="title" 
                            required 
                            placeholder="Contoh: Diskusi Matematika Lanjut"
                            class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold placeholder:text-slate-300 focus:outline-none focus:ring-4 focus:ring-pink-50 focus:bg-white transition-all"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ms-1">DESKRIPSI</label>
                        <textarea 
                            name="description" 
                            rows="3" 
                            placeholder="Jelaskan apa yang akan dibahas di forum ini..."
                            class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold placeholder:text-slate-300 focus:outline-none focus:ring-4 focus:ring-pink-50 focus:bg-white transition-all resize-none"
                        ></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ms-1">VISIBILITAS</label>
                        <select 
                            name="visibility" 
                            x-model="visibility"
                            class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-pink-50 focus:bg-white transition-all"
                        >
                            <option value="all">Publik (Semua Orang)</option>
                            <option value="school">Sekolah Ini Saja</option>
                            <option value="class">Kelas Tertentu</option>
                            @if(auth()->user()->role === 'dinas')
                            <option value="specific_schools">Sekolah Tertentu</option>
                            @endif
                        </select>
                    </div>

                    {{-- Class Selector (shown when visibility=class) --}}
                    <div x-show="visibility === 'class'" x-transition class="pt-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ms-1">PILIH KELAS</label>
                        <select 
                            name="class_id"
                            class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-pink-50 focus:bg-white transition-all"
                        >
                            <option value="">-- Pilih Kelas --</option>
                            @php $kelas = \App\Models\Kelas::where('school_id', auth()->user()->school_id)->get(); @endphp
                            @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Schools Selector (shown when visibility=specific_schools) --}}
                    <div x-show="visibility === 'specific_schools'" x-transition class="pt-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ms-1">PILIH SEKOLAH</label>
                        <div class="max-h-40 overflow-y-auto bg-slate-50 border border-slate-100 rounded-2xl p-4 space-y-2">
                            @php $schools = \App\Models\School::where('status', 'approved')->orderBy('nama_sekolah')->get(); @endphp
                            @foreach($schools as $school)
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="allowed_schools[]" value="{{ $school->id }}" class="rounded text-pink-500 focus:ring-pink-300">
                                <span class="text-sm font-bold text-slate-600">{{ $school->nama_sekolah }}</span>
                            </label>
                            @endforeach
                        </div>
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
                        BUAT SEKARANG
                    </button>
                </div>
            </form>
        </div>
    </div>
    </template>
</div>
@endsection
