@extends('layouts.app')

@section('title', 'Manajemen Berita - Literasia')

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
        min-height: 300px !important;
        font-size: 14px !important;
        color: #334155 !important;
    }
    .ql-editor.ql-blank::before {
        color: #cbd5e1 !important;
        font-style: normal !important;
    }
</style>
@endsection

@section('content')
<div x-data="beritaPage()" x-init="init()" class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Berita & Artikel</h1>
            <p class="text-slate-500 font-medium mt-1">Kelola publikasi berita dan artikel sekolah</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button @click="openCreateModal()" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl text-sm font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                <i class="material-icons text-[20px]">add_circle</i> Tambah Berita
            </button>
        </div>
    </div>

    <!-- Search Area -->
    <div class="w-full md:w-96 relative group">
        <form action="{{ route('berita.index') }}" method="GET">
            <input 
                type="text" 
                name="search" 
                value="{{ $search }}" 
                placeholder="Cari berita..." 
                class="w-full bg-white border border-slate-200 rounded-2xl pl-12 pr-6 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-100 transition-all outline-none"
            >
            <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-slate-600 transition-colors">search</i>
        </form>
    </div>

    <!-- Grid Berita -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($beritas as $item)
        <div class="group bg-white rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500 overflow-hidden flex flex-col">
            <!-- Thumbnail -->
            <div class="relative h-56 overflow-hidden">
                @if($item->thumbnail)
                    <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->judul }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                @else
                    <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300">
                        <i class="material-icons text-6xl">image</i>
                    </div>
                @endif
                <div class="absolute top-4 left-4">
                    <span class="px-4 py-1.5 bg-white/90 backdrop-blur rounded-xl text-[10px] font-black text-slate-800 uppercase tracking-widest shadow-sm">
                        {{ \Carbon\Carbon::parse($item->tanggal_terbit)->format('d M Y') }}
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8 flex-grow space-y-4">
                <div class="flex items-center gap-2 text-[10px] font-black text-[#d90d8b] uppercase tracking-widest">
                    <i class="material-icons text-sm">schedule</i>
                    {{ \Carbon\Carbon::parse($item->jam_terbit)->format('H:i') }} WIB
                </div>
                <h3 class="text-xl font-black text-slate-800 leading-snug group-hover:text-[#d90d8b] transition-all duration-300">
                    {{ Str::limit($item->judul, 60) }}
                </h3>
                <div class="text-slate-500 text-sm font-medium line-clamp-3 leading-relaxed">
                    {!! strip_tags($item->deskripsi) !!}
                </div>
            </div>

            <!-- Footer -->
            <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between mt-auto">
                <div class="flex items-center gap-2">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($item->user->name) }}&background=ba80e8&color=fff" class="w-6 h-6 rounded-lg">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $item->user->name }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="editBerita('{{ $item->id }}')" class="p-2 text-blue-500 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors cursor-pointer" title="Edit">
                        <i class="material-icons text-lg">edit</i>
                    </button>
                    <button @click="deleteBerita('{{ $item->id }}')" class="p-2 text-rose-500 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors cursor-pointer" title="Hapus">
                        <i class="material-icons text-lg">delete</i>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-32 flex flex-col items-center justify-center">
            <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-300 mb-6 shadow-inner">
                <i class="material-icons text-5xl">inventory_2</i>
            </div>
            <p class="font-black text-slate-400 text-lg uppercase tracking-widest">Belum ada berita</p>
            <p class="text-slate-300 font-medium mt-1">Mulai terbitkan artikel pertama Anda hari ini.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $beritas->links() }}
    </div>

    <!-- Modals -->
    @include('admin.berita.modals')

</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    function beritaPage() {
        return {
            openModal: false,
            editMode: false,
            formData: {
                id: '',
                judul: '',
                deskripsi: '',
                tanggal_terbit: '{{ date('Y-m-d') }}',
                jam_terbit: '{{ date('H:i') }}',
            },
            thumbnailPreview: null,

            quill: null,

            init() {
                this.initQuill();
            },

            initQuill() {
                this.quill = new Quill('#deskripsi-editor', {
                    theme: 'snow',
                    placeholder: 'Tulis konten berita di sini...',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            ['link', 'image'],
                            ['clean']
                        ]
                    }
                });

                this.quill.on('text-change', () => {
                    this.formData.deskripsi = this.quill.root.innerHTML;
                });
            },

            openCreateModal() {
                this.editMode = false;
                this.formData = {
                    id: '',
                    judul: '',
                    deskripsi: '',
                    tanggal_terbit: '{{ date('Y-m-d') }}',
                    jam_terbit: '{{ date('H:i') }}',
                };
                this.thumbnailPreview = null;
                if (this.quill) {
                    this.quill.root.innerHTML = '';
                }
                this.openModal = true;
            },

            editBerita(id) {
                $.get(`{{ url('berita') }}/${id}`, (data) => {
                    this.formData = {
                        id: data.id,
                        judul: data.judul,
                        deskripsi: data.deskripsi,
                        tanggal_terbit: data.tanggal_terbit,
                        jam_terbit: data.jam_terbit.substring(0, 5),
                    };
                    this.editMode = true;
                    this.thumbnailPreview = data.thumbnail ? `{{ asset('storage') }}/${data.thumbnail}` : null;
                    if (this.quill) {
                        this.quill.root.innerHTML = data.deskripsi;
                    }
                    this.openModal = true;
                });
            },

            previewThumbnail(event) {
                const file = event.target.files[0];
                if (file) {
                    this.thumbnailPreview = URL.createObjectURL(file);
                }
            },

            saveBerita() {
                // Get content from Quill
                this.formData.deskripsi = this.quill.root.innerHTML;

                // Validate if default empty quill content
                if (this.quill.getText().trim().length === 0) {
                    this.formData.deskripsi = '';
                }

                if (!this.formData.judul || !this.formData.deskripsi) {
                    Swal.fire('Oops...', 'Judul dan Deskripsi wajib diisi!', 'warning');
                    return;
                }

                Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                let form = new FormData();
                form.append('_token', '{{ csrf_token() }}');
                form.append('judul', this.formData.judul);
                form.append('deskripsi', this.formData.deskripsi);
                form.append('tanggal_terbit', this.formData.tanggal_terbit);
                form.append('jam_terbit', this.formData.jam_terbit);
                
                const thumbInput = document.getElementById('thumbnail-input');
                if (thumbInput.files[0]) {
                    form.append('thumbnail', thumbInput.files[0]);
                }

                const url = this.editMode ? `{{ url('berita') }}/${this.formData.id}` : `{{ route('berita.store') }}`;
                if (this.editMode) form.append('_method', 'PUT');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: form,
                    processData: false,
                    contentType: false,
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

            deleteBerita(id) {
                Swal.fire({
                    title: 'Hapus Berita?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d90d8b',
                    confirmButtonText: 'Ya, Hapus'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('berita') }}/${id}`,
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
