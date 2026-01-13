@extends('layouts.app')

@section('title', 'Hasil Ujian - ' . $session->cbt->nama_cbt)

@section('content')
<div class="max-w-4xl mx-auto space-y-10 py-10">
    
    <!-- Result Header -->
    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-[3rem] p-10 md:p-16 text-white text-center shadow-2xl relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMTAiIGN5PSIxMCIgcj0iMiIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjEpIi8+PC9zdmc+')] opacity-50"></div>
        <div class="relative z-10">
            <div class="w-24 h-24 bg-white/20 rounded-[2rem] flex items-center justify-center mx-auto mb-6 backdrop-blur-sm">
                <i class="material-icons text-6xl">emoji_events</i>
            </div>
            <h1 class="text-3xl md:text-4xl font-black tracking-tight uppercase mb-2">Ujian Selesai!</h1>
            <p class="text-white/80 font-medium text-lg">{{ $session->cbt->nama_cbt }}</p>
            
            <div class="mt-10 flex flex-col md:flex-row items-center justify-center gap-8">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl px-8 py-5">
                    <p class="text-[10px] font-black uppercase tracking-widest text-white/70 mb-1">Skor Anda</p>
                    <p class="text-5xl font-black">{{ $session->skor }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl px-8 py-5">
                    <p class="text-[10px] font-black uppercase tracking-widest text-white/70 mb-1">Skor Maksimal</p>
                    <p class="text-5xl font-black">{{ $session->cbt->skor_maksimal }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-10">
        <h2 class="text-xl font-black text-slate-800 mb-6">Ringkasan Pengerjaan</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-slate-50 rounded-2xl p-5 text-center">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Jumlah Soal</p>
                <p class="text-2xl font-black text-slate-700">{{ count($session->cbt->questions) }}</p>
            </div>
            <div class="bg-emerald-50 rounded-2xl p-5 text-center">
                <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Benar</p>
                <p class="text-2xl font-black text-emerald-600">{{ $session->answers->where('poin_didapat', '>', 0)->count() }}</p>
            </div>
            <div class="bg-rose-50 rounded-2xl p-5 text-center">
                <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest mb-1">Salah</p>
                <p class="text-2xl font-black text-rose-600">{{ $session->answers->where('poin_didapat', 0)->where('is_graded', true)->count() }}</p>
            </div>
            <div class="bg-amber-50 rounded-2xl p-5 text-center">
                <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-1">Belum Dinilai</p>
                <p class="text-2xl font-black text-amber-600">{{ $session->answers->where('is_graded', false)->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Review Answers -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-10 space-y-8">
        <h2 class="text-xl font-black text-slate-800">Review Jawaban</h2>
        
        @foreach($session->cbt->questions as $index => $q)
        @php
            $answer = $session->answers->where('question_id', $q->id)->first();
            $isCorrect = $answer && $answer->poin_didapat > 0;
            $isGraded = $answer && $answer->is_graded;
        @endphp
        <div class="p-6 rounded-2xl border {{ $isGraded ? ($isCorrect ? 'border-emerald-100 bg-emerald-50/50' : 'border-rose-100 bg-rose-50/50') : 'border-amber-100 bg-amber-50/50' }}">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm shrink-0 {{ $isGraded ? ($isCorrect ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white') : 'bg-amber-500 text-white' }}">
                    {{ $index + 1 }}
                </div>
                <div class="flex-grow space-y-4">
                    <div class="font-bold text-slate-700 leading-relaxed">{!! nl2br(e($q->pertanyaan)) !!}</div>
                    
                    @if($q->jenis_soal == 'pilihan_ganda')
                        <div class="space-y-2">
                            @foreach($q->options as $opt)
                                @php
                                    $isSelected = $answer && $answer->option_id == $opt->id;
                                    $isCorrectOption = $opt->is_correct;
                                @endphp
                                <div class="flex items-center gap-3 px-4 py-3 rounded-xl {{ $isCorrectOption ? 'bg-emerald-100 text-emerald-700' : ($isSelected && !$isCorrectOption ? 'bg-rose-100 text-rose-700' : 'bg-white text-slate-500') }}">
                                    @if($isCorrectOption)
                                        <i class="material-icons text-sm">check_circle</i>
                                    @elseif($isSelected && !$isCorrectOption)
                                        <i class="material-icons text-sm">cancel</i>
                                    @else
                                        <span class="w-5 h-5 border border-slate-200 rounded-full"></span>
                                    @endif
                                    <span class="font-bold text-sm">{{ $opt->opsi }}</span>
                                    @if($isSelected)
                                        <span class="ml-auto text-[9px] font-black uppercase tracking-widest">Jawaban Anda</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white p-4 rounded-xl border border-slate-100">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Jawaban Anda:</p>
                            <p class="text-slate-600 font-medium">{{ $answer?->essay_answer ?? '-' }}</p>
                        </div>
                        @if(!$isGraded)
                            <p class="text-[10px] font-bold text-amber-600"><i class="material-icons text-sm align-middle">info</i> Soal esai ini belum dinilai oleh guru.</p>
                        @endif
                    @endif
                </div>
                <div class="shrink-0 text-right">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Poin</p>
                    <p class="text-xl font-black {{ $isCorrect ? 'text-emerald-500' : 'text-slate-300' }}">{{ $answer?->poin_didapat ?? 0 }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Action Button -->
    <div class="text-center">
        <a href="{{ route('test.index') }}" class="inline-flex items-center gap-3 px-10 py-5 bg-slate-800 text-white rounded-2xl text-sm font-black uppercase tracking-widest shadow-xl hover:bg-slate-700 transition-all">
            <i class="material-icons">home</i> KEMBALI KE HALAMAN UTAMA
        </a>
    </div>

</div>
@endsection
