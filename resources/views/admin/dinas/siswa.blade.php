@extends('layouts.app')

@section('title', 'Manajemen Siswa Dinas - Literasia')

@section('content')
<div x-data="dinasSiswaPage()" x-init="init()" class="space-y-8">
    
    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#ba80e8] to-[#d90d8b] shadow-lg shadow-pink-100 flex items-center justify-center text-white">
                    <i class="material-icons text-2xl">groups</i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-800 tracking-tight">Data Siswa Global</h1>
                    <p class="text-slate-500 font-medium text-sm">Kelola dan pantau data siswa di seluruh sekolah naungan.</p>
                </div>
            </div>
        </div>
        
        @if($selectedSchoolId)
        <div class="flex gap-3">
            <button @click="openImportModal = true" class="flex items-center gap-2 px-6 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
                <i class="material-icons text-[20px]">file_upload</i> Import Siswa
            </button>
        </div>
        @endif
    </div>

    <!-- Filter & School Selection Card -->
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6" x-data="{ 
        showSchools: false, 
        searchSchool: '',
        selectedJenjang: '{{ $selectedJenjang ?? "" }}',
        schools: @js($schools),
        get filteredSchools() {
            return this.schools.filter(s => 
                s.nama_sekolah.toLowerCase().includes(this.searchSchool.toLowerCase()) || 
                (s.npsn && s.npsn.includes(this.searchSchool))
            );
        }
    }">
        <div class="flex flex-col lg:flex-row items-end gap-6">
            <!-- Jenjang Filter -->
            <div class="w-full lg:w-1/4">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Pilih Jenjang</label>
                <select 
                    x-model="selectedJenjang" 
                    @change="window.location.href = `{{ route('dinas.siswa') }}?jenjang=${selectedJenjang}`"
                    class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-pink-100 transition-all"
                >
                    <option value="">Semua Jenjang</option>
                    <option value="sd" {{ ($selectedJenjang ?? '') == 'sd' ? 'selected' : '' }}>SD</option>
                    <option value="smp" {{ ($selectedJenjang ?? '') == 'smp' ? 'selected' : '' }}>SMP</option>
                    <option value="sma_smk" {{ ($selectedJenjang ?? '') == 'sma_smk' ? 'selected' : '' }}>SMA/SMK</option>
                </select>
            </div>

            <!-- Searchable School Select -->
            <div class="w-full lg:w-2/4 relative">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Pilih Sekolah</label>
                <div class="relative">
                    <button 
                        @click="showSchools = !showSchools"
                        type="button"
                        class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 text-left flex justify-between items-center focus:ring-2 focus:ring-pink-100 transition-all"
                    >
                        @php
                            $currentSchool = $schools->firstWhere('id', $selectedSchoolId);
                        @endphp
                        <span>{{ $currentSchool ? $currentSchool->nama_sekolah : '-- Pilih Sekolah --' }}</span>
                        <i class="material-icons text-slate-400" :class="showSchools ? 'rotate-180' : ''">expand_more</i>
                    </button>

                    <!-- Dropdown Panel -->
                    <div 
                        x-show="showSchools" 
                        @click.away="showSchools = false"
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="absolute left-0 right-0 mt-2 bg-white border border-slate-100 rounded-[2rem] shadow-xl z-50 overflow-hidden"
                    >
                        <div class="p-4 border-b border-slate-50">
                            <div class="relative">
                                <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-300">search</i>
                                <input 
                                    type="text" 
                                    x-model="searchSchool"
                                    placeholder="Cari sekolah..." 
                                    class="w-full bg-slate-50 border border-slate-100 rounded-xl pl-12 pr-4 py-3 text-sm font-semibold outline-none focus:ring-2 focus:ring-pink-100"
                                    @click.stop
                                >
                            </div>
                        </div>
                        <div class="max-h-64 overflow-y-auto custom-scrollbar">
                            <template x-for="school in filteredSchools" :key="school.id">
                                <button 
                                    @click="window.location.href = `{{ route('dinas.siswa') }}?school_id=${school.id}&jenjang=${selectedJenjang}`"
                                    class="w-full text-left px-6 py-4 text-sm font-bold transition-all hover:bg-slate-50 flex items-center justify-between"
                                    :class="school.id == '{{ $selectedSchoolId }}' ? 'bg-pink-50 text-[#d90d8b]' : 'text-slate-600'"
                                >
                                    <div>
                                        <div x-text="school.nama_sekolah"></div>
                                        <div class="text-[10px] text-slate-400 font-medium" x-text="'NPSN: ' + school.npsn"></div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] uppercase px-2 py-0.5 rounded-full" 
                                              :class="school.siswa_count > 0 ? 'bg-pink-100 text-[#d90d8b]' : 'bg-slate-100 text-slate-400'"
                                              x-text="school.siswa_count + ' Siswa'"></span>
                                    </div>
                                </button>
                            </template>
                            <div x-show="filteredSchools.length === 0" class="p-8 text-center text-slate-400 text-sm italic">
                                Sekolah tidak ditemukan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex-grow grid grid-cols-2 gap-4 w-full lg:w-1/4">
                <div class="p-4 rounded-3xl bg-slate-50 border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-2">Sekolah</p>
                    <p class="text-xl font-black text-slate-800 leading-none">{{ count($schools) }}</p>
                </div>
                <div class="p-4 rounded-3xl bg-pink-50 border border-pink-100">
                    <p class="text-[10px] font-black text-pink-400 uppercase tracking-widest leading-none mb-2">Siswa</p>
                    <p class="text-xl font-black text-slate-800 leading-none">{{ $schools->sum('siswa_count') }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($selectedSchoolId)
    <!-- Main Table Card -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-2">
        <!-- Search Bar Inside Table -->
        <div class="p-4 border-b border-slate-50 mb-4">
            <form action="{{ route('dinas.siswa') }}" method="GET" class="flex items-center gap-4">
                <input type="hidden" name="school_id" value="{{ $selectedSchoolId }}">
                <div class="relative flex-grow">
                    <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-300">search</i>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari nama, NIS atau NISN..." 
                        class="w-full bg-slate-50 border border-slate-100 rounded-2xl pl-12 pr-6 py-3 text-sm font-semibold text-slate-700 placeholder-slate-300 outline-none focus:ring-2 focus:ring-pink-100"
                    >
                </div>
                <button type="submit" class="px-8 py-3 bg-slate-800 text-white rounded-2xl text-sm font-bold hover:bg-slate-900 transition-all">
                    Cari
                </button>
            </form>
        </div>

        <div class="overflow-x-auto p-4">
            <table class="w-full text-left border-separate border-spacing-y-3">
                <thead>
                    <tr class="text-slate-400 text-[11px] uppercase font-black tracking-widest">
                        <th class="px-6 py-3 text-center">No</th>
                        <th class="px-6 py-3">Nama Lengkap</th>
                        <th class="px-6 py-3 text-center">NIS / NISN</th>
                        <th class="px-6 py-3">Kelas</th>
                        <th class="px-6 py-3 text-center">L/P</th>
                        <th class="px-6 py-3 text-center">Akun</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($siswas as $index => $item)
                    <tr class="group hover:scale-[1.005] transition-transform duration-200">
                        <td class="px-6 py-4 bg-slate-50 border-y border-l border-transparent group-hover:border-slate-100 group-hover:bg-white first:rounded-l-2xl text-center font-bold text-slate-400">
                            {{ ($siswas->currentPage() - 1) * $siswas->perPage() + $index + 1 }}
                        </td>
                        <td class="px-6 py-4 bg-slate-50 border-y border-transparent group-hover:border-slate-100 group-hover:bg-white">
                            <div class="flex items-center gap-3">
                                <img class="w-9 h-9 rounded-xl border-2 border-white shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode($item->nama_lengkap) }}&background=fdf2f8&color=d90d8b" alt="">
                                <div>
                                    <p class="font-bold text-slate-800 leading-none">{{ $item->nama_lengkap }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase">{{ $item->user->username ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 bg-slate-50 border-y border-transparent group-hover:border-slate-100 group-hover:bg-white text-center">
                            <p class="font-bold text-slate-700 leading-none">{{ $item->nis }}</p>
                            <p class="text-[10px] font-medium text-slate-400 mt-1">{{ $item->nisn }}</p>
                        </td>
                        <td class="px-6 py-4 bg-slate-50 border-y border-transparent group-hover:border-slate-100 group-hover:bg-white">
                            <span class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-[11px] font-bold text-slate-600">
                                {{ $item->kelas->nama ?? 'Tanpa Kelas' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 bg-slate-50 border-y border-transparent group-hover:border-slate-100 group-hover:bg-white text-center">
                            <span class="font-bold text-slate-600">{{ $item->jenis_kelamin }}</span>
                        </td>
                        <td class="px-6 py-4 bg-slate-50 border-y border-r border-transparent group-hover:border-slate-100 group-hover:bg-white last:rounded-r-2xl text-center">
                            @if($item->user_id)
                                <span class="inline-flex items-center gap-1 text-emerald-600 text-[10px] font-black uppercase">
                                    <i class="material-icons text-sm">check_circle</i> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-slate-300 text-[10px] font-black uppercase">
                                    <i class="material-icons text-sm">radio_button_unchecked</i> Belum
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mb-4">
                                    <i class="material-icons text-4xl">inventory_2</i>
                                </div>
                                <p class="text-sm font-bold text-slate-400">Pilih sekolah atau belum ada data siswa</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($siswas->hasPages())
        <div class="px-8 py-6 border-t border-slate-50">
            {{ $siswas->links() }}
        </div>
        @endif
    </div>
    @else
    <!-- Empty State / Welcome -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-20 text-center">
        <div class="w-24 h-24 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-200 mx-auto mb-6">
            <i class="material-icons text-6xl">school</i>
        </div>
        <h2 class="text-2xl font-black text-slate-800">Mulai Mengelola Siswa</h2>
        <p class="text-slate-400 font-medium max-w-md mx-auto mt-2">Pilih data sekolah terlebih dahulu untuk melihat daftar siswa dan melakukan import data.</p>
    </div>
    @endif

    <!-- Import Modal -->
    @if($selectedSchoolId)
    <div x-show="openImportModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="openImportModal = false"></div>
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg p-10 relative z-10">
            <h3 class="text-2xl font-black text-slate-800 tracking-tight mb-2">Import Siswa</h3>
            <p class="text-slate-400 font-medium text-sm mb-6">Import data ke sekolah: <br><span class="text-slate-800 font-bold" id="importSchoolName"></span></p>
            
            <a href="{{ route('siswa.download-template') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">
                <i class="material-icons text-[18px]">file_download</i> Unduh Template Siswa
            </a>

            <form id="importForm" method="POST" enctype="multipart/form-data" class="mt-6">
                @csrf
                <div class="relative group">
                    <input type="file" name="file" class="hidden" id="siswaFile" @change="handleFileSelect($event)">
                    <label 
                        for="siswaFile" 
                        class="flex flex-col items-center justify-center w-full h-48 bg-slate-50 border-2 border-dashed rounded-3xl cursor-pointer transition-all"
                        :class="isDragging ? 'bg-pink-100 border-[#d90d8b] scale-[1.02]' : 'border-slate-200 group-hover:bg-pink-50 group-hover:border-[#d90d8b]/30'"
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="handleFileDrop($event)"
                    >
                        <div 
                            class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-4 transition-all"
                            :class="isDragging ? 'text-[#d90d8b] scale-110' : 'text-slate-400 group-hover:text-[#d90d8b]'"
                        >
                            <i class="material-icons text-3xl" x-text="isDragging ? 'download' : 'cloud_upload'"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-500" x-text="isDragging ? 'Lepas file untuk import' : (fileName || 'Klik atau pindahkan file ke sini')"></p>
                        <p class="text-[11px] text-slate-400 font-semibold mt-2" x-show="!isDragging">Format .xlsx atau .xls</p>
                    </label>
                </div>

                <div x-show="importing" class="mt-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-black text-slate-600 uppercase tracking-widest">Memproses...</span>
                        <span class="text-xs font-black text-[#d90d8b]" x-text="importProgress + '%'"></span>
                    </div>
                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] transition-all duration-300" :style="'width: ' + importProgress + '%'"></div>
                    </div>
                </div>

                <div class="flex gap-3 mt-8" x-show="!importing">
                    <button type="button" @click="openImportModal = false; showErrorTable = false" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl text-sm font-bold hover:bg-slate-200 transition-all">Batal</button>
                    <button type="button" @click="importData()" class="flex-1 py-4 bg-[#d90d8b] text-white rounded-2xl text-sm font-bold shadow-lg shadow-pink-100 hover:bg-[#ba80e8] transition-all">Import / Update</button>
                </div>

                <!-- Error Table Section -->
                <div x-show="showErrorTable" x-cloak class="mt-8 border-t border-slate-100 pt-8 animate-in fade-in slide-in-from-top-4 duration-500">
                    <div class="flex items-center gap-2 mb-4 text-red-500">
                        <i class="material-icons">error_outline</i>
                        <h4 class="font-black text-sm uppercase tracking-wider">Detail Kesalahan Data</h4>
                    </div>
                    <div class="bg-red-50 rounded-3xl border border-red-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-[11px]">
                                <thead>
                                    <tr class="bg-red-100/50 text-red-700 font-black uppercase tracking-widest">
                                        <th class="px-4 py-3">Baris</th>
                                        <th class="px-4 py-3">Nama Siswa</th>
                                        <th class="px-4 py-3">Keterangan Error</th>
                                        <th class="px-4 py-3">Rekomendasi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-red-100/50">
                                    <template x-for="err in importErrors" :key="err.row + '-' + err.attribute">
                                        <tr class="text-red-600 font-medium">
                                            <td class="px-4 py-3 font-bold" x-text="err.row"></td>
                                            <td class="px-4 py-3 font-bold text-slate-700" x-text="err.student"></td>
                                            <td class="px-4 py-3 italic" x-text="err.error"></td>
                                            <td class="px-4 py-3">
                                                <div class="bg-white/60 rounded-lg px-2 py-1 border border-red-200 inline-block font-bold" x-text="err.recommendation"></div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
    function dinasSiswaPage() {
        return {
            selectedSchoolId: '{{ $selectedSchoolId ?? "" }}',
            openImportModal: false,
            isDragging: false,
            importing: false,
            importProgress: 0,
            importErrors: [],
            showErrorTable: false,

            handleFileSelect(e) {
                if (e.target.files.length > 0) {
                    this.fileName = e.target.files[0].name;
                }
            },

            handleFileDrop(e) {
                this.isDragging = false;
                if (e.dataTransfer.files.length > 0) {
                    const fileInput = document.getElementById('siswaFile');
                    fileInput.files = e.dataTransfer.files;
                    this.fileName = e.dataTransfer.files[0].name;
                }
            },

            importData() {
                const fileInput = document.getElementById('siswaFile');
                if (!fileInput.files.length) {
                    Swal.fire('Oops!', 'Pilih file Excel terlebih dahulu bro.', 'warning');
                    return;
                }

                this.importing = true;
                this.importProgress = 0;

                const progressInterval = setInterval(() => {
                    if (this.importProgress < 90) {
                        this.importProgress += Math.random() * 10;
                    }
                }, 150);

                let formData = new FormData(document.getElementById('importForm'));

                $.ajax({
                    url: `{{ url('dinas/siswa/import') }}/${this.selectedSchoolId}`,
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: (res) => {
                        clearInterval(progressInterval);
                        this.importProgress = 100;
                        setTimeout(() => {
                            this.importing = false;
                            this.showErrorTable = false;
                            Swal.fire('Berhasil!', res.success, 'success').then(() => location.reload());
                        }, 500);
                    },
                    error: (err) => {
                        clearInterval(progressInterval);
                        this.importing = false;
                        
                        if (err.responseJSON?.import_errors) {
                            this.importErrors = err.responseJSON.import_errors;
                            this.showErrorTable = true;
                            Swal.fire({
                                icon: 'error',
                                title: 'Data Tidak Sesuai',
                                text: 'Beberapa baris data siswa memiliki format yang salah. Lihat tabel di bawah untuk detailnya.',
                                confirmButtonColor: '#d90d8b'
                            });
                        } else {
                            let msg = err.responseJSON?.message || 'Gagal mengimport data.';
                            if (err.responseJSON?.errors) {
                                msg = Array.isArray(err.responseJSON.errors) ? err.responseJSON.errors.join('<br>') : err.responseJSON.errors;
                            }
                            Swal.fire('Error Import', msg, 'error');
                        }
                    }
                });
            }
        }
    }
</script>
@endsection
