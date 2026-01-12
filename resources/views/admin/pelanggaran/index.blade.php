@extends('layouts.app')

@section('title', 'Manajemen Pelanggaran - Literasia')

@section('content')
<div x-data="pelanggaranPage()" x-init="init()" class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Pelanggaran</h1>
            <p class="text-slate-500 font-medium mt-1">Kelola data pelanggaran siswa dan pegawai</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <template x-if="activeTab === 'pengaturan'">
                <button @click="openCreateMasterModal()" class="flex items-center gap-2 px-6 py-3 bg-slate-800 text-white rounded-2xl text-sm font-bold shadow-lg shadow-slate-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    <i class="material-icons text-[20px]">add_circle</i> Tambah Master Pelanggaran
                </button>
            </template>
            <template x-if="activeTab === 'siswa'">
                <div class="flex items-center gap-3">
                    <a href="{{ route('pelanggaran.export-excel-siswa') }}" class="flex items-center gap-2 px-6 py-3 bg-emerald-500 text-white rounded-2xl text-sm font-bold shadow-lg shadow-emerald-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        <i class="material-icons text-[20px]">description</i> Excel
                    </a>
                    <button @click="openCreateSiswaModal()" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl text-sm font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        <i class="material-icons text-[20px]">add_circle</i> Catat Pelanggaran Siswa
                    </button>
                </div>
            </template>
            <template x-if="activeTab === 'pegawai'">
                <div class="flex items-center gap-3">
                    <a href="{{ route('pelanggaran.export-excel-pegawai') }}" class="flex items-center gap-2 px-6 py-3 bg-emerald-500 text-white rounded-2xl text-sm font-bold shadow-lg shadow-emerald-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        <i class="material-icons text-[20px]">description</i> Excel
                    </a>
                    <button @click="openCreatePegawaiModal()" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-2xl text-sm font-bold shadow-lg shadow-blue-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        <i class="material-icons text-[20px]">add_circle</i> Catat Pelanggaran Pegawai
                    </button>
                </div>
            </template>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex items-center gap-2 p-1.5 bg-slate-100 w-fit rounded-[2rem]">
        <button @click="switchTab('pengaturan')" :class="activeTab === 'pengaturan' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-8 py-3 rounded-[1.5rem] text-sm font-bold transition-all duration-200">
            Pengaturan
        </button>
        <button @click="switchTab('siswa')" :class="activeTab === 'siswa' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-8 py-3 rounded-[1.5rem] text-sm font-bold transition-all duration-200">
            Siswa
        </button>
        <button @click="switchTab('pegawai')" :class="activeTab === 'pegawai' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-8 py-3 rounded-[1.5rem] text-sm font-bold transition-all duration-200">
            Pegawai
        </button>
    </div>

    <!-- Search Area -->
    <div class="w-full md:w-96 relative group">
        <form :action="'{{ route('pelanggaran.index') }}'" method="GET">
            <input type="hidden" name="tab" :value="activeTab">
            <input 
                type="text" 
                name="search" 
                value="{{ $search }}" 
                placeholder="Cari data..." 
                class="w-full bg-white border border-slate-200 rounded-2xl pl-12 pr-6 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-100 transition-all outline-none"
            >
            <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-slate-600 transition-colors">search</i>
        </form>
    </div>

    <!-- Tab Contents -->
    <div class="space-y-6">
        
        <!-- Tab: Pengaturan -->
        <div x-show="activeTab === 'pengaturan'" x-cloak>
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-2">
                <div class="overflow-x-auto p-4">
                    <table class="w-full text-left border-separate border-spacing-y-3">
                        <thead>
                            <tr class="text-slate-400 text-[11px] uppercase font-black tracking-widest">
                                <th class="px-6 py-3">Nama Pelanggaran</th>
                                <th class="px-6 py-3">Jenis</th>
                                <th class="px-6 py-3">Poin</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($masterPelanggarans as $item)
                            <tr class="group hover:scale-[1.005] transition-transform duration-200">
                                <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-l border-transparent group-hover:border-slate-100 first:rounded-l-2xl">
                                    <p class="font-bold text-slate-800">{{ $item->nama }}</p>
                                </td>
                                <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100">
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $item->jenis == 'siswa' ? 'bg-pink-50 text-pink-600' : 'bg-blue-50 text-blue-600' }}">
                                        {{ $item->jenis }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100 font-black text-slate-700">
                                    {{ $item->poin }} Poin
                                </td>
                                <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full {{ $item->status ? 'bg-emerald-500' : 'bg-slate-300' }}"></div>
                                        <span class="font-bold {{ $item->status ? 'text-emerald-600' : 'text-slate-400' }}">{{ $item->status ? 'Aktif' : 'Non-aktif' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-r border-transparent group-hover:border-slate-100 last:rounded-r-2xl text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="editMaster('{{ $item->id }}')" class="p-2 text-blue-500 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors cursor-pointer">
                                            <i class="material-icons text-lg">edit</i>
                                        </button>
                                        <button @click="deleteMaster('{{ $item->id }}')" class="p-2 text-rose-500 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors cursor-pointer">
                                            <i class="material-icons text-lg">delete</i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="py-20 text-center font-bold text-slate-400 italic">Belum ada master data pelanggaran</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-slate-50">
                    {{ $masterPelanggarans->links() }}
                </div>
            </div>
        </div>

        <!-- Tab: Siswa -->
        <div x-show="activeTab === 'siswa'" x-cloak>
            @if(count($masterSiswa) == 0)
                <div class="bg-amber-50 border border-amber-100 p-8 rounded-[2.5rem] flex items-center gap-6">
                    <div class="w-16 h-16 bg-white rounded-3xl shadow-sm flex items-center justify-center text-amber-500">
                        <i class="material-icons text-4xl">warning</i>
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-amber-900">Pengaturan Belum Lengkap!</h4>
                        <p class="text-amber-700 font-medium mt-1">Anda harus mengisi data master pelanggaran kategori <b>Siswa</b> terlebih dahulu sebelum dapat mencatat pelanggaran.</p>
                    </div>
                </div>
            @else
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-2">
                <div class="overflow-x-auto p-4">
                    <table class="w-full text-left border-separate border-spacing-y-3">
                        <thead>
                            <tr class="text-slate-400 text-[11px] uppercase font-black tracking-widest">
                                <th class="px-6 py-3">Siswa</th>
                                <th class="px-6 py-3">Pelanggaran</th>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Poin</th>
                                <th class="px-6 py-3 text-right">Laporan</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($pelanggaranSiswas as $item)
                            <tr class="group hover:scale-[1.005] transition-transform duration-200">
                                <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-l border-transparent group-hover:border-slate-100 first:rounded-l-2xl">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-pink-50 flex items-center justify-center text-[#d90d8b]">
                                            <i class="material-icons">person</i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800 leading-none">{{ $item->siswa->nama_lengkap }}</p>
                                            <p class="text-[10px] font-bold text-slate-400 mt-1">NIS: {{ $item->siswa->nis }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100">
                                    <p class="font-bold text-slate-700 leading-none">{{ $item->masterPelanggaran->nama }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium mt-1 uppercase">{{ Str::limit($item->deskripsi, 30) }}</p>
                                </td>
                                <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100 font-bold text-slate-600">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100">
                                    <span class="px-3 py-1 bg-rose-50 text-rose-600 rounded-lg font-black text-[10px]">+{{ $item->masterPelanggaran->poin }}</span>
                                </td>
                                <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100 text-right">
                                    <a href="{{ route('pelanggaran.pdf-siswa', $item->id) }}" target="_blank" class="p-2 text-indigo-500 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors inline-flex items-center gap-1 cursor-pointer">
                                        <i class="material-icons text-lg">picture_as_pdf</i>
                                        <span class="text-[10px] font-black uppercase pr-1">PDF</span>
                                    </a>
                                </td>
                                <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-r border-transparent group-hover:border-slate-100 last:rounded-r-2xl text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="showDetail('siswa', '{{ $item->id }}')" class="p-2 text-slate-500 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer" title="Detail">
                                            <i class="material-icons text-lg">visibility</i>
                                        </button>
                                        <button @click="editSiswa('{{ $item->id }}')" class="p-2 text-blue-500 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors cursor-pointer">
                                            <i class="material-icons text-lg">edit</i>
                                        </button>
                                        <button @click="deleteSiswa('{{ $item->id }}')" class="p-2 text-rose-500 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors cursor-pointer">
                                            <i class="material-icons text-lg">delete</i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="py-20 text-center font-bold text-slate-400 italic">Belum ada catatan pelanggaran siswa</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-slate-50">
                    {{ $pelanggaranSiswas->links() }}
                </div>
            </div>
            @endif
        </div>

        <!-- Tab: Pegawai -->
        <div x-show="activeTab === 'pegawai'" x-cloak>
            @if(count($masterPegawai) == 0)
                <div class="bg-amber-50 border border-amber-100 p-8 rounded-[2.5rem] flex items-center gap-6">
                    <div class="w-16 h-16 bg-white rounded-3xl shadow-sm flex items-center justify-center text-amber-500">
                        <i class="material-icons text-4xl">warning</i>
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-amber-900">Pengaturan Belum Lengkap!</h4>
                        <p class="text-amber-700 font-medium mt-1">Anda harus mengisi data master pelanggaran kategori <b>Pegawai</b> terlebih dahulu sebelum dapat mencatat pelanggaran.</p>
                    </div>
                </div>
            @else
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-2">
                <div class="overflow-x-auto p-4">
                    <table class="w-full text-left border-separate border-spacing-y-3">
                        <thead>
                            <tr class="text-slate-400 text-[11px] uppercase font-black tracking-widest">
                                <th class="px-6 py-3">Pegawai / Guru</th>
                                <th class="px-6 py-3">Pelanggaran</th>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Poin</th>
                                <th class="px-6 py-3 text-right">Laporan</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($pelanggaranPegawais as $item)
                            <tr class="group hover:scale-[1.005] transition-transform duration-200">
                                <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-l border-transparent group-hover:border-slate-100 first:rounded-l-2xl">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                                            <i class="material-icons">badge</i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800 leading-none">{{ $item->fungsionaris->nama }}</p>
                                            <p class="text-[10px] font-black text-slate-400 mt-1 uppercase tracking-tighter">{{ $item->fungsionaris->jabatan }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100">
                                    <p class="font-bold text-slate-700 leading-none">{{ $item->masterPelanggaran->nama }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium mt-1 uppercase">{{ Str::limit($item->deskripsi, 30) }}</p>
                                </td>
                                <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100 font-bold text-slate-600">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100">
                                    <span class="px-3 py-1 bg-rose-50 text-rose-600 rounded-lg font-black text-[10px]">+{{ $item->masterPelanggaran->poin }}</span>
                                </td>
                                <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100 text-right">
                                    <a href="{{ route('pelanggaran.pdf-pegawai', $item->id) }}" target="_blank" class="p-2 text-indigo-500 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors inline-flex items-center gap-1 cursor-pointer">
                                        <i class="material-icons text-lg">picture_as_pdf</i>
                                        <span class="text-[10px] font-black uppercase pr-1">PDF</span>
                                    </a>
                                </td>
                                <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-r border-transparent group-hover:border-slate-100 last:rounded-r-2xl text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="showDetail('pegawai', '{{ $item->id }}')" class="p-2 text-slate-500 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer" title="Detail">
                                            <i class="material-icons text-lg">visibility</i>
                                        </button>
                                        <button @click="editPegawai('{{ $item->id }}')" class="p-2 text-blue-500 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors cursor-pointer">
                                            <i class="material-icons text-lg">edit</i>
                                        </button>
                                        <button @click="deletePegawai('{{ $item->id }}')" class="p-2 text-rose-500 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors cursor-pointer">
                                            <i class="material-icons text-lg">delete</i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="py-20 text-center font-bold text-slate-400 italic">Belum ada catatan pelanggaran pegawai</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-slate-50">
                    {{ $pelanggaranPegawais->links() }}
                </div>
            </div>
            @endif
        </div>

    </div>

    <!-- Modals (Master, Siswa, Pegawai) -->
    @include('admin.pelanggaran.modals')
    @include('admin.pelanggaran.detail-modal')

</div>
@endsection

@section('scripts')
<script>
    function pelanggaranPage() {
        return {
            activeTab: '{{ $tab }}',
            openModal: false,
            openDetailModal: false,
            modalType: '', // 'master', 'siswa', 'pegawai'
            detailData: null,
            editMode: false,
            masterData: { id: '', nama: '', jenis: 'siswa', poin: 0, status: 1 },
            siswaData: { id: '', siswa_id: '', master_pelanggaran_id: '', tanggal: '{{ date('Y-m-d') }}', deskripsi: '', tindak_lanjut: '' },
            pegawaiData: { id: '', fungsionaris_id: '', master_pelanggaran_id: '', tanggal: '{{ date('Y-m-d') }}', deskripsi: '', tindak_lanjut: '' },

            init() {},

            switchTab(tab) {
                this.activeTab = tab;
                const url = new URL(window.location);
                url.searchParams.set('tab', tab);
                window.history.pushState({}, '', url);
            },

            // Master Modals
            openCreateMasterModal() {
                this.editMode = false;
                this.modalType = 'master';
                this.masterData = { id: '', nama: '', jenis: 'siswa', poin: 0, status: 1 };
                this.openModal = true;
            },
            editMaster(id) {
                $.get(`{{ url('pelanggaran/show-master') }}/${id}`, (data) => {
                    this.masterData = data;
                    this.editMode = true;
                    this.modalType = 'master';
                    this.openModal = true;
                });
            },
            saveMaster() {
                const url = this.editMode ? `{{ url('pelanggaran/update-master') }}/${this.masterData.id}` : `{{ route('pelanggaran.store-master') }}`;
                this.postRequest(url, this.masterData);
            },
            deleteMaster(id) {
                this.confirmDelete(`{{ url('pelanggaran/destroy-master') }}/${id}`);
            },

            // Siswa Modals
            openCreateSiswaModal() {
                this.editMode = false;
                this.modalType = 'siswa';
                this.siswaData = { id: '', siswa_id: '', master_pelanggaran_id: '', tanggal: '{{ date('Y-m-d') }}', deskripsi: '', tindak_lanjut: '' };
                this.openModal = true;
            },
            editSiswa(id) {
                $.get(`{{ url('pelanggaran/show-siswa') }}/${id}`, (data) => {
                    this.siswaData = data;
                    this.editMode = true;
                    this.modalType = 'siswa';
                    this.openModal = true;
                });
            },
            saveSiswa() {
                const url = this.editMode ? `{{ url('pelanggaran/update-siswa') }}/${this.siswaData.id}` : `{{ route('pelanggaran.store-siswa') }}`;
                this.postRequest(url, this.siswaData, true);
            },
            deleteSiswa(id) {
                this.confirmDelete(`{{ url('pelanggaran/destroy-siswa') }}/${id}`);
            },

            // Pegawai Modals
            openCreatePegawaiModal() {
                this.editMode = false;
                this.modalType = 'pegawai';
                this.pegawaiData = { id: '', fungsionaris_id: '', master_pelanggaran_id: '', tanggal: '{{ date('Y-m-d') }}', deskripsi: '', tindak_lanjut: '' };
                this.openModal = true;
            },
            editPegawai(id) {
                $.get(`{{ url('pelanggaran/show-pegawai') }}/${id}`, (data) => {
                    this.pegawaiData = data;
                    this.editMode = true;
                    this.modalType = 'pegawai';
                    this.openModal = true;
                });
            },
            savePegawai() {
                const url = this.editMode ? `{{ url('pelanggaran/update-pegawai') }}/${this.pegawaiData.id}` : `{{ route('pelanggaran.store-pegawai') }}`;
                this.postRequest(url, this.pegawaiData, true);
            },
            deletePegawai(id) {
                this.confirmDelete(`{{ url('pelanggaran/destroy-pegawai') }}/${id}`);
            },

            // Show Detail
            showDetail(type, id) {
                const endpoint = type === 'siswa' ? `{{ url('pelanggaran/show-siswa') }}/${id}` : `{{ url('pelanggaran/show-pegawai') }}/${id}`;
                $.get(endpoint, (data) => {
                    this.detailData = data;
                    this.modalType = type;
                    this.openDetailModal = true;
                });
            },

            // Helper Requests
            postRequest(url, data, showPdf = false) {
                Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: { ...data, _token: '{{ csrf_token() }}' },
                    success: (res) => {
                        this.openModal = false;
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.success, timer: 1500, showConfirmButton: false }).then(() => {
                            if (showPdf && res.pdf_url) {
                                window.open(res.pdf_url, '_blank');
                            }
                            location.reload();
                        });
                    },
                    error: (err) => {
                        let msg = err.responseJSON?.message || 'Terjadi kesalahan.';
                        if (err.responseJSON?.errors) msg = Object.values(err.responseJSON.errors).join('<br>');
                        Swal.fire('Oops...', msg, 'error');
                    }
                });
            },
            confirmDelete(url) {
                Swal.fire({
                    title: 'Hapus data?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d90d8b',
                    confirmButtonText: 'Ya, Hapus'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
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
