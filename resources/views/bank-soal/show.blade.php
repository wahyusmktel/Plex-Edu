@extends('layouts.app')

@section('title', $bankSoal->title . ' - Bank Soal')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb & Back -->
    <div class="mb-8">
        <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">
            <a href="{{ route('bank-soal.index') }}" class="hover:text-indigo-600 transition-colors">BANK SOAL</a>
            <i class="material-icons text-xs">chevron_right</i>
            <span class="text-slate-300">{{ $bankSoal->title }}</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-grow">
                <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight uppercase">{{ $bankSoal->title }}</h1>
                <div class="flex flex-wrap items-center gap-4 mt-3">
                    <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-[9px] font-black uppercase tracking-widest">{{ $bankSoal->subject->nama_pelajaran }}</span>
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-500 rounded-full text-[9px] font-black uppercase tracking-widest">KELAS {{ $bankSoal->level }}</span>
                    <div class="h-4 w-px bg-slate-200 mx-1"></div>
                    <div class="flex items-center gap-2">
                        <i class="material-icons text-slate-400 text-sm">person</i>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $bankSoal->teacher->nama ?? 'Administrator' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="material-icons text-indigo-400 text-sm">school</i>
                        <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">{{ $bankSoal->school->nama_sekolah ?? '-' }}</span>
                    </div>
                </div>
            </div>
            @if($bankSoal->school_id === Auth::user()->school_id)
            <button x-data @click="$dispatch('open-modal', 'add-question')" class="flex items-center gap-2 px-8 py-4 bg-slate-900 text-white font-black rounded-2xl shadow-lg hover:bg-slate-800 active:scale-95 transition-all text-xs uppercase tracking-widest cursor-pointer">
                <i class="material-icons text-lg">add_circle</i>
                TAMBAH SOAL
            </button>
            @endif
        </div>
    </div>

    <!-- Questions List -->
    <div class="space-y-6">
        @forelse($bankSoal->questions as $index => $question)
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden group hover:border-indigo-100 transition-all">
            <div class="p-8">
                <div class="flex items-start justify-between gap-6">
                    <div class="flex items-start gap-6 flex-grow">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 font-black text-lg group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-grow pt-1">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-500">
                                    {{ $question->jenis_soal === 'pilihan_ganda' ? 'PILIHAN GANDA' : 'ESSAY' }}
                                </span>
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-slate-50 text-slate-400">
                                    {{ $question->poin }} POIN
                                </span>
                            </div>
                            
                            <div class="prose max-w-none text-slate-700 font-bold mb-6">
                                {!! nl2br(e($question->pertanyaan)) !!}
                            </div>

                            @if($question->gambar)
                            <div class="mb-6">
                                <img src="{{ asset('storage/' . $question->gambar) }}" class="max-w-md rounded-2xl border border-slate-100 shadow-sm">
                            </div>
                            @endif

                            @if($question->jenis_soal === 'pilihan_ganda')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                                @foreach($question->options as $option)
                                <div class="flex items-center gap-4 p-4 rounded-xl {{ $option->is_correct ? 'bg-emerald-50 border border-emerald-100' : 'bg-slate-50' }}">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-xs
                                        {{ $option->is_correct ? 'bg-emerald-500 text-white' : 'bg-white text-slate-400' }}">
                                        {{ chr(65 + $loop->index) }}
                                    </div>
                                    <span class="text-sm font-bold {{ $option->is_correct ? 'text-emerald-700' : 'text-slate-600' }}">{{ $option->opsi }}</span>
                                    @if($option->is_correct)
                                    <i class="material-icons text-emerald-500 text-sm ml-auto">verified</i>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($bankSoal->school_id === Auth::user()->school_id)
                    <div class="flex flex-col gap-2">
                        <button x-data @click="$dispatch('open-modal', 'edit-question-{{ $question->id }}')" class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl hover:bg-slate-900 hover:text-white transition-all">
                            <i class="material-icons text-base">edit</i>
                        </button>
                        <form action="{{ route('bank-soal.question.destroy', $question->id) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-10 h-10 flex items-center justify-center bg-rose-50 text-rose-500 rounded-xl hover:bg-rose-500 hover:text-white transition-all">
                                <i class="material-icons text-base">delete</i>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Edit Question Modal -->
        <x-modal name="edit-question-{{ $question->id }}" title="Edit Soal">
            <form action="{{ route('bank-soal.question.update', $question->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Pertanyaan</label>
                    <textarea name="pertanyaan" required rows="4" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 transition-all">{{ $question->pertanyaan }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Poin Soal</label>
                        <input type="number" name="poin" value="{{ $question->poin }}" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Gambar (Opsional)</label>
                        <input type="file" name="gambar" accept="image/*" class="w-full bg-slate-50 file:hidden rounded-2xl px-6 py-4 text-[10px] font-black text-slate-400">
                    </div>
                </div>

                @if($question->jenis_soal === 'pilihan_ganda')
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Pilihan Jawaban</label>
                    @foreach($question->options as $optIndex => $option)
                    <div class="flex items-center gap-4">
                        <div class="flex-grow flex items-center bg-slate-50 rounded-2xl px-6 py-2">
                            <span class="text-xs font-black text-slate-300 mr-4">{{ chr(65 + $optIndex) }}</span>
                            <input type="text" name="options[]" value="{{ $option->opsi }}" required class="flex-grow bg-transparent border-none p-0 text-sm font-bold text-slate-700 focus:ring-0">
                        </div>
                        <label class="cursor-pointer group">
                            <input type="radio" name="correct_option" value="{{ $optIndex }}" {{ $option->is_correct ? 'checked' : '' }} class="hidden">
                            <div class="w-10 h-10 rounded-xl border-2 border-slate-100 flex items-center justify-center text-slate-200 group-hover:border-emerald-200 transition-all py-radio-checked">
                                <i class="material-icons text-xl">check_circle</i>
                            </div>
                        </label>
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="pt-4">
                    <button type="submit" class="w-full py-5 bg-slate-900 text-white text-[10px] font-black rounded-2xl uppercase tracking-widest hover:bg-slate-800 transition-all">
                        SIMPAN PERUBAHAN
                    </button>
                </div>
            </form>
        </x-modal>
        @empty
        <div class="py-20 text-center bg-white rounded-[2.5rem] border border-slate-100">
            <div class="w-24 h-24 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="material-icons text-5xl">quiz</i>
            </div>
            <h3 class="text-xl font-black text-slate-800 mb-2">Belum ada Soal</h3>
            <p class="text-slate-500 font-medium mb-8">Tambahkan pertanyaan pertama untuk bank soal ini.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Add Question Modal -->
