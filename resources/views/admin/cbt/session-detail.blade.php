@extends('layouts.app')

@section('title', 'Detail Jawaban Siswa - ' . ($session->siswa->nama_lengkap ?? 'Siswa'))

@section('content')
<div class="space-y-8" x-data="{
    saveGrade(answerId, score) {
        if (score === '' || score < 0) {
            Swal.fire('Error', 'Nilai tidak valid', 'error');
            return;
        }

        let btn = document.getElementById('btn-' + answerId);
        let originalText = btn.innerText;
        btn.innerText = '...';
        btn.disabled = true;

        $.post('{{ route('cbt.gradeEssay.store') }}', {
            _token: '{{ csrf_token() }}',
            answer_id: answerId,
            poin: score
        }, (res) => {
            Swal.fire({
                icon: 'success',
                title: 'Tersimpan',
                text: res.success,
                timer: 1000,
                showConfirmButton: false
            }).then(() => location.reload());
        }).fail((err) => {
            btn.innerText = originalText;
            btn.disabled = false;
            let msg = err.responseJSON?.message || 'Terjadi kesalahan';
            Swal.fire('Gagal', msg, 'error');
        });
    }
}">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-6">
            <a href="javascript:history.back()" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-[#d90d8b] transition-all shadow-sm">
                <i class="material-icons">arrow_back</i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Detail Jawaban</h1>
                <p class="text-slate-500 font-medium mt-1">{{ $session->cbt->nama_cbt ?? 'CBT' }} • {{ $session->siswa->nama_lengkap ?? 'Siswa' }}</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
            <div class="text-center px-4 border-r border-slate-50">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">SKOR AKHIR</p>
                <h3 class="text-2xl font-black text-[#d90d8b]">{{ $session->skor }} <span class="text-xs text-slate-400">/ 100</span></h3>
            </div>
            <div class="text-center px-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">DURASI</p>
                <h3 class="text-base font-black text-slate-700">
                    @if($session->start_time && $session->end_time)
                        {{ $session->start_time->diffInMinutes($session->end_time) }} Menit
                    @else
                        -
                    @endif
                </h3>
            </div>
        </div>
    </div>

    <!-- Student Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Informasi Siswa</p>
            <div class="flex items-center gap-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($session->siswa->nama_lengkap ?? 'S') }}&background=ba80e8&color=fff" class="w-14 h-14 rounded-2xl shadow-sm">
                <div>
                    <h4 class="font-black text-slate-800">{{ $session->siswa->nama_lengkap ?? 'N/A' }}</h4>
                    <p class="text-xs font-bold text-slate-400">{{ $session->siswa->nisn ?? '-' }} • {{ $session->siswa->kelas->nama_kelas ?? '-' }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Waktu Pengerjaan</p>
            <div class="space-y-2">
                <div class="flex justify-between text-xs font-bold">
                    <span class="text-slate-400">Mulai:</span>
                    <span class="text-slate-700">{{ $session->start_time?->format('H:i:s, d M Y') ?? '-' }}</span>
                </div>
                <div class="flex justify-between text-xs font-bold">
                    <span class="text-slate-400">Selesai:</span>
                    <span class="text-slate-700">{{ $session->end_time?->format('H:i:s, d M Y') ?? '-' }}</span>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Ringkasan Soal</p>
            <div class="flex items-center gap-4">
                <div class="flex-1 text-center border-r border-slate-50">
                    <h5 class="text-xl font-black text-emerald-500">{{ $session->answers->where('poin_didapat', '>', 0)->count() }}</h5>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Benar</p>
                </div>
                <div class="flex-1 text-center border-r border-slate-50">
                    <h5 class="text-xl font-black text-rose-500">{{ $session->answers->where('poin_didapat', 0)->where('is_graded', true)->count() }}</h5>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Salah</p>
                </div>
                <div class="flex-1 text-center">
                    <h5 class="text-xl font-black text-amber-500">{{ $session->answers->where('is_graded', false)->count() }}</h5>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Pending</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Questions Detail -->
    <div class="space-y-6">
        <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Detail Jawaban per Soal</h3>
        
        @foreach($session->answers as $index => $answer)
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                <div class="flex items-center gap-4">
                    <span class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center font-black text-slate-400 text-sm shadow-sm">{{ $index + 1 }}</span>
                    <div>
                        <span class="text-[10px] font-black text-[#ba80e8] uppercase tracking-widest">{{ $answer->question->jenis_soal == 'pilihan_ganda' ? 'PILIHAN GANDA' : 'ESAI' }}</span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">POIN DIDAPAT</span>
                    <span class="px-3 py-1 bg-white border border-slate-100 rounded-lg text-sm font-black text-slate-700 shadow-sm">{{ $answer->poin_didapat }} <span class="text-[10px] text-slate-300">/ {{ $answer->question->poin }}</span></span>
                </div>
            </div>
            
            <div class="p-8">
                <!-- Question -->
                <div class="prose max-w-none text-slate-700 font-medium text-lg mb-8">
                    {!! nl2br(e($answer->question->pertanyaan)) !!}
                </div>
                
                @if($answer->question->gambar)
                <div class="mb-8">
                    <img src="{{ asset('storage/' . $answer->question->gambar) }}" class="rounded-2xl max-h-64 object-cover border border-slate-100">
                </div>
                @endif

                <!-- Answer Content -->
                @if($answer->question->jenis_soal == 'pilihan_ganda')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($answer->question->options as $option)
                        @php
                            $isSelected = $answer->option_id == $option->id;
                            $isCorrect = $option->is_correct;
                            
                            $bgColor = 'bg-slate-50 border-slate-100';
                            $icon = 'radio_button_unchecked';
                            $iconColor = 'text-slate-300';
                            
                            if ($isSelected && $isCorrect) {
                                $bgColor = 'bg-emerald-50 border-emerald-200';
                                $icon = 'check_circle';
                                $iconColor = 'text-emerald-500';
                            } elseif ($isSelected && !$isCorrect) {
                                $bgColor = 'bg-rose-50 border-rose-200';
                                $icon = 'cancel';
                                $iconColor = 'text-rose-500';
                            } elseif (!$isSelected && $isCorrect) {
                                $bgColor = 'bg-emerald-50 border-emerald-100 border-dashed';
                                $icon = 'check_circle_outline';
                                $iconColor = 'text-emerald-300';
                            }
                        @endphp
                        <div class="p-4 rounded-2xl border-2 flex items-center gap-4 {{ $bgColor }} transition-all">
                            <i class="material-icons {{ $iconColor }}">{{ $icon }}</i>
                            <span class="text-sm font-bold {{ $isSelected ? 'text-slate-800' : 'text-slate-500' }}">{{ $option->opsi }}</span>
                            @if($isSelected)
                                <span class="ml-auto text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full {{ $isCorrect ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">JAWABAN ANDA</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <!-- Essay Answer -->
                    <div class="space-y-6">
                        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">JAWABAN SISWA:</p>
                            <p class="text-slate-700 font-medium leading-relaxed italic">{{ $answer->essay_answer ?? '- Tidak menjawab -' }}</p>
                        </div>
                        
                        <div class="flex items-center gap-4 p-5 bg-white rounded-2xl border-2 border-slate-100 shadow-sm max-w-md">
                            <i class="material-icons text-slate-400">rate_review</i>
                            <div class="relative w-32">
                                <input 
                                    type="number" 
                                    min="0" 
                                    max="{{ $answer->question->poin }}" 
                                    value="{{ $answer->poin_didapat }}" 
                                    id="score-{{ $answer->id }}"
                                    class="w-full pl-4 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#ba80e8] focus:ring-0 transition-all outline-none font-black text-slate-700 text-center"
                                >
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-400">/ {{ $answer->question->poin }}</span>
                            </div>
                            <button 
                                id="btn-{{ $answer->id }}"
                                @click="saveGrade('{{ $answer->id }}', document.getElementById('score-{{ $answer->id }}').value)"
                                class="flex-grow py-3 bg-slate-800 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-[#d90d8b] active:scale-95 transition-all shadow-lg shadow-slate-200"
                            >
                                SIMPAN NILAI
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Navigation Footer -->
    <div class="mt-8 flex items-center justify-between">
        <a href="javascript:history.back()" class="flex items-center gap-2 text-xs font-black text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">
            <i class="material-icons text-base">arrow_back</i>
            KEMBALI
        </a>
    </div>
</div>
@endsection
