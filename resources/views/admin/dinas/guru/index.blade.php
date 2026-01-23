@extends('layouts.app')

@section('title', 'Master Data Guru Dinas')

@section('content')
<div class="p-6" x-data="guruDinasManager()">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Master Data Guru Dinas</h2>
            <p class="text-gray-600">Manajemen data guru masal dari Dinas Pendidikan</p>
        </div>
        <div class="flex gap-2">
            <button @click="showImportModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-all">
                <i class="material-icons mr-2">upload_file</i> Import Excel
            </button>
            <form action="{{ route('dinas.master-guru.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SEMUA data master guru?')">
                @csrf
                <button type="submit" class="bg-red-100 text-red-600 hover:bg-red-200 px-4 py-2 rounded-lg flex items-center transition-all">
                    <i class="material-icons mr-2">delete_sweep</i> Kosongkan Data
                </button>
            </form>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form action="{{ route('dinas.master-guru.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Guru</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="material-icons text-sm">search</i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Nama, NIK, NIP, atau Sekolah...">
                </div>
            </div>
            <div x-data="{ 
                showSchools: false, 
                searchSchool: '',
                schools: @js($schools),
                get filteredSchools() {
                    return this.schools.filter(s => s.tempat_tugas.toLowerCase().includes(this.searchSchool.toLowerCase()) || (s.npsn && s.npsn.includes(this.searchSchool)));
                }
            }">
                <label class="block text-sm font-medium text-gray-700 mb-1">Sekolah (Tempat Tugas)</label>
                <div class="relative">
                    <input type="hidden" name="npsn" :value="searchSchool"> <!-- This is tricky because we need the value and the display -->
                    
                    <button 
                        @click="showSchools = !showSchools"
                        type="button"
                        class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-700 text-left flex justify-between items-center focus:ring-2 focus:ring-blue-500 transition-all"
                    >
                        @php
                            $selectedNpsn = request('npsn');
                            $currentSchool = $schools->firstWhere('npsn', $selectedNpsn);
                        @endphp
                        <span>{{ $currentSchool ? $currentSchool->tempat_tugas : '-- Semua Sekolah --' }}</span>
                        <i class="material-icons text-gray-400" :class="showSchools ? 'rotate-180' : ''">expand_more</i>
                    </button>

                    <!-- Dropdown Panel -->
                    <div 
                        x-show="showSchools" 
                        @click.away="showSchools = false"
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="absolute left-0 right-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden"
                    >
                        <div class="p-3 border-b border-gray-50">
                            <div class="relative">
                                <i class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm">search</i>
                                <input 
                                    type="text" 
                                    x-model="searchSchool"
                                    placeholder="Cari sekolah atau NPSN..." 
                                    class="w-full bg-gray-50 border border-gray-100 rounded-lg pl-10 pr-4 py-2 text-xs font-semibold outline-none focus:ring-2 focus:ring-blue-100"
                                    @click.stop
                                >
                            </div>
                        </div>
                        <div class="max-h-60 overflow-y-auto">
                            <button 
                                type="button"
                                @click="window.location.href = '{{ route('dinas.master-guru.index') }}' + (window.location.search.replace(/npsn=[^&]*/, '').replace(/&&+/, '&'))"
                                class="w-full text-left px-4 py-3 text-xs font-bold transition-all hover:bg-gray-50 flex items-center justify-between text-gray-600"
                            >
                                <span>-- Semua Sekolah --</span>
                            </button>
                            <template x-for="school in filteredSchools" :key="school.npsn">
                                <button 
                                    type="button"
                                    @click="window.location.href = `{{ route('dinas.master-guru.index') }}?npsn=${school.npsn}&search={{ request('search') }}`"
                                    class="w-full text-left px-4 py-3 text-xs font-bold transition-all hover:bg-gray-50 flex items-center justify-between"
                                    :class="school.npsn == '{{ request('npsn') }}' ? 'bg-blue-50 text-blue-600' : 'text-gray-600'"
                                >
                                    <div>
                                        <div x-text="school.tempat_tugas"></div>
                                        <div class="text-[10px] text-gray-400 font-medium" x-text="'NPSN: ' + school.npsn"></div>
                                    </div>
                                </button>
                            </template>
                            <div x-show="filteredSchools.length === 0" class="p-4 text-center text-gray-400 text-xs italic">
                                Sekolah tidak ditemukan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition-all w-full md:w-auto">
                    Filter Data
                </button>
                @if(request()->anyFilled(['search', 'npsn']))
                    <a href="{{ route('dinas.master-guru.index') }}" class="ml-2 text-gray-500 hover:text-gray-700 flex items-center py-2">
                        <i class="material-icons mr-1">cancel</i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama & Detail</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">NIK / NIP</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tempat Tugas</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status & Jabatan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($gurus as $guru)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $guru->nama }}</div>
                            <div class="text-xs text-gray-500">{{ $guru->jenis_kelamin }} | {{ $guru->tempat_lahir }}, {{ $guru->tanggal_lahir ? $guru->tanggal_lahir->format('d/m/Y') : '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">NIK: {{ $guru->nik ?: '-' }}</div>
                            <div class="text-xs text-blue-600 font-mono">NIP: {{ $guru->nip ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 font-medium">{{ $guru->tempat_tugas }}</div>
                            <div class="text-xs text-gray-500">NPSN: {{ $guru->npsn }} | {{ $guru->kecamatan }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-1">
                                {{ $guru->status_kepegawaian }}
                            </div>
                            <div class="text-xs text-gray-600">{{ $guru->jabatan_ptk }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('dinas.master-guru.show', $guru->id) }}" class="p-2 text-blue-400 hover:text-blue-600 transition-colors" title="Detail Guru">
                                    <i class="material-icons text-lg">visibility</i>
                                </a>
                                <form action="{{ route('dinas.master-guru.destroy', $guru->id) }}" method="POST" onsubmit="return confirm('Hapus data guru ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-400 hover:text-red-600 transition-colors">
                                        <i class="material-icons text-lg">delete</i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="material-icons text-5xl mb-4 text-gray-200">group_off</i>
                                <p>Belum ada data guru dinas yang diimport.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($gurus->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $gurus->links() }}
        </div>
        @endif
    </div>

    <!-- Import Modal -->
    <div x-show="showImportModal" 
        class="fixed inset-0 z-50 overflow-y-auto" 
        style="display: none;"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showImportModal = false"></div>

            <div class="bg-white rounded-2xl shadow-xl transform transition-all max-w-lg w-full p-8 relative">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Import Master Guru Dinas</h3>
                    <button @click="showImportModal = false" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <form @submit.prevent="submitImport">
                    <div class="mb-6 px-4 py-3 bg-blue-50 rounded-lg flex items-start">
                        <i class="material-icons text-blue-500 mt-1 mr-3">info</i>
                        <p class="text-sm text-blue-700">Gunakan file Excel dari Dinas Pendidikan. Sistem akan membaca mulai dari baris 6 dan memetakan kolom B sampai AE secara otomatis.</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih File Excel (.xls, .xlsx)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl transition-all"
                            :class="isDragging ? 'border-blue-400 bg-blue-50' : 'hover:border-blue-400'">
                            <div class="space-y-1 text-center">
                                <i class="material-icons text-5xl text-gray-400 mb-3" :class="file ? 'text-blue-500' : ''">description</i>
                                <div class="flex text-sm text-gray-600">
                                    <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span x-text="file ? file.name : 'Pilih file'"></span>
                                        <input type="file" @change="file = $event.target.files[0]" class="sr-only" accept=".xlsx,.xls">
                                    </label>
                                    <p class="pl-1" x-show="!file">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">Format: XLS, XLSX (Maks. 10MB)</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="showImportModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all">
                            Batal
                        </button>
                        <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all disabled:opacity-50 flex items-center"
                            :disabled="!file || uploading">
                            <span x-show="uploading" class="mr-2">
                                <i class="material-icons animate-spin">sync</i>
                            </span>
                            <span x-text="uploading ? 'Mengimport...' : 'Mulai Import'"></span>
                        </button>
                    </div>
                </form>

                <!-- Progress Bar -->
                <div x-show="uploading" class="mt-6">
                    <div class="flex justify-between mb-1">
                        <span class="text-xs font-semibold text-blue-600">Proses Import Data...</span>
                        <span class="text-xs font-semibold text-blue-600" x-text="uploadProgress + '%'"></span>
                    </div>
                    <div class="overflow-hidden h-2 text-xs flex rounded bg-blue-100">
                        <div :style="'width: ' + uploadProgress + '%'" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500 transition-all duration-300"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function guruDinasManager() {
        return {
            showImportModal: false,
            file: null,
            uploading: false,
            uploadProgress: 0,
            isDragging: false,

            async submitImport() {
                if (!this.file) return;

                this.uploading = true;
                this.uploadProgress = 0;

                const formData = new FormData();
                formData.append('file', this.file);
                formData.append('_token', '{{ csrf_token() }}');

                try {
                    const response = await axios.post('{{ route("dinas.master-guru.import") }}', formData, {
                        onUploadProgress: (progressEvent) => {
                            this.uploadProgress = Math.round((progressEvent.loaded * 90) / progressEvent.total);
                        }
                    });

                    this.uploadProgress = 100;
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.data.success,
                        confirmButtonColor: '#3B82F6'
                    }).then(() => {
                        window.location.reload();
                    });
                } catch (error) {
                    this.uploading = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Import',
                        text: error.response?.data?.error || 'Terjadi kesalahan sistem',
                        confirmButtonColor: '#EF4444'
                    });
                }
            }
        }
    }
</script>
@endpush
@endsection
