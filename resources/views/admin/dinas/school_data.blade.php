@extends('layouts.app')

@section('title', 'Data Sekolah - Literasia')

@section('content')
<div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div class="flex items-center gap-5">
        <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-100 flex items-center justify-center text-white">
            <i class="material-icons text-3xl">domain</i>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Analisis Data</p>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Data Sekolah Terdaftar</h1>
        </div>
    </div>
    
    <div class="flex gap-4">
        <a href="{{ route('dinas.schools', ['status' => 'Negeri']) }}" class="px-6 py-2.5 bg-white text-slate-600 text-sm font-bold rounded-xl border border-slate-100 hover:bg-slate-50 transition-all">Negeri</a>
        <a href="{{ route('dinas.schools', ['status' => 'Swasta']) }}" class="px-6 py-2.5 bg-white text-slate-600 text-sm font-bold rounded-xl border border-slate-100 hover:bg-slate-50 transition-all">Swasta</a>
        <a href="{{ route('dinas.schools') }}" class="px-6 py-2.5 bg-indigo-500 text-white text-sm font-bold rounded-xl shadow-md hover:bg-indigo-600 transition-all">Semua</a>
    </div>
</div>

<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                    <th class="py-6 px-8 border-b border-slate-50">Nama Sekolah</th>
                    <th class="py-6 px-8 border-b border-slate-50">NPSN</th>
                    <th class="py-6 px-8 border-b border-slate-50">Status</th>
                    <th class="py-6 px-8 border-b border-slate-50">Wilayah</th>
                    <th class="py-6 px-8 border-b border-slate-50">Koneksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($schools as $school)
                <tr class="group hover:bg-slate-50/50 transition-all">
                    <td class="py-6 px-8">
                        <p class="font-bold text-slate-700">{{ $school->nama_sekolah }}</p>
                        <p class="text-xs text-slate-400">{{ $school->jenjang }}</p>
                    </td>
                    <td class="py-6 px-8 font-mono text-xs font-bold text-slate-500">{{ $school->npsn }}</td>
                    <td class="py-6 px-8 text-xs font-bold text-slate-600">
                        @if($school->status_sekolah == 'Negeri')
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full border border-blue-100 uppercase text-[10px]">Negeri</span>
                        @else
                            <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-full border border-purple-100 uppercase text-[10px]">Swasta</span>
                        @endif
                    </td>
                    <td class="py-6 px-8 text-xs font-bold text-slate-600">{{ $school->kabupaten_kota }}, {{ $school->provinsi }}</td>
                    <td class="py-6 px-8">
                        @if($school->is_active)
                            <span class="flex items-center gap-1.5 text-emerald-600 text-[10px] font-black uppercase">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Terhubung
                            </span>
                        @else
                            <span class="flex items-center gap-1.5 text-slate-400 text-[10px] font-black uppercase">
                                <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span> Terputus
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($schools->hasPages())
    <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/30">
        {{ $schools->links() }}
    </div>
    @endif
</div>
@endsection
