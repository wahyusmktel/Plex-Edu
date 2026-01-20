@extends('layouts.app')

@section('title', 'Riwayat Pelanggaran - Literasia')

@section('content')
<div class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Catatan Pelanggaran</h1>
            <p class="text-slate-500 font-medium mt-1">Pantau kedisiplinan dan poin pelanggaran Anda</p>
        </div>
        <div class="px-6 py-4 bg-white border border-slate-100 rounded-[2rem] shadow-sm flex items-center gap-4 border-l-8 {{ $totalPoints > 50 ? 'border-red-500' : ($totalPoints > 20 ? 'border-amber-500' : 'border-emerald-500') }}">
            <div class="w-12 h-12 rounded-2xl {{ $totalPoints > 50 ? 'bg-red-50 text-red-500' : ($totalPoints > 20 ? 'bg-amber-50 text-amber-500' : 'bg-emerald-50 text-emerald-500') }} flex items-center justify-center">
                <i class="material-icons text-2xl">warning_amber</i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">AKUMULASI POIN</p>
                <p class="text-2xl font-black text-slate-800 mt-1">{{ $totalPoints }} <span class="text-xs font-bold text-slate-400">Poin</span></p>
            </div>
        </div>
    </div>

    <!-- Violation Timeline/List -->
    <div class="space-y-6">
        @forelse($violations as $violation)
        <div class="group bg-white rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all p-8 md:p-10 relative overflow-hidden">
             <!-- Status Accent -->
             <div class="absolute top-0 left-0 w-1.5 h-full {{ $violation->masterPelanggaran->poin >= 15 ? 'bg-red-500' : ($violation->masterPelanggaran->poin >= 5 ? 'bg-amber-500' : 'bg-emerald-500') }}"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Date & Icon -->
                <div class="lg:col-span-3 flex flex-row lg:flex-col items-center lg:items-start gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center group-hover:bg-slate-800 group-hover:text-white transition-all">
                        <i class="material-icons text-2xl">event_note</i>
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-800 tracking-tight">{{ \Carbon\Carbon::parse($violation->tanggal)->translatedFormat('d F Y') }}</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">TANGGAL KEJADIAN</p>
                    </div>
                </div>

                <!-- Content -->
                <div class="lg:col-span-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-black text-slate-800 tracking-tight leading-none">{{ $violation->masterPelanggaran->nama }}</h3>
                        <div class="mt-3 flex items-center gap-2">
                             <span class="px-3 py-1 bg-slate-50 text-slate-500 text-[10px] font-black rounded-full uppercase tracking-widest border border-slate-100 italic">{{ $violation->masterPelanggaran->jenis }}</span>
                        </div>
                    </div>

                    <div class="p-5 bg-slate-50/50 rounded-2xl border border-slate-50">
                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-2 flex items-center gap-2">
                            <i class="material-icons text-xs">notes</i>
                            Deskripsi Kejadian
                        </p>
                        <p class="text-xs font-medium text-slate-600 leading-relaxed">{{ $violation->deskripsi ?? 'Tidak ada keterangan tambahan.' }}</p>
                    </div>

                    @if($violation->tindak_lanjut)
                    <div class="p-5 bg-blue-50/30 rounded-2xl border border-blue-50/50">
                        <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                            <i class="material-icons text-xs">tips_and_updates</i>
                            Tindak Lanjut Sekolah
                        </p>
                        <p class="text-xs font-bold text-blue-700 leading-relaxed">{{ $violation->tindak_lanjut }}</p>
                    </div>
                    @endif
                </div>

                <!-- Points info -->
                <div class="lg:col-span-3 lg:text-right flex lg:flex-col items-center lg:items-end justify-between lg:justify-start gap-4 h-full">
                    <div class="lg:mb-auto">
                         <p class="text-xs font-black text-slate-300 uppercase tracking-widest">POIN PELANGGARAN</p>
                         <h4 class="text-3xl font-black {{ $violation->masterPelanggaran->poin >= 15 ? 'text-red-500' : ($violation->masterPelanggaran->poin >= 5 ? 'text-amber-500' : 'text-emerald-500') }} mt-1">+{{ $violation->masterPelanggaran->poin }}</h4>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-slate-50 text-slate-300 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="material-icons">gavel</i>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="py-24 bg-white rounded-[3rem] border-2 border-dashed border-slate-100 flex flex-col items-center text-center">
            <div class="w-24 h-24 bg-emerald-50 text-emerald-500 rounded-3xl flex items-center justify-center mb-6">
                <i class="material-icons text-5xl">verified_user</i>
            </div>
            <h4 class="text-xl font-black text-slate-800 tracking-tight">Prestasi Luar Biasa!</h4>
            <p class="text-slate-500 text-sm mt-2 max-w-sm mx-auto">Kami tidak menemukan catatan pelanggaran apapun. Pertahankan kedisiplinan dan perilakumu yang baik!</p>
        </div>
        @endforelse
    </div>

    <!-- Guidance Note -->
    @if($violations->isNotEmpty())
    <div class="p-6 bg-slate-800 rounded-[2rem] text-white flex flex-col md:flex-row items-center gap-6 shadow-xl shadow-slate-100">
        <div class="w-14 h-14 bg-slate-700 rounded-2xl flex items-center justify-center flex-shrink-0">
            <i class="material-icons text-amber-400">info_outline</i>
        </div>
        <div>
            <h4 class="font-black text-sm uppercase tracking-widest mb-1">Penting Untuk Diperhatikan</h4>
            <p class="text-xs text-slate-400 font-medium leading-relaxed">Akumulasi poin pelanggaran dapat mempengaruhi penilaian sikap dan mendapatkan sanksi sesuai aturan sekolah. Harap perbaiki disiplin diri untuk masa depan yang lebih baik.</p>
        </div>
    </div>
    @endif
</div>
@endsection
