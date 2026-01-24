@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Pengaturan Aplikasi</h1>
            <p class="text-slate-500 mt-1">Atur identitas global aplikasi seperti nama dan logo.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <form action="{{ route('dinas.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            
            <div class="space-y-8">
                <!-- App Name Section -->
                <div>
                    <label for="app_name" class="block text-sm font-bold text-slate-700 uppercase tracking-wider mb-3">Nama Aplikasi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="material-icons text-slate-400">label</i>
                        </div>
                        <input 
                            type="text" 
                            name="app_name" 
                            id="app_name" 
                            value="{{ old('app_name', $settings->app_name) }}"
                            class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 font-medium focus:ring-2 focus:ring-[#d90d8b]/20 focus:border-[#d90d8b] transition-all duration-200"
                            placeholder="Contoh: LITERASIA"
                            required
                        >
                    </div>
                </div>

                <!-- Logo Section -->
                <div x-data="{ photoName: null, photoPreview: null }">
                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider mb-3">Logo Aplikasi</label>
                    
                    <div class="flex flex-col md:flex-row items-center gap-8">
                        <!-- Preview Container -->
                        <div class="relative group">
                            <div class="w-32 h-32 rounded-3xl bg-slate-100 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden shadow-inner">
                                <!-- Fallback Icon -->
                                <div x-show="!photoPreview && !{{ $settings->app_logo ? 'true' : 'false' }}" class="text-slate-300">
                                    <i class="material-icons text-5xl">image</i>
                                </div>
                                
                                <!-- Current Logo -->
                                @if($settings->app_logo)
                                    <img x-show="!photoPreview" src="{{ $settings->logo_url }}" class="w-full h-full object-contain p-4">
                                @endif

                                <!-- New Preview -->
                                <img x-show="photoPreview" :src="photoPreview" class="w-full h-full object-contain p-4" style="display: none;">
                            </div>
                        </div>

                        <div class="flex-grow space-y-4">
                            <div class="flex items-center gap-4">
                                <input 
                                    type="file" 
                                    name="app_logo" 
                                    id="app_logo" 
                                    class="hidden" 
                                    accept="image/*"
                                    @change="
                                        photoName = $event.target.files[0].name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($event.target.files[0]);
                                    "
                                >
                                <button 
                                    type="button" 
                                    @click="$refs.logoInput.click()"
                                    class="px-6 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 shadow-sm flex items-center gap-2"
                                >
                                    <i class="material-icons text-lg">upload</i> Pilih Logo Baru
                                </button>
                                
                                <button 
                                    x-show="photoPreview" 
                                    type="button"
                                    @click="photoPreview = null; $refs.logoInput.value = null"
                                    class="px-4 py-3 bg-red-50 text-red-500 rounded-xl text-sm font-bold hover:bg-red-100 transition-all duration-200"
                                    style="display: none;"
                                >
                                    Batal
                                </button>
                            </div>
                            <p class="text-xs text-slate-400 font-medium">Recomendasi format: PNG transparan. Ukuran maksimal 2MB.</p>
                        </div>
                        <input type="file" x-ref="logoInput" class="hidden" @change="
                            photoName = $event.target.files[0].name;
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                photoPreview = e.target.result;
                            };
                            reader.readAsDataURL($event.target.files[0]);
                        " name="app_logo">
                    </div>
                </div>
            </div>

                <!-- Registration Control Section -->
                <div class="pt-8 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider mb-1">Pendaftaran Sekolah</label>
                            <p class="text-xs text-slate-400 font-medium">Aktifkan atau nonaktifkan pendaftaran untuk sekolah baru di halaman login.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="school_registration_enabled" 
                                value="1" 
                                class="sr-only peer"
                                {{ $settings->school_registration_enabled ? 'checked' : '' }}
                            >
                            <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none ring-0 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-[#ba80e8] peer-checked:to-[#d90d8b]"></div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex justify-end">
                <button type="submit" class="px-10 py-4 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl font-bold shadow-lg shadow-pink-200 hover:scale-105 transition-all duration-300 flex items-center gap-3">
                    <i class="material-icons">save_alt</i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Danger Zone Card -->
    <div class="mt-12 bg-white rounded-3xl shadow-sm border border-red-100 overflow-hidden" x-data="{ 
        openResetModal: false, 
        confirm1: false, 
        confirm2: false,
        resetting: false,
        get canReset() { return this.confirm1 && this.confirm2 && !this.resetting }
    }">
        <div class="p-8">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center text-red-500">
                    <i class="material-icons">report_problem</i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight">Danger Zone</h2>
                    <p class="text-slate-400 font-medium text-sm">Aksi di bawah ini bersifat permanen dan berdampak pada seluruh sekolah.</p>
                </div>
            </div>

            <div class="p-6 bg-red-50 rounded-2xl border border-red-100 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex-grow">
                    <h3 class="text-sm font-black text-red-900 uppercase tracking-widest leading-none mb-2">Kosongkan Seluruh Data Siswa</h3>
                    <p class="text-xs text-red-700 font-medium">Ini akan menghapus semua data siswa dan akun login siswa di **seluruh sekolah**. Pastikan Anda sudah memiliki backup file excel.</p>
                </div>
                <button 
                    type="button" 
                    @click="openResetModal = true"
                    class="px-8 py-4 bg-red-600 text-white rounded-2xl text-sm font-extrabold hover:bg-red-700 transition-all shadow-lg shadow-red-200"
                >
                    Reset Seluruh Data
                </button>
            </div>
        </div>

        <!-- Multi-layer Confirmation Modal -->
        <div x-show="openResetModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md" @click="if(!resetting) openResetModal = false"></div>
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg p-10 relative z-10 my-auto text-center border-t-8 border-red-500">
                <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center text-red-500 mx-auto mb-6">
                    <i class="material-icons text-4xl">warning</i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight mb-4">Konfirmasi Penghapusan Global</h3>
                <p class="text-slate-500 font-medium text-sm mb-8">Anda akan menghapus data siswa dari seluruh sistem. Mohon berikan pernyataan kesadaran berikut:</p>
                
                <div class="space-y-4 mb-10 text-left">
                    <label class="flex items-start gap-4 p-4 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100 transition-all">
                        <input type="checkbox" x-model="confirm1" class="mt-1 w-5 h-5 rounded-lg border-slate-300 text-red-600 focus:ring-red-500">
                        <span class="text-sm font-bold text-slate-600">Saya menyadari bahwa aksi ini **permanen** dan data tidak dapat dikembalikan.</span>
                    </label>

                    <label class="flex items-start gap-4 p-4 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100 transition-all">
                        <input type="checkbox" x-model="confirm2" class="mt-1 w-5 h-5 rounded-lg border-slate-300 text-red-600 focus:ring-red-500">
                        <span class="text-sm font-bold text-slate-600">Saya bertanggung jawab penuh atas keputusan menghapus seluruh data siswa di sistem Literasia.</span>
                    </label>
                </div>

                <div class="flex flex-col gap-3">
                    <button 
                        type="button" 
                        @click="
                            Swal.fire({
                                title: 'FINAL CONFIRMATION',
                                text: 'DATA AKAN DIHAPUS SEKARANG JUGA!',
                                icon: 'error',
                                showCancelButton: true,
                                confirmButtonText: 'YA, HAPUS SEMUA!',
                                cancelButtonText: 'BATAL'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    resetting = true;
                                    $.ajax({
                                        url: '{{ route('dinas.siswa.reset-all') }}',
                                        method: 'DELETE',
                                        data: { _token: '{{ csrf_token() }}' },
                                        success: (res) => {
                                            Swal.fire('Terhapus!', res.success, 'success').then(() => location.reload());
                                        },
                                        error: (err) => {
                                            resetting = false;
                                            Swal.fire('Gagal!', err.responseJSON?.message || 'Terjadi kesalahan sistem.', 'error');
                                        }
                                    });
                                }
                            })
                        "
                        :disabled="!canReset"
                        class="py-4 rounded-full text-base font-black transition-all shadow-xl"
                        :class="canReset ? 'bg-red-600 text-white hover:bg-red-700 shadow-red-200' : 'bg-slate-100 text-slate-300 cursor-not-allowed'"
                    >
                        <span x-show="!resetting">KONFIRMASI RESET TOTAL</span>
                        <span x-show="resetting">Sedang Menghapus...</span>
                    </button>
                    <button type="button" @click="openResetModal = false; confirm1 = false; confirm2 = false" class="py-4 text-slate-500 font-bold text-sm hover:text-slate-700 transition-all" x-show="!resetting">Batal & Kembali</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
