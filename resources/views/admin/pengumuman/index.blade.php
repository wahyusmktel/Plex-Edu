@extends('layouts.app')

@section('title', 'Manajemen Pengumuman - Literasia')

@section('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar {
        border-top-left-radius: 1.5rem !important;
        border-top-right-radius: 1.5rem !important;
        border-color: #f1f5f9 !important;
        background: #f8fafc !important;
        padding: 1rem !important;
    }
    .ql-container {
        border-bottom-left-radius: 1.5rem !important;
        border-bottom-right-radius: 1.5rem !important;
        border-color: #f1f5f9 !important;
        background: #f8fafc !important;
        font-family: 'Inter', sans-serif !important;
    }
    .ql-editor {
        min-height: 200px !important;
        font-size: 14px !important;
        color: #334155 !important;
    }
</style>
@endsection

@section('content')
<div x-data="pengumumanPage()" x-init="init()" class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Pengumuman</h1>
            <p class="text-slate-500 font-medium mt-1">Kelola pengumuman dan informasi penting sekolah</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button @click="openCreateModal()" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl text-sm font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                <i class="material-icons text-[20px]">add_circle</i> Tambah Pengumuman
            </button>
        </div>
    </div>

    <!-- Search Area -->
    <div class="w-full md:w-96 relative group">
        <form action="{{ route('pengumuman.index') }}" method="GET">
            <input 
                type="text" 
                name="search" 
                value="{{ $search }}" 
                placeholder="Cari pengumuman..." 
                class="w-full bg-white border border-slate-200 rounded-2xl pl-12 pr-6 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-100 transition-all outline-none"
            >
            <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-slate-600 transition-colors">search</i>
        </form>
    </div>

    <!-- Table Layout -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-2">
        <div class="overflow-x-auto p-4">
            <table class="w-full text-left border-separate border-spacing-y-3">
                <thead>
                    <tr class="text-slate-400 text-[11px] uppercase font-black tracking-widest">
                        <th class="px-6 py-3">Pengumuman</th>
                        <th class="px-6 py-3">Masa Berlaku</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($pengumumans as $item)
                    @php
                        $today = \Carbon\Carbon::today();
                        $start = \Carbon\Carbon::parse($item->tanggal_terbit);
                        $end = $item->tanggal_berakhir ? \Carbon\Carbon::parse($item->tanggal_berakhir) : null;
                        
                        $status = 'Aktif';
                        $statusColor = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                        
                        if ($item->is_permanen) {
                            $status = 'Permanen';
                            $statusColor = 'bg-indigo-100 text-indigo-700 border-indigo-200';
                        } elseif ($today->lt($start)) {
                            $status = 'Akan Datang';
                            $statusColor = 'bg-blue-100 text-blue-700 border-blue-200';
                        } elseif ($end && $today->gt($end)) {
                            $status = 'Kedaluwarsa';
                            $statusColor = 'bg-slate-100 text-slate-500 border-slate-200';
                        }
                    @endphp
                    <tr class="group hover:scale-[1.005] transition-transform duration-200">
                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-l border-transparent group-hover:border-slate-100 first:rounded-l-2xl">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-[#ba80e8] shadow-sm">
                                    <i class="material-icons">campaign</i>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 leading-none">{{ $item->judul }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest">Oleh: {{ $item->user->name ?? 'Administrator' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100">
                            @if($item->is_permanen)
                                <span class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest">Tanpa Batas Waktu</span>
                            @else
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-700">{{ $start->format('d M Y') }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">s/d {{ $end ? $end->format('d M Y') : 'Selesai' }}</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-transparent group-hover:border-slate-100 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border {{ $statusColor }}">
                                {{ $status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white border-y border-r border-transparent group-hover:border-slate-100 last:rounded-r-2xl text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="editPengumuman('{{ $item->id }}')" class="p-2 text-blue-500 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors cursor-pointer" title="Edit">
                                    <i class="material-icons text-lg">edit</i>
                                </button>
                                <button @click="deletePengumuman('{{ $item->id }}')" class="p-2 text-rose-500 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors cursor-pointer" title="Hapus">
                                    <i class="material-icons text-lg">delete</i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-300 mb-6 shadow-inner">
                                    <i class="material-icons text-5xl">inventory_2</i>
                                </div>
                                <p class="font-black text-slate-400 text-lg uppercase tracking-widest">Belum ada pengumuman</p>
                                <p class="text-slate-300 font-medium mt-1">Informasi penting akan muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-50">
            {{ $pengumumans->links() }}
        </div>
    </div>

    <!-- Modals -->
    @include('admin.pengumuman.modals')

</div>
@endsection

@section('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    function pengumumanPage() {
        return {
            openModal: false,
            editMode: false,
            formData: {
                id: '',
                judul: '',
                pesan: '',
                tanggal_terbit: '{{ date('Y-m-d') }}',
                tanggal_berakhir: '',
                is_permanen: false,
            },
            quill: null,

            init() {
                this.initQuill();
            },

            initQuill() {
                if (this.quill) return;
                
                const editorContainer = document.getElementById('pesan-editor');
                if (!editorContainer) return;

                const existingToolbar = editorContainer.parentElement.querySelector('.ql-toolbar');
                if (existingToolbar) existingToolbar.remove();

                this.quill = new Quill('#pesan-editor', {
                    theme: 'snow',
                    placeholder: 'Tulis isi pengumuman di sini...',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            ['link', 'clean']
                        ]
                    }
                });

                this.quill.on('text-change', () => {
                    this.formData.pesan = this.quill.root.innerHTML;
                });
            },

            openCreateModal() {
                this.editMode = false;
                this.formData = {
                    id: '',
                    judul: '',
                    pesan: '',
                    tanggal_terbit: '{{ date('Y-m-d') }}',
                    tanggal_berakhir: '',
                    is_permanen: false,
                };
                if (this.quill) this.quill.root.innerHTML = '';
                this.openModal = true;
            },

            editPengumuman(id) {
                $.get(`{{ url('pengumuman') }}/${id}`, (data) => {
                    this.formData = {
                        id: data.id,
                        judul: data.judul,
                        pesan: data.pesan,
                        tanggal_terbit: data.tanggal_terbit,
                        tanggal_berakhir: data.tanggal_berakhir || '',
                        is_permanen: data.is_permanen == 1,
                    };
                    this.editMode = true;
                    if (this.quill) this.quill.root.innerHTML = data.pesan;
                    this.openModal = true;
                });
            },

            savePengumuman() {
                this.formData.pesan = this.quill.root.innerHTML;

                if (this.quill.getText().trim().length === 0) {
                    this.formData.pesan = '';
                }

                if (!this.formData.judul || !this.formData.pesan) {
                    Swal.fire('Oops...', 'Judul dan Pesan wajib diisi!', 'warning');
                    return;
                }

                Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                const url = this.editMode ? `{{ url('pengumuman') }}/${this.formData.id}` : `{{ route('pengumuman.store') }}`;
                const method = this.editMode ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        ...this.formData,
                        is_permanen: this.formData.is_permanen ? 1 : 0,
                        _token: '{{ csrf_token() }}',
                        _method: method
                    },
                    success: (res) => {
                        this.openModal = false;
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.success, timer: 1500, showConfirmButton: false }).then(() => {
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

            deletePengumuman(id) {
                Swal.fire({
                    title: 'Hapus Pengumuman?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d90d8b',
                    confirmButtonText: 'Ya, Hapus'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('pengumuman') }}/${id}`,
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
