@extends('layouts.app')

@section('title', 'Analisis Soal - ' . $cbt->nama_cbt)

@section('content')
<div class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-6">
            <a href="{{ route('cbt.results', $cbt->id) }}" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-[#d90d8b] transition-all shadow-sm">
                <i class="material-icons">arrow_back</i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Analisis Soal</h1>
                <p class="text-slate-500 font-medium mt-1">{{ $cbt->nama_cbt }} ({{ $cbt->subject->nama_pelajaran ?? 'Umum' }})</p>
            </div>
        </div>
    </div>

    <!-- Analysis Cards -->
    <div class="space-y-8">
        @foreach($cbt->questions as $index => $q)
        @php
            $totalAnswers = $q->answers->count();
            $correctAnswers = $q->answers->where('poin_didapat', '>', 0)->count();
            $percentage = $totalAnswers > 0 ? round(($correctAnswers / $totalAnswers) * 100) : 0;
        @endphp
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8">
            <div class="flex items-start gap-6">
                <!-- Question Number -->
                <div class="w-14 h-14 rounded-2xl bg-slate-800 text-white flex items-center justify-center font-black text-xl shrink-0">
                    {{ $index + 1 }}
                </div>

                <div class="flex-grow space-y-6">
                    <!-- Question Text -->
                    <div class="flex items-start justify-between gap-8">
                        <div class="flex-grow">
                            <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest mb-3 inline-block {{ $q->jenis_soal == 'pilihan_ganda' ? 'bg-blue-50 text-blue-500' : 'bg-purple-50 text-purple-500' }}">
                                {{ $q->jenis_soal == 'pilihan_ganda' ? 'Pilihan Ganda' : 'Esai' }} • {{ $q->poin }} Poin
                            </span>
                            <p class="text-lg font-bold text-slate-700 leading-relaxed">{!! nl2br(e($q->pertanyaan)) !!}</p>
                        </div>
                        
                        <!-- Stats Circle -->
                        <div class="shrink-0 text-center">
                            <div class="w-20 h-20 rounded-full border-4 {{ $percentage >= 70 ? 'border-emerald-500' : ($percentage >= 40 ? 'border-amber-500' : 'border-rose-500') }} flex items-center justify-center">
                                <span class="text-2xl font-black {{ $percentage >= 70 ? 'text-emerald-500' : ($percentage >= 40 ? 'text-amber-500' : 'text-rose-500') }}">{{ $percentage }}%</span>
                            </div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-2">Benar</p>
                        </div>
                    </div>

                    @if($q->jenis_soal == 'pilihan_ganda')
                    <!-- Options Analysis -->
                    <div class="bg-slate-50 rounded-2xl p-6 space-y-4">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Distribusi Jawaban</p>
                        @foreach($q->options as $opt)
                        @php
                            $optCount = $q->answers->where('option_id', $opt->id)->count();
                            $optPercentage = $totalAnswers > 0 ? round(($optCount / $totalAnswers) * 100) : 0;
                        @endphp
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 rounded-lg {{ $opt->is_correct ? 'bg-emerald-500 text-white' : 'bg-white border border-slate-200 text-slate-400' }} flex items-center justify-center font-black text-xs shrink-0">
                                {{ $opt->is_correct ? '✓' : '' }}
                            </div>
                            <div class="flex-grow">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-bold text-sm text-slate-600">{{ $opt->opsi }}</span>
                                    <span class="text-xs font-bold text-slate-400">{{ $optCount }} siswa ({{ $optPercentage }}%)</span>
                                </div>
                                <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $opt->is_correct ? 'bg-emerald-500' : 'bg-slate-400' }}" style="width: {{ $optPercentage }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <!-- Essay Analysis -->
                    <div class="bg-slate-50 rounded-2xl p-6">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Status Penilaian</p>
                        <div class="flex items-center gap-4">
                            <div class="px-4 py-2 bg-emerald-100 rounded-xl">
                                <span class="text-sm font-bold text-emerald-600">{{ $q->answers->where('is_graded', true)->count() }} Dinilai</span>
                            </div>
                            <div class="px-4 py-2 bg-amber-100 rounded-xl">
                                <span class="text-sm font-bold text-amber-600">{{ $q->answers->where('is_graded', false)->count() }} Belum Dinilai</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Summary Row -->
                    <div class="flex items-center gap-6 pt-4 border-t border-slate-100">
                        <div class="flex items-center gap-2 text-sm">
                            <i class="material-icons text-lg text-slate-300">people</i>
                            <span class="font-bold text-slate-500">{{ $totalAnswers }} Menjawab</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class="material-icons text-lg text-emerald-400">check_circle</i>
                            <span class="font-bold text-emerald-600">{{ $correctAnswers }} Benar</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class="material-icons text-lg text-rose-400">cancel</i>
                            <span class="font-bold text-rose-600">{{ $totalAnswers - $correctAnswers }} Salah</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection
