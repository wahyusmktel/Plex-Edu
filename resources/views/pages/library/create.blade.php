@extends('layouts.app')

@section('title', 'Tambah Koleksi - Literasia')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('library.index') }}" class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-[#d90d8b] hover:border-[#d90d8b] transition-all">
            <i class="material-icons">arrow_back</i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tambah Koleksi Baru</h1>
            <p class="text-slate-500">Tambahkan materi digital baru ke perpustakaan.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <form action="{{ route('library.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Kategori Koleksi</label>
                        <select name="category" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#d90d8b] focus:ring-0 transition-all outline-none bg-slate-50" required>
                            <option value="book">E-Book (PDF)</option>
                            <option value="audio">Audio Book (MP3)</option>
                            <option value="video">Video Book (MP4/WebM)</option>
                        </select>
                        @error('category') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Judul Koleksi</label>
                        <input type="text" name="title" placeholder="Masukkan judul buku/audio/video" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#d90d8b] focus:ring-0 transition-all outline-none" required>
                        @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Pengarang / Pembuat</label>
                        <input type="text" name="author" placeholder="Nama pengarang atau pembuat" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#d90d8b] focus:ring-0 transition-all outline-none" required>
                        @error('author') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Deskripsi Ringkas</label>
                        <textarea name="description" rows="4" placeholder="Berikan informasi singkat tentang koleksi ini..." class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#d90d8b] focus:ring-0 transition-all outline-none"></textarea>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Unggah Fail Digital</label>
                        <div class="relative group border-2 border-dashed border-slate-200 rounded-2xl p-8 transition-all hover:border-[#d90d8b]/50 hover:bg-pink-50/20">
                            <input type="file" name="file" class="absolute inset-0 opacity-0 cursor-pointer" required>
                            <div class="text-center">
                                <i class="material-icons text-4xl text-slate-300 group-hover:text-[#d90d8b] transition-colors mb-2">upload_file</i>
                                <p class="text-sm font-medium text-slate-500 group-hover:text-[#d90d8b]">Klik atau seret fail ke sini</p>
                                <p class="text-xs text-slate-400 mt-1">Sesuai kategori: PDF, MP3, atau MP4</p>
                            </div>
                        </div>
                        @error('file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Gambar Sampul (Opsional)</label>
                        <div class="relative group border-2 border-dashed border-slate-200 rounded-2xl p-8 transition-all hover:border-[#d90d8b]/50 hover:bg-pink-50/20">
                            <input type="file" name="cover_image" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                            <div class="text-center">
                                <i class="material-icons text-4xl text-slate-300 group-hover:text-[#d90d8b] transition-colors mb-2">add_photo_alternate</i>
                                <p class="text-sm font-medium text-slate-500 group-hover:text-[#d90d8b]">Gunakan gambar JPEG/PNG</p>
                                <p class="text-xs text-slate-400 mt-1">Maksimum 2MB</p>
                            </div>
                        </div>
                        @error('cover_image') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white font-bold rounded-xl shadow-lg shadow-pink-100 hover:shadow-xl hover:scale-[1.02] transition-all">
                    Simpan Koleksi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
