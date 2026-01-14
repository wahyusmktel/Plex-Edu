@extends('layouts.app')

@section('title', 'E-Raport Digital Archive - Literasia')

@section('content')
<div x-data="raportPage()" x-init="init()" class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">E-Raport</h1>
            <p class="text-slate-500 font-medium mt-1">Arsip Digital Dokumen Raport Siswa</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button @click="openCreateModal()" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl text-sm font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                <i class="material-icons text-[20px]">add_circle</i> Tambah Arsap Raport
            </button>
        </div>
    </div>

    <!-- Stats for context -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tahun Pelajaran Aktif</p>
            <h4 class="text-lg font-bold text-slate-800">{{ $activeSetting->tahun_pelajaran ?? '-' }}</h4>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Semester Aktif</p>
            <h4 class="text-lg font-bold text-slate-800 uppercase">{{ $activeSetting->semester ?? '-' }}</h4>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Arsip</p>
            <h4 class="text-lg font-bold text-slate-800">{{ count($raports) }} Dokumen</h4>
        </div>
    </div>

    <!-- Filter & Search Area -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="w-full md:w-96 relative group">
            <form action="{{ route('e-raport.index') }}" method="GET">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search }}" 
                    placeholder="Cari Nama Siswa atau NIS..." 
                    class="w-full bg-white border border-slate-200 rounded-2xl pl-12 pr-6 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 focus:border-[#d90d8b]/30 transition-all outline-none"
                >
                <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#d90d8b] transition-colors">search</i>
            </form>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-2">
        <div class="overflow-x-auto p-4">
            <table class="w-full text-left border-separate border-spacing-y-3">
                <thead>
                    <tr class="text-slate-400 text-[11px] uppercase font-black tracking-widest">
                        <th class="px-6 py-3">Siswa</th>
                        <th class="px-6 py-3">Kelas</th>
                        <th class="px-6 py-3">TP / Semester</th>
                        <th class="px-6 py-3">File Arsip</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($raports as $item)
                    <tr class="group hover:scale-[1.005] transition-transform duration-200">
                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-l border-transparent group-hover:border-slate-100 first:rounded-l-2xl">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-pink-50 flex items-center justify-center text-[#d90d8b]">
                                    <i class="material-icons">article</i>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 leading-none">{{ $item->siswa->nama_lengkap ?? 'Siswa Terhapus' }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase">NIS: {{ $item->siswa->nis ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100">
                            <p class="font-bold text-slate-700">{{ $item->siswa->kelas->nama ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100">
                            <p class="font-bold text-slate-700 leading-none">{{ $item->tahun_pelajaran }}</p>
                            <p class="text-[10px] font-bold text-[#d90d8b] uppercase mt-1">Semester {{ $item->semester }}</p>
                        </td>
                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100">
                            <a href="{{ Storage::url($item->file_path) }}" target="_blank" class="flex items-center gap-2 text-[#ba80e8] font-bold hover:underline">
                                <i class="material-icons text-sm">visibility</i>
                                {{ Str::limit($item->file_name, 20) }}
                            </a>
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
                                    <i class="material-icons text-4xl">folder_off</i>
                                </div>
                                <p class="text-sm font-bold text-slate-400">Belum ada arsip raport digital</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-50">
            {{ $raports->links() }}
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
            class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-2xl overflow-hidden relative z-10 flex flex-col"
        >
            <!-- Modal Header -->
            <div class="px-10 py-8 flex items-center justify-between border-b border-slate-50">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight" x-text="editMode ? 'Edit Arsip Raport' : 'Tambah Arsip Raport'"></h2>
                    <p class="text-slate-400 font-medium text-sm mt-1">Pilih siswa dan unggah dokumen raport.</p>
                </div>
                <button @click="openModal = false" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all">
                    <i class="material-icons">close</i>
                </button>
            </div>

            <form id="raportForm" class="p-10 space-y-6 overflow-y-auto">
                @csrf
                <input type="hidden" name="id" x-model="formData.id">
                
                <div class="space-y-1.5">
                    <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Nama Siswa</label>
                    <select name="siswa_id" x-model="formData.siswa_id" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all">
                        <option value="">Pilih Siswa</option>
                        @foreach($siswas as $s)
                            <option value="{{ $s->id }}">{{ $s->nama_lengkap }} ({{ $s->kelas->nama ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Tahun Pelajaran</label>
                        <input type="text" name="tahun_pelajaran" x-model="formData.tahun_pelajaran" readonly class="w-full bg-slate-100 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-500 cursor-not-allowed">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Semester</label>
                        <input type="text" name="semester" x-model="formData.semester" readonly class="w-full bg-slate-100 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-500 cursor-not-allowed uppercase">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">File Scan Raport</label>
                    <div class="relative group">
                        <input type="file" name="file_raport" class="hidden" id="raportFile" @change="fileName = $event.target.files[0].name">
                        <label for="raportFile" class="flex flex-col items-center justify-center w-full h-40 bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl cursor-pointer group-hover:bg-pink-50 group-hover:border-[#d90d8b]/30 transition-all">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-slate-400 shadow-sm mb-3 group-hover:text-[#d90d8b]">
                                <i class="material-icons text-2xl">cloud_upload</i>
                            </div>
                            <p class="text-sm font-bold text-slate-500" x-text="fileName || 'Klik untuk pilih file'"></p>
                            <p class="text-[10px] uppercase font-black tracking-widest text-slate-400 mt-1">JPG, JPEG, PDF (Maks. 2MB)</p>
                        </label>
                    </div>
                    <p class="text-[10px] text-slate-400 font-bold px-1" x-show="editMode">* Kosongkan jika tidak ingin mengganti file</p>
                </div>
            </form>

            <!-- Modal Footer -->
            <div class="px-10 py-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <button @click="openModal = false" class="px-8 py-3.5 rounded-2xl text-sm font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-all">Batal</button>
                <button @click="saveData()" class="px-10 py-3.5 rounded-2xl bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white text-sm font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Simpan Arsip
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    function raportPage() {
        return {
            openModal: false,
            editMode: false,
            fileName: '',
            formData: {
                id: '',
                siswa_id: '',
                semester: '{{ $activeSetting->semester ?? 'ganjil' }}',
                tahun_pelajaran: '{{ $activeSetting->tahun_pelajaran ?? '-' }}',
            },
            init() {},
            openCreateModal() {
                this.editMode = false;
                this.openModal = true;
                this.fileName = '';
                this.formData = {
                    id: '',
                    siswa_id: '',
                    semester: '{{ $activeSetting->semester ?? 'ganjil' }}',
                    tahun_pelajaran: '{{ $activeSetting->tahun_pelajaran ?? '-' }}',
                };
            },
            saveData() {
                const url = this.formData.id ? `{{ url('e-raport/update') }}/${this.formData.id}` : `{{ route('e-raport.store') }}`;
                
                let form = document.getElementById('raportForm');
                let data = new FormData(form);
                
                Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    contentType: false,
                    processData: false,
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
            editData(id) {
                $.get(`{{ url('e-raport/show') }}/${id}`, (data) => {
                    this.formData = {
                        id: data.id,
                        siswa_id: data.siswa_id,
                        semester: data.semester,
                        tahun_pelajaran: data.tahun_pelajaran,
                    };
                    this.fileName = data.file_name;
                    this.editMode = true;
                    this.openModal = true;
                });
            },
            deleteData(id) {
                Swal.fire({
                    title: 'Hapus Arsip?',
                    text: "File fisik raport juga akan terhapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d90d8b',
                    confirmButtonText: 'Ya, Hapus'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('e-raport/destroy') }}/${id}`,
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
