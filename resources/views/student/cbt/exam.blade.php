@extends('layouts.app')

@section('title', 'Sedang Ujian: ' . $session->cbt->nama_cbt)

@section('content')
<div x-data="examPage()" x-init="init()" class="max-w-5xl mx-auto space-y-10 pb-20">
    
    <!-- Exam Header sticky -->
    <div class="sticky top-24 z-20 bg-white/80 backdrop-blur-md rounded-[2.5rem] border border-slate-100 shadow-xl p-8 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-6">
            <div class="w-14 h-14 rounded-2xl bg-slate-800 text-white flex items-center justify-center">
                <i class="material-icons text-3xl">timer</i>
            </div>
            <div>
                <h1 class="text-xl font-black text-slate-800 tracking-tight line-clamp-1">{{ $session->cbt->nama_cbt }}</h1>
                <p class="text-slate-400 font-bold text-[10px] uppercase tracking-widest mt-1">{{ $session->cbt->subject->nama_pelajaran ?? 'Assessment' }} • {{ count($session->cbt->questions) }} Soal</p>
            </div>
        </div>

        <div class="flex items-center gap-8">
            <div class="text-center">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Sisa Waktu</p>
                <div class="px-6 py-2 bg-rose-50 border border-rose-100 rounded-xl">
                    <span class="text-2xl font-black text-rose-500 tracking-tighter" x-text="formatTime(timeLeft)">00:00:00</span>
                </div>
            </div>
            <button @click="submitExam()" class="px-8 py-4 bg-[#d90d8b] text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-pink-100 hover:scale-[1.05] transition-all cursor-pointer">
                SELESAI & KIRIM
            </button>
        </div>
    </div>

    <!-- Questions list -->
    <div class="space-y-10">
        @foreach($session->cbt->questions as $index => $q)
        <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm p-10 md:p-16 space-y-10">
            <div class="flex items-start gap-8">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-800 flex items-center justify-center font-black shrink-0 border border-slate-200">
                    {{ $index + 1 }}
                </div>
                <div class="flex-grow space-y-8">
                    <!-- Pertanyaan Content -->
                    <div class="space-y-8">
                        @if($q->gambar)
                        <div class="w-full max-w-2xl rounded-[2rem] overflow-hidden border border-slate-100 shadow-sm">
                            <img src="{{ asset('storage/' . $q->gambar) }}" class="w-full h-auto">
                        </div>
                        @endif
                        <div class="text-xl md:text-2xl font-bold text-slate-700 leading-relaxed">
                            {!! nl2br(e($q->pertanyaan)) !!}
                        </div>
                    </div>

                    <!-- Answers area -->
                    <div class="pt-8 border-t border-slate-50">
                        @if($q->jenis_soal == 'pilihan_ganda')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($q->options as $optIndex => $opt)
                            <label class="relative group cursor-pointer">
                                <input 
                                    type="radio" 
                                    name="answers[{{ $q->id }}]" 
                                    value="{{ $opt->id }}" 
                                    x-model="answers['{{ $q->id }}']"
                                    class="peer hidden"
                                >
                                <div class="p-6 rounded-2xl bg-slate-50 border-2 border-transparent peer-checked:bg-pink-50 peer-checked:border-[#d90d8b] peer-checked:text-[#d90d8b] group-hover:bg-white group-hover:border-slate-200 transition-all flex items-center gap-4">
                                    <div class="w-8 h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center font-black text-xs shrink-0 group-hover:scale-110 transition-transform peer-checked:border-[#d90d8b] peer-checked:bg-[#d90d8b] peer-checked:text-white">
                                        {{ chr(65 + $optIndex) }}
                                    </div>
                                    <span class="font-bold text-lg leading-tight">{{ $opt->opsi }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @else
                        <div class="space-y-4">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Ketikkan Jawaban Esai Anda</label>
                            <textarea 
                                x-model="answers['{{ $q->id }}']"
                                rows="6" 
                                class="w-full px-8 py-6 bg-slate-50 border-2 border-transparent rounded-[2rem] focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700 text-lg placeholder:text-slate-300"
                                placeholder="..."
                            ></textarea>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Final Submit Button bottom -->
    <div class="pt-10 flex flex-col items-center gap-6">
        <p class="text-slate-400 font-bold text-sm">Pastikan semua soal telah terjawab dengan benar.</p>
        <button @click="submitExam()" class="px-16 py-6 bg-slate-800 text-white rounded-[2.5rem] text-sm font-black uppercase tracking-[0.2em] shadow-2xl hover:bg-slate-700 hover:scale-[1.05] active:scale-[0.98] transition-all cursor-pointer">
            KIRIM JAWABAN UJIAN SEKARANG
        </button>
    </div>

</div>

@endsection

@section('scripts')
<script>
    function examPage() {
        return {
            answers: {},
            timeLeft: 0,
            timer: null,

            init() {
                this.initTimer();
            },

            initTimer() {
                const endTime = new Date("{{ $session->cbt->tanggal }} {{ $session->cbt->jam_selesai }}").getTime();
                this.updateClock(endTime);
                
                this.timer = setInterval(() => {
                    this.updateClock(endTime);
                }, 1000);
            },

            updateClock(endTime) {
                const now = new Date().getTime();
                const distance = endTime - now;
                
                if (distance < 0) {
                    clearInterval(this.timer);
                    this.timeLeft = 0;
                    Swal.fire({
                        title: 'Waktu Habis!',
                        text: 'Ujian telah berakhir. Sistem akan mencoba mengirimkan jawaban Anda secara otomatis.',
                        icon: 'warning',
                        confirmButtonText: 'Oke'
                    }).then(() => {
                        this.submitExam();
                    });
                } else {
                    this.timeLeft = Math.floor(distance / 1000);
                }
            },

            formatTime(seconds) {
                const h = Math.floor(seconds / 3600);
                const m = Math.floor((seconds % 3600) / 60);
                const s = Math.floor(seconds % 60);
                return [h, m, s].map(v => v < 10 ? "0" + v : v).join(":");
            },

            submitExam() {
                Swal.fire({
                    title: 'Selesai Ujian?',
                    text: "Apakah Anda yakin ingin mengirimkan seluruh jawaban Anda?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d90d8b',
                    confirmButtonText: 'Ya, Kirim Sekarang'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({ title: 'Sedang mengirim...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                        
                        $.post("{{ route('test.submit', $session->id) }}", {
                            _token: "{{ csrf_token() }}",
                            answers: this.answers
                        }, (res) => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.success,
                                confirmButtonText: 'Halaman Utama'
                            }).then(() => {
                                window.location.href = "{{ route('test.index') }}";
                            });
                        }).fail((err) => {
                            Swal.fire('Gagal!', err.responseJSON?.message || 'Terjadi kesalahan saat mengirim jawaban.', 'error');
                        });
                    }
                });
            }
        }
    }
</script>
@endsection
