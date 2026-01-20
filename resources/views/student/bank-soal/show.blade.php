@extends('layouts.app')

@section('title', 'Belajar: ' . $bankSoal->title . ' - Literasia')

@section('content')
<div x-data="{ 
        currentQuestion: 0,
        totalQuestions: {{ $bankSoal->questions->count() }},
        questions: @js($bankSoal->questions),
        
        nextQuestion() {
            if (this.currentQuestion < this.totalQuestions - 1) {
                this.currentQuestion++;
            }
        },
        
        prevQuestion() {
            if (this.currentQuestion > 0) {
                this.currentQuestion--;
            }
        },
        
        goTo(index) {
            this.currentQuestion = index;
        }
    }" 
    class="space-y-8"
>
    <!-- Header Section -->
    <div class="flex flex-col gap-6">
        <a href="{{ route('student.bank-soal.index') }}" class="flex items-center gap-2 text-orange-500 font-bold text-sm w-fit hover:gap-3 transition-all">
            <i class="material-icons text-sm">arrow_back</i>
            Kembali ke Daftar
        </a>
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 rounded-[1.5rem] bg-orange-500 text-white flex items-center justify-center shadow-lg shadow-orange-100">
                    <i class="material-icons text-3xl">menu_book</i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-800 tracking-tight leading-tight">{{ $bankSoal->title }}</h1>
                    <p class="text-slate-500 font-medium mt-1">Siswa sedang mempelajari bank soal mandiri</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                 <div class="px-5 py-2.5 bg-white border border-slate-100 rounded-xl shadow-sm text-center">
                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest block">PROGRESS BELAJAR</span>
                    <span class="text-sm font-black text-slate-700" x-text="(currentQuestion + 1) + ' / ' + totalQuestions"></span>
                 </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Question Container -->
        <div class="lg:col-span-8 space-y-6">
            <template x-for="(question, index) in questions" :key="question.id">
                <div x-show="currentQuestion === index" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white rounded-[3rem] border border-slate-100 shadow-sm p-10 min-h-[500px] flex flex-col justify-between"
                >
                    <div class="space-y-8">
                        <div class="flex items-center justify-between pb-6 border-b border-slate-50">
                            <div class="flex items-center gap-3">
                                <span class="px-4 py-1.5 bg-orange-50 text-orange-500 text-[10px] font-black rounded-full uppercase tracking-widest">
                                    SOAL NOMOR <span x-text="index + 1"></span>
                                </span>
                                <span class="px-4 py-1.5 bg-emerald-50 text-emerald-500 text-[10px] font-black rounded-full uppercase tracking-widest flex items-center gap-2">
                                    <i class="material-icons text-xs">key</i>
                                    KUNCI JAWABAN: <span x-text="String.fromCharCode(65 + (question.options.findIndex(o => o.is_correct === 1 || o.is_correct === true)))"></span>
                                </span>
                            </div>
                            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest" x-text="question.jenis_soal"></span>
                        </div>

                        <!-- Question Text -->
                        <div class="space-y-6">
                            <template x-if="question.gambar">
                                <img :src="'/storage/' + question.gambar" class="w-full max-h-64 object-contain rounded-2xl bg-slate-50 border border-slate-100">
                            </template>
                            
                            <div class="text-lg font-bold text-slate-700 leading-relaxed" x-html="question.pertanyaan"></div>
                        </div>

                        <!-- Options (Multiple Choice) -->
                        <div class="grid grid-cols-1 gap-4 pt-6">
                            <template x-for="(option, optIdx) in question.options" :key="option.id">
                                <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/30 flex items-center gap-4 transition-all hover:bg-white hover:border-orange-200">
                                    <div class="w-8 h-8 rounded-lg bg-white border border-slate-100 shadow-sm flex items-center justify-center text-xs font-black text-slate-400 group-hover:text-orange-500 transition-colors uppercase" x-text="String.fromCharCode(65 + optIdx)"></div>
                                    <div class="text-sm font-bold text-slate-600" x-html="option.opsi"></div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Question Navigation Footer -->
                    <div class="mt-12 pt-10 border-t border-slate-50 flex items-center justify-between">
                        <button 
                            @click="prevQuestion()" 
                            :disabled="currentQuestion === 0"
                            class="flex items-center gap-3 px-6 py-3 rounded-2xl bg-slate-50 text-slate-400 font-black text-xs uppercase tracking-widest transition-all hover:bg-slate-100 disabled:opacity-30 disabled:cursor-not-allowed"
                        >
                            <i class="material-icons">arrow_back</i>
                            Sebelumnya
                        </button>
                        
                        <div class="flex items-center gap-2">
                             <template x-if="currentQuestion === totalQuestions - 1">
                                <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">SELESAI MEMPELAJARI</span>
                             </template>
                        </div>

                        <button 
                            @click="nextQuestion()" 
                            :disabled="currentQuestion === totalQuestions - 1"
                            class="flex items-center gap-3 px-8 py-3 rounded-2xl bg-orange-500 text-white font-black text-xs uppercase tracking-widest shadow-lg shadow-orange-100 transition-all hover:scale-105 disabled:opacity-30 disabled:cursor-not-allowed"
                        >
                            Selanjutnya
                            <i class="material-icons">arrow_forward</i>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Sidebar Picker -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <i class="material-icons text-orange-500 text-base">apps</i>
                    Navigasi Soal
                </h3>
                
                <div class="grid grid-cols-5 md:grid-cols-6 lg:grid-cols-5 gap-3">
                    <template x-for="n in totalQuestions" :key="n">
                        <button 
                            @click="goTo(n-1)"
                            :class="currentQuestion === (n-1) ? 'bg-orange-500 text-white shadow-md' : 'bg-slate-50 text-slate-400 border-slate-100 hover:bg-slate-100 hover:text-slate-600'"
                            class="w-full aspect-square rounded-xl border flex items-center justify-center text-xs font-black transition-all"
                            x-text="n"
                        ></button>
                    </template>
                </div>

                <div class="mt-10 pt-8 border-t border-slate-50 space-y-5">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center">
                            <i class="material-icons text-slate-300">info</i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none">KET. BELAJAR</p>
                            <p class="text-xs font-bold text-slate-500 mt-1 leading-tight">Gunakan fitur ini untuk mempelajari pola soal ujian.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
