@extends('layouts.app')

@section('title', $subject->nama_pelajaran . ' - Literasia')

@section('content')
<div class="space-y-8">
    
    <!-- Header & Back Button -->
    <div class="flex flex-col gap-6">
        <a href="{{ route('student.subjects.index') }}" class="flex items-center gap-2 text-indigo-500 font-bold text-sm w-fit hover:gap-3 transition-all">
            <i class="material-icons text-sm">arrow_back</i>
            Kembali ke Daftar
        </a>
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 rounded-[1.5rem] bg-indigo-500 text-white flex items-center justify-center shadow-lg shadow-indigo-100">
                    <i class="material-icons text-3xl">book</i>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight">{{ $subject->nama_pelajaran }}</h1>
                    <p class="text-slate-500 font-medium mt-1">Jadwal rutin mingguan untuk mata pelajaran ini</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Subject Info Card -->
        <div class="space-y-6">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8">
                <h3 class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-6">INFORMASI PENGAJAR</h3>
                
                <div class="flex items-center gap-5 mb-8">
                    <div class="w-14 h-14 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center overflow-hidden">
                        @if($subject->guru && $subject->guru->user && $subject->guru->user->avatar)
                            <img src="{{ asset('storage/' . $subject->guru->user->avatar) }}" class="w-full h-full object-cover">
                        @else
                            <i class="material-icons text-3xl text-slate-200">person</i>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-black text-slate-800 tracking-tight">{{ $subject->guru->nama ?? 'Guru Pengampu' }}</h4>
                        <p class="text-xs text-slate-400 font-medium">{{ $subject->guru->nip ?? 'NIP tidak tersedia' }}</p>
                    </div>
                </div>

                <div class="space-y-4 pt-6 border-t border-slate-50">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-400">Kode Pelajaran</span>
                        <span class="font-bold text-slate-700">{{ $subject->kode_pelajaran }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-400">Status</span>
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-500 text-[10px] font-black rounded-full uppercase">Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Weekly Schedule -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm p-10">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 bg-pink-50 text-pink-500 rounded-2xl flex items-center justify-center">
                        <i class="material-icons">event_note</i>
                    </div>
                    <h3 class="text-lg font-black text-slate-800 tracking-tight">Jadwal Mingguan</h3>
                </div>

                <div class="space-y-8">
                    @php 
                        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                    @endphp

                    @foreach($days as $day)
                    @if($schedules->has($day))
                    <div class="relative pl-16 border-l-2 border-indigo-100 pb-12 last:pb-0">
                        <!-- Dot -->
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full border-4 border-white bg-indigo-500 shadow-sm"></div>
                        
                        <div class="flex flex-col gap-8">
                            <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest opacity-80">{{ $day }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($schedules[$day] as $index => $sched)
                                <div class="p-6 bg-slate-50/50 rounded-3xl border border-slate-100 flex items-center justify-between group hover:bg-white hover:border-indigo-100 transition-all">
                                    <div class="flex items-center gap-5">
                                        <div class="w-12 h-12 bg-white rounded-2xl flex flex-col items-center justify-center shadow-sm border border-slate-100 group-hover:bg-indigo-50 group-hover:border-indigo-100 transition-colors">
                                            <span class="text-[9px] font-black text-slate-300 leading-none group-hover:text-indigo-300">JAM</span>
                                            <span class="text-sm font-black text-slate-700 leading-none mt-1 group-hover:text-indigo-600">{{ $index + 1 }}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-tight">SESI PELAJARAN</span>
                                            <span class="text-xs font-bold text-slate-700 mt-1 uppercase">{{ $sched->jam->jam_mulai->format('H:i') }} - {{ $sched->jam->jam_selesai->format('H:i') }}</span>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-slate-300 group-hover:text-indigo-400 group-hover:bg-indigo-50/50 transition-all">
                                        <i class="material-icons text-xl">schedule</i>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach

                    @if($schedules->isEmpty())
                    <div class="py-10 text-center">
                        <p class="text-slate-400 font-medium italic">Data jadwal belum diinput untuk mata pelajaran ini.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
