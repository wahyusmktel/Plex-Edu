@extends('layouts.app')

@section('title', 'Monitoring Pelanggaran - Literasia')

@section('content')
<div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div class="flex items-center gap-5">
        <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 shadow-lg shadow-rose-100 flex items-center justify-center text-white">
            <i class="material-icons text-3xl">report_problem</i>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Ketertiban</p>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Monitoring Pelanggaran</h1>
        </div>
    </div>
</div>

<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                    <th class="py-6 px-8 border-b border-slate-50">Siswa / Pelanggar</th>
                    <th class="py-6 px-8 border-b border-slate-50">Asal Sekolah</th>
                    <th class="py-6 px-8 border-b border-slate-50">Jenis Pelanggaran</th>
                    <th class="py-6 px-8 border-b border-slate-50">Poin</th>
                    <th class="py-6 px-8 border-b border-slate-50">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($violations as $v)
                <tr class="group hover:bg-rose-50/30 transition-all">
                    <td class="py-6 px-8">
                        <p class="font-bold text-slate-700 font-medium">{{ $v->siswa->nama_lengkap ?? 'User' }}</p>
                        <p class="text-xs text-slate-400">NISN: {{ $v->siswa->nisn ?? '-' }}</p>
                    </td>
                    <td class="py-6 px-8 text-xs font-bold text-slate-600">
                        {{ $v->school->nama_sekolah ?? '-' }}
                    </td>
                    <td class="py-6 px-8 text-xs font-bold text-slate-600">
                        {{ $v->masterPelanggaran->nama_pelanggaran ?? 'Pelanggaran Umum' }}
                    </td>
                    <td class="py-6 px-8">
                        <span class="px-2.5 py-1 bg-red-50 text-red-600 font-black rounded text-[10px] border border-red-100 italic">
                            {{ $v->poin }} pts
                        </span>
                    </td>
                    <td class="py-6 px-8 text-xs font-bold text-slate-400">
                        {{ $v->created_at->translatedFormat('d F Y') }}
                    </td>
                </tr>
                @endforeach
                @if($violations->isEmpty())
                <tr>
                    <td colspan="5" class="py-20 text-center text-slate-400 font-bold italic">Belum ada data pelanggaran tercatat secara.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    @if($violations->hasPages())
    <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/30">
        {{ $violations->links() }}
    </div>
    @endif
</div>
@endsection
