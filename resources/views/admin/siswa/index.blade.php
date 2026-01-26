@extends('layouts.app')

@section('title', 'Manajemen Siswa - Literasia')

@section('content')
<div x-data="siswaPage()" x-init="init()" class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Siswa</h1>
            <p class="text-slate-500 font-medium mt-1">Kelola data Peserta Didik Literasia</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button @click="openTemplateModal = true" class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
                <i class="material-icons text-[20px]">info</i> Template
            </button>
            <button @click="openImportModal = true" class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
                <i class="material-icons text-[20px]">file_upload</i> Import
            </button>
            <a href="{{ route('siswa.export', request()->all()) }}" class="flex items-center gap-2 px-6 py-3 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm font-bold text-emerald-600 hover:bg-emerald-100 transition-all shadow-sm">
                <i class="material-icons text-[20px]">file_download</i> Export Excel
            </a>
            @if($withoutAccount > 0)
            <button @click="generateAccounts()" class="flex items-center gap-2 px-6 py-3 bg-emerald-500 text-white rounded-2xl text-sm font-bold shadow-md hover:bg-emerald-600 transition-all">
                <i class="material-icons text-[20px]">group_add</i> Generate Akun ({{ $withoutAccount }})
            </button>
            @endif
            <button @click="openCreateModal()" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl text-sm font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                <i class="material-icons text-[20px]">add_circle</i> Tambah Siswa
            </button>
        </div>
    </div>

    <!-- Filter & Search Area -->
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-4">
        <form action="{{ route('siswa.index') }}" method="GET" id="filterForm" class="flex flex-col lg:flex-row items-center gap-4">
            <!-- Items per Page -->
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tampil</span>
                <select name="per_page" onchange="this.form.submit()" class="bg-slate-50 border border-slate-100 rounded-xl px-4 py-2 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-pink-100 transition-all">
                    @foreach([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" {{ request('per_page') == $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Search -->
            <div class="relative flex-grow w-full">
                <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-[20px]">search</i>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari nama, NIS atau NISN..." 
                    class="w-full bg-slate-50 border border-slate-100 rounded-2xl pl-12 pr-6 py-3 text-sm font-semibold text-slate-700 placeholder-slate-300 focus:ring-2 focus:ring-pink-100 outline-none transition-all"
                >
            </div>

            <!-- Class Filter -->
            <div class="min-w-[200px] w-full lg:w-auto">
                <select name="kelas" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-3 text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-pink-100 transition-all">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Reset Button -->
            @if(request('search') || request('kelas') || request('per_page'))
            <a href="{{ route('siswa.index') }}" class="px-6 py-3 bg-slate-50 text-slate-400 hover:text-slate-600 rounded-2xl text-sm font-bold transition-all">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-2 mt-4">
        <!-- Table Container -->
        <div class="overflow-x-auto p-4">
            <table class="w-full text-left border-separate border-spacing-y-3">
                <thead>
                    <tr class="text-slate-400 text-[11px] uppercase font-black tracking-widest">
                        <th class="px-6 py-3 text-center">No</th>
                        <th class="px-6 py-3">Nama Lengkap</th>
                        <th class="px-6 py-3">NIS / NISN</th>
                        <th class="px-6 py-3">Kelas</th>
                        <th class="px-6 py-3 text-center">L/P</th>
                        <th class="px-6 py-3 text-center">Akun</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($siswas as $index => $item)
                    <tr class="group hover:scale-[1.005] transition-transform duration-200">
                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-l border-transparent group-hover:border-slate-100 first:rounded-l-2xl text-center font-bold text-slate-400">
                            {{ ($siswas->currentPage() - 1) * $siswas->perPage() + $index + 1 }}
                        </td>
                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100">
                            <div class="flex items-center gap-3">
                                <img class="w-9 h-9 rounded-xl border-2 border-white shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode($item->nama_lengkap) }}&background=fdf2f8&color=d90d8b" alt="">
                                <div>
                                    <p class="font-bold text-slate-800 leading-none">{{ $item->nama_lengkap }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase">{{ $item->user->username ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100">
                            <p class="font-bold text-slate-700 leading-none">{{ $item->nis }}</p>
                            <p class="text-[10px] font-medium text-slate-400 mt-1">{{ $item->nisn }}</p>
                        </td>
                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100">
                            <span class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-[11px] font-bold text-slate-600">
                                {{ $item->kelas->nama ?? 'Tanpa Kelas' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100 text-center">
                            <span class="font-bold text-slate-600">{{ $item->jenis_kelamin }}</span>
                        </td>
                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100 text-center">
                            @if($item->user_id)
                                <span class="inline-flex items-center gap-1 text-emerald-600 text-[10px] font-black uppercase">
                                    <i class="material-icons text-sm">check_circle</i> Ada
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-amber-500 text-[10px] font-black uppercase">
                                    <i class="material-icons text-sm">warning</i> Belum
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-r border-transparent group-hover:border-slate-100 last:rounded-r-2xl text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                @if($item->user_id)
                                <button @click="resetPassword('{{ $item->id }}', '{{ $item->nama_lengkap }}', '{{ $item->nisn }}')" class="p-2 text-amber-500 bg-amber-50 hover:bg-amber-100 rounded-xl transition-colors cursor-pointer" title="Reset Password">
                                    <i class="material-icons text-lg">lock_reset</i>
                                </button>
                                @endif
                                <a href="{{ route('siswa.edit', $item->id) }}" class="p-2 text-blue-500 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors cursor-pointer">
                                    <i class="material-icons text-lg">edit</i>
                                </a>
                                <button @click="deleteData('{{ $item->id }}')" class="p-2 text-rose-500 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors cursor-pointer">
                                    <i class="material-icons text-lg">delete</i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mb-4">
                                    <i class="material-icons text-4xl">inventory_2</i>
                                </div>
                                <p class="text-sm font-bold text-slate-400">Belum ada data siswa</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($siswas->hasPages())
        <div class="px-8 py-6 border-t border-slate-50">
            {{ $siswas->links() }}
        </div>
        @endif
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
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight" x-text="editMode ? 'Edit Siswa' : 'Tambah Siswa Baru'"></h2>
                    <p class="text-slate-400 font-medium text-sm mt-1">Lengkapi informasi siswa di bawah ini.</p>
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
                        Data Wajib
                    </button>
                    <button 
                        @click="formTab = 'opsional'"
                        class="px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all cursor-pointer"
                        :class="formTab === 'opsional' ? 'bg-[#d90d8b] text-white' : 'bg-slate-50 text-slate-400 hover:bg-slate-100'"
                    >
                        Data Diri (Opsional)
                    </button>
                </div>

                <form id="siswaForm" class="pb-10">
                    @csrf
                    <input type="hidden" name="id" x-model="formData.id">
                    
                    <!-- Tab Data Wajib -->
                    <div x-show="formTab === 'wajib'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form-input label="Nama Lengkap" name="nama_lengkap" placeholder="Contoh: Ahmad Syahputra" x-model="formData.nama_lengkap" />
                        <x-form-input label="NIS" name="nis" placeholder="Nomor Induk Siswa" x-model="formData.nis" />
                        <x-form-input label="NISN" name="nisn" placeholder="Nomor Induk Siswa Nasional" x-model="formData.nisn" />
                        
                        <div class="space-y-1.5">
                            <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Kelas</label>
                            <select name="kelas_id" x-model="formData.kelas_id" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all">
                                <option value="">Pilih Kelas</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" x-model="formData.jenis_kelamin" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <x-form-input label="Username Login" name="username" placeholder="ahmad123" x-model="formData.username" />
                        
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
                        <x-form-input label="Tempat Lahir" name="tempat_lahir" x-model="formData.tempat_lahir" />
                        <x-form-input type="date" label="Tanggal Lahir" name="tanggal_lahir" x-model="formData.tanggal_lahir" />
                        
                        <x-form-input label="Nama Ayah" name="nama_ayah" x-model="formData.nama_ayah" />
                        <x-form-input label="Nama Ibu" name="nama_ibu" x-model="formData.nama_ibu" />
                        
                        <x-form-input label="No. HP Siswa" name="no_hp" x-model="formData.no_hp" />
                        <x-form-input label="No. HP Orang Tua" name="no_hp_ortu" x-model="formData.no_hp_ortu" />
                        
                        <div class="md:col-span-2 space-y-1.5">
                            <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                            <textarea name="alamat" x-model="formData.alamat" rows="3" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all"></textarea>
                        </div>
                        
                        <div class="md:col-span-2">
                            <x-form-input label="Sekolah Asal" name="sekolah_asal" placeholder="SMPN 1..." x-model="formData.sekolah_asal" />
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="px-10 py-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <button @click="openModal = false" class="px-8 py-3.5 rounded-2xl text-sm font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-all">Batal</button>
                <button @click="saveData()" class="px-10 py-3.5 rounded-2xl bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white text-sm font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Simpan Data
                </button>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div x-show="openImportModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="openImportModal = false"></div>
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg p-10 relative z-10">
            <h3 class="text-2xl font-black text-slate-800 tracking-tight mb-2">Import Data Siswa</h3>
            <p class="text-slate-400 font-medium text-sm mb-8">Pilih file Excel sesuai format template.</p>
            
            <form id="importForm" enctype="multipart/form-data">
                @csrf
                <div class="relative group">
                    <input type="file" name="file" class="hidden" id="excelFile" @change="fileName = $event.target.files[0].name">
                    <label for="excelFile" class="flex flex-col items-center justify-center w-full h-48 bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl cursor-pointer group-hover:bg-pink-50 group-hover:border-[#d90d8b]/30 transition-all">
                        <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-slate-400 shadow-sm mb-4 group-hover:text-[#d90d8b]">
                            <i class="material-icons text-3xl">cloud_upload</i>
                        </div>
                        <p class="text-sm font-bold text-slate-500" x-text="fileName || 'Klik untuk pilih file'"></p>
                    </label>
                </div>
            </form>

            <!-- Progress Indicator -->
            <div x-show="importing" class="mt-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-bold text-slate-600">Mengimport data...</span>
                    <span class="text-sm font-bold text-[#d90d8b]" x-text="importProgress + '%'"></span>
                </div>
                <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] rounded-full transition-all duration-300 ease-out" 
                         :style="'width: ' + importProgress + '%'"></div>
                </div>
            </div>

            <div class="flex gap-3 mt-8" x-show="!importing">
                <button @click="openImportModal = false" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl text-sm font-bold hover:bg-slate-200 transition-all">Batal</button>
                <button @click="importData()" class="flex-1 py-4 bg-[#d90d8b] text-white rounded-2xl text-sm font-bold shadow-lg shadow-pink-100 hover:bg-[#ba80e8] transition-all">Import</button>
            </div>
        </div>
    </div>

    <!-- Generate Accounts Progress Modal -->
    <div x-show="generating" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md p-10 relative z-10 text-center">
            <div class="relative w-24 h-24 mx-auto mb-6">
                <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 36 36">
                    <path class="text-slate-100" stroke="currentColor" stroke-width="2.5" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                    <path class="text-emerald-500" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round"
                          :stroke-dasharray="generateProgress + ', 100'"
                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-2xl font-black text-emerald-600" x-text="generateProgress + '%'"></span>
                </div>
            </div>
            <h3 class="text-xl font-black text-slate-800 mb-2">Generate Akun Siswa</h3>
            <p class="text-slate-500 font-medium mb-4">Membuat akun untuk siswa yang belum memiliki akun...</p>
            
            <!-- Current Student Detail -->
            <div class="bg-slate-50 rounded-2xl p-4 mb-4" x-show="currentStudent">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Sedang Memproses</p>
                <p class="text-sm font-bold text-slate-700 truncate" x-text="currentStudent"></p>
                <p class="text-xs font-mono text-slate-500" x-text="'NISN: ' + currentNisn"></p>
            </div>
            
            <!-- Progress Counter -->
            <div class="flex items-center justify-center gap-2 text-sm text-slate-500 font-medium">
                <span x-text="generateCurrent + ' / ' + generateTotal + ' siswa'"></span>
            </div>
            
            <div class="mt-4 flex items-center justify-center gap-2 text-sm text-slate-400">
                <i class="material-icons text-lg animate-spin">autorenew</i>
                <span>Mohon tunggu sebentar</span>
            </div>
        </div>
    </div>

    <!-- Template Info Modal -->
    <div x-show="openTemplateModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="openTemplateModal = false"></div>
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg p-10 relative z-10">
            <div class="w-20 h-20 mx-auto mb-6 rounded-3xl bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center">
                <i class="material-icons text-4xl text-amber-500">warning</i>
            </div>
            <h3 class="text-2xl font-black text-slate-800 tracking-tight text-center mb-4">Informasi Penting!</h3>
            
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-6">
                <p class="text-slate-700 font-medium text-sm leading-relaxed">
                    Gunakan format Excel yang dihasilkan dari <strong class="text-amber-600">Data Peserta Didik</strong> pada aplikasi <strong class="text-amber-600">DAPODIK</strong>, dan langsung import file tersebut ke sistem ini.
                </p>
            </div>
            
            <div class="bg-red-50 border border-red-200 rounded-2xl p-6 mb-6">
                <div class="flex items-start gap-3">
                    <i class="material-icons text-red-500 mt-0.5">dangerous</i>
                    <p class="text-red-700 font-bold text-sm leading-relaxed">
                        DILARANG merubah isi data dan format file yang diunduh dari Dapodik karena dapat mengakibatkan <strong>ERROR</strong> saat proses import!
                    </p>
                </div>
            </div>
            
            <div class="flex justify-center">
                <button @click="openTemplateModal = false" class="px-10 py-4 rounded-2xl bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white text-sm font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Saya Mengerti
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    function siswaPage() {
        return {
            openModal: false,
            openImportModal: false,
            openTemplateModal: false,
            editMode: false,
            formTab: 'wajib',
            fileName: '',
            importing: false,
            importProgress: 0,
            generating: false,
            generateProgress: 0,
            generateCurrent: 0,
            generateTotal: 0,
            currentStudent: '',
            currentNisn: '',
            formData: {
                id: '',
                nama_lengkap: '',
                nis: '',
                nisn: '',
                kelas_id: '',
                jenis_kelamin: 'L',
                username: '',
                password: '',
            },
            init() {},
            openCreateModal() {
                this.editMode = false;
                this.openModal = true;
                this.formTab = 'wajib';
                this.formData = {
                    id: '', nama_lengkap: '', nis: '', nisn: '', kelas_id: '', jenis_kelamin: 'L',
                    username: '', password: '', tempat_lahir: '', tanggal_lahir: '',
                    nama_ayah: '', nama_ibu: '', no_hp: '', no_hp_ortu: '', alamat: '', sekolah_asal: ''
                };
            },
            saveData() {
                const url = this.formData.id ? `{{ url('siswa/update') }}/${this.formData.id}` : `{{ route('siswa.store') }}`;
                Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: $('#siswaForm').serialize(),
                    success: (res) => {
                        this.openModal = false;
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.success, timer: 1500, showConfirmButton: false }).then(() => location.reload());
                    },
                    error: (err) => {
                        let msg = err.responseJSON?.message || 'Terjadi kesalahan.';
                        if (err.responseJSON?.errors) msg = Object.values(err.responseJSON.errors).join('<br>');
                        Swal.fire('Oops...', msg, 'error');
                    }
                });
            },
            deleteData(id) {
                Swal.fire({
                    title: 'Hapus Data?',
                    text: "Data user terkait akan ikut terhapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d90d8b',
                    confirmButtonText: 'Ya, Hapus'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('siswa/destroy') }}/${id}`,
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
                
                this.importing = true;
                this.importProgress = 0;

                const progressInterval = setInterval(() => {
                    if (this.importProgress < 90) {
                        this.importProgress += Math.random() * 15;
                    }
                }, 200);

                $.ajax({
                    url: `{{ route('siswa.import') }}`,
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: (res) => {
                        clearInterval(progressInterval);
                        this.importProgress = 100;
                        
                        setTimeout(() => {
                            this.importing = false;
                            Swal.fire('Berhasil', res.success, 'success').then(() => location.reload());
                        }, 500);
                    },
                    error: (err) => {
                        clearInterval(progressInterval);
                        this.importing = false;
                        let msg = err.responseJSON?.message || 'Terjadi kesalahan saat import data.';
                        if (err.responseJSON?.errors) {
                            msg = err.responseJSON.errors.join('<br>');
                        }
                        Swal.fire('Gagal Import', msg, 'error');
                    }
                });
            },
            generateAccounts() {
                Swal.fire({
                    title: 'Generate Akun Siswa',
                    text: 'Akun akan dibuat untuk siswa yang belum memiliki akun. Email: NISN@siswa.literasia.org, Password: NISN',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    confirmButtonText: 'Ya, Generate',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.generating = true;
                        this.generateProgress = 0;
                        this.generateCurrent = 0;
                        this.generateTotal = 0;
                        this.currentStudent = '';
                        this.currentNisn = '';

                        // Use polling for real-time progress
                        $.ajax({
                            url: '{{ route("siswa.generate-accounts") }}',
                            method: 'POST',
                            data: { _token: '{{ csrf_token() }}' },
                            success: (res) => {
                                if (res.tracking_id) {
                                    this.pollProgress(res.tracking_id);
                                } else {
                                    this.generating = false;
                                    Swal.fire('Selesai', res.message, 'success').then(() => location.reload());
                                }
                            },
                            error: (err) => {
                                this.generating = false;
                                let msg = err.responseJSON?.message || 'Terjadi kesalahan saat memulai generate akun.';
                                Swal.fire('Gagal', msg, 'error');
                            }
                        });
                    }
                });
            },

            pollProgress(trackingId) {
                const interval = setInterval(() => {
                    $.ajax({
                        url: `/siswa/generate-accounts/progress/${trackingId}`,
                        method: 'GET',
                        success: (data) => {
                            if (data.type === 'progress') {
                                this.generateProgress = data.progress;
                                this.generateCurrent = data.current;
                                this.generateTotal = data.total;
                                this.currentStudent = data.student_name;
                                this.currentNisn = data.nisn;
                            } else if (data.type === 'complete') {
                                clearInterval(interval);
                                this.generateProgress = 100;
                                
                                setTimeout(() => {
                                    this.generating = false;
                                    
                                    let message = data.message;
                                    if (data.errors && data.errors.length > 0) {
                                        message += '\n\n' + data.errors.length + ' siswa gagal diproses.';
                                    }

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Selesai!',
                                        text: message,
                                        footer: data.errors.length > 0 ? '<div class="text-xs text-red-500 max-h-40 overflow-y-auto text-left">' + data.errors.join('<br>') + '</div>' : '',
                                        showConfirmButton: true
                                    }).then(() => location.reload());
                                }, 500);
                            } else if (data.type === 'error') {
                                clearInterval(interval);
                                this.generating = false;
                                Swal.fire('Gagal', data.message, 'error');
                            }
                        },
                        error: (err) => {
                            clearInterval(interval);
                            this.generating = false;
                            console.error('Polling failed', err);
                        }
                    });
                }, 2000);
            },
            resetPassword(id, nama, nisn) {
                Swal.fire({
                    title: 'Reset Password',
                    html: `Reset password <strong>${nama}</strong> ke NISN (${nisn})?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    confirmButtonText: 'Ya, Reset',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                        $.ajax({
                            url: `/siswa/${id}/reset-password`,
                            method: 'POST',
                            data: { _token: '{{ csrf_token() }}' },
                            success: (res) => {
                                Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, timer: 2000, showConfirmButton: false });
                            },
                            error: (err) => {
                                let msg = err.responseJSON?.error || 'Terjadi kesalahan.';
                                Swal.fire('Gagal', msg, 'error');
                            }
                        });
                    }
                });
            }
        }
    }
</script>
@endsection
