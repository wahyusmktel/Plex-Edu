<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ $app_settings->app_name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        .text-fill-transparent { -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="bg-white font-['Inter'] antialiased overflow-hidden">
    <div class="flex min-h-screen">
        <!-- Left Side: Decorative & Branding -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-[#ba80e8] to-[#d90d8b] items-center justify-center p-12 overflow-hidden">
            <!-- Animated Background Elements -->
            <div class="absolute top-0 left-0 w-full h-full">
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>
            </div>

            <div class="relative z-10 text-center text-white max-w-lg">
                <div class="mb-10 transform hover:scale-105 transition-transform duration-500">
                    @if($app_settings->app_logo)
                        <img src="{{ $app_settings->logo_url }}" class="w-28 h-28 mx-auto rounded-3xl object-contain bg-white/20 backdrop-blur-md p-4 shadow-2xl border border-white/30" alt="Logo">
                    @else
                        <div class="w-28 h-28 mx-auto rounded-3xl bg-white/20 backdrop-blur-md flex items-center justify-center shadow-2xl border border-white/30">
                            <i class="material-icons text-6xl">import_contacts</i>
                        </div>
                    @endif
                </div>
                <h1 class="text-5xl font-black tracking-tight mb-6 uppercase bg-gradient-to-r from-white via-white to-white/70 bg-clip-text text-transparent">{{ $app_settings->app_name }}</h1>
                <p class="text-xl font-medium text-white/80 leading-relaxed mb-12">Ekosistem pendidikan digital modern untuk mendukung masa depan literasi dan manajemen sekolah yang lebih baik.</p>
                
                <div class="grid grid-cols-2 gap-6 text-left">
                    <div class="p-6 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20">
                        <i class="material-icons mb-3 text-pink-300">verified</i>
                        <h3 class="font-bold text-lg">Terintegrasi</h3>
                        <p class="text-sm text-white/70">Semua layanan dalam satu dashboard.</p>
                    </div>
                    <div class="p-6 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20">
                        <i class="material-icons mb-3 text-purple-300">security</i>
                        <h3 class="font-bold text-lg">Aman</h3>
                        <p class="text-sm text-white/70">Perlindungan data tingkat tinggi.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-24 relative bg-slate-50">
            <!-- Mobile Branding (Show only on small screens) -->
            <div class="lg:hidden absolute top-12 left-0 w-full text-center px-6">
                <div class="inline-flex items-center gap-4">
                    @if($app_settings->app_logo)
                        <img src="{{ $app_settings->logo_url }}" class="w-12 h-12 rounded-xl object-contain bg-white shadow-md p-2" alt="Logo">
                    @else
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-[#ba80e8] to-[#d90d8b] flex items-center justify-center text-white shadow-md">
                            <i class="material-icons text-2xl">import_contacts</i>
                        </div>
                    @endif
                    <span class="text-2xl font-black tracking-tight bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] bg-clip-text text-transparent uppercase">{{ $app_settings->app_name }}</span>
                </div>
            </div>

            <div class="w-full max-w-md">
                <div class="mb-10">
                    <h2 class="text-4xl font-extrabold text-slate-800 tracking-tight">Selamat Datang</h2>
                    <p class="text-slate-500 mt-3 font-medium text-lg">Silakan masuk untuk melanjutkan ke portal Anda.</p>
                </div>

                @if($errors->any() || session('error'))
                    <div class="mb-8 p-5 bg-red-50 border-l-4 border-red-500 rounded-2xl flex items-start gap-4 animate-shake">
                        <i class="material-icons text-red-500">error_outline</i>
                        <div>
                            <p class="text-sm text-red-700 font-bold">Terjadi Kesalahan</p>
                            <p class="text-xs text-red-600 font-medium mt-1">{{ $errors->first() ?? session('error') }}</p>
                        </div>
                    </div>
                @endif

                <form action="{{ url('/login') }}" method="POST" class="space-y-7">
                    @csrf
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-bold text-slate-700 uppercase tracking-widest flex items-center gap-2 ml-1">
                            <i class="material-icons text-lg">alternate_email</i>
                            Email / Username
                        </label>
                        <input id="email" name="email" type="text" value="{{ old('email') }}" required autofocus
                            class="block w-full px-6 py-4.5 bg-white border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-[#ba80e8]/10 focus:border-[#d90d8b] transition-all duration-300 font-medium shadow-sm"
                            placeholder="Masukkan email atau username">
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between ml-1">
                            <label for="password" class="text-sm font-bold text-slate-700 uppercase tracking-widest flex items-center gap-2">
                                <i class="material-icons text-lg">lock</i>
                                Password
                            </label>
                            <a href="#" class="text-xs font-bold text-[#d90d8b] hover:text-[#ba80e8] transition-colors tracking-wide">LUPA PASSWORD?</a>
                        </div>
                        <div class="relative group">
                            <input id="password" name="password" type="password" required
                                class="block w-full px-6 py-4.5 bg-white border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-[#ba80e8]/10 focus:border-[#d90d8b] transition-all duration-300 font-medium shadow-sm"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center px-1">
                        <label class="flex items-center cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="remember" class="sr-only peer">
                                <div class="w-6 h-6 bg-white border-2 border-slate-200 rounded-lg peer-checked:bg-gradient-to-r peer-checked:from-[#ba80e8] peer-checked:to-[#d90d8b] peer-checked:border-transparent transition-all duration-300"></div>
                                <i class="material-icons absolute inset-0 text-white text-lg scale-0 peer-checked:scale-100 transition-transform flex items-center justify-center">check</i>
                            </div>
                            <span class="ml-4 text-sm font-bold text-slate-600 group-hover:text-slate-800 transition-colors uppercase tracking-wide">Ingat Saya</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-5 px-6 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] hover:from-[#d90d8b] hover:to-[#ba80e8] text-white font-black rounded-2xl shadow-xl shadow-pink-200 hover:shadow-pink-300 transform hover:-translate-y-1 transition-all duration-300 active:scale-[0.98] tracking-widest uppercase text-lg">
                        Masuk Sekarang
                    </button>
                    
                    @if($app_settings->school_registration_enabled)
                    <div class="relative flex items-center justify-center py-4">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-200"></div>
                        </div>
                        <div class="relative px-6 bg-slate-50 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Atau</div>
                    </div>

                    <div class="text-center">
                        <p class="text-slate-500 font-medium text-sm">
                            Sekolah Anda belum terdaftar? 
                            <a href="{{ url('/register-school') }}" class="text-[#d90d8b] font-extrabold hover:text-[#ba80e8] transition-all decoration-2 underline-offset-4 hover:underline ml-1 uppercase text-xs tracking-widest">Daftar Sekolah Baru</a>
                        </p>
                    </div>
                    @endif
                </form>
            </div>

            <!-- Footer info -->
            <div class="absolute bottom-8 text-center text-xs font-bold text-slate-300 tracking-[0.3em] uppercase">
                &copy; 2026 {{ $app_settings->app_name }} - Managed by Literasia
            </div>
        </div>
    </div>

    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        .animate-shake { animation: shake 0.6s cubic-bezier(.36,.07,.19,.97) both; }
        
        /* Custom input sizing for 4.5 class as it's not standard tailwind */
        .py-4\.5 { padding-top: 1.125rem; padding-bottom: 1.125rem; }
    </style>
</body>
</html>
