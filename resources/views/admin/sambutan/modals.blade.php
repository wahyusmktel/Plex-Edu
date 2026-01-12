<!-- Sambutan Create/Edit Modal -->
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
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-white sticky top-0 z-20">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight" x-text="editMode ? 'Edit Sambutan' : 'Tambah Sambutan Baru'"></h2>
                <p class="text-slate-400 text-xs font-medium mt-0.5">Lengkapi informasi sambutan di bawah ini.</p>
            </div>
            <button @click="openModal = false" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all">
                <i class="material-icons text-xl">close</i>
            </button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="p-8 overflow-y-auto custom-scrollbar flex-grow bg-white">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                
                <!-- Left Side: Thumbnail -->
                <div class="lg:col-span-4 space-y-6">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Thumbnail Sambutan</label>
                    <div 
                        @click="$refs.thumbnailInput.click()"
                        class="group relative h-64 rounded-[2.5rem] border-2 border-dashed border-slate-200 hover:border-[#ba80e8] bg-slate-50 hover:bg-white transition-all cursor-pointer overflow-hidden flex flex-col items-center justify-center gap-3"
                    >
                        <template x-if="!imagePreview">
                            <div class="text-center group-hover:scale-110 transition-transform">
                                <i class="material-icons text-4xl text-slate-300 group-hover:text-[#ba80e8] mb-2">add_photo_alternate</i>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-relaxed">Pilih Gambar<br><span class="text-[8px] opacity-60">Klik untuk Unggah</span></p>
                            </div>
                        </template>
                        <template x-if="imagePreview">
                            <img :src="imagePreview" class="w-full h-full object-cover">
                        </template>
                        
                        <div class="absolute inset-0 bg-[#ba80e8]/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="px-6 py-3 bg-white rounded-2xl text-[10px] font-black text-[#ba80e8] shadow-lg uppercase tracking-widest">Ganti Gambar</span>
                        </div>
                    </div>
                    <input type="file" id="thumbnail-input" x-ref="thumbnailInput" class="hidden" accept="image/*" @change="previewImage">
                </div>

                <!-- Right Side: Content -->
                <div class="lg:col-span-8 space-y-6">
                    <!-- Title -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Judul Sambutan</label>
                        <input 
                            type="text" 
                            x-model="formData.judul"
                            placeholder="Contoh: Sambutan Kepala Sekolah Awal Semester" 
                            class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700 placeholder:text-slate-300 shadow-sm"
                        >
                    </div>

                    <!-- Quill Editor -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Isi Sambutan</label>
                        <div class="rounded-[2rem] overflow-hidden border-2 border-slate-50 bg-slate-50 transition-all focus-within:border-[#ba80e8] focus-within:bg-white">
                            <div id="konten-editor"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-8 py-5 border-t border-slate-100 flex justify-between items-center bg-white sticky bottom-0 z-20">
            <button @click="openModal = false" class="px-6 py-3 rounded-2xl text-xs font-bold text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-all uppercase tracking-widest">Batal</button>
            <button @click="saveSambutan()" class="flex items-center gap-3 px-8 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl text-xs font-black shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all cursor-pointer">
                <i class="material-icons text-lg" x-text="editMode ? 'save' : 'publish'"></i>
                <span x-text="editMode ? 'SIMPAN PERUBAHAN' : 'TERBITKAN SAMBUTAN'"></span>
            </button>
        </div>
    </div>
</div>
