@extends('layouts.app')

@section('title', 'Profil & Pengaturan')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Profil & Pengaturan</h1>
        <p class="text-slate-500">Kelola informasi profil dan pengaturan akun Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Profile Preview & Avatar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="h-32 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b]"></div>
                <div class="px-6 pb-8 -mt-16 text-center">
                    <div class="relative inline-block group" x-data="{ 
                        preview: '{{ $user->avatar_url }}',
                        uploading: false,
                        async handleFile(e) {
                            const file = e.target.files[0];
                            if (!file) return;

                            this.preview = URL.createObjectURL(file);
                            this.uploading = true;

                            const formData = new FormData();
                            formData.append('avatar', file);
                            formData.append('_token', '{{ csrf_token() }}');

                            try {
                                const response = await fetch('{{ route('profile.avatar.update') }}', {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                });

                                const data = await response.json();
                                if (data.success) {
                                    this.preview = data.avatar_url;
                                    // Also update header avatars if needed (handled by browser cache/refresh usually, 
                                    // but we can update DOM elements with specific IDs if they exist)
                                    const headerAvatars = document.querySelectorAll('img[alt=\'Avatar\']');
                                    headerAvatars.forEach(img => img.src = data.avatar_url);
                                    
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: data.message,
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true
                                    });
                                } else {
                                    throw new Error(data.message || 'Gagal mengupload foto.');
                                }
                            } catch (error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: error.message
                                });
                            } finally {
                                this.uploading = false;
                            }
                        }
                    }">
                        <div x-show="uploading" x-cloak class="absolute inset-0 z-10 flex items-center justify-center bg-white/60 rounded-3xl">
                            <i class="material-icons animate-spin text-[#d90d8b]">sync</i>
                        </div>
                        <img :src="preview" 
                             class="w-32 h-32 rounded-3xl object-cover border-4 border-white shadow-lg mx-auto bg-white" 
                             alt="Avatar Preview">
                        
                        <label for="avatar-input" class="absolute bottom-2 right-2 p-2 rounded-xl bg-white shadow-md border border-slate-100 text-[#d90d8b] hover:scale-110 transition-transform cursor-pointer">
                            <i class="material-icons text-sm">photo_camera</i>
                            <input type="file" id="avatar-input" name="avatar" class="hidden" @change="handleFile">
                        </label>
                    </div>

                    <div class="mt-4">
                        <h2 class="text-xl font-bold text-slate-800 leading-tight">{{ $user->name }}</h2>
                        <p class="text-sm font-semibold text-[#d90d8b] uppercase tracking-wider mt-1">{{ $user->role }}</p>
                    </div>

                    <div class="mt-6 pt-6 border-t border-slate-50 flex justify-center gap-4 text-sm text-slate-500 font-medium">
                        <div class="flex items-center gap-1">
                            <i class="material-icons text-slate-300 text-lg">alternate_email</i>
                            <span>{{ $user->username }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role Specific Info -->
            @if($user->fungsionaris)
                <div class="mt-6 bg-slate-900 rounded-3xl p-6 text-white shadow-lg shadow-slate-200">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Informasi Kepegawaian</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">NIP</p>
                            <p class="font-bold">{{ $user->fungsionaris->nip }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">Jabatan</p>
                            <p class="font-bold">{{ ucfirst($user->fungsionaris->jabatan) }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">Posisi</p>
                            <p class="font-bold">{{ $user->fungsionaris->posisi }}</p>
                        </div>
                    </div>
                </div>
            @elseif($user->siswa)
                <div class="mt-6 bg-slate-900 rounded-3xl p-6 text-white shadow-lg shadow-slate-200">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Informasi Siswa</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">NISN / NIPD</p>
                            <p class="font-bold">{{ $user->siswa->nisn }} / {{ $user->siswa->nipd }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">Kelas</p>
                            <p class="font-bold">{{ $user->siswa->kelas->nama_kelas ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right: Edit Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                <form id="profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Profile Information -->
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-lg bg-pink-50 text-[#d90d8b] flex items-center justify-center">
                                    <i class="material-icons text-lg">person</i>
                                </span>
                                Informasi Profil
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <x-form-input label="Nama Lengkap" name="name" value="{{ old('name', $user->name) }}" required />
                                </div>
                                <div class="space-y-2">
                                    <x-form-input label="Username" name="username" value="{{ old('username', $user->username) }}" required />
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-sm font-bold text-slate-700">Email Utama (Read-only)</label>
                                    <div class="flex items-center gap-3 px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-slate-400">
                                        <i class="material-icons text-xl">lock_outline</i>
                                        <span class="font-medium">{{ $user->email }}</span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 italic">Email diatur secara otomatis oleh sistem dan tidak dapat diubah.</p>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100 my-8"></div>

                        <!-- Security -->
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center">
                                    <i class="material-icons text-lg">security</i>
                                </span>
                                Keamanan Akun
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <x-form-input type="password" label="Password Baru" name="password" placeholder="Kosongkan jika tidak diubah" />
                                </div>
                                <div class="space-y-2">
                                    <x-form-input type="password" label="Konfirmasi Password Baru" name="password_confirmation" placeholder="Ulangi password baru" />
                                </div>
                            </div>
                            <p class="mt-4 text-[11px] text-slate-500 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <i class="material-icons text-[14px] align-middle mr-1 text-slate-400">info_outline</i>
                                Pastikan password Anda kuat dengan kombinasi huruf, angka, dan karakter spesial. Minimal 8 karakter.
                            </p>
                        </div>

                        <div class="pt-6 flex justify-end gap-4">
                            <button type="button" onclick="window.history.back()" class="px-6 py-3 rounded-2xl font-bold text-slate-500 hover:bg-slate-50 transition-all">
                                Batal
                            </button>
                            <button type="submit" class="px-8 py-3 rounded-2xl bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-95 transition-all">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
