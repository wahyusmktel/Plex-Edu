<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Literasia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-slate-50 font-['Inter'] antialiased">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100">
            <div class="p-8 sm:p-10">
                <div class="text-center mb-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-tr from-[#ba80e8] to-[#d90d8b] mb-6 shadow-lg shadow-pink-200">
                        <i class="material-icons text-white text-4xl">import_contacts</i>
                    </div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] bg-clip-text text-fill-transparent text-transparent tracking-tight">
                        LITERASIA
                    </h1>
                    <p class="text-slate-500 mt-2 font-medium">Silakan masuk ke akun Anda</p>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                        <p class="text-sm text-red-700 font-medium">{{ $errors->first() }}</p>
                    </div>
                @endif

                <form action="{{ url('/login') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-1">
                        <label for="email" class="text-sm font-semibold text-slate-700 ml-1">Email Address / Username</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#d90d8b] transition-colors">
                                <i class="material-icons text-xl">alternate_email</i>
                            </div>
                            <input id="email" name="email" type="text" value="{{ old('email') }}" required autofocus
                                class="block w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#ba80e8]/20 focus:border-[#d90d8b] transition-all duration-200 text-sm"
                                placeholder="Masukkan email atau username">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label for="password" class="text-sm font-semibold text-slate-700 ml-1">Password</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#d90d8b] transition-colors">
                                <i class="material-icons text-xl">lock</i>
                            </div>
                            <input id="password" name="password" type="password" required
                                class="block w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#ba80e8]/20 focus:border-[#d90d8b] transition-all duration-200 text-sm"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-1">
                        <label class="flex items-center cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="remember" class="sr-only peer">
                                <div class="w-5 h-5 bg-slate-200 border border-slate-300 rounded peer-checked:bg-[#d90d8b] peer-checked:border-[#d90d8b] transition-all"></div>
                                <i class="material-icons absolute inset-0 text-white text-base scale-0 peer-checked:scale-100 transition-transform">check</i>
                            </div>
                            <span class="ml-3 text-sm font-medium text-slate-600 group-hover:text-[#d90d8b] transition-colors">Ingat Saya</span>
                        </label>
                        <a href="#" class="text-sm font-semibold text-[#d90d8b] hover:text-[#ba80e8] transition-colors">Lupa Password?</a>
                    </div>

                    <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] hover:from-[#d90d8b] hover:to-[#ba80e8] text-white font-bold rounded-2xl shadow-lg shadow-pink-200 hover:shadow-pink-300 transform hover:-translate-y-0.5 transition-all duration-200 active:scale-[0.98] tracking-wide">
                        MASUK SEKARANG
                    </button>
                    
                    <div class="pt-4 text-center">
                        <p class="text-slate-500 text-sm font-medium">
                            Belum punya akun? 
                            <a href="#" class="text-[#d90d8b] font-bold hover:underline">Daftar Sekolah</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
