@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Pengaturan Aplikasi</h1>
            <p class="text-slate-500 mt-1">Atur identitas global aplikasi seperti nama dan logo.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <form action="{{ route('dinas.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            
            <div class="space-y-8">
                <!-- App Name Section -->
                <div>
                    <label for="app_name" class="block text-sm font-bold text-slate-700 uppercase tracking-wider mb-3">Nama Aplikasi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="material-icons text-slate-400">label</i>
                        </div>
                        <input 
                            type="text" 
                            name="app_name" 
                            id="app_name" 
                            value="{{ old('app_name', $settings->app_name) }}"
                            class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 font-medium focus:ring-2 focus:ring-[#d90d8b]/20 focus:border-[#d90d8b] transition-all duration-200"
                            placeholder="Contoh: LITERASIA"
                            required
                        >
                    </div>
                </div>

                <!-- Logo Section -->
                <div x-data="{ photoName: null, photoPreview: null }">
                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider mb-3">Logo Aplikasi</label>
                    
                    <div class="flex flex-col md:flex-row items-center gap-8">
                        <!-- Preview Container -->
                        <div class="relative group">
                            <div class="w-32 h-32 rounded-3xl bg-slate-100 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden shadow-inner">
                                <!-- Fallback Icon -->
                                <div x-show="!photoPreview && !{{ $settings->app_logo ? 'true' : 'false' }}" class="text-slate-300">
                                    <i class="material-icons text-5xl">image</i>
                                </div>
                                
                                <!-- Current Logo -->
                                @if($settings->app_logo)
                                    <img x-show="!photoPreview" src="{{ $settings->logo_url }}" class="w-full h-full object-contain p-4">
                                @endif

                                <!-- New Preview -->
                                <img x-show="photoPreview" :src="photoPreview" class="w-full h-full object-contain p-4" style="display: none;">
                            </div>
                        </div>

                        <div class="flex-grow space-y-4">
                            <div class="flex items-center gap-4">
                                <input 
                                    type="file" 
                                    name="app_logo" 
                                    id="app_logo" 
                                    class="hidden" 
                                    accept="image/*"
                                    @change="
                                        photoName = $event.target.files[0].name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($event.target.files[0]);
                                    "
                                >
                                <button 
                                    type="button" 
                                    @click="$refs.logoInput.click()"
                                    class="px-6 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 shadow-sm flex items-center gap-2"
                                >
                                    <i class="material-icons text-lg">upload</i> Pilih Logo Baru
                                </button>
                                
                                <button 
                                    x-show="photoPreview" 
                                    type="button"
                                    @click="photoPreview = null; $refs.logoInput.value = null"
                                    class="px-4 py-3 bg-red-50 text-red-500 rounded-xl text-sm font-bold hover:bg-red-100 transition-all duration-200"
                                    style="display: none;"
                                >
                                    Batal
                                </button>
                            </div>
                            <p class="text-xs text-slate-400 font-medium">Recomendasi format: PNG transparan. Ukuran maksimal 2MB.</p>
                        </div>
                        <input type="file" x-ref="logoInput" class="hidden" @change="
                            photoName = $event.target.files[0].name;
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                photoPreview = e.target.result;
                            };
                            reader.readAsDataURL($event.target.files[0]);
                        " name="app_logo">
                    </div>
                </div>
            </div>

                <!-- Registration Control Section -->
                <div class="pt-8 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider mb-1">Pendaftaran Sekolah</label>
                            <p class="text-xs text-slate-400 font-medium">Aktifkan atau nonaktifkan pendaftaran untuk sekolah baru di halaman login.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="school_registration_enabled" 
                                value="1" 
                                class="sr-only peer"
                                {{ $settings->school_registration_enabled ? 'checked' : '' }}
                            >
                            <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none ring-0 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-[#ba80e8] peer-checked:to-[#d90d8b]"></div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex justify-end">
                <button type="submit" class="px-10 py-4 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl font-bold shadow-lg shadow-pink-200 hover:scale-105 transition-all duration-300 flex items-center gap-3">
                    <i class="material-icons">save_alt</i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
