<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: true, mobileMenuOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $app_settings->app_name)</title>
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
            @if($app_settings->app_logo)
                <img src="{{ $app_settings->logo_url }}" class="w-10 h-10 rounded-xl object-contain shadow-md shadow-pink-100 bg-white" alt="Logo">
            @else
                <div class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white shadow-md shadow-pink-100">
                    <i class="material-icons text-2xl">import_contacts</i>
                </div>
            @endif
            <span 
                x-show="sidebarOpen" 
                x-transition:enter="transition ease-out duration-300" 
                x-transition:enter-start="opacity-0 -translate-x-4" 
                x-transition:enter-end="opacity-100 translate-x-0"
                class="text-2xl font-extrabold tracking-tight bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] bg-clip-text text-transparent uppercase"
            >
                {{ $app_settings->app_name }}
            </span>
        </div>

        <!-- Navigation items -->
        <nav class="px-4 space-y-1.5">
            {{-- Dinas Role --}}
            @if(Auth::user()->role === 'dinas')
                <x-nav-item icon="dashboard" label="Dashboard" :active="Request::is('dashboard')" href="{{ route('dashboard') }}" />
                <x-nav-item icon="admin_panel_settings" label="Manajemen Sekolah" :active="Request::is('dinas*') && !Request::is('dinas/stats*') && !Request::is('dinas/schools*') && !Request::is('dinas/certificates*') && !Request::is('dinas/violations*')" href="{{ route('dinas.index') }}" />
                <x-nav-item icon="analytics" label="Statistik Siswa" :active="Request::is('dinas/stats*')" href="{{ route('dinas.stats') }}" />
                <x-nav-item icon="domain" label="Data Sekolah" :active="Request::is('dinas/schools*')" href="{{ route('dinas.schools') }}" />
                <x-nav-item icon="groups" label="Data Siswa" :active="Request::is('dinas/siswa*')" href="{{ route('dinas.siswa') }}" />
                <x-nav-item icon="local_library" label="E-Library Global" :active="Request::is('dinas/library*')" href="{{ route('dinas.library') }}" />
                <x-nav-item icon="badge" label="Sertifikat Guru" :active="Request::is('dinas/certificates*')" href="{{ route('dinas.certificates') }}" />
                <x-nav-item icon="report_problem" label="Pelanggaran" :active="Request::is('dinas/violations*')" href="{{ route('dinas.violations') }}" />
                
                <div class="h-px bg-slate-100 my-4 mx-4"></div>
                <p class="px-6 pb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Konten Global</p>
                <x-nav-item icon="forum" label="Forum Diskusi" :active="Request::is('forum*')" href="{{ route('forum.index') }}" />
                <x-nav-item icon="record_voice_over" label="Sambutan Dinas" :active="Request::is('sambutan*')" href="{{ route('sambutan.index') }}" />
                <x-nav-item icon="article" label="Berita Dinas" :active="Request::is('berita*')" href="{{ route('berita.index') }}" />
                <x-nav-item icon="computer" label="CBT Global" :active="Request::is('cbt*')" href="{{ route('cbt.index') }}" />
                <x-nav-item icon="calendar_today" label="Agenda Global" :active="Request::is('calendar*')" href="{{ route('calendar.index') }}" />
                <x-nav-item icon="settings" label="Pengaturan Aplikasi" :active="Request::is('dinas/settings*')" href="{{ route('dinas.settings') }}" />
            @endif

            {{-- Guru Role --}}
            @if(Auth::user()->role === 'guru')
                <x-nav-item icon="dashboard" label="Dashboard" :active="Request::is('dashboard')" href="{{ route('dashboard') }}" />
                <x-nav-item icon="assignment_ind" label="Absensi" :active="Request::is('absensi*')" href="{{ route('absensi.index') }}" />
                <x-nav-item icon="computer" label="CBT" :active="Request::is('cbt*')" href="{{ route('cbt.index') }}" />
                <x-nav-item icon="calendar_today" label="Kalender" :active="Request::is('calendar*')" href="{{ route('calendar.index') }}" />
                <x-nav-item icon="forum" label="Forum Diskusi" :active="Request::is('forum*')" href="{{ route('forum.index') }}" />
                <x-nav-item icon="cast_for_education" label="E-Learning" :active="Request::is('elearning*')" href="{{ route('elearning.index') }}" />
                <x-nav-item icon="how_to_vote" label="E-Voting" :active="Request::is('e-voting*')" href="{{ route('e-voting.index') }}" />
                <x-nav-item icon="collections_bookmark" label="Bank Soal" :active="Request::is('bank-soal*')" href="{{ route('bank-soal.index') }}" />
                <x-nav-item icon="workspace_premium" label="Sertifikat Saya" :active="Request::is('certificates*')" href="{{ route('certificates.index') }}" />
            @endif

            {{-- Admin Role (School Content Manager) --}}
            @if(Auth::user()->role === 'admin')
                <x-nav-item icon="dashboard" label="Dashboard Sekolah" :active="Request::is('dashboard')" href="{{ route('dashboard') }}" />
                <x-nav-item icon="school" label="Sekolah" :active="Request::is('sekolah*')" href="{{ route('sekolah.index') }}" />
                <x-nav-item icon="people" label="Fungsionaris" :active="Request::is('fungsionaris*')" href="{{ route('fungsionaris.index') }}" />
                <x-nav-item icon="person_outline" label="Siswa" :active="Request::is('siswa*')" href="{{ route('siswa.index') }}" />
                <x-nav-item icon="assignment_turned_in" label="E-Raport" :active="Request::is('e-raport*')" href="{{ route('e-raport.index') }}" />
                <x-nav-item icon="menu_book" label="Mata Pelajaran" :active="Request::is('mata-pelajaran*')" href="{{ route('mata-pelajaran.index') }}" />
                <x-nav-item icon="computer" label="CBT" :active="Request::is('cbt*')" href="{{ route('cbt.index') }}" />
                <x-nav-item icon="forum" label="Forum Diskusi" :active="Request::is('forum*')" href="{{ route('forum.index') }}" />
                <x-nav-item icon="cast_for_education" label="E-Learning" :active="Request::is('elearning*')" href="{{ route('elearning.index') }}" />
                <x-nav-item icon="library_books" label="E-Library" :active="Request::is('library*')" href="{{ route('library.index') }}" />
                <x-nav-item icon="workspace_premium" label="Sertifikat Guru" :active="Request::is('certificates*')" href="{{ route('certificates.index') }}" />
                <x-nav-item icon="warning" label="Pelanggaran" :active="Request::is('pelanggaran*')" href="{{ route('pelanggaran.index') }}" />
                <x-nav-item icon="article" label="Berita" :active="Request::is('berita*')" href="{{ route('berita.index') }}" />                
                <x-nav-item icon="campaign" label="Pengumuman" :active="Request::is('pengumuman*')" href="{{ route('pengumuman.index') }}" />
                <x-nav-item icon="image" label="Slider Admin" :active="Request::is('slider*')" href="{{ route('slider.index') }}" />
                <x-nav-item icon="calendar_today" label="Kalender" :active="Request::is('calendar*')" href="{{ route('calendar.index') }}" />
                <x-nav-item icon="record_voice_over" label="Sambutan" :active="Request::is('sambutan*')" href="{{ route('sambutan.index') }}" />
                <x-nav-item icon="how_to_vote" label="E-Voting" :active="Request::is('e-voting*')" href="{{ route('e-voting.index') }}" />
                <x-nav-item icon="assignment_ind" label="Absensi" :active="Request::is('absensi*')" href="{{ route('absensi.index') }}" />
            @endif

            {{-- Siswa Role --}}
            @if(Auth::user()->role === 'siswa')
                <x-nav-item icon="dashboard" label="Dashboard" :active="Request::is('dashboard')" href="{{ route('dashboard') }}" />
                <x-nav-item icon="assignment_ind" label="Absensi" :active="Request::is('student/absensi*')" href="{{ route('student.absensi.index') }}" />
                <x-nav-item icon="class" label="Mata Pelajaran" :active="Request::is('student/subjects*')" href="{{ route('student.subjects.index') }}" />
                <x-nav-item icon="event_note" label="Jadwal Pelajaran" :active="Request::is('student/schedule*')" href="{{ route('student.schedule.index') }}" />
                <x-nav-item icon="grade" label="Nilai" :active="Request::is('student/grades*')" href="{{ route('student.grades.index') }}" />
                <x-nav-item icon="inventory_2" label="Bank Soal" :active="Request::is('student/bank-soal*')" href="{{ route('student.bank-soal.index') }}" />
                <x-nav-item icon="quiz" label="CBT" :active="Request::is('test*')" href="{{ route('test.index') }}" />
                <x-nav-item icon="library_books" label="E-Library" :active="Request::is('library*')" href="{{ route('library.index') }}" />
                <x-nav-item icon="description" label="E-Raport" :active="Request::is('student/raport*')" href="{{ route('student.raport.index') }}" />
                <x-nav-item icon="forum" label="Forum Diskusi" :active="Request::is('forum*')" href="{{ route('forum.index') }}" />
                <x-nav-item icon="cast_for_education" label="E-Learning" :active="Request::is('elearning*')" href="{{ route('elearning.index') }}" />
                <x-nav-item icon="how_to_vote" label="E-Voting" :active="Request::is('e-voting*')" href="{{ route('e-voting.index') }}" />
                <x-nav-item icon="gavel" label="Pelanggaran" :active="Request::is('student/pelanggaran*')" href="{{ route('student.pelanggaran.index') }}" />
                <x-nav-item icon="newspaper" label="Berita" :active="Request::is('berita*')" href="{{ route('berita.index') }}" />
            @endif

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
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest">
                        {{ Auth::user()->role === 'dinas' ? 'Admin Dinas' : (Auth::user()->role === 'admin' ? 'Administrator' : ucfirst(Auth::user()->role)) }}
                    </h2>
                </div>
            </div>

            <!-- Right: Search & Profile -->
            <div class="flex items-center gap-3 sm:gap-6">
                <!-- Notifications Dropdown -->
                <div class="relative" x-data="notificationDropdown()" @click.away="open = false">
                    <button @click="toggle()" class="relative p-2.5 rounded-xl text-slate-400 hover:text-[#d90d8b] hover:bg-pink-50 transition-all duration-200">
                        <i class="material-icons">notifications_none</i>
                        <span x-show="unreadCount > 0" x-cloak class="absolute top-1.5 right-1.5 min-w-[18px] h-[18px] px-1 bg-red-500 rounded-full text-white text-[10px] font-bold flex items-center justify-center" x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                    </button>

                    <!-- Notification Panel -->
                    <div 
                        x-show="open"
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        class="absolute right-0 mt-3 w-96 bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 origin-top-right overflow-hidden"
                    >
                        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                            <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest">Notifikasi</h4>
                            <button @click="markAllAsRead()" class="text-[10px] font-bold text-[#d90d8b] hover:underline uppercase tracking-wider">Tandai Dibaca</button>
                        </div>
                        <div class="max-h-80 overflow-y-auto divide-y divide-slate-50">
                            <template x-if="notifications.length === 0">
                                <div class="px-5 py-10 text-center">
                                    <i class="material-icons text-4xl text-slate-200">notifications_off</i>
                                    <p class="text-xs text-slate-400 font-medium mt-2">Belum ada notifikasi</p>
                                </div>
                            </template>
                            <template x-for="notif in notifications" :key="notif.id">
                                <a :href="notif.data.url" @click="markAsRead(notif.id)" class="flex gap-4 px-5 py-4 hover:bg-slate-50 transition-colors" :class="!notif.read_at ? 'bg-blue-50/30' : ''">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center" :class="getIconBg(notif.data.color)">
                                        <i class="material-icons text-lg" :class="getIconColor(notif.data.color)" x-text="notif.data.icon"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-slate-800 line-clamp-1" x-text="notif.data.title"></p>
                                        <p class="text-[11px] text-slate-500 font-medium line-clamp-1 mt-0.5" x-text="notif.data.message"></p>
                                        <p class="text-[10px] text-slate-400 mt-1" x-text="notif.created_at"></p>
                                    </div>
                                    <div x-show="!notif.read_at" class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>

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
                        <img class="w-10 h-10 rounded-xl object-cover border-2 border-white shadow-sm" src="{{ Auth::user()->avatar_url }}" alt="Avatar">
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
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 rounded-xl hover:bg-slate-50 transition-colors">
                            <i class="material-icons text-slate-400 text-lg">person_outline</i> Profil Saya
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 rounded-xl hover:bg-slate-50 transition-colors">
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
                    &copy; 2026 <span class="text-[#d90d8b] font-bold uppercase">{{ $app_settings->app_name }}</span>. Seluruh hak cipta dilindungi.
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

    <script>
        function notificationDropdown() {
            return {
                open: false,
                notifications: [],
                unreadCount: 0,
                init() {
                    this.fetchNotifications();
                    // Refresh every 30 seconds
                    setInterval(() => this.fetchNotifications(), 30000);
                },
                toggle() {
                    this.open = !this.open;
                    if (this.open) this.fetchNotifications();
                },
                async fetchNotifications() {
                    try {
                        const res = await fetch('{{ route("notifications.index") }}');
                        const data = await res.json();
                        this.notifications = data.notifications;
                        this.unreadCount = data.unread_count;
                    } catch (e) { console.error('Failed to load notifications', e); }
                },
                async markAsRead(id) {
                    try {
                        await fetch('{{ url("/notifications") }}/' + id + '/read', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
                        });
                        this.fetchNotifications();
                    } catch (e) { console.error('Failed to mark as read', e); }
                },
                async markAllAsRead() {
                    try {
                        await fetch('{{ route("notifications.readAll") }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
                        });
                        this.fetchNotifications();
                    } catch (e) { console.error('Failed to mark all as read', e); }
                },
                getIconBg(color) {
                    const map = { 'purple': 'bg-purple-50', 'blue': 'bg-blue-50', 'red': 'bg-red-50', 'yellow': 'bg-yellow-50', 'amber': 'bg-amber-50', 'green': 'bg-green-50' };
                    return map[color] || 'bg-slate-50';
                },
                getIconColor(color) {
                    const map = { 'purple': 'text-purple-500', 'blue': 'text-blue-500', 'red': 'text-red-500', 'yellow': 'text-yellow-500', 'amber': 'text-amber-500', 'green': 'text-green-500' };
                    return map[color] || 'text-slate-500';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#d90d8b',
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#d90d8b'
                });
            @endif
        });
    </script>
</body>
</html>
