@extends('layouts.app')

@section('title', $module->title . ' - Modul')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-8">
        <a href="{{ route('elearning.index') }}" class="hover:text-[#d90d8b] transition-colors">E-LEARNING</a>
        <i class="material-icons text-xs">chevron_right</i>
        <a href="{{ route('elearning.show', $module->chapter->e_learning_id) }}" class="hover:text-[#d90d8b] transition-colors">{{ $module->chapter->course->title }}</a>
        <i class="material-icons text-xs">chevron_right</i>
        <span class="text-slate-300">{{ $module->title }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Content Area -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                <!-- Module Header -->
                <div class="p-8 md:p-12 bg-slate-50/50 border-b border-slate-50">
                    <div class="flex items-center gap-6 mb-8">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg 
                            @if($module->type === 'material') bg-blue-50 text-blue-400 
                            @elseif($module->type === 'assignment') bg-amber-50 text-amber-500 
                            @elseif($module->type === 'exercise') bg-emerald-50 text-emerald-500 
                            @else bg-rose-50 text-rose-400 @endif">
                            <i class="material-icons text-4xl">
                                @if($module->type === 'material') article
                                @elseif($module->type === 'assignment') task
                                @elseif($module->type === 'exercise') quiz
                                @else psychological_test @endif
                            </i>
                        </div>
                        <div>
                            <span class="text-[10px] font-black text-[#d90d8b] uppercase tracking-[0.2em] mb-2 block">{{ $module->type }}</span>
                            <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight">{{ $module->title }}</h1>
                        </div>
                    </div>

                    @if($module->due_date)
                    <div class="flex items-center gap-3 p-4 bg-white rounded-2xl border border-rose-50 text-rose-500">
                        <i class="material-icons text-lg">event_busy</i>
                        <span class="text-xs font-black uppercase tracking-widest">Batas Waktu: {{ \Carbon\Carbon::parse($module->due_date)->format('d M Y, H:i') }}</span>
                    </div>
                    @endif
                </div>

                <!-- Module Content -->
                <div class="p-8 md:p-12">
                    <div class="prose max-w-none text-slate-600 font-medium leading-[1.8] text-lg mb-10">
                        @if($module->content)
                            {!! nl2br(e($module->content)) !!}
                        @else
                            <p class="text-slate-400 italic">Tidak ada konten uraian untuk modul ini.</p>
                        @endif
                    </div>

                    @if($module->file_path)
                    <div class="mt-10 p-6 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-slate-400">
                                <i class="material-icons text-2xl">download_for_offline</i>
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-700">Lampiran Materi</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Klik untuk mengunduh file</p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $module->file_path) }}" target="_blank" class="px-6 py-3 bg-white text-slate-600 text-[10px] font-black rounded-xl border border-slate-100 shadow-sm group-hover:bg-[#d90d8b] group-hover:text-white group-hover:border-[#d90d8b] transition-all uppercase tracking-widest">
                            UNDUH SEKARANG
                        </a>
                    </div>
                    @endif

                    <!-- Action: Exam/Exercise -->
                    @if(($module->type === 'exam' || $module->type === 'exercise') && $module->cbt)
                    <div class="mt-12 p-10 bg-gradient-to-br from-slate-900 to-slate-800 rounded-[2rem] text-center shadow-xl shadow-slate-200">
                        <div class="w-20 h-20 rounded-3xl bg-white/10 flex items-center justify-center text-white mx-auto mb-6">
                            <i class="material-icons text-4xl">computer</i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">{{ $module->cbt->nama_cbt }}</h3>
                        <p class="text-slate-400 text-sm font-medium mb-8">Ujian ini memerlukan token akses untuk memulai. Pastikan Anda sudah siap.</p>
                        
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 mb-8 inline-block mx-auto">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">GUNAKAN TOKEN INI</p>
                            <span class="text-2xl font-black text-white tracking-[0.3em] font-mono">{{ $module->cbt->token }}</span>
                        </div>

                        <form action="{{ route('test.join') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $module->cbt->token }}">
                            <button type="submit" class="w-full py-5 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white font-black rounded-2xl shadow-lg shadow-pink-900/20 hover:scale-[1.02] active:scale-95 transition-all text-xs uppercase tracking-[0.2em]">
                                MULAI KERJAKAN TEST
                            </button>
                        </form>
                    </div>
                    @endif

                    @if(auth()->user()->role === 'siswa')
                    <div class="mt-12 flex justify-center border-t border-slate-50 pt-10">
                        @if(!($module->is_completed ?? false))
                        <form action="{{ route('elearning.module.complete', $module->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 px-10 py-5 bg-gradient-to-r from-emerald-400 to-emerald-600 text-white font-black rounded-2xl shadow-lg shadow-emerald-100 hover:scale-[1.02] active:scale-95 transition-all text-xs uppercase tracking-[0.2em] cursor-pointer">
                                <i class="material-icons">check_circle</i>
                                SELESAI MEMPELAJARI
                            </button>
                        </form>
                        @else
                        <div class="flex items-center gap-3 px-10 py-5 bg-emerald-50 text-emerald-600 font-black rounded-2xl border border-emerald-100 text-xs uppercase tracking-[0.2em]">
                            <i class="material-icons">verified</i>
                            MODUL SUDAH SELESAI
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Navigation Footer -->
            <div class="mt-8 flex items-center justify-between">
                <a href="{{ route('elearning.show', $module->chapter->e_learning_id) }}" class="flex items-center gap-2 text-xs font-black text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">
                    <i class="material-icons text-base">arrow_back</i>
                    KEMBALI KE KURIKULUM
                </a>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Course Info -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-8 text-center">
                <div class="relative w-32 h-32 mx-auto mb-6 rounded-3xl overflow-hidden bg-slate-50 border border-slate-100">
                    @if($module->chapter->course->thumbnail)
                        <img src="{{ asset('storage/' . $module->chapter->course->thumbnail) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-200">
                            <i class="material-icons text-4xl">school</i>
                        </div>
                    @endif
                </div>
                <h4 class="text-base font-black text-slate-800 mb-1">{{ $module->chapter->course->title }}</h4>
                <p class="text-[10px] font-bold text-[#ba80e8] uppercase tracking-widest mb-6">{{ $module->chapter->course->subject->nama_pelajaran }}</p>
                <div class="space-y-3">
                    <div class="p-4 bg-slate-50 rounded-2xl text-start">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">GURU PENGAJAR</p>
                        <p class="text-sm font-black text-slate-700">{{ $module->chapter->course->teacher->nama_lengkap ?? 'Administrator' }}</p>
                    </div>
                </div>
            </div>

            <!-- Chapter Context -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-8">
                <h5 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-6 border-b border-slate-50 pb-4">Isi BAB Ini</h5>
                <div class="space-y-4">
                    @foreach($module->chapter->modules as $sibling)
                    <a href="{{ route('elearning.module.view', $sibling->id) }}" class="flex items-center gap-4 group">
                        <div class="w-2 h-2 rounded-full {{ $sibling->id === $module->id ? 'bg-[#d90d8b]' : 'bg-slate-100' }} group-hover:scale-150 transition-transform"></div>
                        <span class="text-xs font-bold {{ $sibling->id === $module->id ? 'text-slate-800' : 'text-slate-400' }} group-hover:text-slate-600 transition-colors line-clamp-1">{{ $sibling->title }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
