@extends('layouts.app_guest')

@section('title', 'Terms of Service - ' . $app_settings->app_name)

@section('content')
<section class="pt-32 pb-20 px-6 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-purple-50 rounded-full mb-6 border border-purple-100">
            <span class="w-2 h-2 rounded-full bg-purple-600"></span>
            <span class="text-xs font-black text-purple-600 uppercase tracking-widest">Legal Document</span>
        </div>
        <h1 class="text-4xl lg:text-5xl font-black text-slate-800 mb-8 font-outfit">Syarat & <span class="text-gradient">Ketentuan</span></h1>
        
        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100 prose prose-slate max-w-none">
            <p class="text-slate-500 font-medium leading-relaxed mb-6">
                Terakhir diperbarui: {{ date('d F Y') }}
            </p>

            <h3 class="text-xl font-black text-slate-800 mb-4">1. Penerimaan Ketentuan</h3>
            <p class="text-slate-600 mb-6">
                Dengan mengakses dan menggunakan platform {{ $app_settings->app_name }}, Anda setuju untuk terikat oleh Ketentuan Layanan ini. Jika Anda tidak setuju dengan ketentuan ini, mohon untuk tidak menggunakan layanan kami.
            </p>

            <h3 class="text-xl font-black text-slate-800 mb-4">2. Tanggung Jawab Akun</h3>
            <p class="text-slate-600 mb-6">
                Sebagai pengguna, Anda bertanggung jawab penuh atas keamanan kata sandi dan aktivitas yang terjadi di bawah akun Anda. Anda setuju untuk segera memberitahu kami tentang penggunaan akun Anda yang tidak sah.
            </p>

            <h3 class="text-xl font-black text-slate-800 mb-4">3. Penggunaan yang Diperbolehkan</h3>
            <p class="text-slate-600 mb-6">
                Layanan kami disediakan untuk tujuan pendidikan. Anda dilarang menggunakan platform untuk aktivitas ilegal, penyebaran konten berbahaya, atau tindakan yang dapat mengganggu integritas sistem CBT dan data pendidikan.
            </p>

            <h3 class="text-xl font-black text-slate-800 mb-4">4. Batasan Tanggung Jawab</h3>
            <p class="text-slate-600 mb-6">
                Dalam batas maksimal yang diizinkan oleh hukum, {{ $app_settings->app_name }} tidak bertanggung jawab atas kerugian tidak langsung, insidental, atau konsekuensial yang timbul dari penggunaan atau ketidakmampuan menggunakan layanan.
            </p>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-slate-400 font-bold hover:text-pink-600 transition-colors">
                <i class="material-icons">arrow_back</i> Kembali ke Beranda
            </a>
        </div>
    </div>
</section>
@endsection
