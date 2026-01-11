@extends('layouts.app')

@section('title', 'Manajemen Sekolah - Literasia')

@section('content')
<div x-data="sekolahPage()">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Manajemen Sekolah</h1>
            <p class="text-sm font-medium text-slate-400 mt-1">Kelola pengaturan instansi dan daftar kelas.</p>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="flex p-4 border-b border-slate-50 gap-2 overflow-x-auto">
            <button 
                @click="activeTab = 'settings'" 
                class="px-8 py-3.5 rounded-2xl text-sm font-bold transition-all duration-300 flex items-center gap-2 cursor-pointer whitespace-nowrap"
                :class="activeTab === 'settings' ? 'bg-pink-50 text-[#d90d8b]' : 'text-slate-400 hover:bg-slate-50'"
            >
                <i class="material-icons text-[20px]">settings</i> Pengaturan Sekolah
            </button>
            <button 
                @click="goToKelasTab()" 
                class="px-8 py-3.5 rounded-2xl text-sm font-bold transition-all duration-300 flex items-center gap-2 cursor-pointer whitespace-nowrap"
                :class="activeTab === 'kelas' ? 'bg-pink-50 text-[#d90d8b]' : 'text-slate-400 hover:bg-slate-50'"
            >
                <i class="material-icons text-[20px]">class</i> Daftar Kelas
            </button>
        </div>

        <!-- Tab Content -->
        <div class="p-8">
            <!-- Tab 1: Settings -->
            <div x-show="activeTab === 'settings'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <!-- Form Section -->
                    <div class="lg:col-span-1">
                        <h3 class="text-lg font-bold text-slate-800 mb-6">Tambah Semester/Tahun</h3>
                        <form @submit.prevent="saveSettings" class="space-y-6">
                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Semester</label>
                                <select x-model="settings.semester" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700">
                                    <option value="">Pilih Semester</option>
                                    <option value="ganjil">Ganjil</option>
                                    <option value="genap">Genap</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Tahun Pelajaran</label>
                                <select x-model="settings.tahun_pelajaran" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700">
                                    <option value="">Pilih Tahun Pelajaran</option>
                                    <template x-for="year in ['2025/2026', '2026/2027', '2027/2028', '2028/2029', '2029/2030']">
                                        <option :value="year" x-text="year"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Jenjang Sekolah</label>
                                <select x-model="settings.jenjang" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700">
                                    <option value="">Pilih Jenjang</option>
                                    <option value="sd">SD</option>
                                    <option value="smp">SMP</option>
                                    <option value="sma_smk">SMA/SMK</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-3 py-2">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" x-model="settings.is_active" class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                </label>
                                <span class="text-sm font-bold text-slate-600">Langsung Aktifkan</span>
                            </div>
                            <button type="submit" class="w-full py-4 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3 cursor-pointer">
                                <i class="material-icons">add</i> TAMBAH SETTING
                            </button>
                        </form>
                    </div>

                    <!-- History Section -->
                    <div class="lg:col-span-2">
                        <h3 class="text-lg font-bold text-slate-800 mb-6">Riwayat Tahun Pelajaran</h3>
                        <div class="overflow-hidden border border-slate-100 rounded-3xl">
                            <table class="w-full text-left">
                                <thead class="bg-slate-50 text-slate-400 text-[11px] uppercase font-black tracking-widest">
                                    <tr>
                                        <th class="px-6 py-4">Tahun Pelajaran</th>
                                        <th class="px-6 py-4">Semester</th>
                                        <th class="px-6 py-4">Jenjang</th>
                                        <th class="px-6 py-4 text-center">Status</th>
                                        <th class="px-6 py-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <template x-for="item in allSettings" :key="item.id">
                                        <tr class="border-t border-slate-50 hover:bg-slate-50/50 transition-colors group">
                                            <td class="px-6 py-4 font-bold text-slate-700" x-text="item.tahun_pelajaran"></td>
                                            <td class="px-6 py-4 font-medium text-slate-500" x-text="item.semester.toUpperCase()"></td>
                                            <td class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-tighter" x-text="item.jenjang.replace('_', '/')"></td>
                                            <td class="px-6 py-4 text-center">
                                                <template x-if="item.is_active">
                                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-widest">AKTIF</span>
                                                </template>
                                                <template x-if="!item.is_active">
                                                    <button @click="activateSetting(item.id)" class="px-3 py-1 bg-slate-100 text-slate-400 hover:bg-[#ba80e8] hover:text-white rounded-lg text-[10px] font-black uppercase tracking-widest transition-all cursor-pointer">AKTIFKAN</button>
                                                </template>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <button @click="deleteSetting(item.id)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer opacity-0 group-hover:opacity-100">
                                                    <i class="material-icons text-lg">delete</i>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Jurusan Table (SMA/SMK only) -->
                <div x-show="settings.jenjang === 'sma_smk'" class="mt-12 pt-12 border-t border-slate-50" x-transition>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Daftar Jurusan</h3>
                            <p class="text-xs font-medium text-slate-400 uppercase tracking-widest mt-1">Dinas Pendidikan Menengah</p>
                        </div>
                        <button @click="openJurusanModal()" class="flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-xs hover:bg-slate-50 transition-all cursor-pointer">
                            <i class="material-icons text-lg">add</i> TAMBAH JURUSAN
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-3">
                            <thead>
                                <tr class="text-slate-400 text-[11px] uppercase font-black tracking-widest">
                                    <th class="px-6 py-3">Nama Jurusan</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                <template x-for="item in jurusans" :key="item.id">
                                    <tr class="group hover:scale-[1.005] transition-transform duration-200">
                                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-l border-transparent group-hover:border-slate-100 first:rounded-l-2xl">
                                            <p class="font-bold text-slate-800" x-text="item.nama"></p>
                                        </td>
                                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" :checked="item.is_active" class="sr-only peer" @change="toggleJurusan(item)">
                                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                            </label>
                                        </td>
                                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-r border-transparent group-hover:border-slate-100 last:rounded-r-2xl text-right">
                                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button @click="editJurusan(item.id)" class="p-2 text-blue-500 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors cursor-pointer">
                                                    <i class="material-icons text-lg">edit</i>
                                                </button>
                                                <button @click="deleteJurusan(item.id)" class="p-2 text-rose-500 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors cursor-pointer">
                                                    <i class="material-icons text-lg">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Kelas -->
            <div x-show="activeTab === 'kelas'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Daftar Kelas</h3>
                        <p class="text-sm font-medium text-slate-400 mt-1">Total <span class="text-[#d90d8b] font-bold" x-text="kelas.length"></span> kelas terdaftar.</p>
                    </div>
                    <button @click="openKelasModal()" class="px-6 py-3 bg-[#d90d8b] text-white rounded-2xl font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] transition-all flex items-center gap-2 cursor-pointer">
                        <i class="material-icons">add</i> TAMBAH KELAS
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-3">
                        <thead>
                            <tr class="text-slate-400 text-[11px] uppercase font-black tracking-widest">
                                <th class="px-6 py-3">Nama Kelas</th>
                                <th class="px-6 py-3">Tingkat</th>
                                <th class="px-6 py-3">Wali Kelas</th>
                                <th x-show="settings.jenjang === 'sma_smk'" class="px-6 py-3">Jurusan</th>
                                <th class="px-6 py-3 text-center">Kapasitas</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <template x-for="item in kelas" :key="item.id">
                                <tr class="group hover:scale-[1.005] transition-transform duration-200">
                                    <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-l border-transparent group-hover:border-slate-100 first:rounded-l-2xl">
                                        <p class="font-bold text-slate-800" x-text="item.nama"></p>
                                    </td>
                                    <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100 font-bold text-slate-600" x-text="item.tingkat"></td>
                                    <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100">
                                        <div class="flex items-center gap-2">
                                            <img :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(item.wali_kelas ? item.wali_kelas.nama : 'No Teacher')}&background=ba80e8&color=fff`" class="w-7 h-7 rounded-lg">
                                            <p class="font-bold text-slate-700" x-text="item.wali_kelas ? item.wali_kelas.nama : '-'"></p>
                                        </div>
                                    </td>
                                    <td x-show="settings.jenjang === 'sma_smk'" class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100">
                                        <span class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-[10px] font-black text-slate-500 uppercase" x-text="item.jurusan ? item.jurusan.nama : '-'"></span>
                                    </td>
                                    <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100 text-center font-black text-[#d90d8b]" x-text="item.kapasitas"></td>
                                    <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-r border-transparent group-hover:border-slate-100 last:rounded-r-2xl text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button @click="editKelas(item.id)" class="p-2 text-blue-500 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors cursor-pointer">
                                                <i class="material-icons text-lg">edit</i>
                                            </button>
                                            <button @click="deleteKelas(item.id)" class="p-2 text-rose-500 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors cursor-pointer">
                                                <i class="material-icons text-lg">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Jurusan Modal -->
    <div x-show="showJurusanModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showJurusanModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 transition-opacity" @click="showJurusanModal = false">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
            <div x-show="showJurusanModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="relative z-10 inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white p-8">
                    <h3 class="text-xl font-bold text-slate-800 mb-6" x-text="jurusanForm.id ? 'Edit Jurusan' : 'Tambah Jurusan'"></h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nama Jurusan</label>
                            <input type="text" x-model="jurusanForm.nama" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700">
                        </div>
                    </div>
                    <div class="mt-8 flex gap-3">
                        <button @click="showJurusanModal = false" class="flex-1 py-4 bg-slate-50 text-slate-500 rounded-2xl font-bold hover:bg-slate-100 transition-all cursor-pointer">Batal</button>
                        <button @click="saveJurusan" class="flex-1 py-4 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] transition-all cursor-pointer">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kelas Modal -->
    <div x-show="showKelasModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showKelasModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 transition-opacity" @click="showKelasModal = false">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
            <div x-show="showKelasModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="relative z-10 inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                <div class="bg-white p-8">
                    <h3 class="text-xl font-bold text-slate-800 mb-6" x-text="kelasForm.id ? 'Edit Kelas' : 'Tambah Kelas'"></h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nama Kelas</label>
                            <input type="text" x-model="kelasForm.nama" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Tingkat</label>
                            <input type="text" x-model="kelasForm.tingkat" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700" placeholder="Contoh: 10, 11, 12">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Kapasitas</label>
                            <input type="number" x-model="kelasForm.kapasitas" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Wali Kelas</label>
                            <select x-model="kelasForm.wali_kelas_id" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700">
                                <option value="">Pilih Wali Kelas</option>
                                <template x-for="guru in gurus" :key="guru.id">
                                    <option :value="guru.id" x-text="guru.nama"></option>
                                </template>
                            </select>
                        </div>
                        <div class="col-span-2" x-show="settings.jenjang === 'sma_smk'">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Jurusan</label>
                            <select x-model="kelasForm.jurusan_id" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700">
                                <option value="">Pilih Jurusan</option>
                                <template x-for="jr in activeJurusans" :key="jr.id">
                                    <option :value="jr.id" x-text="jr.nama"></option>
                                </template>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Keterangan (Opsional)</label>
                            <textarea x-model="kelasForm.keterangan" rows="3" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700"></textarea>
                        </div>
                    </div>
                    <div class="mt-8 flex gap-3">
                        <button @click="showKelasModal = false" class="flex-1 py-4 bg-slate-50 text-slate-500 rounded-2xl font-bold hover:bg-slate-100 transition-all cursor-pointer">Batal</button>
                        <button @click="saveKelas" class="flex-1 py-4 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] transition-all cursor-pointer">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function sekolahPage() {
    return {
        activeTab: 'settings',
        settings: {
            semester: '',
            tahun_pelajaran: '',
            jenjang: '',
            is_active: true,
        },
        allSettings: @json($allSettings),
        jurusans: @json($jurusans),
        kelas: @json($kelas),
        gurus: @json($gurus),
        
        showJurusanModal: false,
        jurusanForm: { id: null, nama: '', is_active: 1 },

        showKelasModal: false,
        kelasForm: { id: null, nama: '', tingkat: '', wali_kelas_id: '', jurusan_id: '', kapasitas: 36, keterangan: '' },

        get activeJurusans() {
            return this.jurusans.filter(j => j.is_active);
        },

        isSettingsFull() {
            return this.allSettings.some(s => s.is_active);
        },

        goToKelasTab() {
            if (!this.isSettingsFull()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Harap aktifkan salah satu Semester/Tahun Pelajaran terlebih dahulu.',
                    confirmButtonText: 'Lengkapi Sekarang'
                });
                this.activeTab = 'settings';
            } else {
                this.activeTab = 'kelas';
            }
        },

        saveSettings() {
            Swal.fire({ title: 'Memproses...', didOpen: () => Swal.showLoading() });
            $.post('{{ route("sekolah.update-settings") }}', {
                _token: '{{ csrf_token() }}',
                ...this.settings
            }).done(res => {
                Swal.fire('Berhasil', 'Pengaturan ditambahkan', 'success').then(() => location.reload());
            }).fail(err => {
                Swal.fire('Error', 'Gagal menambahkan pengaturan', 'error');
            });
        },

        activateSetting(id) {
            Swal.fire({ title: 'Mengaktifkan...', didOpen: () => Swal.showLoading() });
            $.post(`{{ url('sekolah/settings/activate') }}/${id}`, {
                _token: '{{ csrf_token() }}'
            }).done(() => location.reload());
        },

        deleteSetting(id) {
            Swal.fire({
                title: 'Hapus Pengaturan?',
                text: 'Data yang terkait dengan semester ini mungkin akan terpengaruh.',
                icon: 'warning',
                showCancelButton: true
            }).then(r => {
                if (r.isConfirmed) {
                    $.ajax({
                        url: `{{ url('sekolah/settings/destroy') }}/${id}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: () => location.reload()
                    });
                }
            });
        },

        // Jurusan Logic
        openJurusanModal() {
            this.jurusanForm = { id: null, nama: '', is_active: 1 };
            this.showJurusanModal = true;
        },
        editJurusan(id) {
            Swal.fire({ title: 'Mengambil data...', didOpen: () => Swal.showLoading() });
            $.get(`{{ url('sekolah/jurusan/show') }}/${id}`).done(res => {
                this.jurusanForm = res;
                this.showJurusanModal = true;
                Swal.close();
            });
        },
        saveJurusan() {
            const url = this.jurusanForm.id ? `{{ url('sekolah/jurusan/update') }}/${this.jurusanForm.id}` : '{{ route("sekolah.jurusan.store") }}';
            $.post(url, { _token: '{{ csrf_token() }}', ...this.jurusanForm }).done(res => {
                this.showJurusanModal = false;
                location.reload();
            });
        },
        toggleJurusan(item) {
            item.is_active = !item.is_active;
            $.post(`{{ url('sekolah/jurusan/update') }}/${item.id}`, {
                _token: '{{ csrf_token() }}',
                nama: item.nama,
                is_active: item.is_active ? 1 : 0
            }).done(() => {
                // optional success message
            });
        },
        deleteJurusan(id) {
            Swal.fire({
                title: 'Hapus Jurusan?',
                text: "Semua kelas yang berkaitan akan kehilangan mapping jurusan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('sekolah/jurusan/destroy') }}/${id}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: () => location.reload()
                    });
                }
            });
        },

        // Kelas Logic
        openKelasModal() {
            this.kelasForm = { id: null, nama: '', tingkat: '', wali_kelas_id: '', jurusan_id: '', kapasitas: 36, keterangan: '' };
            this.showKelasModal = true;
        },
        editKelas(id) {
            Swal.fire({ title: 'Mengambil data...', didOpen: () => Swal.showLoading() });
            $.get(`{{ url('sekolah/kelas/show') }}/${id}`).done(res => {
                this.kelasForm = res;
                this.showKelasModal = true;
                Swal.close();
            });
        },
        saveKelas() {
            const url = this.kelasForm.id ? `{{ url('sekolah/kelas/update') }}/${this.kelasForm.id}` : '{{ route("sekolah.kelas.store") }}';
            $.post(url, { _token: '{{ csrf_token() }}', ...this.kelasForm }).done(res => {
                this.showKelasModal = false;
                location.reload();
            }).fail(err => {
                Swal.fire('Gagal', 'Pastikan semua field wajib diisi.', 'error');
            });
        },
        deleteKelas(id) {
            Swal.fire({
                title: 'Hapus Kelas?',
                text: "Data yang dihapus tidak dapat dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('sekolah/kelas/destroy') }}/${id}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: () => location.reload()
                    });
                }
            });
        }
    }
}
</script>
@endpush
@endsection
