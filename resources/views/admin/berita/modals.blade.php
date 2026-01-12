<!-- Berita Create/Edit Modal -->
<div 
    x-show="openModal" 
    x-cloak
    class="fixed inset-0 z-[60] flex items-center justify-center px-4 py-6 sm:p-0"
>
    <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="openModal = false"></div>

    <div 
        x-show="openModal" 
        x-transition:enter="ease-out duration-300" 
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        class="bg-white rounded-[3rem] shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden relative z-10 flex flex-col"
    >
        <!-- Modal Header -->
        <div class="px-10 py-8 border-b border-slate-50 flex items-center justify-between bg-white sticky top-0 z-20">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight" x-text="editMode ? 'Edit Berita' : 'Buat Berita Baru'"></h2>
                <p class="text-slate-400 text-sm font-medium mt-1">Lengkapi informasi berita berikut ini.</p>
            </div>
            <button @click="openModal = false" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all">
                <i class="material-icons">close</i>
            </button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="p-10 overflow-y-auto custom-scrollbar flex-grow bg-white">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                
                <!-- Left Side: Forms -->
                <div class="lg:col-span-8 space-y-8">
                    <!-- Title -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Judul Berita</label>
                        <input 
                            type="text" 
                            x-model="formData.judul"
                            placeholder="Masukkan judul berita yang menarik..." 
                            class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700 text-lg placeholder:text-slate-300"
                        >
                    </div>

                    <!-- Quill Editor -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Konten Berita</label>
                        <div class="rounded-[2.5rem] overflow-hidden border-2 border-slate-50 bg-slate-50 transition-all focus-within:border-[#ba80e8] focus-within:bg-white">
                            <div id="deskripsi-editor"></div>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Metadata -->
                <div class="lg:col-span-4 space-y-8">
                    
                    <!-- Thumbnail Upload -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Thumbnail</label>
                        <div 
                            @click="$refs.thumbnailInput.click()"
                            class="group relative h-48 rounded-[2.5rem] border-2 border-dashed border-slate-200 hover:border-[#ba80e8] bg-slate-50 hover:bg-white transition-all cursor-pointer overflow-hidden flex flex-col items-center justify-center gap-3"
                        >
                            <template x-if="!thumbnailPreview">
                                <div class="text-center group-hover:scale-110 transition-transform">
                                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-slate-300 shadow-sm mx-auto mb-2">
                                        <i class="material-icons text-2xl group-hover:text-[#ba80e8]">image</i>
                                    </div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Klik untuk Unggah</p>
                                </div>
                            </template>
                            <template x-if="thumbnailPreview">
                                <img :src="thumbnailPreview" class="w-full h-full object-cover">
                            </template>
                            
                            <!-- Overlay on Hover -->
                            <div class="absolute inset-0 bg-[#ba80e8]/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="px-4 py-2 bg-white rounded-xl text-[10px] font-black text-[#ba80e8] shadow-sm uppercase tracking-widest">Ganti Gambar</span>
                            </div>
                        </div>
                        <input type="file" id="thumbnail-input" x-ref="thumbnailInput" class="hidden" accept="image/*" @change="previewThumbnail">
                        <p class="text-[9px] text-slate-400 font-bold text-center px-4 leading-relaxed uppercase tracking-tighter">Maksimal 2MB. Format: JPG, PNG, WEBP.</p>
                    </div>

                    <!-- Publishing Info -->
                    <div class="bg-slate-50 rounded-[2.5rem] p-8 space-y-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal Terbit</label>
                            <div class="relative">
                                <input 
                                    type="date" 
                                    x-model="formData.tanggal_terbit"
                                    class="w-full pl-12 pr-6 py-4 bg-white border border-slate-100 rounded-2xl focus:border-[#ba80e8] transition-all outline-none font-bold text-slate-700 text-sm shadow-sm"
                                >
                                <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-300">calendar_today</i>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Jam Terbit</label>
                            <div class="relative">
                                <input 
                                    type="time" 
                                    x-model="formData.jam_terbit"
                                    class="w-full pl-12 pr-6 py-4 bg-white border border-slate-100 rounded-2xl focus:border-[#ba80e8] transition-all outline-none font-bold text-slate-700 text-sm shadow-sm"
                                >
                                <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-300">schedule</i>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-200/50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-[#ba80e8] shadow-sm">
                                    <i class="material-icons">person</i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Penulis</p>
                                    <p class="text-[12px] font-bold text-slate-700 mt-1">{{ Auth::user()->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-10 py-6 border-t border-slate-100 flex justify-between items-center bg-white sticky bottom-0 z-20">
            <button @click="openModal = false" class="px-8 py-4 rounded-2xl text-sm font-bold text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-all uppercase tracking-widest">Batal</button>
            <button @click="saveBerita()" class="flex items-center gap-3 px-10 py-4 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl text-sm font-black shadow-xl shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all cursor-pointer">
                <i class="material-icons text-xl" x-text="editMode ? 'published_with_changes' : 'publish'"></i>
                <span x-text="editMode ? 'SIMPAN PERUBAHAN' : 'TERBITKAN BERITA'"></span>
            </button>
        </div>
    </div>
</div>

<style>
    /* Custom Scrollbar for Modal Body */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>
