@extends('layouts.app')

@section('title', 'Manajemen Fungsionaris - Literasia')

@section('content')
<div x-data="fungsionarisPage()" x-init="init()" class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Fungsionaris</h1>
            <p class="text-slate-500 font-medium mt-1">Kelola data Guru dan Staf Pegawai Literasia</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('fungsionaris.download-template') }}" class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
                <i class="material-icons text-[20px]">file_download</i> Template
            </a>
            <button @click="openImportModal = true" class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
                <i class="material-icons text-[20px]">file_upload</i> Import
            </button>
            <button @click="openCreateModal()" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl text-sm font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                <i class="material-icons text-[20px]">add_circle</i> Tambah Fungsionaris
            </button>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-2">
        <!-- Tabs -->
        <div class="flex p-4 border-b border-slate-50 gap-2">
            <button 
                @click="activeTab = 'guru'" 
                class="px-8 py-3.5 rounded-2xl text-sm font-bold transition-all duration-300 flex items-center gap-2 cursor-pointer"
                :class="activeTab === 'guru' ? 'bg-pink-50 text-[#d90d8b]' : 'text-slate-400 hover:bg-slate-50'"
            >
                <i class="material-icons text-[20px]">school</i> Guru
            </button>
            <button 
                @click="activeTab = 'pegawai'" 
                class="px-8 py-3.5 rounded-2xl text-sm font-bold transition-all duration-300 flex items-center gap-2 cursor-pointer"
                :class="activeTab === 'pegawai' ? 'bg-pink-50 text-[#d90d8b]' : 'text-slate-400 hover:bg-slate-50'"
            >
                <i class="material-icons text-[20px]">badge</i> Pegawai
            </button>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto p-4">
            <!-- Guru Table -->
            <div x-show="activeTab === 'guru'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                <table class="w-full text-left border-separate border-spacing-y-3">
                    <thead>
                        <tr class="text-slate-400 text-[11px] uppercase font-black tracking-widest">
                            <th class="px-6 py-3">Nama Lengkap</th>
                            <th class="px-6 py-3">NIP / NIK</th>
                            <th class="px-6 py-3">Posisi</th>
                            <th class="px-6 py-3 text-center">Status</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($guru as $item)
                        <tr class="group hover:scale-[1.005] transition-transform duration-200">
                            <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-l border-transparent group-hover:border-slate-100 first:rounded-l-2xl">
                                <div class="flex items-center gap-3">
                                    <img class="w-9 h-9 rounded-xl border-2 border-white shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode($item->nama) }}&background=fdf2f8&color=d90d8b" alt="">
                                    <div>
                                        <p class="font-bold text-slate-800 leading-none">{{ $item->nama }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase">{{ $item->user->username }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100">
                                <p class="font-bold text-slate-700 leading-none">{{ $item->nip }}</p>
                                <p class="text-[10px] font-medium text-slate-400 mt-1">{{ $item->nik }}</p>
                            </td>
                            <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100">
                                <span class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-[11px] font-bold text-slate-600 truncate inline-block max-w-[150px]">
                                    {{ $item->posisi }}
                                </span>
                            </td>
                            <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100 text-center">
                                @if($item->status === 'aktif')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 uppercase tracking-widest border border-emerald-200">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 uppercase tracking-widest border border-slate-200">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-r border-transparent group-hover:border-slate-100 last:rounded-r-2xl text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button @click="editData('{{ $item->id }}')" class="p-2 text-blue-500 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors cursor-pointer">
                                        <i class="material-icons text-lg">edit</i>
                                    </button>
                                    <button @click="deleteData('{{ $item->id }}')" class="p-2 text-rose-500 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors cursor-pointer">
                                        <i class="material-icons text-lg">delete</i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mb-4">
                                        <i class="material-icons text-4xl">inventory_2</i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-400">Belum ada data guru</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pegawai Table -->
            <div x-show="activeTab === 'pegawai'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                <table class="w-full text-left border-separate border-spacing-y-3">
                    <thead>
                        <tr class="text-slate-400 text-[11px] uppercase font-black tracking-widest">
                            <th class="px-6 py-3">Nama Lengkap</th>
                            <th class="px-6 py-3">NIP / NIK</th>
                            <th class="px-6 py-3">Posisi</th>
                            <th class="px-6 py-3 text-center">Status</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($pegawai as $item)
                        <tr class="group hover:scale-[1.005] transition-transform duration-200">
                            <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-l border-transparent group-hover:border-slate-100 first:rounded-l-2xl">
                                <div class="flex items-center gap-3">
                                    <img class="w-9 h-9 rounded-xl border-2 border-white shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode($item->nama) }}&background=eff6ff&color=2563eb" alt="">
                                    <div>
                                        <p class="font-bold text-slate-800 leading-none">{{ $item->nama }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase">{{ $item->user->username }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100">
                                <p class="font-bold text-slate-700 leading-none">{{ $item->nip }}</p>
                                <p class="text-[10px] font-medium text-slate-400 mt-1">{{ $item->nik }}</p>
                            </td>
                            <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100">
                                <span class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-[11px] font-bold text-slate-600 truncate inline-block max-w-[150px]">
                                    {{ $item->posisi }}
                                </span>
                            </td>
                            <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100 text-center">
                                @if($item->status === 'aktif')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 uppercase tracking-widest border border-emerald-200">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 uppercase tracking-widest border border-slate-200">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-r border-transparent group-hover:border-slate-100 last:rounded-r-2xl text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button @click="editData('{{ $item->id }}')" class="p-2 text-blue-500 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors cursor-pointer">
                                        <i class="material-icons text-lg">edit</i>
                                    </button>
                                    <button @click="deleteData('{{ $item->id }}')" class="p-2 text-rose-500 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors cursor-pointer">
                                        <i class="material-icons text-lg">delete</i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mb-4">
                                        <i class="material-icons text-4xl">inventory_2</i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-400">Belum ada data pegawai</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Management Modal -->
    <div 
        x-show="openModal" 
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:p-0"
    >
        <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="openModal = false"></div>

        <div 
            x-show="openModal" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden relative z-10 flex flex-col"
        >
            <!-- Modal Header -->
            <div class="px-10 py-8 flex items-center justify-between border-b border-slate-50">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight" x-text="editMode ? 'Edit Fungsionaris' : 'Tambah Fungsionaris Baru'"></h2>
                    <p class="text-slate-400 font-medium text-sm mt-1">Lengkapi informasi fungsionaris di bawah ini.</p>
                </div>
                <button @click="openModal = false" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all">
                    <i class="material-icons">close</i>
                </button>
            </div>

            <!-- Modal Tabs (Internal) -->
            <div class="px-10 mt-6 overflow-y-auto flex-grow h-full custom-scrollbar">
                <div class="flex gap-4 mb-8">
                    <button 
                        @click="formTab = 'wajib'"
                        class="px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all cursor-pointer"
                        :class="formTab === 'wajib' ? 'bg-[#d90d8b] text-white' : 'bg-slate-50 text-slate-400 hover:bg-slate-100'"
                    >
                        Info Utama
                    </button>
                    <button 
                        @click="formTab = 'opsional'"
                        class="px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all cursor-pointer"
                        :class="formTab === 'opsional' ? 'bg-[#d90d8b] text-white' : 'bg-slate-50 text-slate-400 hover:bg-slate-100'"
                    >
                        Info Tambahan
                    </button>
                </div>

                <form id="fungsionarisForm" class="pb-10">
                    @csrf
                    <input type="hidden" name="id" x-model="formData.id">
                    
                    <!-- Tab Data Wajib -->
                    <div x-show="formTab === 'wajib'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form-input label="Nama Lengkap" name="nama" placeholder="Contoh: Ahmad Subardjo" x-model="formData.nama" />
                        <x-form-input label="NIP" name="nip" placeholder="Nomor Induk Pegawai" x-model="formData.nip" />
                        <x-form-input label="NIK" name="nik" placeholder="Nomor Induk Kependudukan" x-model="formData.nik" />
                        <x-form-input label="Posisi / Peran" name="posisi" placeholder="Contoh: Guru Matematika" x-model="formData.posisi" />
                        
                        <div class="space-y-1.5">
                            <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Jabatan Sistem</label>
                            <select name="jabatan" x-model="formData.jabatan" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all">
                                <option value="guru">Guru</option>
                                <option value="pegawai">Pegawai</option>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Status Kepegawaian</label>
                            <select name="status" x-model="formData.status" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>

                        <x-form-input label="Username Login" name="username" placeholder="johndoe123" x-model="formData.username" />
                        
                        <div class="space-y-1.5">
                            <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Password</label>
                            <input 
                                type="password" 
                                name="password" 
                                x-model="formData.password"
                                class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all"
                                placeholder="••••••••"
                            >
                            <p class="text-[10px] text-slate-400 font-bold px-1" x-show="editMode">* Kosongkan jika tidak ingin mengubah</p>
                        </div>
                    </div>

                    <!-- Tab Data Opsional -->
                    <div x-show="formTab === 'opsional'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form-input label="No. Handphone" name="no_hp" placeholder="08xxxx" x-model="formData.no_hp" />
                        
                        <div class="space-y-1.5">
                            <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" x-model="formData.jenis_kelamin" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all">
                                <option value="">Pilih</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <x-form-input label="Tempat Lahir" name="tempat_lahir" x-model="formData.tempat_lahir" />
                        <x-form-input type="date" label="Tanggal Lahir" name="tanggal_lahir" x-model="formData.tanggal_lahir" />
                        
                        <div class="md:col-span-2 space-y-1.5">
                            <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                            <textarea name="alamat" x-model="formData.alamat" rows="3" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all"></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <x-form-input label="Pendidikan Terakhir" name="pendidikan_terakhir" placeholder="Contoh: S1 Teknologi Informasi" x-model="formData.pendidikan_terakhir" />
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="px-10 py-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <button @click="openModal = false" class="px-8 py-3.5 rounded-2xl text-sm font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-all">Batal</button>
                <button @click="saveData()" class="px-10 py-3.5 rounded-2xl bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white text-sm font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div x-show="openImportModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="openImportModal = false"></div>
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg p-10 relative z-10">
            <h3 class="text-2xl font-black text-slate-800 tracking-tight mb-2">Import Data</h3>
            <p class="text-slate-400 font-medium text-sm mb-8">Pilih file Excel sesuai format yang ditentukan.</p>
            
            <form id="importForm" enctype="multipart/form-data">
                @csrf
                <div class="relative group">
                    <input type="file" name="file" class="hidden" id="excelFile" @change="fileName = $event.target.files[0].name">
                    <label for="excelFile" class="flex flex-col items-center justify-center w-full h-48 bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl cursor-pointer group-hover:bg-pink-50 group-hover:border-[#d90d8b]/30 transition-all">
                        <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-slate-400 shadow-sm mb-4 group-hover:text-[#d90d8b]">
                            <i class="material-icons text-3xl">cloud_upload</i>
                        </div>
                        <p class="text-sm font-bold text-slate-500" x-text="fileName || 'Klik untuk pilih file'"></p>
                        <p class="text-[10px] uppercase font-black tracking-widest text-slate-400 mt-1">Hanya file .xlsx atau .xls</p>
                    </label>
                </div>
            </form>

            <div class="flex gap-3 mt-8">
                <button @click="openImportModal = false" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl text-sm font-bold hover:bg-slate-200 transition-all">Batal</button>
                <button @click="importData()" class="flex-1 py-4 bg-[#d90d8b] text-white rounded-2xl text-sm font-bold shadow-lg shadow-pink-100 hover:bg-[#ba80e8] transition-all">Import Sekarang</button>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    function fungsionarisPage() {
        return {
            activeTab: 'guru',
            openModal: false,
            openImportModal: false,
            editMode: false,
            formTab: 'wajib',
            fileName: '',
            formData: {
                id: '',
                nama: '',
                nip: '',
                nik: '',
                posisi: '',
                jabatan: 'guru',
                status: 'aktif',
                username: '',
                password: '',
                no_hp: '',
                jenis_kelamin: '',
                tempat_lahir: '',
                tanggal_lahir: '',
                alamat: '',
                pendidikan_terakhir: ''
            },
            init() {
                // Initial logic if needed
            },
            openCreateModal() {
                this.editMode = false;
                this.openModal = true;
                this.formTab = 'wajib';
                this.formData = {
                    id: '', nama: '', nip: '', nik: '', posisi: '', jabatan: 'guru', status: 'aktif',
                    username: '', password: '', no_hp: '', jenis_kelamin: '', tempat_lahir: '',
                    tanggal_lahir: '', alamat: '', pendidikan_terakhir: ''
                };
            },
            saveData() {
                const url = this.formData.id ? `{{ url('fungsionaris/update') }}/${this.formData.id}` : `{{ route('fungsionaris.store') }}`;
                
                // Show Loading
                Swal.fire({
                    title: 'Memproses...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: $('#fungsionarisForm').serialize(),
                    success: (res) => {
                        this.openModal = false;
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.success,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    },
                    error: (err) => {
                        let msg = 'Terjadi kesalahan pada server.';
                        if (err.responseJSON && err.responseJSON.errors) {
                            msg = Object.values(err.responseJSON.errors).join('<br>');
                        } else if (err.responseJSON && err.responseJSON.message) {
                            msg = err.responseJSON.message;
                        }
                        Swal.fire('Oops...', msg, 'error');
                    }
                });
            },
            editData(id) {
                $.get(`{{ url('fungsionaris/show') }}/${id}`, (data) => {
                    this.formData = {
                        id: data.id,
                        nama: data.nama,
                        nip: data.nip,
                        nik: data.nik,
                        posisi: data.posisi,
                        jabatan: data.jabatan,
                        status: data.status,
                        username: data.user.username,
                        password: '', // Hidden for security
                        no_hp: data.no_hp || '',
                        jenis_kelamin: data.jenis_kelamin || '',
                        tempat_lahir: data.tempat_lahir || '',
                        tanggal_lahir: data.tanggal_lahir || '',
                        alamat: data.alamat || '',
                        pendidikan_terakhir: data.pendidikan_terakhir || ''
                    };
                    this.editMode = true;
                    this.openModal = true;
                    this.formTab = 'wajib';
                });
            },
            deleteData(id) {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: "Seluruh data user terkait akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d90d8b',
                    confirmButtonText: 'Ya, Hapus Data',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('fungsionaris/destroy') }}/${id}`,
                            method: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: (res) => {
                                Swal.fire('Dihapus!', res.success, 'success').then(() => location.reload());
                            }
                        });
                    }
                });
            },
            importData() {
                let formData = new FormData($('#importForm')[0]);
                Swal.fire({ title: 'Mengimport...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                $.ajax({
                    url: `{{ route('fungsionaris.import') }}`,
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: (res) => {
                        Swal.fire('Berhasil', res.success, 'success').then(() => location.reload());
                    },
                    error: (err) => {
                        let msg = 'Terjadi kesalahan pada server.';
                        if (err.responseJSON && err.responseJSON.errors) {
                            msg = Object.values(err.responseJSON.errors).flat().join('<br>');
                        } else if (err.responseJSON && err.responseJSON.message) {
                            msg = err.responseJSON.message;
                        }
                        Swal.fire('Gagal Import', msg, 'error');
                    }
                });
            }
        }
    }
</script>
@endsection
