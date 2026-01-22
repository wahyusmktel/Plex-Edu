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

    <!-- School Selection Card -->
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6">
        <div class="flex flex-col lg:flex-row items-center gap-6">
            <div class="w-full lg:w-1/3">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Pilih Sekolah</label>
                <select 
                    x-model="selectedSchoolId" 
                    @change="changeSchool()"
                    class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-pink-100 transition-all"
                >
                    <option value="">-- Pilih Sekolah --</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ $selectedSchoolId == $school->id ? 'selected' : '' }}>
                            {{ $school->nama_sekolah }} ({{ $school->siswa_count }} Siswa)
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex-grow grid grid-cols-2 md:grid-cols-4 gap-4 w-full">
                <!-- Quick Stats -->
                <div class="p-4 rounded-3xl bg-slate-50 border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-2">Total Sekolah</p>
                    <p class="text-xl font-black text-slate-800 leading-none">{{ count($schools) }}</p>
                </div>
                <div class="p-4 rounded-3xl bg-pink-50 border border-pink-100">
                    <p class="text-[10px] font-black text-pink-400 uppercase tracking-widest leading-none mb-2">Total Siswa</p>
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
                    <label for="siswaFile" class="flex flex-col items-center justify-center w-full h-48 bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl cursor-pointer group-hover:bg-pink-50 group-hover:border-[#d90d8b]/30 transition-all">
                        <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-slate-400 shadow-sm mb-4 group-hover:text-[#d90d8b]">
                            <i class="material-icons text-3xl">cloud_upload</i>
                        </div>
                        <p class="text-sm font-bold text-slate-500" x-text="fileName || 'Klik untuk pilih file'"></p>
                        <p class="text-[11px] text-slate-400 font-semibold mt-2">Format .xlsx atau .xls</p>
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
                    <button type="button" @click="openImportModal = false" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl text-sm font-bold hover:bg-slate-200 transition-all">Batal</button>
                    <button type="button" @click="importData()" class="flex-1 py-4 bg-[#d90d8b] text-white rounded-2xl text-sm font-bold shadow-lg shadow-pink-100 hover:bg-[#ba80e8] transition-all">Import / Update</button>
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
            fileName: '',
            importing: false,
            importProgress: 0,

            init() {
                if(this.selectedSchoolId) {
                   const sel = document.querySelector('select x-model="selectedSchoolId"');
                   // find text for id
                }
            },

            changeSchool() {
                if (this.selectedSchoolId) {
                    window.location.href = `{{ route('dinas.siswa') }}?school_id=${this.selectedSchoolId}`;
                } else {
                    window.location.href = `{{ route('dinas.siswa') }}`;
                }
            },

            handleFileSelect(e) {
                if (e.target.files.length > 0) {
                    this.fileName = e.target.files[0].name;
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
                            Swal.fire('Berhasil!', res.success, 'success').then(() => location.reload());
                        }, 500);
                    },
                    error: (err) => {
                        clearInterval(progressInterval);
                        this.importing = false;
                        let msg = err.responseJSON?.message || 'Gagal mengimport data.';
                        if (err.responseJSON?.errors) {
                            msg = err.responseJSON.errors.join('<br>');
                        }
                        Swal.fire('Error', msg, 'error');
                    }
                });
            }
        }
    }
</script>
@endsection
