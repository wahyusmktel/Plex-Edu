@extends('layouts.app')

@section('title', 'Penilaian Esai')

@section('content')
<div class="space-y-8" x-data="{
    saveGrade(answerId, score) {
        if (score === '' || score < 0) {
            Swal.fire('Error', 'Nilai tidak valid', 'error');
            return;
        }

        let btn = document.getElementById('btn-' + answerId);
        let originalText = btn.innerText;
        btn.innerText = 'Menyimpan...';
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
    <div class="flex items-center gap-6">
        <a href="{{ route('cbt.analysis', $question->cbt_id) }}" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-[#d90d8b] transition-all shadow-sm">
            <i class="material-icons">arrow_back</i>
        </a>
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Penilaian Esai</h1>
            <p class="text-slate-500 font-medium mt-1">{{ $question->cbt->nama_cbt }} • Max Poin: {{ $question->poin }}</p>
        </div>
    </div>

    <!-- Question Card -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Pertanyaan</p>
        <div class="prose max-w-none text-slate-700 font-medium text-lg">
            {!! nl2br(e($question->pertanyaan)) !!}
        </div>
        @if($question->gambar)
        <div class="mt-6">
            <img src="{{ asset('storage/' . $question->gambar) }}" class="rounded-2xl max-h-64 object-cover">
        </div>
        @endif
    </div>

    <!-- Answers List -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50">
            <h3 class="text-xl font-black text-slate-800">Jawaban Siswa ({{ $question->answers->count() }})</h3>
        </div>
        
        <div class="divide-y divide-slate-50">
            @forelse($question->answers as $answer)
            <div class="p-8 hover:bg-slate-50 transition-colors group">
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Student Info -->
                    <div class="w-full md:w-64 shrink-0">
                        <div class="flex items-center gap-4 mb-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($answer->session->siswa->nama_lengkap ?? 'Siswa') }}&background=random&color=fff" class="w-10 h-10 rounded-xl">
                            <div>
                                <p class="font-bold text-slate-700">{{ $answer->session->siswa->nama_lengkap ?? 'Siswa Terhapus' }}</p>
                                <p class="text-xs text-slate-400 font-bold">{{ $answer->session->siswa->kelas->nama ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="px-3 py-1 rounded-lg text-xs font-black uppercase tracking-widest {{ $answer->is_graded ? 'bg-emerald-50 text-emerald-500' : 'bg-amber-50 text-amber-500' }}">
                                {{ $answer->is_graded ? 'Sudah Dinilai' : 'Belum Dinilai' }}
                            </span>
                        </div>
                    </div>

                    <!-- Answer & Grading -->
                    <div class="flex-grow space-y-4">
                        <div class="bg-slate-50 rounded-2xl p-6 text-slate-700 font-medium border border-slate-100">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Jawaban:</p>
                            {!! nl2br(e($answer->essay_answer ?? '- tidak menjawab -')) !!}
                        </div>
                        
                        <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                            <i class="material-icons text-slate-400">edit_note</i>
                            <div class="relative w-32">
                                <input 
                                    type="number" 
                                    min="0" 
                                    max="{{ $question->poin }}" 
                                    value="{{ $answer->poin_didapat }}" 
                                    id="score-{{ $answer->id }}"
                                    placeholder="0"
                                    class="w-full pl-4 pr-12 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-[#ba80e8] focus:ring-4 focus:ring-purple-50 transition-all outline-none font-bold text-slate-700 text-center"
                                >
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-black text-slate-400">/ {{ $question->poin }}</span>
                            </div>
                            <button 
                                id="btn-{{ $answer->id }}"
                                @click="saveGrade('{{ $answer->id }}', document.getElementById('score-{{ $answer->id }}').value)"
                                class="px-6 py-3 bg-slate-800 text-white rounded-xl font-bold hover:bg-[#d90d8b] active:scale-95 transition-all shadow-lg shadow-slate-200 hover:shadow-pink-200"
                            >
                                Simpan Nilai
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center text-slate-400">
                <i class="material-icons text-4xl mb-2">inbox</i>
                <p class="font-bold">Belum ada jawaban masuk.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
