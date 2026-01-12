@extends('layouts.app')

@section('title', 'Manajemen Sambutan - Literasia')

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
        min-height: 250px !important;
        font-size: 14px !important;
        color: #334155 !important;
    }
</style>
@endsection

@section('content')
<div x-data="sambutanPage()" x-init="init()" class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Sambutan Sekolah</h1>
            <p class="text-slate-500 font-medium mt-1">Kelola pesan dan sambutan resmi dari pimpinan sekolah</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button @click="openCreateModal()" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl text-sm font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                <i class="material-icons text-[20px]">add_circle</i> Tambah Sambutan
            </button>
        </div>
    </div>

    <!-- Search Area -->
    <div class="w-full md:w-96 relative group">
        <form action="{{ route('sambutan.index') }}" method="GET">
            <input 
                type="text" 
                name="search" 
                value="{{ $search }}" 
                placeholder="Cari sambutan..." 
                class="w-full bg-white border border-slate-200 rounded-2xl pl-12 pr-6 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-100 transition-all outline-none"
            >
            <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-slate-600 transition-colors">search</i>
        </form>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
        @forelse($sambutans as $item)
        <div class="group relative bg-white rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col md:flex-row h-full">
            <!-- Thumbnail Section -->
            <div class="relative w-full md:w-48 lg:w-64 h-64 md:h-auto overflow-hidden">
                <img src="{{ asset('storage/' . $item->thumbnail) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $item->judul }}">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-900/20 to-transparent"></div>
            </div>

            <!-- Content Section -->
            <div class="p-8 flex-grow flex flex-col justify-between">
                <div>
                    <h3 class="text-xl font-black text-slate-800 leading-tight group-hover:text-[#ba80e8] transition-colors line-clamp-2">
                        {{ $item->judul }}
                    </h3>
                    <div class="text-slate-400 text-sm font-medium mt-4 line-clamp-3 prose prose-slate prose-sm max-w-none">
                        {!! strip_tags($item->konten) !!}
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-50 flex items-center justify-between">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $item->created_at->format('d M Y') }}</span>
                    <div class="flex items-center gap-2">
                        <button @click="editSambutan('{{ $item->id }}')" class="p-2 text-blue-500 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors cursor-pointer" title="Edit">
                            <i class="material-icons text-lg">edit</i>
                        </button>
                        <button @click="deleteSambutan('{{ $item->id }}')" class="p-2 text-rose-500 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors cursor-pointer" title="Hapus">
                            <i class="material-icons text-lg">delete</i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-32 flex flex-col items-center justify-center">
            <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-300 mb-6 shadow-inner">
                <i class="material-icons text-5xl">record_voice_over</i>
            </div>
            <p class="font-black text-slate-400 text-lg uppercase tracking-widest">Belum ada sambutan</p>
            <p class="text-slate-300 font-medium mt-1">Gunakan tombol di atas untuk membuat pesan baru.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12">
        {{ $sambutans->links() }}
    </div>

    <!-- Modals -->
    @include('admin.sambutan.modals')

</div>
@endsection

@section('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    function sambutanPage() {
        return {
            openModal: false,
            editMode: false,
            formData: {
                id: '',
                judul: '',
                konten: '',
            },
            imagePreview: null,
            quill: null,

            init() {
                this.initQuill();
            },

            initQuill() {
                if (this.quill) return;
                
                const editorContainer = document.getElementById('konten-editor');
                if (!editorContainer) return;

                const existingToolbar = editorContainer.parentElement.querySelector('.ql-toolbar');
                if (existingToolbar) existingToolbar.remove();

                this.quill = new Quill('#konten-editor', {
                    theme: 'snow',
                    placeholder: 'Tentukan isi sambutan di sini...',
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
                    this.formData.konten = this.quill.root.innerHTML;
                });
            },

            openCreateModal() {
                this.editMode = false;
                this.formData = {
                    id: '',
                    judul: '',
                    konten: '',
                };
                this.imagePreview = null;
                if (this.quill) this.quill.root.innerHTML = '';
                document.getElementById('thumbnail-input').value = '';
                this.openModal = true;
            },

            editSambutan(id) {
                $.get(`{{ url('sambutan') }}/${id}`, (data) => {
                    this.formData = {
                        id: data.id,
                        judul: data.judul,
                        konten: data.konten,
                    };
                    this.editMode = true;
                    this.imagePreview = `{{ asset('storage') }}/${data.thumbnail}`;
                    if (this.quill) this.quill.root.innerHTML = data.konten;
                    this.openModal = true;
                });
            },

            previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    this.imagePreview = URL.createObjectURL(file);
                }
            },

            saveSambutan() {
                this.formData.konten = this.quill.root.innerHTML;

                if (this.quill.getText().trim().length === 0) {
                    this.formData.konten = '';
                }

                if (!this.formData.judul || !this.formData.konten || (!this.editMode && !document.getElementById('thumbnail-input').files[0])) {
                    Swal.fire('Oops...', 'Judul, Konten, dan Gambar wajib diisi!', 'warning');
                    return;
                }

                Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                let form = new FormData();
                form.append('_token', '{{ csrf_token() }}');
                form.append('judul', this.formData.judul);
                form.append('konten', this.formData.konten);
                
                const thumbInput = document.getElementById('thumbnail-input');
                if (thumbInput.files[0]) {
                    form.append('thumbnail', thumbInput.files[0]);
                }

                const url = this.editMode ? `{{ url('sambutan') }}/${this.formData.id}` : `{{ route('sambutan.store') }}`;
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

            deleteSambutan(id) {
                Swal.fire({
                    title: 'Hapus Sambutan?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d90d8b',
                    confirmButtonText: 'Ya, Hapus'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('sambutan') }}/${id}`,
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
