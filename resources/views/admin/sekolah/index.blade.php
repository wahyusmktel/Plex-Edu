@extends('layouts.app')

@section('title', 'Manajemen Sekolah - Literasia')

@push('styles')
<style>
    #pickerMap { cursor: crosshair; background-color: #f8fafc; }
</style>
@endpush

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
                @click="activeTab = 'identity'" 
                class="px-8 py-3.5 rounded-2xl text-sm font-bold transition-all duration-300 flex items-center gap-2 cursor-pointer whitespace-nowrap"
                :class="activeTab === 'identity' ? 'bg-pink-50 text-[#d90d8b]' : 'text-slate-400 hover:bg-slate-50'"
            >
                <i class="material-icons text-[20px]">info</i> Identitas Sekolah
            </button>
            <button 
                @click="goToSettingsTab()" 
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

        <div class="p-8">
            <!-- Tab 0: Identitas Sekolah -->
            <div x-show="activeTab === 'identity'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                <div class="max-w-4xl">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-bold text-slate-800">Identitas Sekolah</h3>
                            <p class="text-sm font-medium text-slate-400 mt-1">Lengkapi informasi dasar sekolah Anda.</p>
                        </div>
                    </div>

                    <form @submit.prevent="saveIdentity" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Nama Sekolah</label>
                            <input type="text" x-model="identityForm.nama_sekolah" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700" placeholder="Masukkan nama sekolah">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">NPSN</label>
                            <input type="text" x-model="identityForm.npsn" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700" placeholder="Nomor Pokok Sekolah Nasional">
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Status Sekolah</label>
                            <select x-model="identityForm.status_sekolah" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700">
                                <option value="Negeri">Negeri</option>
                                <option value="Swasta">Swasta</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Jenjang Sekolah</label>
                            <select x-model="identityForm.jenjang" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700">
                                <option value="sd">SD</option>
                                <option value="smp">SMP</option>
                                <option value="sma_smk">SMA/SMK</option>
                            </select>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Alamat</label>
                            <textarea x-model="identityForm.alamat" rows="3" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700" placeholder="Alamat lengkap sekolah"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Provinsi</label>
                            <div class="relative">
                                <template x-if="loading.province">
                                    <div class="absolute inset-0 bg-slate-100 animate-pulse rounded-2xl z-10"></div>
                                </template>
                                <select x-model="selectedProvinsi" @change="onProvinsiChange" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700">
                                    <option value="">Pilih Provinsi</option>
                                    <template x-for="item in provinceList" :key="item.code">
                                        <option :value="item.code" x-text="item.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Kabupaten/Kota</label>
                            <div class="relative">
                                <template x-if="loading.regency">
                                    <div class="absolute inset-0 bg-slate-100 animate-pulse rounded-2xl z-10"></div>
                                </template>
                                <select x-model="selectedKabupaten" @change="onKabupatenChange" :disabled="!selectedProvinsi" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700 disabled:opacity-50">
                                    <option value="">Pilih Kabupaten/Kota</option>
                                    <template x-for="item in regencyList" :key="item.code">
                                        <option :value="item.code" x-text="item.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Kecamatan</label>
                            <div class="relative">
                                <template x-if="loading.district">
                                    <div class="absolute inset-0 bg-slate-100 animate-pulse rounded-2xl z-10"></div>
                                </template>
                                <select x-model="selectedKecamatan" @change="onKecamatanChange" :disabled="!selectedKabupaten" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700 disabled:opacity-50">
                                    <option value="">Pilih Kecamatan</option>
                                    <template x-for="item in districtList" :key="item.code">
                                        <option :value="item.code" x-text="item.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Desa/Kelurahan</label>
                            <div class="relative">
                                <template x-if="loading.village">
                                    <div class="absolute inset-0 bg-slate-100 animate-pulse rounded-2xl z-10"></div>
                                </template>
                                <select x-model="selectedDesa" @change="onDesaChange" :disabled="!selectedKecamatan" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700 disabled:opacity-50">
                                    <option value="">Pilih Desa/Kelurahan</option>
                                    <template x-for="item in villageList" :key="item.code">
                                        <option :value="item.code" x-text="item.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Titik Lokasi (Map)</label>
                            <div id="pickerMap" class="w-full h-[400px] bg-slate-100 rounded-2xl border border-slate-100 mb-4 z-10"></div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Latitude</span>
                                    <input type="text" x-model="identityForm.latitude" readonly class="bg-transparent border-none outline-none font-mono font-bold text-slate-600 w-full text-sm">
                                </div>
                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Longitude</span>
                                    <input type="text" x-model="identityForm.longitude" readonly class="bg-transparent border-none outline-none font-mono font-bold text-slate-600 w-full text-sm">
                                </div>
                            </div>
                            <p class="text-[10px] font-medium text-slate-400 mt-2 italic px-2">Geser pin pada peta untuk menyesuaikan titik koordinat sekolah.</p>
                        </div>

                        <div class="col-span-1 md:col-span-2 pt-4">
                            <button type="submit" class="w-full md:w-auto px-12 py-4 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3 cursor-pointer">
                                <i class="material-icons">save</i> SIMPAN IDENTITAS
                            </button>
                        </div>
                    </form>
                </div>
            </div>

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
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}"></script>
<script>
function sekolahPage() {
    return {
        activeTab: '{{ $identity ? "settings" : "identity" }}',
        identity: @json($identity),
            identityForm: {
                nama_sekolah: '{{ $identity->nama_sekolah ?? "" }}',
                npsn: '{{ $identity->npsn ?? "" }}',
                jenjang: '{{ $identity->jenjang ?? "sma_smk" }}',
                alamat: '{{ $identity->alamat ?? "" }}',
                latitude: '{{ $identity->latitude ?? "-6.175392" }}',
                longitude: '{{ $identity->longitude ?? "106.827153" }}',
                desa_kelurahan: '{{ $identity->desa_kelurahan ?? "" }}',
                kecamatan: '{{ $identity->kecamatan ?? "" }}',
                kabupaten_kota: '{{ $identity->kabupaten_kota ?? "" }}',
                provinsi: '{{ $identity->provinsi ?? "" }}',
                status_sekolah: '{{ $identity->status_sekolah ?? "Swasta" }}',
            },

        provinceList: [],
        regencyList: [],
        districtList: [],
        villageList: [],

        selectedProvinsi: '',
        selectedKabupaten: '',
        selectedKecamatan: '',
        selectedDesa: '',

        loading: {
            province: false,
            regency: false,
            district: false,
            village: false
        },

        pickerMap: null,
        pickerMarker: null,

        async init() {
            await this.fetchProvinces();
            
            if (this.activeTab === 'identity') {
                setTimeout(() => this.initPickerMap(), 500);
            }

            this.$watch('activeTab', value => {
                if (value === 'identity') {
                    if (!this.pickerMap) {
                        this.initPickerMap();
                    } else {
                        setTimeout(() => {
                            google.maps.event.trigger(this.pickerMap, 'resize');
                            if (this.pickerMarker) {
                                this.pickerMap.setCenter(this.pickerMarker.getPosition());
                            }
                        }, 300);
                    }
                }
            });
            
            // Rehydrate saved data
            if (this.identity && this.identity.provinsi) {
                const p = this.provinceList.find(item => item.name === this.identity.provinsi);
                if (p) {
                    this.selectedProvinsi = p.code;
                    await this.onProvinsiChange();
                    
                    if (this.identity.kabupaten_kota) {
                        const k = this.regencyList.find(item => item.name === this.identity.kabupaten_kota);
                        if (k) {
                            this.selectedKabupaten = k.code;
                            await this.onKabupatenChange();
                            
                            if (this.identity.kecamatan) {
                                const d = this.districtList.find(item => item.name === this.identity.kecamatan);
                                if (d) {
                                    this.selectedKecamatan = d.code;
                                    await this.onKecamatanChange();
                                    
                                    if (this.identity.desa_kelurahan) {
                                        const v = this.villageList.find(item => item.name === this.identity.desa_kelurahan);
                                        if (v) {
                                            this.selectedDesa = v.code;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        },

        async fetchProvinces() {
            this.loading.province = true;
            try {
                const res = await fetch('{{ route("sekolah.regional", ["type" => "provinces"]) }}');
                const data = await res.json();
                this.provinceList = data.data;
            } catch (e) { 
                console.error(e); 
            } finally {
                this.loading.province = false;
            }
        },

        initPickerMap() {
            if (this.pickerMap || typeof google === 'undefined') return;

            const lat = parseFloat(this.identityForm.latitude) || -6.175392;
            const lng = parseFloat(this.identityForm.longitude) || 106.827153;
            const initialPos = { lat: lat, lng: lng };

            this.pickerMap = new google.maps.Map(document.getElementById('pickerMap'), {
                center: initialPos,
                zoom: 13,
                mapTypeControl: true,
                streetViewControl: true,
                fullscreenControl: true,
            });

            this.pickerMarker = new google.maps.Marker({
                position: initialPos,
                map: this.pickerMap,
                draggable: true,
                animation: google.maps.Animation.DROP
            });

            this.pickerMarker.addListener('dragend', () => {
                const pos = this.pickerMarker.getPosition();
                this.identityForm.latitude = pos.lat().toFixed(8);
                this.identityForm.longitude = pos.lng().toFixed(8);
            });

            this.pickerMap.addListener('click', (e) => {
                const pos = e.latLng;
                this.pickerMarker.setPosition(pos);
                this.identityForm.latitude = pos.lat().toFixed(8);
                this.identityForm.longitude = pos.lng().toFixed(8);
            });
        },

        async onProvinsiChange() {
            this.identityForm.provinsi = this.provinceList.find(p => p.code === this.selectedProvinsi)?.name || '';
            this.selectedKabupaten = '';
            this.selectedKecamatan = '';
            this.selectedDesa = '';
            this.regencyList = [];
            this.districtList = [];
            this.villageList = [];
            
            if (this.selectedProvinsi) {
                this.loading.regency = true;
                try {
                    const res = await fetch(`{{ url('sekolah/regional/regencies') }}/${this.selectedProvinsi}`);
                    const data = await res.json();
                    this.regencyList = data.data;
                } catch (e) { 
                    console.error(e); 
                } finally {
                    this.loading.regency = false;
                }
            }
        },

        async onKabupatenChange() {
            this.identityForm.kabupaten_kota = this.regencyList.find(p => p.code === this.selectedKabupaten)?.name || '';
            this.selectedKecamatan = '';
            this.selectedDesa = '';
            this.districtList = [];
            this.villageList = [];

            if (this.selectedKabupaten) {
                this.loading.district = true;
                try {
                    const res = await fetch(`{{ url('sekolah/regional/districts') }}/${this.selectedKabupaten}`);
                    const data = await res.json();
                    this.districtList = data.data;
                } catch (e) { 
                    console.error(e); 
                } finally {
                    this.loading.district = false;
                }
            }
        },

        async onKecamatanChange() {
            this.identityForm.kecamatan = this.districtList.find(p => p.code === this.selectedKecamatan)?.name || '';
            this.selectedDesa = '';
            this.villageList = [];

            if (this.selectedKecamatan) {
                this.loading.village = true;
                try {
                    const res = await fetch(`{{ url('sekolah/regional/villages') }}/${this.selectedKecamatan}`);
                    const data = await res.json();
                    this.villageList = data.data;
                } catch (e) { 
                    console.error(e); 
                } finally {
                    this.loading.village = false;
                }
            }
        },

        onDesaChange() {
            this.identityForm.desa_kelurahan = this.villageList.find(p => p.code === this.selectedDesa)?.name || '';
        },
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

        isIdentityFilled() {
            return this.identity && this.identity.nama_sekolah && this.identity.npsn;
        },

        goToSettingsTab() {
            if (!this.isIdentityFilled()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Harap lengkapi Identitas Sekolah terlebih dahulu.',
                    confirmButtonText: 'Lengkapi Sekarang'
                });
                this.activeTab = 'identity';
            } else {
                this.activeTab = 'settings';
            }
        },

        goToKelasTab() {
            if (!this.isIdentityFilled()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Harap lengkapi Identitas Sekolah terlebih dahulu.',
                    confirmButtonText: 'Lengkapi Sekarang'
                });
                this.activeTab = 'identity';
                return;
            }

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

        saveIdentity() {
            Swal.fire({ title: 'Memproses...', didOpen: () => Swal.showLoading() });
            $.post('{{ route("sekolah.identity.update") }}', {
                _token: '{{ csrf_token() }}',
                ...this.identityForm
            }).done(res => {
                Swal.fire('Berhasil', 'Identitas sekolah diperbarui', 'success').then(() => location.reload());
            }).fail(err => {
                Swal.fire('Error', 'Gagal memperbarui identitas', 'error');
            });
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
