@extends('layouts.app')

@section('title', 'Manajemen Slider - Literasia')

@section('content')
<div x-data="sliderPage()" x-init="init()" class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Slider Admin</h1>
            <p class="text-slate-500 font-medium mt-1">Kelola konten slider hero pada beranda aplikasi</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button @click="openCreateModal()" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl text-sm font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                <i class="material-icons text-[20px]">add_photo_alternate</i> Tambah Slider
            </button>
        </div>
    </div>

    <!-- Search Area -->
    <div class="w-full md:w-96 relative group">
        <form action="{{ route('slider.index') }}" method="GET">
            <input 
                type="text" 
                name="search" 
                value="{{ $search }}" 
                placeholder="Cari slider..." 
                class="w-full bg-white border border-slate-200 rounded-2xl pl-12 pr-6 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-100 transition-all outline-none"
            >
            <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-slate-600 transition-colors">search</i>
        </form>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($sliders as $item)
        @php
            $today = \Carbon\Carbon::today();
            $start = \Carbon\Carbon::parse($item->waktu_mulai);
            $end = $item->waktu_selesai ? \Carbon\Carbon::parse($item->waktu_selesai) : null;
            
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
        <div class="group relative bg-white rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 flex flex-col overflow-hidden">
            <!-- Image Section -->
            <div class="relative h-56 overflow-hidden">
                <img src="{{ asset('storage/' . $item->gambar) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $item->judul }}">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
                
                <!-- Status Badge -->
                <div class="absolute top-6 left-6">
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border backdrop-blur-md {{ $statusColor }}">
                        {{ $status }}
                    </span>
                </div>

                <!-- Actions Overlay -->
                <div class="absolute top-6 right-6 flex items-center gap-2">
                    <button @click="editSlider('{{ $item->id }}')" class="w-10 h-10 bg-white/20 hover:bg-white/40 backdrop-blur-md rounded-xl text-white flex items-center justify-center transition-all cursor-pointer">
                        <i class="material-icons text-lg">edit</i>
                    </button>
                    <button @click="deleteSlider('{{ $item->id }}')" class="w-10 h-10 bg-white/20 hover:bg-rose-500/80 backdrop-blur-md rounded-xl text-white flex items-center justify-center transition-all cursor-pointer">
                        <i class="material-icons text-lg">delete</i>
                    </button>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-8 flex-grow flex flex-col justify-between">
                <div>
                    <h3 class="text-xl font-black text-slate-800 leading-tight line-clamp-2 group-hover:text-[#ba80e8] transition-colors">
                        {{ $item->judul }}
                    </h3>
                    <p class="text-slate-400 text-xs font-medium mt-2 line-clamp-2 italic">
                        {{ $item->deskripsi ?? 'Tidak ada deskripsi' }}
                    </p>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-50 flex flex-col gap-3">
                    <div class="flex items-center gap-2">
                        <i class="material-icons text-sm text-slate-300">calendar_today</i>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                            {{ $start->format('d M Y') }} 
                            @if(!$item->is_permanen)
                            - {{ $end ? $end->format('d M Y') : 'Selesai' }}
                            @else
                            (Selamanya)
                            @endif
                        </span>
                    </div>
                    @if($item->link)
                    <div class="flex items-center gap-2">
                        <i class="material-icons text-sm text-[#ba80e8]">link</i>
                        <a href="{{ $item->link }}" target="_blank" class="text-[10px] font-bold text-[#ba80e8] uppercase tracking-widest hover:underline truncate">{{ $item->link }}</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-32 flex flex-col items-center justify-center">
            <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-300 mb-6 shadow-inner">
                <i class="material-icons text-5xl">image_not_supported</i>
            </div>
            <p class="font-black text-slate-400 text-lg uppercase tracking-widest">Belum ada slider</p>
            <p class="text-slate-300 font-medium mt-1">Mulai buat slider untuk halaman depan.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12">
        {{ $sliders->links() }}
    </div>

    <!-- Modals -->
    @include('admin.slider.modals')

</div>
@endsection

@section('scripts')
<script>
    function sliderPage() {
        return {
            openModal: false,
            editMode: false,
            formData: {
                id: '',
                judul: '',
                deskripsi: '',
                waktu_mulai: '{{ date('Y-m-d') }}',
                waktu_selesai: '',
                is_permanen: false,
                link: '',
            },
            imagePreview: null,

            init() {
                // Initial setup
            },

            openCreateModal() {
                this.editMode = false;
                this.formData = {
                    id: '',
                    judul: '',
                    deskripsi: '',
                    waktu_mulai: '{{ date('Y-m-d') }}',
                    waktu_selesai: '',
                    is_permanen: false,
                    link: '',
                };
                this.imagePreview = null;
                document.getElementById('slider-image-input').value = '';
                this.openModal = true;
            },

            editSlider(id) {
                $.get(`{{ url('slider') }}/${id}`, (data) => {
                    this.formData = {
                        id: data.id,
                        judul: data.judul,
                        deskripsi: data.deskripsi,
                        waktu_mulai: data.waktu_mulai,
                        waktu_selesai: data.waktu_selesai || '',
                        is_permanen: data.is_permanen == 1,
                        link: data.link || '',
                    };
                    this.editMode = true;
                    this.imagePreview = `{{ asset('storage') }}/${data.gambar}`;
                    this.openModal = true;
                });
            },

            previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    this.imagePreview = URL.createObjectURL(file);
                }
            },

            saveSlider() {
                if (!this.formData.judul || (!this.editMode && !document.getElementById('slider-image-input').files[0])) {
                    Swal.fire('Oops...', 'Judul dan Gambar wajib diisi!', 'warning');
                    return;
                }

                Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                let form = new FormData();
                form.append('_token', '{{ csrf_token() }}');
                form.append('judul', this.formData.judul);
                form.append('deskripsi', this.formData.deskripsi || '');
                form.append('waktu_mulai', this.formData.waktu_mulai);
                form.append('waktu_selesai', this.formData.waktu_selesai || '');
                form.append('is_permanen', this.formData.is_permanen ? 1 : 0);
                form.append('link', this.formData.link || '');
                
                const imgInput = document.getElementById('slider-image-input');
                if (imgInput.files[0]) {
                    form.append('gambar', imgInput.files[0]);
                }

                const url = this.editMode ? `{{ url('slider') }}/${this.formData.id}` : `{{ route('slider.store') }}`;
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

            deleteSlider(id) {
                Swal.fire({
                    title: 'Hapus Slider?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d90d8b',
                    confirmButtonText: 'Ya, Hapus'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('slider') }}/${id}`,
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
