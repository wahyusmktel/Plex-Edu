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
        <div class="flex gap-4 items-end" x-data="{ 
            showSchools: false, 
            searchSchool: '',
            schools: @js($schools),
            get filteredSchools() {
                return this.schools.filter(s => s.nama_sekolah.toLowerCase().includes(this.searchSchool.toLowerCase()) || (s.npsn && s.npsn.includes(this.searchSchool)));
            }
        }">
            <div class="flex-1 relative">
                <label class="block text-sm font-medium text-gray-700 mb-1">Sekolah Terdaftar</label>
                <div class="relative">
                    <button 
                        @click="showSchools = !showSchools"
                        type="button"
                        class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm font-bold text-gray-700 text-left flex justify-between items-center focus:ring-2 focus:ring-blue-500 transition-all"
                    >
                        @php
                            $selectedSchoolId = request('school_id');
                            $currentSchool = $schools->firstWhere('id', $selectedSchoolId);
                        @endphp
                        <span>{{ $currentSchool ? $currentSchool->nama_sekolah : '-- Pilih Sekolah --' }}</span>
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
                            <template x-for="school in filteredSchools" :key="school.id">
                                <button 
                                    type="button"
                                    @click="window.location.href = `{{ route('dinas.master-guru.sync') }}?school_id=${school.id}`"
                                    class="w-full text-left px-4 py-4 text-xs font-bold transition-all hover:bg-gray-50 flex items-center justify-between"
                                    :class="school.id == '{{ $selectedSchoolId }}' ? 'bg-blue-50 text-blue-600' : 'text-gray-600'"
                                >
                                    <div>
                                        <div x-text="school.nama_sekolah"></div>
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
        </div>
    </div>

    @if($selectedSchool)
    <!-- Step 2: Review & Process -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center">
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center mr-2 text-[10px]">2</span>
                Data Ditemukan di Master: {{ $selectedSchool->nama_sekolah }} (NPSN: {{ $selectedSchool->npsn }})
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
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Sync</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($previewData as $item)
                    <tr class="hover:bg-gray-50 transition-colors {{ $item->is_synced ? 'bg-gray-50 opacity-75' : '' }}">
                        <td class="px-6 py-4 text-center">
                            @if(!$item->is_synced)
                                <input type="checkbox" x-model="selectedIds" value="{{ $item->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            @else
                                <i class="material-icons text-green-500 text-sm">check_circle</i>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->nama }}</td>
                        <td class="px-6 py-4">
                            <div class="text-xs">NIK: {{ $item->nik ?: '-' }}</div>
                            <div class="text-xs text-blue-600">NIP: {{ $item->nip ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-600">{{ $item->jenis_ptk }}</td>
                        <td class="px-6 py-4">
                            @if($item->is_synced)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    <i class="material-icons text-[10px] mr-1">done_all</i> Terintegrasi
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="material-icons text-[10px] mr-1">hourglass_empty</i> Belum Sync
                                </span>
                            @endif
                        </td>
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
                    this.selectedIds = {!! json_encode($previewData->where('is_synced', false)->pluck('id')) !!};
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
