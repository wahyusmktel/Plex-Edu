<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $app_settings->app_name)</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-outfit { font-family: 'Outfit', sans-serif; }
        .text-gradient {
            background: linear-gradient(135deg, #d90d8b 0%, #ba80e8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .bg-gradient-main {
            background: linear-gradient(135deg, #d90d8b 0%, #ba80e8 100%);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">
    <nav class="fixed top-0 left-0 right-0 z-50 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between bg-white/70 backdrop-blur-xl border border-white/30 shadow-lg px-6 py-4 rounded-[2rem]">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                @if($app_settings->app_logo)
                    <img src="{{ $app_settings->logo_url }}" class="w-10 h-10 rounded-xl object-contain bg-white shadow-md p-2" alt="Logo">
                @else
                    <div class="w-10 h-10 bg-gradient-main rounded-xl flex items-center justify-center shadow-lg">
                        <i class="material-icons text-white">school</i>
                    </div>
                @endif
                <span class="text-2xl font-black font-outfit tracking-tight text-gradient uppercase">{{ $app_settings->app_name }}</span>
            </a>
            
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-gradient-main text-white px-6 py-3 rounded-2xl text-sm font-black shadow-lg shadow-pink-200">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-black text-slate-600 px-6 py-3 hover:bg-slate-100 rounded-2xl transition-all">Log in</a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-[#1b1b18] text-white py-10 px-6 border-t border-slate-800">
        <div class="max-w-7xl mx-auto text-center">
            <p class="text-slate-500 text-xs font-black uppercase tracking-widest">&copy; 2026 {{ $app_settings->app_name }}. All rights reserved.</p>
        </div>
    </footer>

    @include('cookie-consent::index')
</body>
</html>
