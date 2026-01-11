@extends('layouts.app')

@section('title', 'Akademik - Literasia')

@section('content')
<div x-data="akademikPage()" class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Manajemen Akademik</h1>
            <p class="text-sm font-medium text-slate-400 mt-1">Kelola jam, mata pelajaran, dan jadwal mingguan.</p>
        </div>
        <div class="flex items-center gap-4 bg-white p-2 rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-4 py-2 bg-emerald-50 rounded-xl">
                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Semester Aktif</p>
                <p class="text-sm font-bold text-emerald-700" x-text="activeSetting ? activeSetting.semester.toUpperCase() + ' ' + activeSetting.tahun_pelajaran : 'Belum Atur'">-</p>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="flex p-4 border-b border-slate-50 gap-2 overflow-x-auto">
            <button @click="activeTab = 'jam'" 
                class="px-8 py-3.5 rounded-2xl text-sm font-bold transition-all duration-300 flex items-center gap-2 cursor-pointer whitespace-nowrap"
                :class="activeTab === 'jam' ? 'bg-pink-50 text-[#d90d8b]' : 'text-slate-400 hover:bg-slate-50'">
                <i class="material-icons text-[20px]">access_time</i> Jam Pelajaran
            </button>
            <button @click="goToTab('mapel')" 
                class="px-8 py-3.5 rounded-2xl text-sm font-bold transition-all duration-300 flex items-center gap-2 cursor-pointer whitespace-nowrap"
                :class="activeTab === 'mapel' ? 'bg-pink-50 text-[#d90d8b]' : 'text-slate-400 hover:bg-slate-50'">
                <i class="material-icons text-[20px]">menu_book</i> Mata Pelajaran
            </button>
            <button @click="goToTab('jadwal')" 
                class="px-8 py-3.5 rounded-2xl text-sm font-bold transition-all duration-300 flex items-center gap-2 cursor-pointer whitespace-nowrap"
                :class="activeTab === 'jadwal' ? 'bg-pink-50 text-[#d90d8b]' : 'text-slate-400 hover:bg-slate-50'">
                <i class="material-icons text-[20px]">event_note</i> Jadwal Pelajaran
            </button>
        </div>

        <div class="p-8">
            <!-- Tab 1: Jam Pelajaran -->
            <div x-show="activeTab === 'jam'" x-transition>
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-bold text-slate-800">Pengaturan Jam Pelajaran</h3>
                    <button @click="showJamModal = true" class="px-5 py-2.5 bg-[#d90d8b] text-white rounded-xl font-bold text-xs shadow-lg shadow-pink-100 hover:scale-[1.02] cursor-pointer">
                        TAMBAH JAM
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <template x-for="(items, hari) in jams" :key="hari">
                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                            <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-200">
                                <h4 class="font-black text-slate-400 uppercase tracking-widest text-[11px]" x-text="hari"></h4>
                                <span class="px-2 py-0.5 bg-white rounded-md text-[10px] font-black text-slate-400 border border-slate-200" x-text="items.length + ' JAM'"></span>
                            </div>
                            <div class="space-y-3">
                                <template x-for="item in items" :key="item.id">
                                    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 group flex items-center justify-between">
                                        <div class="flex items-center gap-3 text-slate-700">
                                            <i class="material-icons text-slate-300 text-lg">schedule</i>
                                            <p class="font-bold" x-text="formatTime(item.jam_mulai) + ' - ' + formatTime(item.jam_selesai)"></p>
                                        </div>
                                        <button @click="deleteJam(item.id)" class="opacity-0 group-hover:opacity-100 text-rose-500 p-1 hover:bg-rose-50 rounded-lg transition-all cursor-pointer">
                                            <i class="material-icons text-lg">close</i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Tab 2: Mata Pelajaran -->
            <div x-show="activeTab === 'mapel'" x-transition>
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Daftar Mata Pelajaran</h3>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-widest mt-1">Kelola data kurikulum</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <form action="{{ route('mata-pelajaran.index') }}" method="GET" class="relative">
                            <input type="hidden" name="tab" value="mapel">
                            <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Mapel..." class="pl-12 pr-6 py-3 bg-slate-50 border-2 border-transparent rounded-xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700 text-sm">
                        </form>
                        <button @click="openMapelModal()" class="px-5 py-3 bg-[#d90d8b] text-white rounded-xl font-bold text-xs shadow-lg shadow-pink-100 hover:scale-[1.02] cursor-pointer">
                            TAMBAH MAPEL
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-3">
                        <thead>
                            <tr class="text-slate-400 text-[11px] uppercase font-black tracking-widest">
                                <th class="px-6 py-3">Kode</th>
                                <th class="px-6 py-3">Mata Pelajaran</th>
                                <th class="px-6 py-3">Pengajar</th>
                                <th class="px-6 py-3 text-center">Status</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <template x-for="item in mapels.data" :key="item.id">
                                <tr class="group">
                                    <td class="px-6 py-4 bg-slate-50 border-y border-l border-transparent first:rounded-l-2xl font-black text-slate-400" x-text="item.kode_pelajaran"></td>
                                    <td class="px-6 py-4 bg-slate-50 border-y border-transparent font-bold text-slate-800" x-text="item.nama_pelajaran"></td>
                                    <td class="px-6 py-4 bg-slate-50 border-y border-transparent">
                                        <p class="font-bold text-slate-600" x-text="item.guru ? item.guru.nama : '-'"></p>
                                    </td>
                                    <td class="px-6 py-4 bg-slate-50 border-y border-transparent text-center">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" :checked="item.is_active" class="sr-only peer" @change="toggleMapel(item)">
                                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                        </label>
                                    </td>
                                    <td class="px-6 py-4 bg-slate-50 border-y border-r border-transparent last:rounded-r-2xl text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button @click="editMapel(item.id)" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg cursor-pointer transition-colors"><i class="material-icons text-lg">edit</i></button>
                                            <button @click="deleteMapel(item.id)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg cursor-pointer transition-colors"><i class="material-icons text-lg">delete</i></button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $subjects->links() }}
                </div>
            </div>

            <!-- Tab 3: Jadwal Pelajaran -->
            <div x-show="activeTab === 'jadwal'" x-transition>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Class Selection -->
                    <div class="space-y-6">
                        <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100">
                            <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6 border-b border-slate-200 pb-4">PILIH KELAS</h4>
                            <div class="space-y-2">
                                <template x-for="kls in kelasList" :key="kls.id">
                                    <button @click="selectedKelas = kls; loadSchedules(kls.id)" 
                                        class="w-full text-left px-5 py-4 rounded-xl font-bold transition-all flex items-center justify-between group"
                                        :class="selectedKelas?.id === kls.id ? 'bg-[#d90d8b] text-white shadow-lg shadow-pink-100' : 'bg-white border border-slate-100 text-slate-600 hover:border-pink-200'">
                                        <span x-text="kls.nama"></span>
                                        <i class="material-icons text-lg opacity-0 group-hover:opacity-100 transition-opacity">chevron_right</i>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Area -->
                    <div class="lg:col-span-2 space-y-8">
                        <template x-if="!selectedKelas">
                            <div class="bg-slate-50 rounded-3xl p-20 border-2 border-dashed border-slate-200 text-center">
                                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm border border-slate-100">
                                    <i class="material-icons text-slate-300 text-3xl">school</i>
                                </div>
                                <h4 class="text-lg font-bold text-slate-800">Pilih Kelas</h4>
                                <p class="text-slate-400 mt-2 font-medium">Silakan pilih kelas di samping untuk mengatur jadwal.</p>
                            </div>
                        </template>

                        <template x-if="selectedKelas">
                            <div class="space-y-8" x-transition>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-800" x-text="'Jadwal Kelas ' + selectedKelas.nama"></h3>
                                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mt-1" x-text="activeSetting ? activeSetting.semester + ' ' + activeSetting.tahun_pelajaran : '-'"></p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <template x-for="hariName in ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']" :key="hariName">
                                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                                            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                                                <h5 class="text-[11px] font-black text-slate-400 uppercase tracking-widest" x-text="hariName"></h5>
                                                <i class="material-icons text-slate-300 text-lg">calendar_today</i>
                                            </div>
                                            <div class="p-0">
                                                <table class="w-full text-left">
                                                    <template x-if="!jams[hariName] || jams[hariName].length === 0">
                                                        <tr>
                                                            <td class="p-8 text-center text-xs font-bold text-slate-300 italic">Belum ada jam diatur</td>
                                                        </tr>
                                                    </template>
                                                    <template x-for="jamSlot in jams[hariName]" :key="jamSlot.id">
                                                        <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50/50 transition-colors group">
                                                            <td class="px-6 py-4 w-28 text-[11px] font-black text-slate-400" x-text="formatTime(jamSlot.jam_mulai) + ' - ' + formatTime(jamSlot.jam_selesai)"></td>
                                                            <td class="px-4 py-2">
                                                                <select @change="saveInlineSchedule(hariName, jamSlot.id, $event.target.value)" 
                                                                    class="w-full bg-slate-50/50 border border-transparent hover:border-slate-200 px-3 py-2 rounded-xl text-sm font-bold text-slate-700 outline-none focus:bg-white focus:border-[#ba80e8] transition-all"
                                                                    :value="getScheduleForSlot(hariName, jamSlot.id)?.subject_id || ''">
                                                                    <option value="">Pilih Mapel</option>
                                                                    <template x-for="m in activeMapels" :key="m.id">
                                                                        <option :value="m.id" x-text="m.nama_pelajaran"></option>
                                                                    </template>
                                                                </select>
                                                            </td>
                                                            <td class="px-6 py-4 w-12 text-right">
                                                                <template x-if="getScheduleForSlot(hariName, jamSlot.id)">
                                                                    <button @click="deleteSchedule(getScheduleForSlot(hariName, jamSlot.id).id)" class="opacity-0 group-hover:opacity-100 text-rose-500 hover:bg-rose-50 p-1 rounded-lg transition-all cursor-pointer">
                                                                        <i class="material-icons text-lg">delete_outline</i>
                                                                    </button>
                                                                </template>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </table>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Jam Modal -->
    <div x-show="showJamModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div @click="showJamModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-8">
                <h3 class="text-xl font-bold text-slate-800 mb-6 font-primary">Tambah Jam Pelajaran</h3>
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Hari</label>
                        <select x-model="jamForm.hari" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] transition-all outline-none font-bold">
                            <option value="">Pilih Hari</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Mulai</label>
                            <input type="time" x-model="jamForm.jam_mulai" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] transition-all outline-none font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Selesai</label>
                            <input type="time" x-model="jamForm.jam_selesai" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] transition-all outline-none font-bold">
                        </div>
                    </div>
                </div>
                <div class="mt-8 flex gap-3">
                    <button @click="showJamModal = false" class="flex-1 py-4 bg-slate-50 text-slate-500 rounded-2xl font-bold">Batal</button>
                    <button @click="saveJam" class="flex-1 py-4 bg-[#d90d8b] text-white rounded-2xl font-bold shadow-lg shadow-pink-100">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mapel Modal -->
    <div x-show="showMapelModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div @click="showMapelModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8">
                <h3 class="text-xl font-bold text-slate-800 mb-6" x-text="mapelForm.id ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran'"></h3>
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Kode Pelajaran</label>
                        <input type="text" x-model="mapelForm.kode_pelajaran" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] transition-all outline-none font-bold" placeholder="Contoh: MP001">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nama Mata Pelajaran</label>
                        <input type="text" x-model="mapelForm.nama_pelajaran" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] transition-all outline-none font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Guru Pengajar</label>
                        <select x-model="mapelForm.guru_id" class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] transition-all outline-none font-bold">
                            <option value="">Pilih Guru</option>
                            <template x-for="guru in gurus" :key="guru.id">
                                <option :value="guru.id" x-text="guru.nama"></option>
                            </template>
                        </select>
                    </div>
                </div>
                <div class="mt-8 flex gap-3">
                    <button @click="showMapelModal = false" class="flex-1 py-4 bg-slate-50 text-slate-500 rounded-2xl font-bold">Batal</button>
                    <button @click="saveMapel" class="flex-1 py-4 bg-[#ba80e8] text-white rounded-2xl font-bold shadow-lg shadow-purple-100">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Modal REMOVED for Inline Edit -->
