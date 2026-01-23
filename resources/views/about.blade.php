@extends('layouts.app_guest')

@section('title', 'Tentang Kami - ' . $app_settings->app_name)

@section('content')
<section class="pt-32 pb-20 px-6 min-h-screen relative overflow-hidden">
    <div class="absolute top-0 right-0 -mr-40 -mt-20 w-[600px] h-[600px] bg-pink-100 rounded-full blur-[100px] opacity-40"></div>
    
    <div class="max-w-6xl mx-auto relative z-10">
        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-pink-50 rounded-full mb-6 border border-pink-100">
                <span class="w-2 h-2 rounded-full bg-pink-600"></span>
                <span class="text-xs font-black text-pink-600 uppercase tracking-widest">Who We Are</span>
            </div>
            <h1 class="text-5xl lg:text-7xl font-black text-slate-800 mb-8 font-outfit">Tentang <span class="text-gradient">{{ $app_settings->app_name }}</span></h1>
            <p class="text-lg text-slate-500 font-medium max-w-3xl mx-auto leading-relaxed">
                Kami adalah platform transformasi digital pendidikan yang berkomitmen untuk memodernisasi tata kelola sekolah di seluruh Indonesia melalui inovasi teknologi yang inklusif dan aman.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-20">
            <div class="bg-gradient-main p-1 rounded-[3rem]">
                <div class="bg-white rounded-[2.8rem] p-10">
                    <h3 class="text-3xl font-black text-slate-800 mb-6 font-outfit">Visi Kami</h3>
                    <p class="text-slate-600 font-medium leading-relaxed">
                        Menjadi katalisator utama dalam pemerataan kualitas pendidikan digital di Indonesia, memastikan setiap sekolah memiliki akses ke sistem manajemen dan pembelajaran terbaik.
                    </p>
                </div>
            </div>
            <div class="space-y-8">
                <div class="flex gap-6">
                    <div class="w-14 h-14 bg-pink-100 rounded-2xl flex-shrink-0 flex items-center justify-center">
                        <i class="material-icons text-pink-600">innovation</i>
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-slate-800 mb-2">Inovasi Berkelanjutan</h4>
                        <p class="text-slate-500 font-medium">Kami terus mengembangkan fitur-fitur baru berdasarkan kebutuhan riil di lapangan.</p>
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="w-14 h-14 bg-purple-100 rounded-2xl flex-shrink-0 flex items-center justify-center">
                        <i class="material-icons text-purple-600">security</i>
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-slate-800 mb-2">Keamanan & Privasi</h4>
                        <p class="text-slate-500 font-medium">Kerahasiaan data sekolah dan siswa adalah prioritas tertinggi kami.</p>
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex-shrink-0 flex items-center justify-center">
                        <i class="material-icons text-blue-600">groups</i>
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-slate-800 mb-2">Kolaborasi Inklusif</h4>
                        <p class="text-slate-500 font-medium">Membangun ekosistem yang menghubungkan dinas, sekolah, guru, dan siswa.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-slate-900 rounded-[3rem] p-12 text-center text-white relative overflow-hidden">
            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-pink-600 rounded-full blur-[100px] opacity-20"></div>
            <h3 class="text-3xl font-black mb-6 font-outfit relative z-10">Mari Menjadi Bagian dari Masa Depan Edukasi</h3>
            <p class="text-slate-400 font-medium mb-10 max-w-2xl mx-auto relative z-10">Bergabunglah dengan ribuan sekolah lainnya yang telah melakukan transformasi bersama {{ $app_settings->app_name }}.</p>
            <a href="{{ route('register.school') }}" class="inline-flex items-center gap-2 px-10 py-5 bg-gradient-main rounded-2xl font-black hover:scale-105 transition-transform relative z-10 shadow-xl shadow-pink-900/50">Daftarkan Sekolah Anda Now</a>
        </div>
    </div>
</section>
@endsection
