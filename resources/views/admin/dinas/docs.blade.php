@extends('layouts.app')

@section('title', 'Dokumentasi Dinas - Literasia')

@section('content')
<div x-data="{ activeSection: 'dashboard' }" class="flex flex-col lg:flex-row gap-8">
    <!-- Sidebar Dokumentasi -->
    <aside class="lg:w-72 flex-shrink-0">
        <div class="sticky top-28 bg-white rounded-[2rem] border border-slate-100 p-6 shadow-sm">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 px-4">Menu Panduan</h3>
            <nav class="space-y-1">
                <template x-for="item in [
                    { id: 'dashboard', label: 'Dashboard Utama', icon: 'dashboard' },
                    { id: 'manajemen', label: 'Manajemen Sekolah', icon: 'admin_panel_settings' },
                    { id: 'statistik', label: 'Statistik Siswa', icon: 'analytics' },
                    { id: 'sekolah', label: 'Data Sekolah', icon: 'domain' },
                    { id: 'siswa', label: 'Data Siswa Global', icon: 'groups' },
                    { id: 'guru', label: 'Integrasi Data Guru', icon: 'sync' },
                    { id: 'library', label: 'E-Library Global', icon: 'local_library' },
                    { id: 'settings', label: 'Pengaturan Aplikasi', icon: 'settings' }
                ]">
                    <button 
                        @click="activeSection = item.id"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                        :class="activeSection === item.id ? 'bg-pink-50 text-[#d90d8b]' : 'text-slate-600 hover:bg-slate-50'"
                    >
                        <i class="material-icons text-[20px]" x-text="item.icon"></i>
                        <span x-text="item.label"></span>
                    </button>
                </template>
            </nav>
        </div>
    </aside>

    <!-- Content Dokumentasi -->
    <main class="flex-grow">
        <div class="bg-white rounded-[2.5rem] border border-slate-100 p-10 shadow-sm min-h-[600px]">
            <!-- Dashboard Section -->
            <div x-show="activeSection === 'dashboard'" x-cloak class="space-y-8 animate-fadeIn">
                <div class="border-b border-slate-50 pb-6">
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight">Dashboard Utama</h2>
                    <p class="text-slate-500 font-medium mt-2">Gambaran umum statistik pendidikan di seluruh wilayah naungan.</p>
                </div>

                <div class="prose prose-slate max-w-none">
                    <h4 class="text-lg font-bold text-slate-800 mb-4">Langkah-Langkah Penggunaan:</h4>
                    <ol class="list-decimal pl-5 space-y-4 text-slate-600 font-medium">
                        <li>Buka menu <strong>Dashboard</strong> pada sidebar kiri.</li>
                        <li>Perhatikan panel <strong>Statistik Utama</strong> yang menampilkan jumlah total sekolah, siswa, dan guru secara nasional.</li>
                        <li>Gunakan fitur <strong>Peta Sebaran Sekolah</strong> untuk melihat lokasi geografis sekolah. Anda dapat menggunakan filter jenjang (SD/SMP/SMA) pada peta.</li>
                        <li>Cek daftar <strong>Registrasi Sekolah Terbaru</strong> untuk memberikan respon cepat terhadap pendaftaran sekolah baru.</li>
                    </ol>
                </div>

                <div class="mt-10 p-1 bg-slate-50 rounded-[2rem] border border-slate-100 overflow-hidden group">
                    <div class="aspect-video bg-slate-200 flex flex-col items-center justify-center text-slate-400 relative overflow-hidden">
                        <i class="material-icons text-6xl mb-4 group-hover:scale-110 transition-transform">image</i>
                        <p class="font-bold text-xs uppercase tracking-widest">Screenshot: dashboard_dinas.jpg</p>
                        <div class="absolute inset-0 bg-slate-900/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                </div>
            </div>

            <!-- Manajemen Sekolah Section -->
            <div x-show="activeSection === 'manajemen'" x-cloak class="space-y-8 animate-fadeIn">
                <div class="border-b border-slate-50 pb-6">
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen Sekolah</h2>
                    <p class="text-slate-500 font-medium mt-2">Proses verifikasi, persetujuan, dan pengelolaan aktivasi sekolah.</p>
                </div>

                <div class="prose prose-slate max-w-none">
                    <h4 class="text-lg font-bold text-slate-800 mb-4">Langkah-Langkah Penggunaan:</h4>
                    <ol class="list-decimal pl-5 space-y-4 text-slate-600 font-medium">
                        <li>Gunakan <strong>Tab Filter</strong> (Semua, Menunggu, Disetujui) untuk mengelompokkan data sekolah.</li>
                        <li>Untuk menyetujui sekolah baru, klik tombol <strong>"SETUJUI"</strong> pada kartu sekolah di tab Menunggu.</li>
                        <li>Anda dapat mencari sekolah tertentu berdasarkan Nama atau NPSN menggunakan bilah pencarian di bagian atas.</li>
                        <li>Untuk menonaktifkan sekolah yang melanggar ketentuan, klik tombol <strong>"NONAKTIFKAN"</strong>.</li>
                    </ol>
                </div>

                <div class="mt-10 p-1 bg-slate-50 rounded-[2rem] border border-slate-100 overflow-hidden group">
                    <div class="aspect-video bg-slate-200 flex flex-col items-center justify-center text-slate-400 relative overflow-hidden">
                        <i class="material-icons text-6xl mb-4">image</i>
                        <p class="font-bold text-xs uppercase tracking-widest">Screenshot: manajemen_sekolah.jpg</p>
                    </div>
                </div>
            </div>

            <!-- Statistik Siswa Section -->
            <div x-show="activeSection === 'statistik'" x-cloak class="space-y-8 animate-fadeIn">
                <div class="border-b border-slate-50 pb-6">
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight">Statistik Siswa</h2>
                    <p class="text-slate-500 font-medium mt-2">Analisis mendalam mengenai demografi dan distribusi siswa.</p>
                </div>

                <div class="prose prose-slate max-w-none">
                    <h4 class="text-lg font-bold text-slate-800 mb-4">Langkah-Langkah Penggunaan:</h4>
                    <ol class="list-decimal pl-5 space-y-4 text-slate-600 font-medium">
                        <li>Buka menu <strong>Statistik Siswa</strong>.</li>
                        <li>Pilih <strong>Jenjang Pendidikan</strong> (SD/SMP/SMA) untuk melihat data yang spesifik.</li>
                        <li>Perhatikan grafik <strong>Distribusi Gender</strong> dan <strong>Pertumbuhan Siswa</strong> tahunan.</li>
                        <li>Data ini dapat digunakan sebagai dasar pengambilan kebijakan alokasi sumber daya pendidikan.</li>
                    </ol>
                </div>

                <div class="mt-10 p-1 bg-slate-50 rounded-[2rem] border border-slate-100 overflow-hidden group">
                    <div class="aspect-video bg-slate-200 flex flex-col items-center justify-center text-slate-400 relative overflow-hidden">
                        <i class="material-icons text-6xl mb-4">image</i>
                        <p class="font-bold text-xs uppercase tracking-widest">Screenshot: statistik_siswa.jpg</p>
                    </div>
                </div>
            </div>

            <!-- Data Sekolah Section -->
            <div x-show="activeSection === 'sekolah'" x-cloak class="space-y-8 animate-fadeIn">
                <div class="border-b border-slate-50 pb-6">
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight">Data Sekolah</h2>
                    <p class="text-slate-500 font-medium mt-2">Pengelolaan data master instansi pendidikan dan akun administrator sekolah.</p>
                </div>

                <div class="prose prose-slate max-w-none">
                    <h4 class="text-lg font-bold text-slate-800 mb-4">Langkah-Langkah Penggunaan:</h4>
                    <ul class="list-disc pl-5 space-y-4 text-slate-600 font-medium">
                        <li><strong>Tambah Sekolah:</strong> Klik tombol "Tambah Sekolah" dan lengkapi formulir (Nama, NPSN, Wilayah, Koordinat GPS).</li>
                        <li><strong>Import Excel:</strong> Gunakan fitur Import untuk mengunggah banyak data sekolah sekaligus menggunakan template yang disediakan.</li>
                        <li><strong>Generate Akun:</strong> Untuk sekolah baru, klik "Generate Akun" agar sistem membuatkan akun administrator sekolah secara otomatis.</li>
                        <li><strong>Reset Password:</strong> Jika administrator sekolah lupa password, Anda dapat meresetnya dari daftar aksi di tabel sekolah.</li>
                    </ul>
                </div>

                <div class="mt-10 p-1 bg-slate-50 rounded-[2rem] border border-slate-100 overflow-hidden group">
                    <div class="aspect-video bg-slate-200 flex flex-col items-center justify-center text-slate-400 relative overflow-hidden">
                        <i class="material-icons text-6xl mb-4">image</i>
                        <p class="font-bold text-xs uppercase tracking-widest">Screenshot: data_sekolah.jpg</p>
                    </div>
                </div>
            </div>

            <!-- Integrasi Guru Section -->
            <div x-show="activeSection === 'guru'" x-cloak class="space-y-8 animate-fadeIn">
                <div class="border-b border-slate-50 pb-6">
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight">Integrasi Data Guru</h2>
                    <p class="text-slate-500 font-medium mt-2">Sinkronisasi data pendidik dari database Dinas ke masing-masing unit sekolah.</p>
                </div>

                <div class="prose prose-slate max-w-none">
                    <h4 class="text-lg font-bold text-slate-800 mb-4">Cara Melakukan Sinkronisasi:</h4>
                    <ol class="list-decimal pl-5 space-y-4 text-slate-600 font-medium">
                        <li>Pastikan Master Data Guru sudah diimport melalui menu <strong>Master Guru Dinas</strong>.</li>
                        <li>Buka menu <strong>Integrasi Data Guru</strong>.</li>
                        <li>Pilih <strong>Sekolah Tujuan</strong> dari dropdown.</li>
                        <li>Sistem akan menampilkan daftar guru yang bertugas di sekolah tersebut (berdasarkan NPSN).</li>
                        <li>Pilih data guru yang ingin dikirim ke database sekolah, lalu klik <strong>" Sinkronisasi Data"</strong>.</li>
                    </ol>
                </div>

                <div class="mt-10 p-1 bg-slate-50 rounded-[2rem] border border-slate-100 overflow-hidden group">
                    <div class="aspect-video bg-slate-200 flex flex-col items-center justify-center text-slate-400 relative overflow-hidden">
                        <i class="material-icons text-6xl mb-4">image</i>
                        <p class="font-bold text-xs uppercase tracking-widest">Screenshot: integrasi_guru.jpg</p>
                    </div>
                </div>
            </div>

            <!-- E-Library Section -->
            <div x-show="activeSection === 'library'" x-cloak class="space-y-8 animate-fadeIn">
                <div class="border-b border-slate-50 pb-6">
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight">E-Library Global</h2>
                    <p class="text-slate-500 font-medium mt-2">Distribusi konten literasi digital ke seluruh sekolah.</p>
                </div>

                <div class="prose prose-slate max-w-none">
                    <h4 class="text-lg font-bold text-slate-800 mb-4">Pengelolaan Konten:</h4>
                    <ul class="list-disc pl-5 space-y-4 text-slate-600 font-medium">
                        <li>Unggah buku (PDF), audio, atau video melalui menu E-Library Global.</li>
                        <li>Konten yang diunggah oleh Dinas akan secara otomatis muncul di Dashboard dan Library milik **seluruh sekolah**.</li>
                        <li>Hal ini memastikan standar materi literasi yang seragam di seluruh wilayah.</li>
                    </ul>
                </div>

                <div class="mt-10 p-1 bg-slate-50 rounded-[2rem] border border-slate-100 overflow-hidden group">
                    <div class="aspect-video bg-slate-200 flex flex-col items-center justify-center text-slate-400 relative overflow-hidden">
                        <i class="material-icons text-6xl mb-4">image</i>
                        <p class="font-bold text-xs uppercase tracking-widest">Screenshot: library_global.jpg</p>
                    </div>
                </div>
            </div>

            <!-- Settings Section -->
            <div x-show="activeSection === 'settings'" x-cloak class="space-y-8 animate-fadeIn">
                <div class="border-b border-slate-50 pb-6">
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight">Pengaturan Aplikasi</h2>
                    <p class="text-slate-500 font-medium mt-2">Konfigurasi sistem, branding, dan akses pendaftaran.</p>
                </div>

                <div class="prose prose-slate max-w-none">
                    <h4 class="text-lg font-bold text-slate-800 mb-4">Pengaturan Utama:</h4>
                    <ul class="list-disc pl-5 space-y-4 text-slate-600 font-medium">
                        <li><strong>Branding:</strong> Ubah Nama Aplikasi dan Logo Dinas yang akan muncul di header seluruh platform.</li>
                        <li><strong>Registrasi:</strong> Aktifkan atau nonaktifkan fitur "Pendaftaran Sekolah" pada halaman depan.</li>
                        <li><strong>Maintenance:</strong> Fitur untuk mematikan akses sementara jika sedang dilakukan pemeliharaan sistem.</li>
                    </ul>
                </div>

                <div class="mt-10 p-1 bg-slate-50 rounded-[2rem] border border-slate-100 overflow-hidden group">
                    <div class="aspect-video bg-slate-200 flex flex-col items-center justify-center text-slate-400 relative overflow-hidden">
                        <i class="material-icons text-6xl mb-4">image</i>
                        <p class="font-bold text-xs uppercase tracking-widest">Screenshot: pengaturan_dinas.jpg</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@push('styles')
<style>
    .animate-fadeIn {
        animation: fadeIn 0.4s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush
@endsection
