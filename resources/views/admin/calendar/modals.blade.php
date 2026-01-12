<!-- Event Create/Edit Modal -->
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
        class="bg-white rounded-[3rem] shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden relative z-10 flex flex-col"
    >
        <!-- Modal Header -->
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-white sticky top-0 z-20">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight" x-text="editMode ? 'Edit Acara' : 'Tambah Acara Baru'"></h2>
                <p class="text-slate-400 text-xs font-medium mt-0.5">Lengkapi detail agenda kegiatan sekolah.</p>
            </div>
            <button @click="openModal = false" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all">
                <i class="material-icons text-xl">close</i>
            </button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="p-8 overflow-y-auto custom-scrollbar flex-grow bg-white space-y-6">
            
            <!-- Category Selector -->
            <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kategori Agenda</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <button 
                        @click="formData.category = 'event'"
                        :class="formData.category === 'event' ? 'border-[#ba80e8] bg-purple-50 text-[#ba80e8]' : 'border-slate-100 bg-white text-slate-400'"
                        class="px-4 py-3 rounded-2xl border-2 text-[10px] font-black uppercase tracking-widest transition-all text-center"
                    >Event</button>
                    <button 
                        @click="formData.category = 'holiday'"
                        :class="formData.category === 'holiday' ? 'border-rose-400 bg-rose-50 text-rose-500'"
                        class="px-4 py-3 rounded-2xl border-2 border-slate-100 bg-white text-slate-400 text-[10px] font-black uppercase tracking-widest transition-all text-center"
                    >Libur</button>
                    <button 
                        @click="formData.category = 'exam'"
                        :class="formData.category === 'exam' ? 'border-blue-400 bg-blue-50 text-blue-500'"
                        class="px-4 py-3 rounded-2xl border-2 border-slate-100 bg-white text-slate-400 text-[10px] font-black uppercase tracking-widest transition-all text-center"
                    >Ujian</button>
                    <button 
                        @click="formData.category = 'other'"
                        :class="formData.category === 'other' ? 'border-slate-400 bg-slate-50 text-slate-500'"
                        class="px-4 py-3 rounded-2xl border-2 border-slate-100 bg-white text-slate-400 text-[10px] font-black uppercase tracking-widest transition-all text-center"
                    >Lainnya</button>
                </div>
            </div>

            <!-- Title -->
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Acara / Agenda</label>
                <input 
                    type="text" 
                    x-model="formData.title"
                    placeholder="Contoh: Rapat Pleno Guru" 
                    class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700 placeholder:text-slate-300 shadow-sm"
                >
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Mulai</label>
                    <div class="relative">
                        <input 
                            type="datetime-local" 
                            x-model="formData.start_date"
                            class="w-full pl-12 pr-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700 text-xs shadow-sm"
                        >
                        <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-300">event</i>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Selesai</label>
                    <div class="relative">
                        <input 
                            type="datetime-local" 
                            x-model="formData.end_date"
                            class="w-full pl-12 pr-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700 text-xs shadow-sm"
                        >
                        <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-300">event_busy</i>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Keterangan / Deskripsi</label>
                <textarea 
                    x-model="formData.description"
                    placeholder="Tuliskan detail acara jika diperlukan..." 
                    rows="4"
                    class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-[#ba80e8] focus:bg-white transition-all outline-none font-bold text-slate-700 text-sm placeholder:text-slate-300 shadow-sm resize-none"
                ></textarea>
            </div>

        </div>

        <!-- Modal Footer -->
        <div class="px-8 py-5 border-t border-slate-100 flex justify-between items-center bg-white sticky bottom-0 z-20">
            <div>
                <button 
                    x-show="editMode" 
                    @click="deleteEvent()"
                    class="flex items-center gap-2 px-6 py-3 rounded-2xl text-xs font-bold text-rose-500 hover:bg-rose-50 transition-all uppercase tracking-widest"
                >
                    <i class="material-icons text-lg">delete_outline</i> Hapus
                </button>
            </div>
            <div class="flex items-center gap-3">
                <button @click="openModal = false" class="px-6 py-3 rounded-2xl text-xs font-bold text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-all uppercase tracking-widest">Batal</button>
                <button @click="saveEvent()" class="flex items-center gap-3 px-8 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl text-xs font-black shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all cursor-pointer">
                    <i class="material-icons text-lg" x-text="editMode ? 'save' : 'add_task'"></i>
                    <span x-text="editMode ? 'SIMPAN PERUBAHAN' : 'TAMBAHKAN AGENDA'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Scrollbar */
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
