@extends('layouts.app')

@section('title', $elearning->title . ' - Builder')

@section('content')
<div x-data="{ 
    chapterModalOpen: false, 
    moduleModalOpen: false, 
    activeChapterId: '', 
    activeChapterTitle: '',
    moduleType: 'material'
}">
    <!-- Breadcrumb & Header -->
    <div class="mb-10">
        <nav class="flex items-center gap-3 text-xs font-bold uppercase tracking-widest text-slate-400 mb-6">
            <a href="{{ route('elearning.index') }}" class="hover:text-[#d90d8b] transition-colors">E-LEARNING</a>
            <i class="material-icons text-xs">chevron_right</i>
            <span class="text-slate-300">{{ $elearning->title }}</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-[#d90d8b]">
                    <i class="material-icons text-3xl">architecture</i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ $elearning->title }}</h1>
                    <p class="text-sm text-slate-400 font-medium">Mata Pelajaran: {{ $elearning->subject->nama_pelajaran }}</p>
                </div>
            </div>
            @if(auth()->user()->role === 'guru' || auth()->user()->role === 'admin')
            <button 
                @click="chapterModalOpen = true"
                class="flex items-center gap-2 px-6 py-3 bg-slate-800 text-white text-sm font-bold rounded-2xl shadow-lg shadow-slate-200 hover:scale-[1.02] transition-all cursor-pointer"
            >
                <i class="material-icons text-lg">playlist_add</i>
                TAMBAH BAB
            </button>
            @endif
        </div>
    </div>

    <!-- Chapter Timeline -->
    <div class="space-y-8 relative">
        <!-- Vertical Line -->
        <div class="absolute left-8 top-0 bottom-0 w-1 bg-slate-50 rounded-full hidden md:block"></div>

        @forelse($elearning->chapters as $chapter)
        <div class="relative group">
            <!-- Timeline Node -->
            <div class="absolute left-6 top-6 w-5 h-5 rounded-full bg-white border-4 border-[#d90d8b] shadow-sm z-10 hidden md:block group-hover:scale-125 transition-transform"></div>
            
            <div class="md:ms-20 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-8 md:p-10 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 font-black text-xs shadow-sm shadow-slate-100">
                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">{{ $chapter->title }}</h3>
                    </div>
                    @if(auth()->user()->role === 'guru' || auth()->user()->role === 'admin')
                    <div class="flex items-center gap-3">
                        <button 
                            @click="activeChapterId = '{{ $chapter->id }}'; activeChapterTitle = '{{ $chapter->title }}'; moduleModalOpen = true"
                            class="flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-500 text-[10px] font-black rounded-xl hover:bg-indigo-500 hover:text-white transition-all cursor-pointer uppercase tracking-widest"
                        >
                            <i class="material-icons text-sm">add</i> MODUL
                        </button>
                        <form action="{{ route('elearning.chapter.destroy', $chapter->id) }}" method="POST" onsubmit="return confirm('Hapus BAB ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-300 hover:text-red-500 transition-colors">
                                <i class="material-icons text-sm">delete</i>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>

                <div class="p-8 md:p-10 space-y-4">
                    @forelse($chapter->modules as $module)
                    <a href="{{ route('elearning.module.view', $module->id) }}" class="flex items-center justify-between p-5 bg-white border border-slate-50 rounded-2xl hover:border-indigo-100 hover:shadow-md transition-all group/module">
                        <div class="flex items-center gap-5">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center 
                                @if($module->is_completed ?? false) bg-emerald-500 text-white 
                                @elseif($module->type === 'material') bg-blue-50 text-blue-400 
                                @elseif($module->type === 'assignment') bg-amber-50 text-amber-500 
                                @elseif($module->type === 'exercise') bg-emerald-50 text-emerald-500 
                                @else bg-rose-50 text-rose-400 @endif">
                                <i class="material-icons text-xl">
                                    @if($module->is_completed ?? false) check
                                    @elseif($module->type === 'material') article
                                    @elseif($module->type === 'assignment') task
                                    @elseif($module->type === 'exercise') quiz
                                    @else history_edu @endif
                                </i>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-slate-700 mb-1">{{ $module->title }}</h4>
                                <div class="flex items-center gap-3">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $module->type }}</span>
                                    @if($module->cbt_id)
                                    <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                                    <span class="text-[9px] font-bold text-indigo-400 uppercase tracking-widest flex items-center gap-1">
                                        <i class="material-icons text-[10px]">computer</i> {{ $module->cbt->nama_cbt }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if(auth()->user()->role === 'guru' || auth()->user()->role === 'admin')
                        <form action="{{ route('elearning.module.destroy', $module->id) }}" method="POST" onsubmit="return confirm('Hapus modul ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="opacity-0 group-hover/module:opacity-100 p-2 text-slate-300 hover:text-red-500 transition-all">
                                <i class="material-icons text-sm">close</i>
                            </button>
                        </form>
                        @else
                        <i class="material-icons text-slate-200 group-hover/module:translate-x-1 group-hover/module:text-[#d90d8b] transition-all">arrow_forward</i>
                        @endif
                    </a>
                    @empty
                    <div class="py-6 text-center border-2 border-dashed border-slate-50 rounded-[2rem]">
                        <p class="text-xs font-bold text-slate-300 uppercase tracking-widest">Belum ada modul di BAB ini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        @empty
        <div class="py-20 text-center md:ms-20 flex flex-col items-center">
            <div class="w-20 h-20 rounded-3xl bg-slate-50 flex items-center justify-center text-slate-200 mb-6">
                <i class="material-icons text-4xl">list_alt</i>
            </div>
            <h3 class="text-lg font-extrabold text-slate-800 mb-1">Kurikulum Masih Kosong</h3>
            <p class="text-sm text-slate-400 font-medium tracking-tight">Klik tombol 'Tambah BAB' untuk mulai membangun isi e-learning.</p>
        </div>
        @endforelse
    </div>

    <!-- Chapter Modal -->
    <template x-if="true">
    <div x-show="chapterModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div x-show="chapterModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="chapterModalOpen = false" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
        <div x-show="chapterModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative w-full max-w-md bg-white rounded-[2.5rem] shadow-2xl p-10">
            <h3 class="text-2xl font-black text-slate-800 tracking-tight mb-8">Tambah BAB Baru</h3>
            <form action="{{ route('elearning.chapter.store', $elearning->id) }}" method="POST">
                @csrf
                <div class="mb-8">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ms-1">JUDUL BAB</label>
                    <input type="text" name="title" required placeholder="Contoh: Dasar-dasar Logika" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-pink-50 focus:bg-white transition-all">
                </div>
                <div class="flex gap-4">
                    <button type="button" @click="chapterModalOpen = false" class="flex-1 py-4 bg-slate-50 text-slate-400 font-bold rounded-2xl hover:bg-slate-100 transition-colors cursor-pointer">BATAL</button>
                    <button type="submit" class="flex-[2] py-4 bg-[#d90d8b] text-white font-bold rounded-2xl shadow-lg shadow-pink-100 hover:scale-[1.02] transition-all cursor-pointer uppercase tracking-widest text-xs">SIMPAN BAB</button>
                </div>
            </form>
        </div>
    </div>
    </template>

    <!-- Module Modal -->
    <template x-if="true">
    <div x-show="moduleModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div x-show="moduleModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="moduleModalOpen = false" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
        <div x-show="moduleModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative w-full max-w-xl bg-white rounded-[2.5rem] shadow-2xl p-10 overflow-y-auto max-h-[90vh]">
            <div class="mb-8">
                <p class="text-[10px] font-black text-[#d90d8b] uppercase tracking-widest mb-1" x-text="activeChapterTitle"></p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight">Tambah Modul</h3>
            </div>
            
            <form :action="'{{ url('elearning/chapter') }}/' + activeChapterId + '/module'" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ms-1">TIPE MODUL</label>
                        <div class="grid grid-cols-4 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="material" x-model="moduleType" class="hidden peer">
                                <div class="p-3 text-center rounded-2xl border border-slate-100 bg-slate-50 peer-checked:bg-blue-50 peer-checked:border-blue-200 peer-checked:text-blue-500 transition-all">
                                    <i class="material-icons text-xl block mb-1">article</i>
                                    <span class="text-[8px] font-black uppercase">MATERI</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="assignment" x-model="moduleType" class="hidden peer">
                                <div class="p-3 text-center rounded-2xl border border-slate-100 bg-slate-50 peer-checked:bg-amber-50 peer-checked:border-amber-200 peer-checked:text-amber-500 transition-all">
                                    <i class="material-icons text-xl block mb-1">task</i>
                                    <span class="text-[8px] font-black uppercase">TUGAS</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="exercise" x-model="moduleType" class="hidden peer">
                                <div class="p-3 text-center rounded-2xl border border-slate-100 bg-slate-50 peer-checked:bg-emerald-50 peer-checked:border-emerald-200 peer-checked:text-emerald-500 transition-all">
                                    <i class="material-icons text-xl block mb-1">quiz</i>
                                    <span class="text-[8px] font-black uppercase">LATIHAN</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="exam" x-model="moduleType" class="hidden peer">
                                <div class="p-3 text-center rounded-2xl border border-slate-100 bg-slate-50 peer-checked:bg-rose-50 peer-checked:border-rose-200 peer-checked:text-rose-500 transition-all">
                                    <i class="material-icons text-xl block mb-1">history_edu</i>
                                    <span class="text-[8px] font-black uppercase">UJIAN</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ms-1">JUDUL MODUL</label>
                        <input type="text" name="title" required placeholder="Masukkan judul materi/penugasan" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-indigo-50 focus:bg-white transition-all">
                    </div>

                    <div x-show="moduleType === 'material'">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ms-1">ISI MATERI (OPSIONAL)</label>
                        <textarea name="content" rows="5" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-indigo-50 focus:bg-white transition-all resize-none" placeholder="Tuliskan materi di sini atau lampirkan file..."></textarea>
                    </div>

                    <div x-show="moduleType !== 'exam'">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ms-1">LAMPIRAN FILE (PDF/DOC/ZIP)</label>
                        <input type="file" name="file" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-400 font-bold focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-[#ba80e8] file:text-white transition-all">
                    </div>

                    <div x-show="moduleType === 'exam' || moduleType === 'exercise'">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ms-1">HUBUNGKAN DENGAN CBT</label>
                        <select name="cbt_id" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-rose-50 focus:bg-white transition-all appearance-none">
                            <option value="">Pilih Test CBT...</option>
                            @foreach($cbts as $cbt)
                            <option value="{{ $cbt->id }}">{{ $cbt->nama_cbt }} ({{ $cbt->tanggal }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div x-show="moduleType === 'assignment' || moduleType === 'exam'">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ms-1">BATAS PENGUMPULAN / UJIAN</label>
                        <input type="datetime-local" name="due_date" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-indigo-50 focus:bg-white transition-all">
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <button type="button" @click="moduleModalOpen = false" class="flex-1 py-4 bg-slate-50 text-slate-400 font-bold rounded-2xl hover:bg-slate-100 transition-colors cursor-pointer">BATAL</button>
                    <button type="submit" class="flex-[2] py-4 bg-gradient-to-r from-indigo-500 to-[#d90d8b] text-white font-bold rounded-2xl shadow-lg shadow-pink-100 hover:scale-[1.02] transition-all cursor-pointer uppercase tracking-widest text-xs">SIMPAN MODUL</button>
                </div>
            </form>
        </div>
    </div>
    </template>
</div>
@endsection
