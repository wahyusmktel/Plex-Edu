<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $app_settings->app_name }} - Modern School Management System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        h1, h2, h3, h4, .font-outfit {
            font-family: 'Outfit', sans-serif;
        }
        .glossy-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
            transition: all 0.5s ease;
        }
        .glossy-card:hover {
            background: linear-gradient(135deg, #d90d8b 0%, #ba80e8 100%);
            border-color: transparent;
            transform: translateY(-10px);
        }
        .text-gradient {
            background: linear-gradient(135deg, #d90d8b 0%, #ba80e8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .bg-gradient-main {
            background: linear-gradient(135deg, #d90d8b 0%, #ba80e8 100%);
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    </style>
    <!-- Add FontAwesome for social icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-900 selection:bg-pink-100 selection:text-pink-600">
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between bg-white/70 backdrop-blur-xl border border-white/30 shadow-lg px-6 py-4 rounded-[2rem]">
            <div class="flex items-center gap-3">
                @if($app_settings->app_logo)
                    <img src="{{ $app_settings->logo_url }}" class="w-10 h-10 rounded-xl object-contain bg-white shadow-md p-2" alt="Logo">
                @else
                    <div class="w-10 h-10 bg-gradient-main rounded-xl flex items-center justify-center shadow-lg">
                        <i class="material-icons text-white">school</i>
                    </div>
                @endif
                <span class="text-2xl font-black font-outfit tracking-tight text-gradient uppercase">{{ $app_settings->app_name }}</span>
            </div>
            
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-sm font-bold text-slate-500 hover:text-pink-600 transition-colors">Fitur</a>
                <a href="#news" class="text-sm font-bold text-slate-500 hover:text-pink-600 transition-colors">Berita</a>
                <a href="#about" class="text-sm font-bold text-slate-500 hover:text-pink-600 transition-colors">Tentang</a>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-gradient-main text-white px-6 py-3 rounded-2xl text-sm font-black shadow-lg shadow-pink-200 hover:scale-105 transition-transform active:scale-95">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-black text-slate-600 px-6 py-3 hover:bg-slate-100 rounded-2xl transition-all">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-gradient-main text-white px-6 py-3 rounded-2xl text-sm font-black shadow-lg shadow-pink-200 hover:scale-105 transition-transform active:scale-95">Daftar Sekarang</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="min-h-screen pt-32 pb-20 px-6 overflow-hidden relative">
        <div class="absolute top-0 right-0 -mr-40 -mt-20 w-[600px] h-[600px] bg-pink-100 rounded-full blur-[100px] opacity-60"></div>
        <div class="absolute bottom-0 left-0 -ml-40 -mb-20 w-[500px] h-[500px] bg-purple-100 rounded-full blur-[100px] opacity-60"></div>

        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-16 relative z-10">
            <div class="lg:w-1/2 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-pink-50 rounded-full mb-6 border border-pink-100">
                    <span class="w-2 h-2 rounded-full bg-pink-600 animate-pulse"></span>
                    <span class="text-xs font-black text-pink-600 uppercase tracking-widest">Sistem Manajemen Sekolah Modern</span>
                </div>
                <h1 class="text-5xl lg:text-7xl font-black text-slate-800 leading-[1.1] mb-8">
                    Transformasi Digital <br>
                    <span class="text-gradient">Masa Depan Edukasi</span>
                </h1>
                <p class="text-lg text-slate-500 font-medium mb-10 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Satu platform terintegrasi untuk segala kebutuhan sekolah anda. Mulai dari manajemen data, ujian berbasis komputer (CBT), hingga e-learning interaktif.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-10 py-5 bg-gradient-main text-white rounded-[2rem] font-black text-lg shadow-2xl shadow-pink-200 hover:scale-105 transition-transform active:scale-95 flex items-center justify-center gap-2">
                        Get Started <i class="material-icons">arrow_forward</i>
                    </a>
                    <a href="#features" class="w-full sm:w-auto px-10 py-5 glossy-card text-slate-600 rounded-[2rem] font-black text-lg hover:bg-white transition-all flex items-center justify-center gap-2">
                        Pelajari Fitur
                    </a>
                </div>
                <div class="mt-12 flex items-center justify-center lg:justify-start gap-6">
                    <div class="flex -space-x-3">
                        @for($i=1; $i<=4; $i++)
                            <div class="w-12 h-12 rounded-full border-4 border-white overflow-hidden shadow-sm">
                                <img src="https://i.pravatar.cc/100?img={{$i+10}}" alt="Avatar">
                            </div>
                        @endfor
                        <div class="w-12 h-12 rounded-full border-4 border-white bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-400">
                            +1k
                        </div>
                    </div>
                    <div class="text-left leading-none">
                        <div class="flex text-amber-400 mb-1">
                            @for($i=0; $i<5; $i++)<i class="material-icons text-[18px]">star</i>@endfor
                        </div>
                        <p class="text-xs font-black text-slate-500 uppercase tracking-tighter">Dipercaya oleh 1000+ Pengguna</p>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2 relative">
                <div class="absolute inset-0 bg-gradient-main blur-[80px] opacity-20 animate-pulse"></div>
                <img src="{{ asset('assets/images/hero.png') }}" alt="School Hero Illustration" class="w-full max-w-[550px] mx-auto animate-float drop-shadow-2xl relative z-10">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-32 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <h2 class="text-4xl lg:text-5xl font-black text-slate-800 mb-6">Solusi Terpadu <span class="text-gradient">Tanpa Batas</span></h2>
                <p class="text-lg text-slate-500 font-medium max-w-2xl mx-auto leading-relaxed">
                    Dirancang khusus untuk membantu admin, guru, dan siswa dalam satu ekosistem yang intuitif dan aman.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Feature 1 -->
                <div class="glossy-card p-10 rounded-[3rem] group transition-all duration-500 overflow-hidden relative">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-pink-50 rounded-full opacity-50 group-hover:scale-[3] transition-transform duration-700"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-pink-100 group-hover:bg-white rounded-2xl flex items-center justify-center mb-8 transition-colors">
                            <i class="material-icons text-pink-600">dashboard</i>
                        </div>
                        <h3 class="text-2xl font-black mb-4 group-hover:text-white transition-colors">School Management</h3>
                        <p class="text-slate-500 font-medium group-hover:text-pink-50 transition-colors mb-6">
                            Kelola data fungsionaris, siswa, kelas, hingga identitas sekolah dengan mudah dan terorganisir.
                        </p>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center gap-2 text-sm font-bold text-slate-600 group-hover:text-white"><i class="material-icons text-green-500 group-hover:text-white text-base">check_circle</i> Manajemen Siswa</li>
                            <li class="flex items-center gap-2 text-sm font-bold text-slate-600 group-hover:text-white"><i class="material-icons text-green-500 group-hover:text-white text-base">check_circle</i> Daftar Kelas & Guru</li>
                        </ul>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="glossy-card p-10 rounded-[3rem] group transition-all duration-500 overflow-hidden relative">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-purple-50 rounded-full opacity-50 group-hover:scale-[3] transition-transform duration-700"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-purple-100 group-hover:bg-white rounded-2xl flex items-center justify-center mb-8 transition-colors">
                            <i class="material-icons text-purple-600">computer</i>
                        </div>
                        <h3 class="text-2xl font-black mb-4 group-hover:text-white transition-colors">Computer Based Test</h3>
                        <p class="text-slate-500 font-medium group-hover:text-purple-50 transition-colors mb-6">
                            Sistem ujian modern yang aman dengan bank soal terintegrasi dan analisis hasil otomatis.
                        </p>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center gap-2 text-sm font-bold text-slate-600 group-hover:text-white"><i class="material-icons text-green-500 group-hover:text-white text-base">check_circle</i> Bank Soal & Versi</li>
                            <li class="flex items-center gap-2 text-sm font-bold text-slate-600 group-hover:text-white"><i class="material-icons text-green-500 group-hover:text-white text-base">check_circle</i> Real-time Proctoring</li>
                        </ul>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="glossy-card p-10 rounded-[3rem] group transition-all duration-500 overflow-hidden relative">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-50 rounded-full opacity-50 group-hover:scale-[3] transition-transform duration-700"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-blue-100 group-hover:bg-white rounded-2xl flex items-center justify-center mb-8 transition-colors">
                            <i class="material-icons text-blue-600">import_contacts</i>
                        </div>
                        <h3 class="text-2xl font-black mb-4 group-hover:text-white transition-colors">E-Learning & Library</h3>
                        <p class="text-slate-500 font-medium group-hover:text-blue-50 transition-colors mb-6">
                            Akses materi pembelajaran, modul digital, dan perpustakaan dalam satu genggaman fleksibel.
                        </p>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center gap-2 text-sm font-bold text-slate-600 group-hover:text-white"><i class="material-icons text-green-500 group-hover:text-white text-base">check_circle</i> Modul Video & PDF</li>
                            <li class="flex items-center gap-2 text-sm font-bold text-slate-600 group-hover:text-white"><i class="material-icons text-green-500 group-hover:text-white text-base">check_circle</i> Digital Library</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section id="news" class="py-32 px-6 bg-slate-100">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row items-end justify-between mb-16 gap-6">
                <div class="max-w-xl">
                    <h2 class="text-4xl lg:text-5xl font-black text-slate-800 mb-6">Berita Terbaru & <span class="text-gradient">Informasi Terkini</span></h2>
                    <p class="text-lg text-slate-500 font-medium leading-relaxed">
                        Update informasi mengenai prestasi, kegiatan, dan kebijakan sekolah melalui portal berita resmi kami.
                    </p>
                </div>
                <a href="{{ route('berita.index') }}" class="px-8 py-4 glossy-card rounded-2xl text-sm font-black flex items-center gap-2 hover:bg-white transition-all">Lihat Semua Berita <i class="material-icons">arrow_forward</i></a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($news as $item)
                    <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-xl shadow-slate-200 group hover:-translate-y-2 transition-transform duration-300">
                        <div class="h-64 overflow-hidden relative">
                            <img src="{{ $item->thumbnail ? asset('storage/'.$item->thumbnail) : 'https://images.unsplash.com/photo-1546410531-bb4caa6b424d?q=80&w=1000' }}" alt="News Thumbnail" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                            <div class="absolute top-6 left-6 px-4 py-2 bg-white/90 backdrop-blur rounded-xl text-xs font-black text-pink-600 uppercase tracking-widest shadow-sm">
                                Info Sekolah
                            </div>
                        </div>
                        <div class="p-8">
                            <div class="flex items-center gap-4 text-xs font-bold text-slate-400 mb-4 uppercase tracking-widest">
                                <span class="flex items-center gap-1"><i class="material-icons text-[14px]">calendar_today</i> {{ date('d M Y', strtotime($item->tanggal_terbit)) }}</span>
                                <span class="flex items-center gap-1"><i class="material-icons text-[14px]">schedule</i> {{ $item->jam_terbit }}</span>
                            </div>
                            <h3 class="text-xl font-black text-slate-800 mb-4 leading-tight group-hover:text-pink-600 transition-colors">{{ $item->judul }}</h3>
                            <p class="text-slate-500 text-sm font-medium mb-8 line-clamp-2">
                                {{ Str::limit(strip_tags($item->deskripsi), 120) }}
                            </p>
                            <a href="{{ route('berita.show', $item->id) }}" class="text-sm font-black text-slate-800 flex items-center gap-2 group-hover:underline">Baca Selengkapnya <i class="material-icons text-pink-600">east</i></a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-20 text-center">
                        <div class="w-20 h-20 bg-slate-200 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="material-icons text-slate-400 text-4xl">newspaper</i>
                        </div>
                        <p class="text-slate-400 font-black uppercase tracking-widest">Belum ada berita terbaru</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#1b1b18] text-white pt-32 pb-10 px-6 overflow-hidden relative">
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-pink-600 rounded-full blur-[200px] opacity-10"></div>
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-20 mb-20">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-3 mb-8">
                        @if($app_settings->app_logo)
                            <img src="{{ $app_settings->logo_url }}" class="w-12 h-12 rounded-xl object-contain bg-white shadow-md p-2" alt="Logo">
                        @else
                            <div class="w-12 h-12 bg-gradient-main rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="material-icons text-white">school</i>
                            </div>
                        @endif
                        <span class="text-3xl font-black font-outfit tracking-tight text-gradient uppercase">{{ $app_settings->app_name }}</span>
                    </div>
                    <p class="text-slate-400 font-medium leading-relaxed mb-8">
                        Platform manajemen pendidikan terbaik untuk sekolah modern di Indonesia.
                    </p>
                    <div class="flex items-center gap-4">
                        <a href="#" class="w-10 h-10 border border-slate-800 rounded-xl flex items-center justify-center hover:bg-pink-600 hover:border-pink-600 hover:text-white transition-all">
                            <i class="fa-brands fa-facebook-f text-lg"></i>
                        </a>
                        <a href="#" class="w-10 h-10 border border-slate-800 rounded-xl flex items-center justify-center hover:bg-pink-600 hover:border-pink-600 hover:text-white transition-all">
                            <i class="fa-brands fa-x-twitter text-lg"></i>
                        </a>
                        <a href="#" class="w-10 h-10 border border-slate-800 rounded-xl flex items-center justify-center hover:bg-pink-600 hover:border-pink-600 hover:text-white transition-all">
                            <i class="fa-brands fa-instagram text-lg"></i>
                        </a>
                        <a href="#" class="w-10 h-10 border border-slate-800 rounded-xl flex items-center justify-center hover:bg-pink-600 hover:border-pink-600 hover:text-white transition-all">
                            <i class="fa-brands fa-youtube text-lg"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-xl font-black mb-8 font-outfit">Sitemap</h4>
                    <ul class="space-y-4 text-slate-400 font-bold text-sm">
                        <li><a href="#features" class="hover:text-white transition-colors">Fitur</a></li>
                        <li><a href="#news" class="hover:text-white transition-colors">Berita</a></li>
                        <li><a href="#about" class="hover:text-white transition-colors">Tentang Kami</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xl font-black mb-8 font-outfit">Layanan</h4>
                    <ul class="space-y-4 text-slate-400 font-bold text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Management System</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">CBT Platform</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">E-Learning</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xl font-black mb-8 font-outfit">Hubungi Kami</h4>
                    <ul class="space-y-4 text-slate-400 font-bold text-sm">
                        <li class="flex items-start gap-3"><i class="material-icons text-pink-600">location_on</i> Jl. Pendidikan No. 123, Jakarta</li>
                        <li class="flex items-center gap-3"><i class="material-icons text-pink-600">phone</i> +62 21 1234 5678</li>
                        <li class="flex items-center gap-3"><i class="material-icons text-pink-600">email</i> info@plexedu.id</li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-10 border-t border-slate-800 flex flex-col md:flex-row items-center justify-between gap-6">
                <p class="text-slate-500 text-xs font-black uppercase tracking-widest">&copy; 2026 {{ $app_settings->app_name }}. All rights reserved.</p>
                <div class="flex items-center gap-10 text-xs font-black uppercase tracking-widest text-slate-500">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
