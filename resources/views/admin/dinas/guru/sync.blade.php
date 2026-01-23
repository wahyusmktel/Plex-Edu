@extends('layouts.app')

@section('title', 'Integrasi Data Guru ke Sekolah')

@section('content')
<div class="p-6" x-data="syncManager()">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Integrasi Data Guru ke Sekolah</h2>
        <p class="text-gray-600">Salin data dari master kolektif ke fungsionaris masing-masing sekolah</p>
    </div>

    <!-- Step 1: Select School -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 flex items-center">
            <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center mr-2 text-[10px]">1</span>
            Pilih Sekolah Tujuan
        </h3>
        <form action="{{ route('dinas.master-guru.sync') }}" method="GET" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Sekolah Terdaftar</label>
                <select name="school_id" class="w-full border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-2">
                    <option value="">-- Pilih Sekolah --</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                            {{ $school->nama_sekolah }} ({{ $school->npsn }})
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-all">
                Cari Data di Master
            </button>
        </form>
    </div>

    @if($selectedSchool)
    <!-- Step 2: Review & Process -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center">
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center mr-2 text-[10px]">2</span>
                Data Ditemukan di Master (NPSN: {{ $selectedSchool->npsn }})
            </h3>
            <div class="flex gap-2">
                <span class="text-sm text-gray-500 mr-4 mt-2" x-text="'Terpilih: ' + selectedIds.length + ' / {{ $previewData->count() }}'"></span>
                <button 
                    @click="processSync" 
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-all flex items-center disabled:opacity-50"
                    :disabled="selectedIds.length === 0 || syncing">
                    <i class="material-icons mr-2" :class="syncing ? 'animate-spin' : ''" x-text="syncing ? 'sync' : 'content_copy'"></i>
                    <span x-text="syncing ? 'Memproses...' : 'Salin ke Fungsionaris'"></span>
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white border-b border-gray-100">
                        <th class="px-6 py-4 text-center">
                            <input type="checkbox" @change="toggleAll" id="checkAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">NIK / NIP</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jabatan Master</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($previewData as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-center">
                            <input type="checkbox" x-model="selectedIds" value="{{ $item->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->nama }}</td>
                        <td class="px-6 py-4">
                            <div class="text-xs">NIK: {{ $item->nik ?: '-' }}</div>
                            <div class="text-xs text-blue-600">NIP: {{ $item->nip ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-600">{{ $item->jenis_ptk }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="material-icons text-5xl mb-4 text-gray-200">search_off</i>
                                <p>Tidak ada data di master yang memiliki NPSN {{ $selectedSchool->npsn }}.</p>
                                <p class="text-xs mt-1">Pastikan data Excel yang diimport sudah benar.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    function syncManager() {
        return {
            selectedIds: [],
            syncing: false,
            
            toggleAll(e) {
                if (e.target.checked) {
                    this.selectedIds = {!! json_encode($previewData->pluck('id')) !!};
                } else {
                    this.selectedIds = [];
                }
            },

            async processSync() {
                if (this.selectedIds.length === 0) return;

                const result = await Swal.fire({
                    title: 'Konfirmasi Sinkronisasi',
                    text: `Apakah Anda yakin ingin menyalin ${this.selectedIds.length} data guru ke sekolah {{ $selectedSchool->nama_sekolah ?? '' }}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Salin Sekarang',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#10B981',
                });

                if (result.isConfirmed) {
                    this.syncing = true;
                    try {
                        const response = await axios.post('{{ route("dinas.master-guru.sync.process") }}', {
                            school_id: '{{ $selectedSchool->id ?? '' }}',
                            ids: this.selectedIds
                        });

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.data.message,
                            confirmButtonColor: '#3B82F6'
                        }).then(() => {
                            window.location.reload();
                        });
                    } catch (error) {
                        this.syncing = false;
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Sinkronisasi',
                            text: error.response?.data?.message || 'Terjadi kesalahan sistem',
                            confirmButtonColor: '#EF4444'
                        });
                    }
                }
            }
        }
    }
</script>
@endpush
@endsection
