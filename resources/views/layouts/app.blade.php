<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: true, mobileMenuOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Literasia')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .text-fill-transparent { -webkit-text-fill-color: transparent; }
    </style>
    @yield('styles')
</head>
<body class="bg-[#f8fafc] font-['Inter'] text-slate-700 antialiased overflow-x-hidden">

    <!-- Sidebar Section -->
    <aside 
        class="fixed left-0 top-0 z-40 h-screen transition-all duration-300 bg-white border-r border-slate-100 shadow-sm overflow-y-auto overflow-x-hidden"
        :class="sidebarOpen ? 'w-72' : 'w-20'"
        @resize.window="if (window.innerWidth < 1024) { sidebarOpen = false } else { sidebarOpen = true }"
    >
        <!-- Logo -->
        <div class="flex items-center gap-4 px-6 py-8 mb-6">
            <div class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white shadow-md shadow-pink-100">
                <i class="material-icons text-2xl">import_contacts</i>
            </div>
            <span 
                x-show="sidebarOpen" 
                x-transition:enter="transition ease-out duration-300" 
                x-transition:enter-start="opacity-0 -translate-x-4" 
                x-transition:enter-end="opacity-100 translate-x-0"
                class="text-2xl font-extrabold tracking-tight bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] bg-clip-text text-transparent"
            >
                LITERASIA
            </span>
        </div>

        <!-- Navigation items -->
        <nav class="px-4 space-y-1.5">
            <x-nav-item icon="dashboard" label="Dashboard Sekolah" :active="Request::is('dashboard')" />
            <x-nav-item icon="assignment_turned_in" label="E-Raport" />
            <x-nav-item icon="menu_book" label="Mata Pelajaran" :active="Request::is('mata-pelajaran*')" href="{{ route('mata-pelajaran.index') }}" />
            <x-nav-item icon="computer" label="CBT" />
            <x-nav-item icon="warning" label="Pelanggaran" />
            <x-nav-item icon="article" label="Berita" />
            <x-nav-item icon="school" label="Sekolah" :active="Request::is('sekolah*')" href="{{ route('sekolah.index') }}" />
            <x-nav-item icon="people" label="Fungsionaris" :active="Request::is('fungsionaris*')" href="{{ route('fungsionaris.index') }}" />
            <x-nav-item icon="person_outline" label="Siswa" :active="Request::is('siswa*')" href="{{ route('siswa.index') }}" />
            <x-nav-item icon="notifications" label="Pengumuman" />
            <x-nav-item icon="image" label="Slider Admin" />
            <x-nav-item icon="calendar_today" label="Kalender" />
            <x-nav-item icon="account_balance" label="Mata Pelajaran" />
            <x-nav-item icon="record_voice_over" label="Sambutan" />

            <div class="pt-4 mt-4 border-t border-slate-50">
                <a 
                    href="#" 
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="group flex items-center gap-4 px-4 py-3 text-slate-500 font-medium rounded-xl hover:bg-red-50 hover:text-red-500 transition-all duration-200"
                >
                    <i class="material-icons text-xl group-hover:scale-110 transition-transform">exit_to_app</i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Keluar Aplikasi</span>
                </a>
            </div>
        </nav>
    </aside>

    <!-- Main Content wrapper -->
    <div 
        class="min-h-screen flex flex-col transition-all duration-300"
        :class="sidebarOpen ? 'pl-72' : 'pl-20'"
    >
        <!-- Navbar -->
        <header class="sticky top-0 z-30 flex items-center justify-between h-20 px-8 bg-white/80 backdrop-blur-md border-b border-slate-100">
            <!-- Left: Toggle & Page Title -->
            <div class="flex items-center gap-6">
                <button 
                    @click="sidebarOpen = !sidebarOpen" 
                    class="p-2.5 rounded-xl bg-slate-50 text-slate-500 hover:text-[#d90d8b] hover:bg-pink-50 transition-all duration-200 cursor-pointer"
                >
                    <i class="material-icons" x-text="sidebarOpen ? 'menu_open' : 'menu'"></i>
                </button>
                <div class="hidden sm:block">
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest">Administrator</h2>
                </div>
            </div>

            <!-- Right: Search & Profile -->
            <div class="flex items-center gap-3 sm:gap-6">
                <!-- Notifications -->
                <button class="relative p-2.5 rounded-xl text-slate-400 hover:text-[#d90d8b] hover:bg-pink-50 transition-all duration-200">
                    <i class="material-icons">notifications_none</i>
                    <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button 
                        @click="open = !open"
                        class="flex items-center gap-3 p-1.5 pl-4 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-white hover:shadow-md transition-all duration-300 cursor-pointer"
                    >
                        <div class="text-right hidden md:block">
                            <p class="text-xs font-bold text-slate-800 leading-tight">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] uppercase font-bold text-[#d90d8b] tracking-wider">{{ Auth::user()->role }}</p>
                        </div>
                        <img class="w-10 h-10 rounded-xl object-cover border-2 border-white shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ba80e8&color=fff" alt="Avatar">
                        <i class="material-icons text-slate-400 text-lg transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</i>
                    </button>

                    <!-- Profile Dropdown Menu -->
                    <div 
                        x-show="open"
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        class="absolute right-0 mt-3 w-56 p-2 bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 origin-top-right"
                    >
                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 rounded-xl hover:bg-slate-50 transition-colors">
                            <i class="material-icons text-slate-400 text-lg">person_outline</i> Profil Saya
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 rounded-xl hover:bg-slate-50 transition-colors">
                            <i class="material-icons text-slate-400 text-lg">settings</i> Pengaturan
                        </a>
                        <div class="my-2 border-t border-slate-50"></div>
                        <a 
                            href="#" 
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-red-500 rounded-xl hover:bg-red-50 transition-colors"
                        >
                            <i class="material-icons text-lg">exit_to_app</i> Keluar
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dynamic Content -->
        <main class="flex-grow p-8">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="px-8 py-6 bg-white border-t border-slate-50">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-slate-400 font-medium">
                    &copy; 2026 <span class="text-[#d90d8b] font-bold uppercase">Literasia</span>. Seluruh hak cipta dilindungi.
                </p>
                <div class="flex gap-6">
                    <a href="#" class="text-xs font-bold text-slate-300 hover:text-[#d90d8b] transition-colors">BANTUAN</a>
                    <a href="#" class="text-xs font-bold text-slate-300 hover:text-[#d90d8b] transition-colors">PRIVASI</a>
                    <a href="#" class="text-xs font-bold text-slate-300 hover:text-[#d90d8b] transition-colors">SYARAT & KETENTUAN</a>
                </div>
            </div>
        </footer>
    </div>

    <!-- Forms & Utilities -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    @stack('scripts')
    @yield('scripts')
</body>
</html>