<x-modal name="add-question" title="Tambah Soal Baru">
    <div x-data="{ type: 'pilihan_ganda' }">
        <form action="{{ route('bank-soal.question.store', $bankSoal->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Jenis Soal</label>
                <div class="grid grid-cols-2 gap-4">
                    <button type="button" @click="type = 'pilihan_ganda'" :class="type === 'pilihan_ganda' ? 'bg-indigo-600 text-white' : 'bg-slate-50 text-slate-400'" class="py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all cursor-pointer">
                        PILIHAN GANDA
                    </button>
                    <button type="button" @click="type = 'essay'" :class="type === 'essay' ? 'bg-indigo-600 text-white' : 'bg-slate-50 text-slate-400'" class="py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all cursor-pointer">
                        ESSAY / URAIAN
                    </button>
                    <input type="hidden" name="jenis_soal" :value="type">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Pertanyaan</label>
                <textarea name="pertanyaan" required rows="4" placeholder="Tuliskan pertanyaan di sini..." class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 transition-all"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Poin Soal</label>
                    <input type="number" name="poin" value="5" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Gambar (Opsional)</label>
                    <input type="file" name="gambar" accept="image/*" class="w-full bg-slate-50 file:hidden rounded-2xl px-6 py-4 text-[10px] font-black text-slate-400">
                </div>
            </div>

            <!-- Choice Options -->
            <div x-show="type === 'pilihan_ganda'" class="space-y-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Pilihan Jawaban</label>
                    <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">Centang Jawaban Benar</span>
                </div>
                @for($i=0; $i<5; $i++)
                <div class="flex items-center gap-4">
                    <div class="flex-grow flex items-center bg-slate-50 rounded-2xl px-6 py-2">
                        <span class="text-xs font-black text-slate-300 mr-4">{{ chr(65 + $i) }}</span>
                        <input type="text" name="options[]" placeholder="Isi pilihan..." class="flex-grow bg-transparent border-none p-0 text-sm font-bold text-slate-700 focus:ring-0">
                    </div>
                    <label class="cursor-pointer group">
                        <input type="radio" name="correct_option" value="{{ $i }}" {{ $i === 0 ? 'checked' : '' }} class="hidden">
                        <div class="w-10 h-10 rounded-xl border-2 border-slate-100 flex items-center justify-center text-slate-200 group-hover:border-emerald-200 transition-all py-radio-checked">
                            <i class="material-icons text-xl">check_circle</i>
                        </div>
                    </label>
                </div>
                @endfor
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-5 bg-gradient-to-r from-slate-900 to-slate-800 text-white text-[10px] font-black rounded-2xl uppercase tracking-widest hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-slate-200">
                    SIMPAN SOAL
                </button>
            </div>
        </form>
    </div>
</x-modal>

<style>
    input[type="radio"]:checked + .py-radio-checked {
        border-color: #10b981;
        background-color: #ecfdf5;
        color: #10b981;
    }
</style>
@endsection
