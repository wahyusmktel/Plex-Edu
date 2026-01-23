@extends('layouts.app_guest')

@section('title', 'Privacy Policy - ' . $app_settings->app_name)

@section('content')
<section class="pt-32 pb-20 px-6 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-pink-50 rounded-full mb-6 border border-pink-100">
            <span class="w-2 h-2 rounded-full bg-pink-600"></span>
            <span class="text-xs font-black text-pink-600 uppercase tracking-widest">Legal Document</span>
        </div>
        <h1 class="text-4xl lg:text-5xl font-black text-slate-800 mb-8 font-outfit">Kebijakan <span class="text-gradient">Privasi</span></h1>
        
        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100 prose prose-slate max-w-none">
            <p class="text-slate-500 font-medium leading-relaxed mb-6">
                Terakhir diperbarui: {{ date('d F Y') }}
            </p>

            <h3 class="text-xl font-black text-slate-800 mb-4">1. Informasi yang Kami Kumpulkan</h3>
            <p class="text-slate-600 mb-6">
                Kami mengumpulkan informasi yang Anda berikan langsung kepada kami saat mendaftarkan sekolah, membuat akun fungsionaris, atau menggunakan layanan e-learning dan CBT kami. Ini termasuk nama, alamat email, nomor telepon, dan data pendidikan.
            </p>

            <h3 class="text-xl font-black text-slate-800 mb-4">2. Bagaimana Kami Menggunakan Informasi Anda</h3>
            <p class="text-slate-600 mb-6">
                Informasi yang dikumpulkan digunakan untuk:
                <ul class="list-disc pl-5 space-y-2 mt-2">
                    <li>Menyediakan, memelihara, dan meningkatkan layanan kami.</li>
                    <li>Memproses pendaftaran sekolah dan verifikasi data.</li>
                    <li>Mengirimkan pemberitahuan teknis, pembaruan, dan pesan dukungan.</li>
                    <li>Menganalisis tren penggunaan untuk pengembangan fitur baru.</li>
                </ul>
            </p>

            <h3 class="text-xl font-black text-slate-800 mb-4">3. Keamanan Data</h3>
            <p class="text-slate-600 mb-6">
                Kami menggunakan langkah-langkah keamanan teknis dan organisasional yang standar industri untuk melindungi informasi Anda dari akses yang tidak sah, pengungkapan, atau modifikasi.
            </p>

            <h3 class="text-xl font-black text-slate-800 mb-4">4. Hak Pengguna</h3>
            <p class="text-slate-600 mb-6">
                Anda memiliki hak untuk mengakses, memperbaiki, atau menghapus data pribadi Anda yang tersimpan dalam sistem kami. Anda dapat menghubungi Customer Service kami untuk bantuan lebih lanjut.
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
