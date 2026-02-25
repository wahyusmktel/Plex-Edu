@extends('layouts.app')

@section('title', 'Edit Koleksi - Literasia')

@section('content')
<div x-data="libraryCreate()" class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('library.index') }}" class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-[#d90d8b] hover:border-[#d90d8b] transition-all">
            <i class="material-icons">arrow_back</i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Koleksi E-Library</h1>
            <p class="text-slate-500">Ubah informasi atau fail materi digital perpustakaan.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <form id="libraryForm" action="{{ route('library.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Kategori Koleksi</label>
                        <select name="category" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#d90d8b] focus:ring-0 transition-all outline-none bg-slate-50" required>
                            <option value="book" {{ old('category', $item->category) == 'book' ? 'selected' : '' }}>E-Book (PDF)</option>
                            <option value="audio" {{ old('category', $item->category) == 'audio' ? 'selected' : '' }}>Audio Book (MP3)</option>
                            <option value="video" {{ old('category', $item->category) == 'video' ? 'selected' : '' }}>Video Book (MP4/WebM)</option>
                        </select>
                        @error('category') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Judul Koleksi</label>
                        <input type="text" name="title" value="{{ old('title', $item->title) }}" placeholder="Masukkan judul buku/audio/video" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#d90d8b] focus:ring-0 transition-all outline-none" required>
                        @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Pengarang / Pembuat</label>
                        <input type="text" name="author" value="{{ old('author', $item->author) }}" placeholder="Nama pengarang atau pembuat" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#d90d8b] focus:ring-0 transition-all outline-none" required>
                        @error('author') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Kategori / Label (Opsional)</label>
                        <input type="text" name="kategori" value="{{ old('kategori', $item->kategori) }}" placeholder="Contoh: Fiksi, Sains, Pelajaran" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#d90d8b] focus:ring-0 transition-all outline-none">
                        @error('kategori') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Deskripsi Ringkas</label>
                        <textarea name="description" rows="4" placeholder="Berikan informasi singkat tentang koleksi ini..." class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#d90d8b] focus:ring-0 transition-all outline-none">{{ old('description', $item->description) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Visibilitas Koleksi</label>
                        <div class="flex gap-4">
                            <label class="flex-1 relative">
                                <input type="radio" name="visibility" value="public" class="peer sr-only" {{ old('visibility', $item->visibility) == 'public' ? 'checked' : '' }}>
                                <div class="p-4 rounded-xl border-2 border-slate-200 cursor-pointer transition-all peer-checked:border-[#d90d8b] peer-checked:bg-pink-50 hover:bg-slate-50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 peer-checked:bg-[#d90d8b] peer-checked:text-white transition-colors">
                                            <i class="material-icons">public</i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-700">Publik</p>
                                            <p class="text-[10px] text-slate-500 mt-0.5">Semua sekolah dapat melihat</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            <label class="flex-1 relative">
                                <input type="radio" name="visibility" value="private" class="peer sr-only" {{ old('visibility', $item->visibility) == 'private' ? 'checked' : '' }}>
                                <div class="p-4 rounded-xl border-2 border-slate-200 cursor-pointer transition-all peer-checked:border-[#d90d8b] peer-checked:bg-pink-50 hover:bg-slate-50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 peer-checked:bg-[#d90d8b] peer-checked:text-white transition-colors">
                                            <i class="material-icons">lock</i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-700">Privat</p>
                                            <p class="text-[10px] text-slate-500 mt-0.5">Khusus sekolah Anda saja</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('visibility') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Unggah Fail Digital</label>
                        <div class="relative group border-2 border-dashed border-slate-200 rounded-2xl p-8 transition-all hover:border-[#d90d8b]/50 hover:bg-pink-50/20">
                            <input type="file" name="file" id="digitalFile" class="absolute inset-0 opacity-0 cursor-pointer" @change="handleFileSelect($event)">
                            <div class="text-center" x-show="!fileName">
                                @if($item->file_path)
                                    <i class="material-icons text-4xl text-emerald-500 mb-2">check_circle</i>
                                    <p class="text-sm font-medium text-slate-700">Fail Saat Ini: {{ basename($item->file_path) }}</p>
                                    <p class="text-xs text-slate-400 mt-1 hover:text-[#d90d8b]">Klik atau seret fail ke sini untuk mengganti</p>
                                @else
                                    <i class="material-icons text-4xl text-slate-300 group-hover:text-[#d90d8b] transition-colors mb-2">upload_file</i>
                                    <p class="text-sm font-medium text-slate-500 group-hover:text-[#d90d8b]">Klik atau seret fail ke sini</p>
                                    <p class="text-xs text-slate-400 mt-1">Sesuai kategori: PDF, MP3, atau MP4</p>
                                @endif
                                <p class="text-xs text-slate-400 font-bold mt-1">Maksimum 500MB</p>
                            </div>
                            <div x-show="fileName" class="text-center">
                                <i class="material-icons text-4xl text-emerald-500 mb-2">check_circle</i>
                                <p class="text-sm font-medium text-slate-700" x-text="fileName"></p>
                                <p class="text-xs text-slate-400 mt-1" x-text="fileSize"></p>
                            </div>
                        </div>
                        @error('file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Upload Progress Indicator -->
                    <div x-show="uploading" class="bg-gradient-to-br from-slate-50 to-pink-50 rounded-2xl p-6 border border-pink-100">
                        <div class="flex items-center gap-5">
                            <div class="relative w-20 h-20 flex-shrink-0">
                                <svg class="w-20 h-20 transform -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-slate-200" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    <path class="text-[#d90d8b]" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"
                                          :stroke-dasharray="uploadProgress + ', 100'"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-lg font-black text-[#d90d8b]" x-text="Math.round(uploadProgress) + '%'"></span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-700 truncate" x-text="fileName"></p>
                                <p class="text-xs text-slate-400 mt-1">Mengunggah file...</p>
                                <div class="w-full h-2.5 bg-slate-200 rounded-full mt-3 overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] rounded-full transition-all duration-300 ease-out" :style="'width: ' + uploadProgress + '%'"></div>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-2" x-text="'Kecepatan upload bergantung pada koneksi internet Anda'"></p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Gambar Sampul (Opsional)</label>
                        <div class="relative group border-2 border-dashed border-slate-200 rounded-2xl p-8 transition-all hover:border-[#d90d8b]/50 hover:bg-pink-50/20">
                            <input type="file" name="cover_image" id="coverImage" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" @change="handleCoverSelect($event)">
                            <div class="text-center" x-show="!coverName">
                                @if($item->cover_image)
                                    <img src="{{ Storage::url($item->cover_image) }}" class="w-24 h-32 object-cover mx-auto rounded-lg mb-4 shadow-md">
                                    <p class="text-sm font-medium text-slate-500 group-hover:text-[#d90d8b]">Klik untuk mangganti gambar sampul</p>
                                @else
                                    <i class="material-icons text-4xl text-slate-300 group-hover:text-[#d90d8b] transition-colors mb-2">add_photo_alternate</i>
                                    <p class="text-sm font-medium text-slate-500 group-hover:text-[#d90d8b]">Gunakan gambar JPEG/PNG</p>
                                @endif
                                <p class="text-xs text-slate-400 mt-1">Maksimum 2MB</p>
                            </div>
                            <div x-show="coverName" class="text-center">
                                <i class="material-icons text-4xl text-[#d90d8b] mb-2">image</i>
                                <p class="text-sm font-medium text-slate-700" x-text="coverName"></p>
                                <p class="text-xs text-slate-400 mt-1" x-text="coverSize"></p>
                            </div>
                        </div>
                        @error('cover_image') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="button" @click="submitForm()" :disabled="uploading" class="px-8 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white font-bold rounded-xl shadow-lg shadow-pink-100 hover:shadow-xl hover:scale-[1.02] transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                    <span x-show="!uploading">Perbarui Koleksi</span>
                    <span x-show="uploading" class="flex items-center gap-2">
                        <i class="material-icons text-lg animate-spin">autorenew</i> Mengunggah...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function libraryCreate() {
        return {
            fileName: '',
            fileSize: '',
            coverName: '',
            coverSize: '',
            uploading: false,
            uploadProgress: 0,

            handleFileSelect(event) {
                if (event.target.files.length > 0) {
                    const file = event.target.files[0];
                    this.fileName = file.name;
                    const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    this.fileSize = sizeMB + ' MB';
                }
            },

            handleCoverSelect(event) {
                if (event.target.files.length > 0) {
                    const file = event.target.files[0];
                    
                    // Check if file exceeds 2MB limit (2 * 1024 * 1024 bytes)
                    if (file.size > 2097152) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ukuran Gambar Terlalu Besar',
                            text: 'Maksimum ukuran gambar sampul adalah 2MB. Silakan pilih gambar lain.',
                            confirmButtonColor: '#d90d8b'
                        });
                        // Reset the input
                        event.target.value = '';
                        this.coverName = '';
                        this.coverSize = '';
                        return;
                    }

                    this.coverName = file.name;
                    const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    this.coverSize = sizeMB + ' MB';
                } else {
                    this.coverName = '';
                    this.coverSize = '';
                }
            },

            async submitForm() {
                const form = document.getElementById('libraryForm');
                const digitalFile = document.getElementById('digitalFile').files[0];
                const coverFile = document.getElementById('coverImage')?.files[0];

                // Extra safety check for cover image size before upload
                if (coverFile && coverFile.size > 2097152) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Ukuran Gambar Terlalu Besar',
                        text: 'Maksimum ukuran gambar sampul adalah 2MB. Silakan pilih gambar yang lebih kecil.',
                        confirmButtonColor: '#d90d8b'
                    });
                    return;
                }

                this.uploading = true;
                this.uploadProgress = 0;

                try {
                    // If a new file is uploaded, process cloud upload
                    if (digitalFile) {
                    // 1. Get Signed URL from Backend
                    const signedResponse = await $.ajax({
                        url: '{{ route("library.signed-url") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            file_name: digitalFile.name,
                            file_type: digitalFile.type,
                            category: form.category.value
                        }
                    });

                    // Check if signed URL is supported by the storage driver
                    if (signedResponse.supported) {
                        const uploadUrl = signedResponse.url;
                        const filePath = signedResponse.path;

                        // 2. Direct Upload to Cloud Storage using XMLHttpRequest (to track progress)
                        await new Promise((resolve, reject) => {
                            const xhr = new XMLHttpRequest();
                            xhr.open('PUT', uploadUrl, true);
                            xhr.setRequestHeader('Content-Type', digitalFile.type);
                            
                            xhr.upload.onprogress = (e) => {
                                if (e.lengthComputable) {
                                    this.uploadProgress = (e.loaded / e.total) * 100;
                                }
                            };
                            
                            xhr.onload = () => {
                                if (xhr.status === 200 || xhr.status === 201) resolve();
                                else reject(new Error('Gagal mengunggah file ke cloud storage.'));
                            };
                            
                            xhr.onerror = () => reject(new Error('Kesalahan jaringan saat mengunggah ke cloud.'));
                            xhr.send(digitalFile);
                        });

                        // 3. Complete the process by saving metadata to Laravel
                        const formData = new FormData(form);
                        formData.append('file_path', filePath);
                        formData.delete('file'); // Don't send the heavy file to Laravel

                        await $.ajax({
                            url: form.action,
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false
                        });
                    } else {
                        // FALLBACK: Traditional multipart upload to Laravel
                        const formData = new FormData(form);
                        
                        await new Promise((resolve, reject) => {
                            const xhr = new XMLHttpRequest();
                            xhr.open('POST', form.action, true);
                            
                            xhr.upload.onprogress = (e) => {
                                if (e.lengthComputable) {
                                    this.uploadProgress = (e.loaded / e.total) * 100;
                                }
                            };
                            
                            xhr.onload = () => {
                                if (xhr.status === 200 || xhr.status === 302) resolve();
                                else {
                                    try {
                                        const err = JSON.parse(xhr.responseText);
                                        reject(new Error(err.message || 'Gagal menyimpan data ke server.'));
                                    } catch(e) {
                                        reject(new Error('Gagal menyimpan data ke server.'));
                                    }
                                }
                            };
                            
                            xhr.onerror = () => reject(new Error('Kesalahan jaringan saat mengunggah.'));
                            xhr.send(formData);
                        });
                    }
                    } else {
                        // No new file, just submit the metadata
                        const formData = new FormData(form);
                        
                        await new Promise((resolve, reject) => {
                            const xhr = new XMLHttpRequest();
                            xhr.open('POST', form.action, true);
                            
                            xhr.onload = () => {
                                if (xhr.status === 200 || xhr.status === 302 || xhr.status === 201) resolve();
                                else {
                                    try {
                                        const err = JSON.parse(xhr.responseText);
                                        reject(new Error(err.message || 'Gagal memperbarui data ke server.'));
                                    } catch(e) {
                                        reject(new Error('Gagal memperbarui data ke server.'));
                                    }
                                }
                            };
                            
                            xhr.onerror = () => reject(new Error('Kesalahan jaringan saat memperbarui.'));
                            xhr.send(formData);
                        });
                    }

                    this.uploading = false;
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Koleksi berhasil diperbarui.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '{{ route("library.index") }}';
                    });

                } catch (err) {
                    this.uploading = false;
                    console.error('Upload Error:', err);
                    
                    let msg = 'Terjadi kesalahan saat mengunggah.';
                    if (err.message) msg = err.message;
                    if (err.responseJSON?.message) msg = err.responseJSON.message;
                    if (err.statusText) msg += ' (' + err.statusText + ')';
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: msg,
                        footer: '<p class="text-xs text-slate-400 text-center">Bagi admin: Cek Console (F12) untuk detail teknis atau pastikan konfigurasi CORS di bucket GCS sudah benar.</p>'
                    });
                }
            }
        }
    }
</script>
@endsection
