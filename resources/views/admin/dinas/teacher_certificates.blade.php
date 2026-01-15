@extends('layouts.app')

@section('title', 'Sertifikat Guru Nasional - Literasia')

@section('content')
<div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div class="flex items-center gap-5">
        <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg shadow-emerald-100 flex items-center justify-center text-white">
            <i class="material-icons text-3xl">workspace_premium</i>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Pengembangan Karir</p>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Monitoring Sertifikat Guru</h1>
        </div>
    </div>
</div>

<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                    <th class="py-6 px-8 border-b border-slate-50">Nama Guru</th>
                    <th class="py-6 px-8 border-b border-slate-50">Sekolah Asal</th>
                    <th class="py-6 px-8 border-b border-slate-50">Jabatan / Mapel</th>
                    <th class="py-6 px-8 border-b border-slate-50">Status Sertifikat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($teachers as $teacher)
                <tr class="group hover:bg-slate-50/50 transition-all">
                    <td class="py-6 px-8">
                        <p class="font-bold text-slate-700 font-medium">{{ $teacher->name }}</p>
                        <p class="text-xs text-slate-400">{{ $teacher->email }}</p>
                    </td>
                    <td class="py-6 px-8 text-xs font-bold text-slate-600">
                        {{ $teacher->school->nama_sekolah ?? '-' }}
                    </td>
                    <td class="py-6 px-8">
                        <p class="text-xs font-bold text-slate-600 uppercase tracking-wider">{{ $teacher->fungsionaris->jabatan ?? 'Guru' }}</p>
                        <p class="text-[10px] text-slate-400">{{ $teacher->fungsionaris->mata_pelajaran ?? '-' }}</p>
                    </td>
                    <td class="py-6 px-8">
                        <span class="px-3 py-1 bg-slate-50 text-slate-400 text-[10px] font-black rounded-full uppercase border border-slate-100 italic">
                            Belum Ada Data
                        </span>
                        <!-- In the future, this can link to a detailed certificate list -->
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($teachers->hasPages())
    <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/30">
        {{ $teachers->links() }}
    </div>
    @endif
</div>
@endsection
