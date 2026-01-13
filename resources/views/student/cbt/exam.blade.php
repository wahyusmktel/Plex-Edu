@extends('layouts.app')

@section('title', 'Sedang Ujian: ' . $session->cbt->nama_cbt)

@section('content')
<div x-data="examPage()" x-init="init()" class="max-w-6xl mx-auto">
    
    <!-- Exam Header sticky -->
    <div class="sticky top-20 z-20 bg-white/95 backdrop-blur-md rounded-[2rem] border border-slate-100 shadow-xl p-6 mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-slate-800 text-white flex items-center justify-center">
                <i class="material-icons text-2xl">timer</i>
            </div>
            <div>
                <h1 class="text-lg font-black text-slate-800 tracking-tight line-clamp-1">{{ $session->cbt->nama_cbt }}</h1>
                <p class="text-slate-400 font-bold text-[10px] uppercase tracking-widest">{{ $session->cbt->subject->nama_pelajaran ?? 'Assessment' }}</p>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <div class="text-center">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Sisa Waktu</p>
                <div class="px-5 py-2 bg-rose-50 border border-rose-100 rounded-xl">
                    <span class="text-xl font-black text-rose-500 tracking-tighter" x-text="formatTime(timeLeft)">00:00:00</span>
                </div>
            </div>
            <div class="text-center hidden md:block">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Dijawab</p>
                <div class="px-5 py-2 bg-emerald-50 border border-emerald-100 rounded-xl">
                    <span class="text-xl font-black text-emerald-500" x-text="answeredCount + '/' + totalQuestions">0/0</span>
                </div>
            </div>
            <button @click="submitExam()" class="px-6 py-3 bg-[#d90d8b] text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-pink-100 hover:scale-[1.05] transition-all cursor-pointer">
                KIRIM
            </button>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Main Content: Single Question -->
        <div class="flex-grow">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-10 md:p-12 min-h-[60vh]">
                <template x-for="(q, index) in questions" :key="q.id">
                    <div x-show="currentQuestion === index" x-transition class="space-y-8">
                        <!-- Question Header -->
                        <div class="flex items-center justify-between pb-6 border-b border-slate-50">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-800 text-white flex items-center justify-center font-black text-lg" x-text="index + 1"></div>
                                <div>
                                    <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest" :class="q.jenis_soal === 'pilihan_ganda' ? 'bg-blue-50 text-blue-500' : 'bg-purple-50 text-purple-500'" x-text="q.jenis_soal === 'pilihan_ganda' ? 'Pilihan Ganda' : 'Esai'"></span>
                                    <p class="text-[10px] font-bold text-slate-400 mt-1" x-text="q.poin + ' Poin'"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Question Image -->
                        <template x-if="q.gambar">
                            <div class="w-full max-w-xl rounded-2xl overflow-hidden border border-slate-100 shadow-sm">
                                <img :src="'{{ asset('storage') }}/' + q.gambar" class="w-full h-auto">
                            </div>
                        </template>

                        <!-- Question Text -->
                        <div class="text-xl md:text-2xl font-bold text-slate-700 leading-relaxed" x-html="q.pertanyaan.replace(/\n/g, '<br>')"></div>

                        <!-- Answer options for Multiple Choice -->
                        <template x-if="q.jenis_soal === 'pilihan_ganda'">
                            <div class="space-y-4 pt-6">
                                <template x-for="(opt, optIndex) in q.options" :key="opt.id">
                                    <label class="relative group cursor-pointer block">
                                        <input 
                                            type="radio" 
                                            :name="'answer_' + q.id" 
                                            :value="opt.id" 
                                            x-model="answers[q.id]"
                                            @change="saveAnswer(q.id)"
                                            class="peer hidden"
                                        >
                                        <div class="p-5 rounded-2xl bg-slate-50 border-2 border-transparent peer-checked:bg-pink-50 peer-checked:border-[#d90d8b] group-hover:bg-white group-hover:border-slate-200 transition-all flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center font-black text-sm shrink-0 peer-checked:border-[#d90d8b] peer-checked:bg-[#d90d8b] peer-checked:text-white transition-all" x-text="String.fromCharCode(65 + optIndex)"></div>
                                            <span class="font-bold text-lg" x-text="opt.opsi"></span>
                                        </div>
                                    </label>
                                </template>
                            </div>
                        </template>

                        <!-- Answer area for Essay -->
                        <template x-if="q.jenis_soal === 'essay'">
                            <div class="space-y-3 pt-6">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Jawaban Anda</label>
                                <textarea 
                                    x-model="answers[q.id]"
                                    @blur="saveAnswer(q.id)"
                                    rows="8" 
                                    class="w-full px-6 py-5 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700 text-lg placeholder:text-slate-300"
                                    placeholder="Tuliskan jawaban Anda di sini..."
                                ></textarea>
                            </div>
                        </template>

                        <!-- Navigation Buttons -->
                        <div class="flex items-center justify-between pt-8 border-t border-slate-50">
                            <button 
                                @click="prevQuestion()" 
                                :disabled="currentQuestion === 0"
                                :class="currentQuestion === 0 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-slate-100'"
                                class="flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold text-slate-500 transition-all"
                            >
                                <i class="material-icons">arrow_back</i> Sebelumnya
                            </button>
                            <button 
                                @click="nextQuestion()" 
                                :disabled="currentQuestion === questions.length - 1"
                                :class="currentQuestion === questions.length - 1 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-slate-800'"
                                class="flex items-center gap-2 px-6 py-3 bg-slate-700 text-white rounded-xl text-sm font-bold transition-all"
                            >
                                Selanjutnya <i class="material-icons">arrow_forward</i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Sidebar: Question Navigator -->
        <div class="lg:w-72 shrink-0">
            <div class="sticky top-44 bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 space-y-6">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Navigasi Soal</h3>
                <div class="grid grid-cols-5 gap-2">
                    <template x-for="(q, index) in questions" :key="q.id">
                        <button 
                            @click="goToQuestion(index)"
                            :class="{
                                'bg-[#d90d8b] text-white border-[#d90d8b]': currentQuestion === index,
                                'bg-emerald-100 text-emerald-600 border-emerald-200': currentQuestion !== index && answers[q.id],
                                'bg-slate-50 text-slate-400 border-slate-100': currentQuestion !== index && !answers[q.id]
                            }"
                            class="w-10 h-10 rounded-xl border font-black text-sm transition-all hover:scale-110"
                            x-text="index + 1"
                        ></button>
                    </template>
                </div>
                <div class="pt-4 border-t border-slate-50 space-y-2">
                    <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400">
                        <span class="w-4 h-4 rounded bg-emerald-100 border border-emerald-200"></span> Sudah Dijawab
                    </div>
                    <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400">
                        <span class="w-4 h-4 rounded bg-slate-50 border border-slate-100"></span> Belum Dijawab
                    </div>
                    <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400">
                        <span class="w-4 h-4 rounded bg-[#d90d8b]"></span> Sedang Dikerjakan
                    </div>
                </div>

                <button @click="submitExam()" class="w-full py-4 bg-slate-800 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-700 transition-all">
                    KIRIM JAWABAN
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    function examPage() {
        return {
            questions: @json($session->cbt->questions),
            answers: {},
            currentQuestion: 0,
            timeLeft: 0,
            timer: null,
            totalQuestions: {{ count($session->cbt->questions) }},
            answeredQuestionIds: @json($answeredQuestionIds),

            get answeredCount() {
                return Object.values(this.answers).filter(a => a !== null && a !== '').length;
            },

            init() {
                // Load previously saved answers
                this.loadSavedAnswers();
                this.initTimer();
            },

            loadSavedAnswers() {
                @foreach($session->answers as $ans)
                    @if($ans->option_id)
                        this.answers['{{ $ans->question_id }}'] = '{{ $ans->option_id }}';
                    @elseif($ans->essay_answer)
                        this.answers['{{ $ans->question_id }}'] = `{!! addslashes($ans->essay_answer) !!}`;
                    @endif
                @endforeach
            },

            initTimer() {
                const endTime = new Date("{{ $session->cbt->tanggal->format('Y-m-d') }} {{ $session->cbt->jam_selesai }}").getTime();
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
                        text: 'Ujian telah berakhir. Sistem akan mencoba mengirimkan jawaban Anda.',
                        icon: 'warning',
                        allowOutsideClick: false,
                        confirmButtonText: 'Oke'
                    }).then(() => {
                        this.forceSubmit();
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

            prevQuestion() {
                if (this.currentQuestion > 0) this.currentQuestion--;
            },

            nextQuestion() {
                if (this.currentQuestion < this.questions.length - 1) this.currentQuestion++;
            },

            goToQuestion(index) {
                this.currentQuestion = index;
            },

            saveAnswer(questionId) {
                const answer = this.answers[questionId];
                if (!answer) return;

                $.post("{{ route('test.saveAnswer', $session->id) }}", {
                    _token: "{{ csrf_token() }}",
                    question_id: questionId,
                    answer: answer
                }).fail((err) => {
                    console.error('Auto-save failed:', err);
                });
            },

            forceSubmit() {
                Swal.fire({ title: 'Mengirim...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                
                $.post("{{ route('test.submit', $session->id) }}", {
                    _token: "{{ csrf_token() }}"
                }, (res) => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Selesai!',
                        text: res.success,
                        confirmButtonText: 'Lihat Hasil'
                    }).then(() => {
                        if (res.show_result && res.result_url) {
                            window.location.href = res.result_url;
                        } else {
                            window.location.href = "{{ route('test.index') }}";
                        }
                    });
                }).fail((err) => {
                    Swal.fire('Gagal!', err.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                });
            },

            submitExam() {
                if (this.answeredCount < this.totalQuestions) {
                    Swal.fire({
                        title: 'Belum Selesai!',
                        text: `Anda baru menjawab ${this.answeredCount} dari ${this.totalQuestions} soal. Yakin ingin mengirim?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d90d8b',
                        confirmButtonText: 'Kirim Saja',
                        cancelButtonText: 'Kembali'
                    }).then((result) => {
                        if (!result.isConfirmed) return;
                        this.forceSubmit();
                    });
                    return;
                }

                Swal.fire({
                    title: 'Kirim Jawaban?',
                    text: "Pastikan semua jawaban sudah benar sebelum mengirim.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d90d8b',
                    confirmButtonText: 'Ya, Kirim Sekarang'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.forceSubmit();
                    }
                });
            }
        }
    }
</script>
@endsection
