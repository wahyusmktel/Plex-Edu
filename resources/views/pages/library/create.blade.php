@extends('layouts.app')

@section('title', 'Tambah Koleksi - Literasia')

@section('content')
<div x-data="libraryCreate()" class="max-w-4xl mx-auto space-y-6">
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
        <form id="libraryForm" action="{{ route('library.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
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
                        <label class="text-sm font-bold text-slate-700 ml-1">Kategori / Label (Opsional)</label>
                        <input type="text" name="kategori" placeholder="Contoh: Fiksi, Sains, Pelajaran" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#d90d8b] focus:ring-0 transition-all outline-none">
                        @error('kategori') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
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
                            <input type="file" name="file" id="digitalFile" class="absolute inset-0 opacity-0 cursor-pointer" @change="handleFileSelect($event)" required>
                            <div class="text-center" x-show="!fileName">
                                <i class="material-icons text-4xl text-slate-300 group-hover:text-[#d90d8b] transition-colors mb-2">upload_file</i>
                                <p class="text-sm font-medium text-slate-500 group-hover:text-[#d90d8b]">Klik atau seret fail ke sini</p>
                                <p class="text-xs text-slate-400 mt-1">Sesuai kategori: PDF, MP3, atau MP4</p>
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
                <button type="button" @click="submitForm()" :disabled="uploading" class="px-8 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white font-bold rounded-xl shadow-lg shadow-pink-100 hover:shadow-xl hover:scale-[1.02] transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                    <span x-show="!uploading">Simpan Koleksi</span>
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

            async submitForm() {
                const form = document.getElementById('libraryForm');
                const digitalFile = document.getElementById('digitalFile').files[0];
                
                // For new items, file is required
                if (!digitalFile) {
                    Swal.fire('Oops...', 'Silakan pilih fail digital.', 'error');
                    return;
                }

                this.uploading = true;
                this.uploadProgress = 0;

                try {
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

                    this.uploading = false;
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Koleksi berhasil ditambahkan.',
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
