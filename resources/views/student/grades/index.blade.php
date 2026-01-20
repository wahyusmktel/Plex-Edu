@extends('layouts.app')

@section('title', 'Daftar Nilai Saya - Literasia')

@section('content')
<div class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Daftar Nilai</h1>
            <p class="text-slate-500 font-medium mt-1">Transkrip nilai tugas dan ujian kamu semester ini</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="px-6 py-3 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center">
                    <i class="material-icons">star</i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">RATA-RATA</p>
                    <p class="text-sm font-bold text-slate-700 mt-1">{{ number_format($overallAvg, 1) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-[2rem] p-8 text-white shadow-xl shadow-indigo-100 relative overflow-hidden">
            <i class="material-icons absolute -right-4 -bottom-4 text-9xl opacity-10">assignment</i>
            <p class="text-indigo-100 text-xs font-black uppercase tracking-widest mb-1">TOTAL TUGAS</p>
            <h3 class="text-4xl font-black tracking-tight">{{ $totalAssignments }}</h3>
            <p class="text-indigo-100 text-[10px] font-medium mt-4">Tugas yang telah dinilai pengajar</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-[2rem] p-8 text-white shadow-xl shadow-purple-100 relative overflow-hidden">
            <i class="material-icons absolute -right-4 -bottom-4 text-9xl opacity-10">quiz</i>
            <p class="text-purple-100 text-xs font-black uppercase tracking-widest mb-1">TOTAL UJIAN</p>
            <h3 class="text-4xl font-black tracking-tight">{{ $totalExams }}</h3>
            <p class="text-purple-100 text-[10px] font-medium mt-4">Ujian CBT yang telah diselesaikan</p>
        </div>
        <div class="bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center">
                    <i class="material-icons">trending_up</i>
                </div>
                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">PERFORMA</span>
            </div>
            <div class="mt-6">
                <div class="flex items-end justify-between mb-2">
                    <span class="text-sm font-bold text-slate-700">Skor Konsistensi</span>
                    <span class="text-xl font-black text-slate-800">{{ round($overallAvg) }}%</span>
                </div>
                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $overallAvg }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subjects Grades List -->
    <div class="space-y-10">
        <h2 class="text-xl font-black text-slate-800 tracking-tight flex items-center gap-3">
            <i class="material-icons text-indigo-500">book</i>
            Nilai Per Mata Pelajaran
        </h2>

        @forelse($grades as $item)
        <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm overflow-hidden">
            <!-- Subject Header -->
            <div class="p-8 border-b border-slate-50 bg-slate-50/30 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-indigo-500 shadow-sm">
                        <i class="material-icons text-2xl">menu_book</i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight leading-none">{{ $item['subject']->nama_pelajaran }}</h3>
                        <p class="text-xs text-slate-400 font-bold mt-2 uppercase tracking-widest">{{ $item['subject']->guru->nama ?? 'Guru Pengampu' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="px-4 py-2 bg-indigo-50 rounded-xl text-center min-w-[80px]">
                        <p class="text-[9px] font-black text-indigo-400 uppercase mb-0.5">RATA TUGAS</p>
                        <p class="text-sm font-black text-indigo-600">{{ number_format($item['avg_assignment'], 1) }}</p>
                    </div>
                    <div class="px-4 py-2 bg-purple-50 rounded-xl text-center min-w-[80px]">
                        <p class="text-[9px] font-black text-purple-400 uppercase mb-0.5">RATA UJIAN</p>
                        <p class="text-sm font-black text-purple-600">{{ number_format($item['avg_exam'], 1) }}</p>
                    </div>
                </div>
            </div>

            <div class="p-8 grid grid-cols-1 lg:grid-cols-2 gap-10">
                <!-- Assignments Column -->
                <div class="space-y-6">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        <i class="material-icons text-base text-indigo-300">description</i>
                        Daftar Nilai Tugas
                    </h4>
                    <div class="space-y-3">
                        @forelse($item['assignments'] as $assign)
                        <div class="p-4 rounded-2xl bg-white border border-slate-100 flex items-center justify-between group hover:border-indigo-100 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-500 flex items-center justify-center transition-all">
                                    <i class="material-icons text-xl">assignment</i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-700 leading-none">{{ $assign->module->title }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium mt-1">{{ $assign->created_at->translatedFormat('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="text-lg font-black {{ $assign->score >= 75 ? 'text-emerald-500' : 'text-amber-500' }} leading-none">{{ $assign->score }}</span>
                                <span class="text-[9px] font-black text-slate-200 mt-1 uppercase">Points</span>
                            </div>
                        </div>
                        @empty
                        <p class="text-center py-6 text-slate-400 text-xs font-medium italic border-2 border-dashed border-slate-50 rounded-2xl">Belum ada tugas yang dinilai</p>
                        @endforelse
                    </div>
                </div>

                <!-- Exams Column -->
                <div class="space-y-6">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        <i class="material-icons text-base text-purple-300">quiz</i>
                        Hasil Ujian CBT
                    </h4>
                    <div class="space-y-3">
                        @forelse($item['exams'] as $exam)
                        <div class="p-4 rounded-2xl bg-white border border-slate-100 flex items-center justify-between group hover:border-purple-100 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 group-hover:bg-purple-50 group-hover:text-purple-500 flex items-center justify-center transition-all">
                                    <i class="material-icons text-xl">psychology</i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-700 leading-none">{{ $exam->cbt->nama_cbt }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium mt-1">{{ $exam->created_at->translatedFormat('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="text-lg font-black {{ $exam->skor >= 75 ? 'text-emerald-500' : 'text-amber-500' }} leading-none">{{ $exam->skor }}</span>
                                <span class="text-[9px] font-black text-slate-200 mt-1 uppercase">Skor</span>
                            </div>
                        </div>
                        @empty
                        <p class="text-center py-6 text-slate-400 text-xs font-medium italic border-2 border-dashed border-slate-50 rounded-2xl">Belum ada data ujian CBT</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-100 flex flex-col items-center text-center">
            <div class="w-20 h-20 bg-slate-50 text-slate-200 rounded-[2rem] flex items-center justify-center mb-6">
                <i class="material-icons text-4xl">rule</i>
            </div>
            <h4 class="text-lg font-black text-slate-400 uppercase tracking-widest">Belum Ada Data Nilai</h4>
            <p class="text-slate-400 text-sm mt-2">Selesaikan tugas dan ujianmu untuk mulai melihat perkembangan prestasimu.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
