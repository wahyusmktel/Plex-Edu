@extends('layouts.app')

@section('title', 'Bank Soal - ' . $cbt->nama_cbt)

@section('content')
<div x-data="questionPage()" x-init="init()" class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-6">
            <a href="{{ route('cbt.index') }}" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-[#d90d8b] transition-all shadow-sm">
                <i class="material-icons">arrow_back</i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Bank Soal</h1>
                <p class="text-slate-500 font-medium mt-1">{{ $cbt->nama_cbt }} ({{ $cbt->subject->nama_pelajaran ?? 'Umum' }})</p>
            </div>
        </div>
        
        <div class="bg-white px-6 py-4 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="text-right">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Poin Saat Ini</p>
                <h3 class="text-lg font-black" :class="totalPoin > {{ $cbt->skor_maksimal }} ? 'text-rose-500' : 'text-emerald-500'">
                    <span x-text="totalPoin"></span> / {{ $cbt->skor_maksimal }}
                </h3>
            </div>
            <div class="w-px h-10 bg-slate-100"></div>
            <button @click="$dispatch('open-import-modal')" class="flex items-center gap-2 px-6 py-3 bg-white text-slate-600 border border-slate-100 rounded-xl text-sm font-black shadow-sm hover:border-indigo-100 hover:text-indigo-600 transition-all cursor-pointer">
                <i class="material-icons">import_export</i> IMPOR DARI BANK
            </button>
            <button @click="openCreateModal()" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-xl text-sm font-black shadow-lg shadow-pink-100 hover:scale-[1.02] transition-all cursor-pointer">
                <i class="material-icons">add_circle</i> TAMBAH SOAL
            </button>
        </div>
    </div>

    <!-- Questions List -->
    <div class="space-y-6">
        @forelse($cbt->questions as $index => $q)
        <div class="group bg-white rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all p-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Question Number & Type -->
                <div class="flex-shrink-0 flex lg:flex-col items-center justify-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-slate-800 text-white flex items-center justify-center text-xl font-black shadow-lg">
                        {{ $index + 1 }}
                    </div>
                    <span class="px-3 py-1 bg-slate-100 rounded-lg text-[9px] font-black text-slate-500 uppercase tracking-tighter">
                        {{ $q->jenis_soal == 'pilihan_ganda' ? 'Pilgan' : 'Essay' }}
                    </span>
                    <span class="px-3 py-1 bg-emerald-50 rounded-lg text-[9px] font-black text-emerald-600 uppercase tracking-tighter">
                        {{ $q->poin }} Poin
                    </span>
                </div>

                <!-- Main Content -->
                <div class="flex-grow space-y-4">
                    <div class="flex flex-col md:flex-row gap-6">
                        @if($q->gambar)
                        <div class="flex-shrink-0 w-full md:w-48 h-32 rounded-2xl overflow-hidden border border-slate-100">
                            <img src="{{ asset('storage/' . $q->gambar) }}" class="w-full h-full object-cover">
                        </div>
                        @endif
                        <div class="flex-grow">
                            <div class="text-slate-700 font-bold text-lg leading-relaxed">
                                {!! nl2br(e($q->pertanyaan)) !!}
                            </div>
                        </div>
                    </div>

                    @if($q->jenis_soal == 'pilihan_ganda')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-6">
                        @foreach($q->options as $opt)
                        <div class="flex items-center gap-3 p-4 rounded-2xl border {{ $opt->is_correct ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : 'bg-slate-50 border-transparent text-slate-500' }}">
                            @if($opt->is_correct)
                            <i class="material-icons text-emerald-500">check_circle</i>
                            @else
                            <div class="w-6 h-6 border-2 border-slate-200 rounded-full"></div>
                            @endif
                            <span class="font-bold text-sm">{{ $opt->opsi }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex-shrink-0 flex lg:flex-col gap-2">
                    <button @click="editQuestion('{{ $q->id }}')" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-blue-50 text-blue-500 hover:bg-blue-100 transition-all cursor-pointer" title="Edit">
                        <i class="material-icons">edit</i>
                    </button>
                    <button @click="deleteQuestion('{{ $q->id }}')" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-rose-50 text-rose-500 hover:bg-rose-100 transition-all cursor-pointer" title="Hapus">
                        <i class="material-icons">delete</i>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="py-32 flex flex-col items-center justify-center bg-white rounded-[3rem] border border-slate-50 shadow-inner">
            <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center text-slate-200 mb-8">
                <i class="material-icons text-6xl">quiz</i>
            </div>
            <p class="font-black text-slate-300 text-xl uppercase tracking-widest">Belum ada soal ditambahkan</p>
            <button @click="openCreateModal()" class="mt-8 px-8 py-4 bg-slate-800 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-700 transition-all">
                Tambah Soal Pertama
            </button>
        </div>
        @endforelse
    </div>

    <!-- Modal Form -->
    <div 
        x-show="openModal" 
        x-cloak
        class="fixed inset-0 z-[60] flex items-center justify-center px-4 py-6"
    >
        <div x-show="openModal" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="openModal = false"></div>
        
        <div 
            x-show="openModal" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95" 
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            class="bg-white rounded-[3rem] shadow-2xl w-full max-w-3xl overflow-hidden relative z-10 flex flex-col max-h-[90vh]"
        >
            <div class="px-10 py-8 border-b border-slate-50 flex items-center justify-between shrink-0">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight" x-text="editMode ? 'Edit Soal' : 'Tambah Soal Baru'"></h2>
                    <p class="text-slate-400 text-sm font-medium mt-1">Lengkapi detail pertanyaan di bawah ini.</p>
                </div>
                <button @click="openModal = false" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all">
                    <i class="material-icons">close</i>
                </button>
            </div>

            <div class="p-10 space-y-8 overflow-y-auto custom-scrollbar">
                
                <!-- Question Type Selection (Only on Create) -->
                <div class="space-y-4" x-show="!editMode">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jenis Soal</label>
                    <div class="grid grid-cols-2 gap-4">
                        <button @click="formData.jenis_soal = 'pilihan_ganda'" :class="formData.jenis_soal == 'pilihan_ganda' ? 'bg-[#ba80e8] text-white shadow-lg' : 'bg-slate-50 text-slate-400 border border-transparent'" class="px-6 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all">
                            Pilihan Ganda
                        </button>
                        <button @click="formData.jenis_soal = 'essay'" :class="formData.jenis_soal == 'essay' ? 'bg-[#ba80e8] text-white shadow-lg' : 'bg-slate-50 text-slate-400 border border-transparent'" class="px-6 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all">
                            Essay / Esai
                        </button>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pertanyaan</label>
                    <textarea x-model="formData.pertanyaan" rows="4" placeholder="Tuliskan pertanyaan Anda di sini..." class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <div class="md:col-span-8 space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Sisipkan Gambar (Opsional)</label>
                        <div class="relative group h-40 rounded-3xl border-2 border-dashed border-slate-200 hover:border-[#ba80e8] bg-slate-50 transition-all cursor-pointer overflow-hidden flex items-center justify-center" @click="$refs.imgInput.click()">
                            <template x-if="!imgPreview">
                                <div class="text-center">
                                    <i class="material-icons text-3xl text-slate-300">add_photo_alternate</i>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">Pilih Gambar</p>
                                </div>
                            </template>
                            <template x-if="imgPreview">
                                <img :src="imgPreview" class="w-full h-full object-cover">
                            </template>
                        </div>
                        <input type="file" x-ref="imgInput" class="hidden" accept="image/*" @change="previewImg">
                    </div>
                    <div class="md:col-span-4 space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Poin Soal</label>
                        <input type="number" x-model="formData.poin" class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700 text-2xl text-center">
                        <p class="text-[9px] text-slate-400 font-medium text-center leading-relaxed">Nilai poin yang didapat jika jawaban benar.</p>
                    </div>
                </div>

                <!-- Multiple Choice Options -->
                <div class="space-y-6 pt-6 border-t border-slate-50" x-show="formData.jenis_soal == 'pilihan_ganda'">
                    <div class="flex items-center justify-between">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Daftar Pilihan Jawaban</label>
                        <p class="text-[10px] font-bold text-[#ba80e8]">Pilih satu jawaban yang benar</p>
                    </div>
                    
                    <div class="space-y-4">
                        <template x-for="(opt, index) in formData.options" :key="index">
                            <div class="flex items-center gap-4">
                                <button @click="formData.correct_option = index" :class="formData.correct_option === index ? 'bg-emerald-500 text-white shadow-emerald-200 shadow-lg' : 'bg-slate-100 text-slate-300 shadow-none'" class="w-12 h-12 rounded-xl flex items-center justify-center transition-all shrink-0">
                                    <i class="material-icons">check_circle</i>
                                </button>
                                <input type="text" x-model="formData.options[index]" :placeholder="'Pilihan ' + String.fromCharCode(65 + index)" class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700">
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            <div class="px-10 py-6 border-t border-slate-50 flex justify-between items-center bg-white shrink-0">
                <button @click="openModal = false" class="px-8 py-4 rounded-2xl text-sm font-bold text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-all uppercase tracking-widest">Batal</button>
                <button @click="saveQuestion()" class="flex items-center gap-3 px-10 py-4 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl text-sm font-black shadow-xl shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all cursor-pointer">
                    <i class="material-icons text-xl">save</i>
                    <span x-text="editMode ? 'SIMPAN PERUBAHAN' : 'MASUKKAN KE BANK SOAL'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div 
        x-show="openImportModal" 
        x-cloak
        class="fixed inset-0 z-[60] flex items-center justify-center px-4 py-6"
    >
        <div x-show="openImportModal" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="openImportModal = false"></div>
        
        <div 
            x-show="openImportModal" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95" 
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            class="bg-white rounded-[3rem] shadow-2xl w-full max-w-lg overflow-hidden relative z-10 flex flex-col"
        >
            <div class="px-10 py-8 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">Impor dari Bank Soal</h2>
                    <p class="text-slate-400 text-sm font-medium mt-1">Pilih bank soal untuk disalin.</p>
                </div>
                <button @click="openImportModal = false" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:bg-slate-100 transition-all">
                    <i class="material-icons">close</i>
                </button>
            </div>

            <form action="{{ route('cbt.questions.import-bank', $cbt->id) }}" method="POST" class="p-10 space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Daftar Bank Soal Anda</label>
                    <select name="bank_soal_id" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <option value="">Pilih Bank Soal...</option>
                        @foreach($bankSoals as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->title }} ({{ count($bank->questions) }} Soal)</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100">
                    <div class="flex gap-3">
                        <i class="material-icons text-amber-500 text-sm">info</i>
                        <p class="text-[10px] font-bold text-amber-700 leading-relaxed uppercase tracking-wider">PENTING: Soal yang diimpor akan ditambahkan ke daftar soal saat ini. Pastikan total poin tidak melebihi skor maksimal CBT.</p>
                    </div>
                </div>

                <div class="pt-4 text-center">
                    <button type="submit" class="w-full py-5 bg-slate-900 text-white text-[10px] font-black rounded-2xl uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-lg">
                        KONFIRMASI IMPOR
                    </button>
                    <p class="mt-4 text-[9px] font-bold text-slate-400 uppercase tracking-widest">PROSES INI TIDAK DAPAT DIBATALKAN</p>
                </div>
            </form>
        </div>
    </div>

</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #f1f5f9; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #e2e8f0; }
</style>
@endsection

@section('scripts')
<script>
    function questionPage() {
        return {
            openModal: false,
            openImportModal: false,
            editMode: false,
            totalPoin: {{ $cbt->questions->sum('poin') }},
            imgPreview: null,
            formData: {
                id: '',
                cbt_id: '{{ $cbt->id }}',
                jenis_soal: 'pilihan_ganda',
                pertanyaan: '',
                poin: 10,
                options: ['', '', '', '', ''],
                correct_option: 0
            },

            init() {
                this.$watch('openImportModal', (val) => {
                    if (val) this.openModal = false;
                });
                window.addEventListener('open-import-modal', () => {
                    this.openImportModal = true;
                });
            },

            openCreateModal() {
                this.editMode = false;
                this.imgPreview = null;
                this.formData = {
                    id: '',
                    cbt_id: '{{ $cbt->id }}',
                    jenis_soal: 'pilihan_ganda',
                    pertanyaan: '',
                    poin: 10,
                    options: ['', '', '', '', ''],
                    correct_option: 0
                };
                this.openModal = true;
            },

            previewImg(e) {
                const file = e.target.files[0];
                if (file) {
                    this.imgPreview = URL.createObjectURL(file);
                }
            },

            editQuestion(id) {
                Swal.fire({ title: 'Memuat data...', didOpen: () => Swal.showLoading() });
                $.get(`{{ url('cbt/questions/show') }}/${id}`, (data) => {
                    Swal.close();
                    this.editMode = true;
                    this.imgPreview = data.gambar ? `{{ asset('storage') }}/${data.gambar}` : null;
                    this.formData = {
                        id: data.id,
                        cbt_id: data.cbt_id,
                        jenis_soal: data.jenis_soal,
                        pertanyaan: data.pertanyaan,
                        poin: data.poin,
                        options: data.jenis_soal === 'pilihan_ganda' ? data.options.map(o => o.opsi) : ['', '', '', '', ''],
                        correct_option: data.jenis_soal === 'pilihan_ganda' ? data.options.findIndex(o => o.is_correct) : 0
                    };
                    while (this.formData.options.length < 5) this.formData.options.push('');
                    this.openModal = true;
                });
            },

            saveQuestion() {
                if (!this.formData.pertanyaan || this.formData.poin < 0) {
                    Swal.fire('Oops!', 'Pertanyaan dan poin minimal harus diisi.', 'warning');
                    return;
                }

                if (this.formData.jenis_soal === 'pilihan_ganda') {
                    const filledOptions = this.formData.options.filter(o => o.trim() !== '');
                    if (filledOptions.length < 2) {
                        Swal.fire('Eits!', 'Berikan minimal 2 pilihan jawaban untuk soal pilihan ganda.', 'warning');
                        return;
                    }
                }

                Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                let form = new FormData();
                form.append('_token', '{{ csrf_token() }}');
                form.append('cbt_id', this.formData.cbt_id);
                form.append('jenis_soal', this.formData.jenis_soal);
                form.append('pertanyaan', this.formData.pertanyaan);
                form.append('poin', this.formData.poin);
                
                if (this.formData.jenis_soal === 'pilihan_ganda') {
                    this.formData.options.forEach((opt, i) => {
                        form.append(`options[${i}]`, opt);
                    });
                    form.append('correct_option', this.formData.correct_option);
                }

                const imgFile = this.$refs.imgInput.files[0];
                if (imgFile) form.append('gambar', imgFile);

                const url = this.editMode ? `{{ url('cbt/questions/update') }}/${this.formData.id}` : `{{ route('cbt.questions.store') }}`;

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: form,
                    processData: false,
                    contentType: false,
                    success: (res) => {
                        this.openModal = false;
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.success, timer: 1500, showConfirmButton: false }).then(() => {
                            location.reload();
                        });
                    },
                    error: (err) => {
                        let msg = err.responseJSON?.message || 'Terjadi kesalahan.';
                        if (err.responseJSON?.errors) msg = Object.values(err.responseJSON.errors).join('<br>');
                        Swal.fire('Oops!', msg, 'error');
                    }
                });
            },

            deleteQuestion(id) {
                Swal.fire({
                    title: 'Hapus soal ini?',
                    text: "Tindakan ini tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d90d8b',
                    confirmButtonText: 'Ya, Hapus'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('cbt/questions/destroy') }}/${id}`,
                            method: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: (res) => {
                                Swal.fire('Dihapus!', res.success, 'success').then(() => location.reload());
                            }
                        });
                    }
                });
            }
        }
    }
</script>
@endsection
