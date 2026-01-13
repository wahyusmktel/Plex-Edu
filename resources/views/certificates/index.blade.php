@extends('layouts.app')

@section('title', 'Sertifikat Guru')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight">SERTIFIKAT <span class="text-[#ba80e8]">GURU</span></h1>
            <p class="text-slate-500 font-medium mt-1">Simpan dan kelola arsip sertifikat profesional Anda.</p>
        </div>
        <button x-data @click="$dispatch('open-modal', 'add-certificate')" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white font-black rounded-2xl shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-95 transition-all text-xs uppercase tracking-widest cursor-pointer">
            <i class="material-icons text-lg">add_circle</i>
            TAMBAH SERTIFIKAT
        </button>
    </div>

    <!-- Grid List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($certificates as $cert)
        <div class="group bg-white rounded-[2.5rem] border border-slate-100 p-8 hover:border-indigo-100 hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-500 flex flex-col relative overflow-hidden">
            <!-- File Icon Badge -->
            <div class="absolute top-6 right-6">
                <span class="px-3 py-1 bg-slate-50 text-slate-400 rounded-full text-[9px] font-black uppercase tracking-widest">
                    {{ strtoupper(pathinfo($cert->file_path, PATHINFO_EXTENSION)) }}
                </span>
            </div>

            <!-- Icon -->
            <div class="w-16 h-16 rounded-[1.5rem] bg-slate-50 text-slate-400 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-all duration-500">
                <i class="material-icons text-3xl">workspace_premium</i>
            </div>

            <div class="flex-grow">
                <p class="text-[10px] font-black text-[#ba80e8] uppercase tracking-[0.2em] mb-2">TAHUN {{ $cert->year }}</p>
                <h3 class="text-xl font-black text-slate-800 leading-tight mb-4 group-hover:text-indigo-600 transition-colors line-clamp-2 min-h-[3.5rem]">{{ $cert->name }}</h3>
                
                @if($cert->expiry_type !== 'none')
                <div class="flex items-center gap-2 mb-4 p-3 bg-rose-50 rounded-xl">
                    <i class="material-icons text-rose-500 text-sm">event_busy</i>
                    <p class="text-[10px] font-black text-rose-600 uppercase tracking-widest">
                        BERLAKU: 
                        {{ $cert->expiry_type === 'date' ? $cert->expiry_date->format('d M Y') : $cert->expiry_year }}
                    </p>
                </div>
                @endif
            </div>

            <div class="flex items-center gap-2 mt-6">
                <a href="{{ asset('storage/' . $cert->file_path) }}" target="_blank" class="flex-grow flex items-center justify-center gap-2 py-4 bg-slate-900 text-white text-[10px] font-black rounded-2xl hover:bg-slate-800 transition-all uppercase tracking-widest">
                    LIHAT FILE
                    <i class="material-icons text-sm">open_in_new</i>
                </a>
                <button x-data @click="$dispatch('open-modal', 'edit-cert-{{ $cert->id }}')" class="w-12 h-12 flex items-center justify-center bg-slate-50 text-slate-400 rounded-2xl hover:bg-indigo-50 hover:text-indigo-500 transition-all">
                    <i class="material-icons text-xl">edit</i>
                </button>
            </div>
        </div>

        <!-- Edit Modal -->
        <x-modal name="edit-cert-{{ $cert->id }}" title="Edit Sertifikat">
            <form x-data="{ type: '{{ $cert->expiry_type }}' }" action="{{ route('certificates.update', $cert->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Nama Sertifikat</label>
                    <input type="text" name="name" value="{{ $cert->name }}" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#ba80e8]/20 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#ba80e8]/20 transition-all">{{ $cert->description }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Tahun Perolehan</label>
                        <input type="number" name="year" value="{{ $cert->year }}" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#ba80e8]/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">File Baru (Opsional)</label>
                        <input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf" class="w-full bg-slate-50 file:hidden rounded-2xl px-6 py-4 text-[10px] font-black text-slate-400">
                    </div>
                </div>
                <div class="pt-4 border-t border-slate-50">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Masa Berlaku</label>
                    <div class="grid grid-cols-3 gap-3 mb-6">
                        <label class="cursor-pointer">
                            <input type="radio" name="expiry_type" value="none" x-model="type" class="sr-only">
                            <div class="py-3 text-center rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="type === 'none' ? 'bg-[#ba80e8] text-white shadow-lg shadow-purple-100' : 'bg-slate-50 text-slate-400'">
                                TIDAK ADA
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="expiry_type" value="year" x-model="type" class="sr-only">
                            <div class="py-3 text-center rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="type === 'year' ? 'bg-[#ba80e8] text-white shadow-lg shadow-purple-100' : 'bg-slate-50 text-slate-400'">
                                TAHUN
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="expiry_type" value="date" x-model="type" class="sr-only">
                            <div class="py-3 text-center rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="type === 'date' ? 'bg-[#ba80e8] text-white shadow-lg shadow-purple-100' : 'bg-slate-50 text-slate-400'">
                                TANGGAL
                            </div>
                        </label>
                    </div>

                    <div x-show="type === 'year'" x-cloak>
                        <input type="number" name="expiry_year" value="{{ $cert->expiry_year }}" placeholder="Masukkan Tahun Kadaluarsa" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#ba80e8]/20 transition-all">
                    </div>
                    <div x-show="type === 'date'" x-cloak>
                        <input type="date" name="expiry_date" value="{{ $cert->expiry_date ? $cert->expiry_date->format('Y-m-d') : '' }}" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#ba80e8]/20 transition-all">
                    </div>
                </div>
                <div class="flex items-center gap-4 pt-6">
                    <button type="submit" class="flex-grow py-5 bg-slate-900 text-white text-[10px] font-black rounded-2xl uppercase tracking-widest hover:bg-slate-800 transition-all">
                        SIMPAN PERUBAHAN
                    </button>
                    <button type="button" x-data @click="$dispatch('open-modal', 'delete-cert-{{ $cert->id }}')" class="w-14 h-14 flex items-center justify-center bg-rose-50 text-rose-500 rounded-2xl hover:bg-rose-500 hover:text-white transition-all">
                        <i class="material-icons">delete_outline</i>
                    </button>
                </div>
            </form>
        </x-modal>

        <!-- Delete Modal -->
        <x-modal name="delete-cert-{{ $cert->id }}" title="Hapus Sertifikat">
            <div class="p-10 text-center">
                <div class="w-20 h-20 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="material-icons text-4xl">warning</i>
                </div>
                <h3 class="text-xl font-black text-slate-800 mb-2">Hapus Sertifikat?</h3>
                <p class="text-slate-500 font-medium mb-8">Tindakan ini akan menghapus arsip sertifikat "{{ $cert->name }}" secara permanen.</p>
                <div class="flex items-center gap-4">
                    <button x-data @click="$dispatch('close-modal', 'delete-cert-{{ $cert->id }}')" class="flex-grow py-4 bg-slate-100 text-slate-600 text-[10px] font-black rounded-2xl uppercase tracking-widest hover:bg-slate-200 transition-all">
                        BATAL
                    </button>
                    <form action="{{ route('certificates.destroy', $cert->id) }}" method="POST" class="flex-grow">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-4 bg-rose-500 text-white text-[10px] font-black rounded-2xl uppercase tracking-widest hover:bg-rose-600 transition-all shadow-lg shadow-rose-200">
                            YA, HAPUS
                        </button>
                    </form>
                </div>
            </div>
        </x-modal>

        @empty
        <div class="col-span-full py-20 text-center bg-white rounded-[2.5rem] border border-slate-100">
            <div class="w-24 h-24 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="material-icons text-5xl">card_membership</i>
            </div>
            <h3 class="text-xl font-black text-slate-800 mb-2">Belum ada Sertifikat</h3>
            <p class="text-slate-500 font-medium mb-8">Arsip sertifikat profesional Anda di sini.</p>
            <button x-data @click="$dispatch('open-modal', 'add-certificate')" class="inline-flex items-center gap-2 px-8 py-4 bg-slate-900 text-white font-black rounded-2xl hover:bg-slate-800 transition-all text-xs uppercase tracking-widest cursor-pointer">
                UNGGAH SEKARANG
            </button>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12">
        {{ $certificates->links() }}
    </div>
</div>

<!-- Add Modal -->
<x-modal name="add-certificate" title="Tambah Sertifikat Baru">
    <form x-data="{ type: 'none' }" action="{{ route('certificates.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
        @csrf
        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Nama Sertifikat</label>
            <input type="text" name="name" required placeholder="Contoh: Sertifikasi Guru Penggerak" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#ba80e8]/20 transition-all">
        </div>
        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Deskripsi (Opsional)</label>
            <textarea name="description" rows="3" placeholder="Berikan informasi tambahan tentang sertifikat ini..." class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#ba80e8]/20 transition-all"></textarea>
        </div>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Tahun Perolehan</label>
                <input type="number" name="year" value="{{ date('Y') }}" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#ba80e8]/20 transition-all">
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Pilih File (JPG/PNG/PDF)</label>
                <input type="file" name="file" required accept=".jpg,.jpeg,.png,.pdf" class="w-full bg-slate-50 file:hidden rounded-2xl px-6 py-4 text-[10px] font-black text-slate-400">
            </div>
        </div>

        <div class="pt-4 border-t border-slate-50">
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Masa Berlaku</label>
            <div class="grid grid-cols-3 gap-3 mb-6">
                <label class="cursor-pointer">
                    <input type="radio" name="expiry_type" value="none" x-model="type" class="sr-only">
                    <div class="py-3 text-center rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="type === 'none' ? 'bg-[#ba80e8] text-white shadow-lg shadow-purple-100' : 'bg-slate-50 text-slate-400'">
                        TIDAK ADA
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="expiry_type" value="year" x-model="type" class="sr-only">
                    <div class="py-3 text-center rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="type === 'year' ? 'bg-[#ba80e8] text-white shadow-lg shadow-purple-100' : 'bg-slate-50 text-slate-400'">
                        TAHUN
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="expiry_type" value="date" x-model="type" class="sr-only">
                    <div class="py-3 text-center rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="type === 'date' ? 'bg-[#ba80e8] text-white shadow-lg shadow-purple-100' : 'bg-slate-50 text-slate-400'">
                        TANGGAL
                    </div>
                </label>
            </div>

            <div x-show="type === 'year'" x-cloak>
                <input type="number" name="expiry_year" placeholder="Masukkan Tahun Kadaluarsa" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#ba80e8]/20 transition-all">
            </div>
            <div x-show="type === 'date'" x-cloak>
                <input type="date" name="expiry_date" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#ba80e8]/20 transition-all">
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full py-5 bg-gradient-to-r from-slate-900 to-slate-800 text-white text-[10px] font-black rounded-2xl uppercase tracking-widest hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-slate-200">
                SIMPAN SERTIFIKAT
            </button>
        </div>
    </form>
</x-modal>
@endsection
