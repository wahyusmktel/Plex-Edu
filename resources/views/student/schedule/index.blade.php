@extends('layouts.app')

@section('title', 'Jadwal Pelajaran Kelas - Literasia')

@section('content')
<div x-data="{ activeDay: '{{ now()->translatedFormat('l') === 'Sunday' ? 'Senin' : now()->translatedFormat('l') }}' }" class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Jadwal Pelajaran</h1>
            <p class="text-slate-500 font-medium mt-1">Seluruh jadwal pelajaran mingguan untuk kelas <strong>{{ $siswa->kelas->nama ?? '-' }}</strong></p>
        </div>
        <div class="px-6 py-3 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-pink-50 text-[#d90d8b] flex items-center justify-center">
                <i class="material-icons">event</i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">SEMESTER</p>
                <p class="text-sm font-bold text-slate-700 mt-1">Ganjil 2024/2025</p>
            </div>
        </div>
    </div>

    <!-- Day Navigation Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-4 custom-scrollbar scroll-smooth no-scrollbar">
        @foreach($days as $day)
        <button 
            @click="activeDay = '{{ $day }}'"
            :class="activeDay === '{{ $day }}' ? 'bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white shadow-lg shadow-pink-100 scale-105' : 'bg-white text-slate-400 hover:text-slate-600 border-slate-100'"
            class="px-8 py-3 rounded-2xl text-xs font-black uppercase tracking-widest border transition-all flex-shrink-0 cursor-pointer"
        >
            {{ $day }}
        </button>
        @endforeach
    </div>

    <!-- Schedule Content -->
    <div class="grid grid-cols-1 gap-6">
        @foreach($days as $day)
        <div x-show="activeDay === '{{ $day }}'" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm p-10">
                <div class="flex items-center justify-between mb-10">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center">
                            <i class="material-icons">list_alt</i>
                        </div>
                        <h3 class="text-lg font-black text-slate-800 tracking-tight">Jadwal Hari {{ $day }}</h3>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($schedules->get($day, collect()) as $index => $item)
                    <div class="group p-6 bg-slate-50/50 rounded-[2rem] border border-slate-100 hover:bg-white hover:border-[#ba80e8] hover:shadow-xl hover:shadow-purple-50 transition-all flex flex-col justify-between">
                        <div class="space-y-6">
                            <div class="flex items-start justify-between">
                                <div class="w-12 h-12 bg-white rounded-2xl flex flex-col items-center justify-center shadow-sm border border-slate-100 group-hover:bg-indigo-50 group-hover:border-indigo-100 transition-colors">
                                    <span class="text-[9px] font-black text-slate-300 leading-none group-hover:text-indigo-300 uppercase">Jam</span>
                                    <span class="text-sm font-black text-slate-700 leading-none mt-1 group-hover:text-indigo-600">{{ $loop->iteration }}</span>
                                </div>
                                <span class="px-4 py-1.5 bg-white border border-slate-100 rounded-full text-[9px] font-black text-slate-400 group-hover:text-indigo-500 group-hover:border-indigo-100 uppercase tracking-widest transition-all">
                                    {{ $item->jam->jam_mulai->format('H:i') }} - {{ $item->jam->jam_selesai->format('H:i') }}
                                </span>
                            </div>

                            <div>
                                <h4 class="text-lg font-black text-slate-800 tracking-tight group-hover:text-[#ba80e8] transition-colors line-clamp-2 leading-snug">{{ $item->subject->nama_pelajaran }}</h4>
                                <div class="flex items-center gap-2 mt-4 text-slate-400 group-hover:text-slate-500 transition-colors">
                                    <div class="w-6 h-6 rounded-lg bg-white border border-slate-100 flex items-center justify-center">
                                        <i class="material-icons text-sm">person</i>
                                    </div>
                                    <span class="text-xs font-bold">{{ $item->subject->guru->nama ?? 'Guru Pengampu' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
                             <a href="{{ route('student.subjects.show', $item->subject_id) }}" class="text-[9px] font-black text-slate-300 hover:text-[#ba80e8] uppercase tracking-widest transition-all">Lihat Detail Mapel</a>
                             <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-300 flex items-center justify-center group-hover:bg-[#ba80e8] group-hover:text-white transition-all scale-75 group-hover:scale-100">
                                 <i class="material-icons text-xs">arrow_forward</i>
                             </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-16 flex flex-col items-center text-center">
                        <div class="w-20 h-20 bg-slate-50 text-slate-200 rounded-[2rem] flex items-center justify-center mb-6">
                            <i class="material-icons text-4xl">event_busy</i>
                        </div>
                        <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest">Tidak Ada Jadwal</h4>
                        <p class="text-slate-400 text-xs mt-1">Nikmati waktu luangmu atau hubungi admin jika ini kesalahan.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection
