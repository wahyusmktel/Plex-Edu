@extends('layouts.app')

@section('title', 'Manajemen CBT - Literasia')

@section('content')
<div x-data="cbtPage()" x-init="init()" class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-8 h-8 rounded-lg bg-pink-50 flex items-center justify-center text-[#d90d8b]">
                    <i class="material-icons text-xl">computer</i>
                </div>
                <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest">Assessment System</h2>
            </div>
            <h1 class="text-4xl font-black text-slate-800 tracking-tight">Computer Based Test</h1>
            <p class="text-slate-500 font-medium mt-1">Kelola ujian daring dan bank soal sekolah</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button @click="openCreateModal()" class="flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-[1.5rem] text-sm font-black shadow-xl shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all cursor-pointer">
                <i class="material-icons text-[20px]">add_circle</i> TAMBAH CBT BARU
            </button>
        </div>
    </div>

    <!-- Stats summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500">
                <i class="material-icons text-3xl">assignment</i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Ujian</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $cbts->total() }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500">
                <i class="material-icons text-3xl">check_circle</i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Aktif Hari Ini</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $cbts->where('tanggal', date('Y-m-d'))->count() }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-500">
                <i class="material-icons text-3xl">people</i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Partisipan</p>
                <h3 class="text-2xl font-black text-slate-800">---</h3>
            </div>
        </div>
    </div>

    <!-- Search & Filter Area -->
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="w-full md:w-96 relative group">
            <form action="{{ route('cbt.index') }}" method="GET">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nama CBT..." 
                    class="w-full bg-white border border-slate-200 rounded-2xl pl-12 pr-6 py-4 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-pink-50 focus:border-[#ba80e8] transition-all outline-none"
                >
                <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-[#ba80e8] transition-colors">search</i>
            </form>
        </div>
    </div>

    <!-- CBT List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($cbts as $item)
        <div class="group bg-white rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-slate-200 transition-all duration-500 flex flex-col overflow-hidden">
            <div class="p-8 space-y-6">
                <!-- Status & Date -->
                <div class="flex items-center justify-between">
                    <span class="px-4 py-1.5 bg-slate-100 rounded-xl text-[10px] font-black text-slate-500 uppercase tracking-widest">
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                    </span>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full {{ $item->tanggal == date('Y-m-d') ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300' }}"></span>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            {{ $item->tanggal == date('Y-m-d') ? 'Aktif' : ($item->tanggal > date('Y-m-d') ? 'Mendatang' : 'Selesai') }}
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="space-y-2">
                    <h3 class="text-xl font-black text-slate-800 leading-tight group-hover:text-[#d90d8b] transition-colors line-clamp-2">
                        {{ $item->nama_cbt }}
                    </h3>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-pink-50 text-[#d90d8b] text-[10px] font-black rounded-lg uppercase">
                            {{ $item->subject->nama_pelajaran ?? 'Umum' }}
                        </span>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-4 py-4 border-y border-slate-50">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Jam</p>
                        <p class="text-xs font-bold text-slate-700 mt-1 flex items-center gap-1">
                            <i class="material-icons text-sm text-slate-300">schedule</i>
                            {{ substr($item->jam_mulai, 0, 5) }} - {{ substr($item->jam_selesai, 0, 5) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Token</p>
                        <p class="text-xs font-black text-[#d90d8b] mt-1 tracking-widest bg-pink-50 px-2 py-0.5 rounded-lg inline-block">
                            {{ $item->token }}
                        </p>
                    </div>
                </div>

                <!-- Creators -->
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($item->creator->name) }}&background=ba80e8&color=fff" class="w-8 h-8 rounded-xl shadow-sm">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-wider leading-none">Dibuat Oleh</p>
                        <p class="text-[11px] font-bold text-slate-600 mt-1">{{ $item->creator->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-3">
                <div class="flex items-center gap-2 flex-grow">
                    <a href="{{ route('cbt.questions', $item->id) }}" class="flex-grow flex items-center justify-center gap-2 px-4 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-[#ba80e8] hover:text-white hover:border-[#ba80e8] transition-all">
                        <i class="material-icons text-lg">quiz</i> SOAL
                    </a>
                    <a href="{{ route('cbt.results', $item->id) }}" class="flex-grow flex items-center justify-center gap-2 px-4 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-500 hover:text-white hover:border-emerald-500 transition-all">
                        <i class="material-icons text-lg">leaderboard</i> HASIL
                    </a>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="editCbt('{{ $item->id }}')" class="w-10 h-10 flex items-center justify-center text-blue-500 bg-white border border-slate-200 hover:bg-blue-50 hover:border-blue-100 rounded-xl transition-all cursor-pointer" title="Edit">
                        <i class="material-icons text-lg">edit</i>
                    </button>
                    <button @click="deleteCbt('{{ $item->id }}')" class="w-10 h-10 flex items-center justify-center text-rose-500 bg-white border border-slate-200 hover:bg-rose-50 hover:border-rose-100 rounded-xl transition-all cursor-pointer" title="Hapus">
                        <i class="material-icons text-lg">delete</i>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-32 flex flex-col items-center justify-center bg-white rounded-[3rem] border border-slate-50">
            <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center text-slate-200 mb-8 shadow-inner">
                <i class="material-icons text-6xl">inventory</i>
            </div>
            <p class="font-black text-slate-400 text-xl uppercase tracking-[0.2em]">Belum ada data CBT</p>
            <p class="text-slate-300 font-medium mt-2">Mulai buat ujian daring pertama Anda sekarang.</p>
            <button @click="openCreateModal()" class="mt-8 px-8 py-4 bg-slate-800 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-700 transition-all">
                Buat CBT Sekarang
            </button>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $cbts->links() }}
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
            class="bg-white rounded-[3rem] shadow-2xl w-full max-w-2xl overflow-hidden relative z-10"
        >
            <div class="px-10 py-8 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight" x-text="editMode ? 'Edit CBT' : 'Tambah CBT Baru'"></h2>
                    <p class="text-slate-400 text-sm font-medium mt-1">Lengkapi rincian ujian CBT berikut.</p>
                </div>
                <button @click="openModal = false" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all">
                    <i class="material-icons">close</i>
                </button>
            </div>

            <div class="p-10 space-y-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama CBT <span class="text-red-500">*</span></label>
                    <input type="text" x-model="formData.nama_cbt" placeholder="Contoh: Penilaian Harian Matematika" class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Mata Pelajaran (Opsional)</label>
                        <select x-model="formData.subject_id" class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->nama_pelajaran }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal <span class="text-red-500">*</span></label>
                        <input type="date" x-model="formData.tanggal" class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jam Mulai <span class="text-red-500">*</span></label>
                        <input type="time" x-model="formData.jam_mulai" class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jam Selesai <span class="text-red-500">*</span></label>
                        <input type="time" x-model="formData.jam_selesai" class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Skor Maksimal <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" x-model="formData.skor_maksimal" class="w-full pl-6 pr-16 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700">
                        <span class="absolute right-6 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-300 uppercase tracking-widest">Poin</span>
                    </div>
                    <p class="text-[9px] text-slate-400 font-medium ml-1">Ini akan digunakan sebagai batas validasi jumlah poin pada soal.</p>
                </div>

                <!-- Show Result Toggle -->
                <div class="p-6 bg-slate-50 rounded-2xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Tampilkan Hasil Ujian</label>
                            <p class="text-[10px] text-slate-400 font-medium mt-1">Jika aktif, siswa dapat melihat hasil dan review jawaban setelah ujian selesai.</p>
                        </div>
                        <button 
                            type="button"
                            @click="formData.show_result = !formData.show_result"
                            :class="formData.show_result ? 'bg-emerald-500' : 'bg-slate-300'"
                            class="relative w-14 h-8 rounded-full transition-colors duration-200 focus:outline-none"
                        >
                            <span 
                                :class="formData.show_result ? 'translate-x-6' : 'translate-x-1'"
                                class="absolute top-1 left-0 w-6 h-6 bg-white rounded-full shadow-md transform transition-transform duration-200"
                            ></span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="px-10 py-6 border-t border-slate-50 flex justify-between items-center bg-white sticky bottom-0 z-20">
                <button @click="openModal = false" class="px-8 py-4 rounded-2xl text-sm font-bold text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-all uppercase tracking-widest">Batal</button>
                <button @click="saveCbt()" class="flex items-center gap-3 px-10 py-4 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl text-sm font-black shadow-xl shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all cursor-pointer">
                    <i class="material-icons text-xl" x-text="editMode ? 'published_with_changes' : 'publish'"></i>
                    <span x-text="editMode ? 'SIMPAN PERUBAHAN' : 'BUAT CBT SEKARANG'"></span>
                </button>
            </div>
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
    function cbtPage() {
        return {
            openModal: false,
            editMode: false,
            formData: {
                id: '',
                nama_cbt: '',
                tanggal: '{{ date('Y-m-d') }}',
                jam_mulai: '07:00',
                jam_selesai: '09:00',
                subject_id: '',
                skor_maksimal: 100,
                show_result: true
            },

            init() {},

            openCreateModal() {
                this.editMode = false;
                this.formData = {
                    id: '',
                    nama_cbt: '',
                    tanggal: '{{ date('Y-m-d') }}',
                    jam_mulai: '07:00',
                    jam_selesai: '09:00',
                    subject_id: '',
                    skor_maksimal: 100,
                    show_result: true
                };
                this.openModal = true;
            },

            editCbt(id) {
                $.get(`{{ url('cbt/show') }}/${id}`, (data) => {
                    this.formData = {
                        id: data.id,
                        nama_cbt: data.nama_cbt,
                        tanggal: data.tanggal,
                        jam_mulai: data.jam_mulai.substring(0, 5),
                        jam_selesai: data.jam_selesai.substring(0, 5),
                        subject_id: data.subject_id || '',
                        skor_maksimal: data.skor_maksimal,
                        show_result: data.show_result ?? true
                    };
                    this.editMode = true;
                    this.openModal = true;
                });
            },

            saveCbt() {
                if (!this.formData.nama_cbt || !this.formData.tanggal || !this.formData.jam_mulai || !this.formData.jam_selesai) {
                    Swal.fire('Eits!', 'Lengkapi seluruh rincian CBT yang bertanda bintang.', 'warning');
                    return;
                }

                Swal.fire({ title: 'Tunggu sebentar...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                const url = this.editMode ? `{{ url('cbt/update') }}/${this.formData.id}` : `{{ route('cbt.store') }}`;
                
                $.post(url, {
                    ...this.formData,
                    _token: '{{ csrf_token() }}'
                }, (res) => {
                    this.openModal = false;
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.success, timer: 1500, showConfirmButton: false }).then(() => {
                        location.reload();
                    });
                }).fail((err) => {
                    let msg = err.responseJSON?.message || 'Terjadi kesalahan.';
                    if (err.responseJSON?.errors) msg = Object.values(err.responseJSON.errors).join('<br>');
                    Swal.fire('Oops!', msg, 'error');
                });
            },

            deleteCbt(id) {
                Swal.fire({
                    title: 'Hapus CBT ini?',
                    text: "Seluruh data soal dan hasil ujian di dalamnya akan ikut terhapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d90d8b',
                    confirmButtonText: 'Ya, Hapus Saja'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('cbt/destroy') }}/${id}`,
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
