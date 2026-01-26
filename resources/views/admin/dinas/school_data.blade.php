@extends('layouts.app')

@section('title', 'Data Sekolah - Literasia')

@section('content')
<div x-data="dinasSchoolPage()" class="space-y-8">
    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-center gap-5">
            <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-100 flex items-center justify-center text-white">
                <i class="material-icons text-3xl">domain</i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Analisis Data</p>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Data Sekolah Terdaftar</h1>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('dinas.schools.download-template') }}" class="flex items-center gap-2 px-6 py-2.5 bg-white border border-slate-100 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all">
                <i class="material-icons text-[20px]">file_download</i> Template
            </a>
            <button @click="openImportModal = true" class="flex items-center gap-2 px-6 py-2.5 bg-white border border-slate-100 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all">
                <i class="material-icons text-[20px]">file_upload</i> Import
            </button>
            @if($schoolsWithoutAccount > 0)
            <button @click="generateAccounts()" class="flex items-center gap-2 px-6 py-2.5 bg-emerald-500 text-white rounded-xl text-sm font-bold shadow-md hover:bg-emerald-600 transition-all">
                <i class="material-icons text-[20px]">group_add</i> Generate Akun ({{ $schoolsWithoutAccount }})
            </button>
            @endif
            <button @click="openCreateModal = true" class="flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-xl text-sm font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                <i class="material-icons text-[20px]">add_circle</i> Tambah Sekolah
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-sm">
        <form action="{{ route('dinas.schools') }}" method="GET" class="flex flex-col lg:flex-row items-end gap-6">
            <div class="w-full lg:w-1/3 space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Cari Sekolah</label>
                <div class="relative">
                    <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-300">search</i>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Nama Sekolah atau NPSN..." 
                        class="w-full bg-slate-50 border border-slate-100 rounded-2xl pl-12 pr-6 py-3.5 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100 transition-all outline-none"
                    >
                </div>
            </div>

            <div class="w-full lg:w-1/4 space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Jenjang</label>
                <select name="jenjang" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-3.5 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100 transition-all outline-none appearance-none">
                    <option value="">Semua Jenjang</option>
                    <option value="sd" {{ request('jenjang') == 'sd' ? 'selected' : '' }}>SD</option>
                    <option value="smp" {{ request('jenjang') == 'smp' ? 'selected' : '' }}>SMP</option>
                    <option value="sma_smk" {{ request('jenjang') == 'sma_smk' ? 'selected' : '' }}>SMA/SMK</option>
                </select>
            </div>

            <div class="w-full lg:w-1/4 space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Status</label>
                <select name="status" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-3.5 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100 transition-all outline-none appearance-none">
                    <option value="">Semua Status</option>
                    <option value="Negeri" {{ request('status') == 'Negeri' ? 'selected' : '' }}>Negeri</option>
                    <option value="Swasta" {{ request('status') == 'Swasta' ? 'selected' : '' }}>Swasta</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="p-3.5 bg-indigo-500 text-white rounded-2xl shadow-lg shadow-indigo-100 hover:bg-indigo-600 transition-all">
                    <i class="material-icons">filter_list</i>
                </button>
                <a href="{{ route('dinas.schools') }}" class="p-3.5 bg-slate-100 text-slate-400 rounded-2xl hover:bg-slate-200 hover:text-slate-600 transition-all" title="Reset Filter">
                    <i class="material-icons">refresh</i>
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                        <th class="py-6 px-8 border-b border-slate-50">Nama Sekolah</th>
                        <th class="py-6 px-8 border-b border-slate-50">NPSN</th>
                        <th class="py-6 px-8 border-b border-slate-50">Status</th>
                        <th class="py-6 px-8 border-b border-slate-50">Wilayah</th>
                        <th class="py-6 px-8 border-b border-slate-50">Koneksi</th>
                        <th class="py-6 px-8 border-b border-slate-50">Akun</th>
                        <th class="py-6 px-8 border-b border-slate-50 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($schools as $school)
                    <tr class="group hover:bg-slate-50/50 transition-all">
                        <td class="py-6 px-8">
                            <p class="font-bold text-slate-700">{{ $school->nama_sekolah }}</p>
                            <p class="text-xs text-slate-400">{{ $school->jenjang }}</p>
                        </td>
                        <td class="py-6 px-8 font-mono text-xs font-bold text-slate-500">{{ $school->npsn }}</td>
                        <td class="py-6 px-8 text-xs font-bold text-slate-600">
                            @if($school->status_sekolah == 'Negeri')
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full border border-blue-100 uppercase text-[10px]">Negeri</span>
                            @else
                                <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-full border border-purple-100 uppercase text-[10px]">Swasta</span>
                            @endif
                        </td>
                        <td class="py-6 px-8 text-xs font-bold text-slate-600">{{ $school->kabupaten_kota }}, {{ $school->provinsi }}</td>
                        <td class="py-6 px-8">
                            @if($school->is_active)
                                <span class="flex items-center gap-1.5 text-emerald-600 text-[10px] font-black uppercase">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Terhubung
                                </span>
                            @else
                                <span class="flex items-center gap-1.5 text-slate-400 text-[10px] font-black uppercase">
                                    <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span> Terputus
                                </span>
                            @endif
                        </td>
                        <td class="py-6 px-8">
                            @php
                                $hasAdmin = $school->users()->where('role', 'admin')->exists();
                            @endphp
                            @if($hasAdmin)
                                <span class="flex items-center gap-1.5 text-emerald-600 text-[10px] font-black uppercase">
                                    <i class="material-icons text-sm">check_circle</i> Ada
                                </span>
                            @else
                                <span class="flex items-center gap-1.5 text-amber-500 text-[10px] font-black uppercase">
                                    <i class="material-icons text-sm">warning</i> Belum
                                </span>
                            @endif
                        </td>
                        <td class="py-6 px-8 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('dinas.schools.show', $school->id) }}" class="p-2 text-indigo-500 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors" title="Detail Sekolah">
                                    <i class="material-icons text-lg">visibility</i>
                                </a>
                                @if($hasAdmin)
                                <button @click="resetPassword('{{ $school->id }}', '{{ $school->nama_sekolah }}')" class="p-2 text-amber-500 bg-amber-50 hover:bg-amber-100 rounded-xl transition-colors" title="Reset Password">
                                    <i class="material-icons text-lg">lock_reset</i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($schools->hasPages())
        <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/30">
            {{ $schools->links() }}
        </div>
        @endif
    </div>

    <!-- Tambah Sekolah Modal -->
    <div x-show="openCreateModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="openCreateModal = false"></div>
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-3xl relative z-10 overflow-hidden">
            <div class="px-10 py-8 flex items-center justify-between border-b border-slate-50">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">Tambah Sekolah</h2>
                    <p class="text-slate-400 font-medium text-sm mt-1">Lengkapi data sekolah yang akan ditambahkan.</p>
                </div>
                <button @click="openCreateModal = false" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all">
                    <i class="material-icons">close</i>
                </button>
            </div>

            <form action="{{ route('dinas.schools.store') }}" method="POST" class="px-10 py-8 space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Nama Sekolah</label>
                        <input name="nama_sekolah" type="text" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all" placeholder="SMA Negeri 1 Jakarta" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">NPSN</label>
                        <input name="npsn" type="text" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all" placeholder="12345678" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Jenjang</label>
                        <select name="jenjang" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all">
                            <option value="sd">SD</option>
                            <option value="smp">SMP</option>
                            <option value="sma_smk">SMA/SMK</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Status Sekolah</label>
                        <select name="status_sekolah" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all">
                            <option value="Negeri">Negeri</option>
                            <option value="Swasta">Swasta</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Provinsi</label>
                        <input name="provinsi" type="text" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all" placeholder="DKI Jakarta" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Kabupaten/Kota</label>
                        <input name="kabupaten_kota" type="text" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all" placeholder="Jakarta Pusat" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Kecamatan</label>
                        <input name="kecamatan" type="text" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all" placeholder="Gambir" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Desa/Kelurahan</label>
                        <input name="desa_kelurahan" type="text" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all" placeholder="Gambir" required>
                    </div>
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                        <textarea name="alamat" rows="2" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all" placeholder="Jl. Merdeka No. 10" required></textarea>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Lintang (Lat)</label>
                        <input name="latitude" type="text" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all" placeholder="-5.2528000">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Bujur (Lng)</label>
                        <input name="longitude" type="text" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all" placeholder="105.0443000">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="openCreateModal = false" class="px-8 py-3.5 rounded-2xl text-sm font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-all">Batal</button>
                    <button type="submit" class="px-10 py-3.5 rounded-2xl bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white text-sm font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Import Sekolah Modal -->
    <div x-show="openImportModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="openImportModal = false"></div>
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg p-10 relative z-10">
            <h3 class="text-2xl font-black text-slate-800 tracking-tight mb-2">Import Data Sekolah</h3>
            <p class="text-slate-400 font-medium text-sm mb-6">Gunakan template Excel agar format data konsisten.</p>
            <a href="{{ route('dinas.schools.download-template') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">
                <i class="material-icons text-[18px]">file_download</i> Unduh Template
            </a>

            <form id="importForm" method="POST" enctype="multipart/form-data" class="mt-6">
                @csrf
                <div class="relative group">
                    <input type="file" name="file" class="hidden" id="excelFile" @change="handleFileSelect($event)">
                    <label for="excelFile" class="flex flex-col items-center justify-center w-full h-48 bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl cursor-pointer group-hover:bg-pink-50 group-hover:border-[#d90d8b]/30 transition-all">
                        <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-slate-400 shadow-sm mb-4 group-hover:text-[#d90d8b]">
                            <i class="material-icons text-3xl">cloud_upload</i>
                        </div>
                        <p class="text-sm font-bold text-slate-500" x-text="fileName || 'Klik untuk pilih file'"></p>
                        <p class="text-[11px] text-slate-400 font-semibold mt-2">Format .xlsx atau .xls</p>
                    </label>
                </div>

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
                    <div class="flex justify-center mt-4">
                        <div class="relative w-16 h-16">
                            <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 36 36">
                                <path class="text-slate-100" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                <path class="text-[#d90d8b]" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"
                                      :stroke-dasharray="importProgress + ', 100'"
                                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="material-icons text-[#d90d8b] animate-pulse">sync</i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-8" x-show="!importing">
                    <button type="button" @click="openImportModal = false" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl text-sm font-bold hover:bg-slate-200 transition-all">Batal</button>
                    <button type="button" @click="importData()" class="flex-1 py-4 bg-[#d90d8b] text-white rounded-2xl text-sm font-bold shadow-lg shadow-pink-100 hover:bg-[#ba80e8] transition-all">Import</button>
                </div>
            </form>
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
            <h3 class="text-xl font-black text-slate-800 mb-2">Generate Akun Sekolah</h3>
            <p class="text-slate-500 font-medium mb-4">Membuat akun admin untuk sekolah yang belum memiliki akun...</p>
            
            <!-- Current School Detail -->
            <div class="bg-slate-50 rounded-2xl p-4 mb-4" x-show="currentSchool">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Sedang Memproses</p>
                <p class="text-sm font-bold text-slate-700 truncate" x-text="currentSchool"></p>
                <p class="text-xs font-mono text-slate-500" x-text="'NPSN: ' + currentNpsn"></p>
            </div>
            
            <!-- Progress Counter -->
            <div class="flex items-center justify-center gap-2 text-sm text-slate-500 font-medium">
                <span x-text="generateCurrent + ' / ' + generateTotal + ' sekolah'"></span>
            </div>
            
            <div class="mt-4 flex items-center justify-center gap-2 text-sm text-slate-400">
                <i class="material-icons text-lg animate-spin">autorenew</i>
                <span>Mohon tunggu sebentar</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function dinasSchoolPage() {
        return {
            openCreateModal: false,
            openImportModal: false,
            fileName: '',
            importing: false,
            importProgress: 0,
            generating: false,
            generateProgress: 0,
            generateCurrent: 0,
            generateTotal: 0,
            currentSchool: '',
            currentNpsn: '',

            handleFileSelect(event) {
                if (event.target.files.length > 0) {
                    this.fileName = event.target.files[0].name;
                }
            },

            importData() {
                const fileInput = document.getElementById('excelFile');
                if (!fileInput.files.length) {
                    Swal.fire('Peringatan', 'Pilih file Excel terlebih dahulu', 'warning');
                    return;
                }

                this.importing = true;
                this.importProgress = 0;

                // Simulate progress animation
                const progressInterval = setInterval(() => {
                    if (this.importProgress < 90) {
                        this.importProgress += Math.random() * 15;
                    }
                }, 200);

                let formData = new FormData(document.getElementById('importForm'));

                $.ajax({
                    url: '{{ route("dinas.schools.import") }}',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: (res) => {
                        clearInterval(progressInterval);
                        this.importProgress = 100;
                        
                        setTimeout(() => {
                            this.importing = false;
                            this.openImportModal = false;
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.success,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        }, 500);
                    },
                    error: (err) => {
                        clearInterval(progressInterval);
                        this.importing = false;
                        let msg = err.responseJSON?.error || 'Terjadi kesalahan saat import.';
                        Swal.fire('Gagal Import', msg, 'error');
                    }
                });
            },

            generateAccounts() {
                Swal.fire({
                    title: 'Generate Akun Sekolah',
                    text: 'Akun admin akan dibuat untuk sekolah yang belum memiliki akun. Email: NPSN@admin.literasia.org, Password: NPSN',
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
                        this.currentSchool = '';
                        this.currentNpsn = '';

                        // Use polling for real-time progress
                        $.ajax({
                            url: '{{ route("dinas.schools.generate-accounts") }}',
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
                        url: `/dinas/schools/generate-accounts/progress/${trackingId}`,
                        method: 'GET',
                        success: (data) => {
                            if (data.type === 'progress') {
                                this.generateProgress = data.progress;
                                this.generateCurrent = data.current;
                                this.generateTotal = data.total;
                                this.currentSchool = data.school_name;
                                this.currentNpsn = data.npsn;
                            } else if (data.type === 'complete') {
                                clearInterval(interval);
                                this.generateProgress = 100;
                                
                                setTimeout(() => {
                                    this.generating = false;
                                    
                                    let message = data.message;
                                    if (data.errors && data.errors.length > 0) {
                                        message += '\n\n' + data.errors.length + ' sekolah gagal diproses.';
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
                            // Silently fail or show error if polling fails consistently
                            console.error('Polling failed', err);
                        }
                    });
                }, 2000); // Poll every 2 seconds
            },

            resetPassword(schoolId, schoolName) {
                Swal.fire({
                    title: 'Reset Password',
                    html: `Reset password akun admin <strong>${schoolName}</strong> ke NPSN?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    confirmButtonText: 'Ya, Reset',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        $.ajax({
                            url: `/dinas/schools/${schoolId}/reset-password`,
                            method: 'POST',
                            data: { _token: '{{ csrf_token() }}' },
                            success: (res) => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: res.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
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