</div>

@push('scripts')
<script>
function akademikPage() {
    return {
        activeTab: '{{ request("tab", "jam") }}',
        activeSetting: @json($activeSetting),
        jams: @json($jams),
        mapels: @json($subjects),
        kelasList: @json($kelas),
        gurus: @json($gurus),
        
        schedules: {},
        selectedKelas: null,

        showJamModal: false,
        jamForm: { hari: '', jam_mulai: '', jam_selesai: '' },

        showMapelModal: false,
        mapelForm: { id: null, kode_pelajaran: '', nama_pelajaran: '', guru_id: '', is_active: 1 },

        scheduleForm: { hari: 'Senin', jam_id: '', subject_id: '', kelas_id: '' },

        init() {
            // Initial checks or loading
        },

        get activeMapels() {
            return (this.mapels.data || []).filter(m => m.is_active);
        },

        get availableJamsByHari() {
            return this.jams[this.scheduleForm.hari] || [];
        },

        formatTime(t) {
            if (!t) return '-';
            // Assuming t is 'HH:mm:ss' or 'H:i'
            return t.substring(0, 5);
        },

        hasJamData() {
            return Object.keys(this.jams).length > 0;
        },

        hasMapelData() {
            return this.mapels.total > 0;
        },

        goToTab(tab) {
            if (tab === 'mapel' && !this.hasJamData()) {
                Swal.fire('Perhatian', 'Harap isi data Jam Pelajaran terlebih dahulu.', 'warning');
                return;
            }
            if (tab === 'jadwal') {
                if (!this.activeSetting) {
                    Swal.fire('Perhatian', 'Harap aktifkan salah satu Semester/Tahun Pelajaran di Manajemen Sekolah.', 'warning');
                    return;
                }
                if (!this.hasJamData() || !this.hasMapelData()) {
                    Swal.fire('Perhatian', 'Harap isi data Jam dan Mata Pelajaran terlebih dahulu.', 'warning');
                    return;
                }
            }
            this.activeTab = tab;
        },

        // Jam Pelajaran
        saveJam() {
            $.post('{{ route("mata-pelajaran.jam.store") }}', { _token: '{{ csrf_token() }}', ...this.jamForm }).done(() => {
                location.reload();
            });
        },
        deleteJam(id) {
            Swal.fire({ title: 'Hapus?', text: 'Hapus jam pelajaran ini?', icon: 'warning', showCancelButton: true }).then(r => {
                if (r.isConfirmed) {
                    $.ajax({
                        url: `{{ url('mata-pelajaran/jam/destroy') }}/${id}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: () => location.reload()
                    });
                }
            });
        },

        // Mapel
        openMapelModal() {
            this.mapelForm = { id: null, kode_pelajaran: '', nama_pelajaran: '', guru_id: '', is_active: 1 };
            this.showMapelModal = true;
        },
        editMapel(id) {
            $.get(`{{ url('mata-pelajaran/subject/show') }}/${id}`).done(res => {
                this.mapelForm = res;
                this.showMapelModal = true;
            });
        },
        saveMapel() {
            const url = this.mapelForm.id ? `{{ url('mata-pelajaran/subject/update') }}/${this.mapelForm.id}` : '{{ route("mata-pelajaran.subject.store") }}';
            $.post(url, { _token: '{{ csrf_token() }}', ...this.mapelForm }).done(() => {
                location.reload();
            }).fail(err => {
                Swal.fire('Error', 'Pastikan kode bersifat unik.', 'error');
            });
        },
        toggleMapel(item) {
            item.is_active = !item.is_active;
            $.post(`{{ url('mata-pelajaran/subject/update') }}/${item.id}`, {
                _token: '{{ csrf_token() }}',
                ...item,
                is_active: item.is_active ? 1 : 0
            });
        },
        deleteMapel(id) {
            Swal.fire({ title: 'Hapus?', text: 'Hapus mata pelajaran ini?', icon: 'warning', showCancelButton: true }).then(r => {
                if (r.isConfirmed) {
                    $.ajax({
                        url: `{{ url('mata-pelajaran/subject/destroy') }}/${id}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: () => location.reload()
                    });
                }
            });
        },

        // Jadwal
        loadSchedules(kelasId) {
            $.get(`{{ url('mata-pelajaran/schedule/get-by-kelas') }}/${kelasId}`).done(res => {
                this.schedules = res;
            });
        },
        getScheduleForSlot(hari, jamId) {
            if (!this.schedules[hari]) return null;
            return this.schedules[hari].find(s => s.jam_id === jamId);
        },
        saveInlineSchedule(hari, jamId, subjectId) {
            if (!subjectId) return;
            $.post('{{ route("mata-pelajaran.schedule.store") }}', { 
                _token: '{{ csrf_token() }}', 
                hari: hari,
                jam_id: jamId,
                subject_id: subjectId,
                kelas_id: this.selectedKelas.id
            }).done(() => {
                this.loadSchedules(this.selectedKelas.id);
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
                Toast.fire({ icon: 'success', title: 'Jadwal diperbarui' });
            });
        },
        deleteSchedule(id) {
            Swal.fire({ title: 'Hapus?', text: 'Hapus jadwal ini?', icon: 'warning', showCancelButton: true }).then(r => {
                if (r.isConfirmed) {
                    $.ajax({
                        url: `{{ url('mata-pelajaran/schedule/destroy') }}/${id}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: () => this.loadSchedules(this.selectedKelas.id)
                    });
                }
            });
        }
    }
}
</script>
@endpush
@endsection
