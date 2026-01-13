@extends('layouts.app')

@section('title', 'Hasil Ujian - ' . $cbt->nama_cbt)

@section('content')
<div class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-6">
            <a href="{{ route('cbt.index') }}" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-[#d90d8b] transition-all shadow-sm">
                <i class="material-icons">arrow_back</i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Hasil Ujian</h1>
                <p class="text-slate-500 font-medium mt-1">{{ $cbt->nama_cbt }} ({{ $cbt->subject->nama_pelajaran ?? 'Umum' }})</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('cbt.analysis', $cbt->id) }}" class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
                <i class="material-icons text-lg">analytics</i> ANALISIS SOAL
            </a>
            <a href="{{ route('cbt.exportExcel', $cbt->id) }}" class="flex items-center gap-2 px-6 py-3 bg-emerald-500 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-600 transition-all">
                <i class="material-icons text-lg">table_chart</i> EXCEL
            </a>
            <a href="{{ route('cbt.exportPdf', $cbt->id) }}" class="flex items-center gap-2 px-6 py-3 bg-rose-500 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-rose-600 transition-all">
                <i class="material-icons text-lg">picture_as_pdf</i> PDF
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500">
                <i class="material-icons text-3xl">people</i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Peserta</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $cbt->sessions->count() }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500">
                <i class="material-icons text-3xl">check_circle</i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Selesai</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $cbt->sessions->where('status', 'completed')->count() }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500">
                <i class="material-icons text-3xl">trending_up</i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Rata-rata Skor</p>
                <h3 class="text-2xl font-black text-slate-800">{{ round($cbt->sessions->avg('skor'), 1) }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-500">
                <i class="material-icons text-3xl">emoji_events</i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Skor Tertinggi</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $cbt->sessions->max('skor') ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- Results Table -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">No</th>
                    <th class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Siswa</th>
                    <th class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Kelas</th>
                    <th class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Jam Mulai</th>
                    <th class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Jam Selesai</th>
                    <th class="px-6 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Skor</th>
                    <th class="px-6 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($cbt->sessions->sortByDesc('skor') as $index => $session)
                <tr class="hover:bg-slate-50/50">
                    <td class="px-6 py-4 text-sm font-bold text-slate-400">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($session->siswa->nama_lengkap ?? 'N') }}&background=ba80e8&color=fff" class="w-10 h-10 rounded-xl shadow-sm">
                            <div>
                                <p class="font-bold text-slate-700">{{ $session->siswa->nama_lengkap ?? 'N/A' }}</p>
                                <p class="text-[10px] text-slate-400 font-medium">{{ $session->siswa->nis ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-500">{{ $session->siswa->kelas->nama ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-500">{{ $session->start_time?->format('H:i:s') }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-500">{{ $session->end_time?->format('H:i:s') ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-4 py-2 bg-slate-100 rounded-xl text-lg font-black text-slate-700">{{ $session->skor }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($session->status == 'completed')
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-black uppercase">Selesai</span>
                        @else
                            <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-lg text-[10px] font-black uppercase">Berlangsung</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <i class="material-icons text-6xl text-slate-200 mb-4">inbox</i>
                            <p class="font-bold text-slate-400">Belum ada peserta yang mengerjakan ujian ini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
