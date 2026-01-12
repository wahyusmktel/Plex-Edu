<!-- E-Voting Create/Edit Modal -->
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
                <h2 class="text-xl font-black text-slate-800 tracking-tight" x-text="editMode ? 'Edit Pemilihan' : 'Tambah Pemilihan Baru'"></h2>
                <p class="text-slate-400 text-xs font-medium mt-0.5">Konfigurasikan sesi pemilihan dan kandidat.</p>
            </div>
            <button @click="openModal = false" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all">
                <i class="material-icons text-xl">close</i>
            </button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="p-8 overflow-y-auto custom-scrollbar flex-grow bg-white">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                
                <!-- Left Side: Basic Info -->
                <div class="space-y-6">
                    <div class="bg-slate-50 rounded-[2.5rem] p-8 space-y-6">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-center">Informasi Dasar</label>
                        
                        <!-- Title -->
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Judul Pemilihan</label>
                            <input 
                                type="text" 
                                x-model="formData.judul"
                                placeholder="Contoh: Pemilihan Ketua OSIS 2026" 
                                class="w-full px-6 py-4 bg-white border-2 border-transparent rounded-2xl focus:border-[#ba80e8] transition-all outline-none font-bold text-slate-700 placeholder:text-slate-300 shadow-sm"
                            >
                        </div>

                        <!-- Type -->
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jenis Pemilihan</label>
                            <select 
                                x-model="formData.jenis"
                                class="w-full px-6 py-4 bg-white border-2 border-transparent rounded-2xl focus:border-[#ba80e8] transition-all outline-none font-bold text-slate-700 shadow-sm appearance-none"
                            >
                                <option value="Ketua OSIS">Ketua OSIS</option>
                                <option value="Siswa Teladan">Siswa Teladan</option>
                                <option value="Ketua Kelas">Ketua Kelas</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Mulai</label>
                                <input 
                                    type="datetime-local" 
                                    x-model="formData.start_date"
                                    class="w-full px-6 py-4 bg-white border-2 border-transparent rounded-2xl focus:border-[#ba80e8] transition-all outline-none font-bold text-slate-700 text-xs shadow-sm"
                                >
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Selesai</label>
                                <input 
                                    type="datetime-local" 
                                    x-model="formData.end_date"
                                    class="w-full px-6 py-4 bg-white border-2 border-transparent rounded-2xl focus:border-[#ba80e8] transition-all outline-none font-bold text-slate-700 text-xs shadow-sm"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Candidates Management -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between ml-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Daftar Kandidat</label>
                        <button @click="addCandidate()" class="text-[10px] font-black text-[#ba80e8] uppercase tracking-widest hover:underline">+ Tambah Kandidat</button>
                    </div>

                    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                        <template x-for="(candidate, index) in formData.candidates" :key="index">
                            <div class="group bg-white border border-slate-100 rounded-3xl p-4 shadow-sm hover:border-[#ba80e8] transition-all relative">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center font-black text-[#ba80e8] text-sm" x-text="index + 1"></div>
                                    <div class="flex-grow">
                                        <select 
                                            x-model="candidate.siswa_id"
                                            class="w-full bg-transparent border-none focus:ring-0 font-bold text-slate-700 text-sm appearance-none"
                                        >
                                            <option value="">Pilih Siswa...</option>
                                            @foreach($siswas as $s)
                                            <option value="{{ $s->id }}">{{ $s->nama_lengkap }} ({{ $s->nisn }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button @click="removeCandidate(index)" class="p-2 text-slate-300 hover:text-rose-500 transition-colors">
                                        <i class="material-icons text-lg">remove_circle_outline</i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                    
                    <div class="bg-blue-50/50 rounded-2xl p-4 flex gap-3">
                        <i class="material-icons text-blue-400 text-sm">info</i>
                        <p class="text-[9px] text-blue-600 font-bold uppercase tracking-widest leading-relaxed">
                            Kandidat otomatis diurutkan berdasarkan nomor di atas.<br>
                            Siswa yang sudah dipilih tidak muncul lagi di list (opsional).
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-8 py-5 border-t border-slate-100 flex justify-between items-center bg-white sticky bottom-0 z-20">
            <button @click="openModal = false" class="px-6 py-3 rounded-2xl text-xs font-bold text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-all uppercase tracking-widest">Batal</button>
            <button @click="saveElection()" class="flex items-center gap-3 px-8 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl text-xs font-black shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all cursor-pointer">
                <i class="material-icons text-lg" x-text="editMode ? 'save' : 'how_to_reg'"></i>
                <span x-text="editMode ? 'SIMPAN PERUBAHAN' : 'BUAT PEMILIHAN'"></span>
            </button>
        </div>
    </div>
</div>
