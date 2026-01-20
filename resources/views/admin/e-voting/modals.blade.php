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

<!-- Student Voting Modal -->
<div 
    x-show="voteModalOpen" 
    x-cloak
    class="fixed inset-0 z-[60] flex items-center justify-center px-4 py-6 sm:p-0"
>
    <div x-show="voteModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md" @click="voteModalOpen = false"></div>

    <div 
        x-show="voteModalOpen" 
        x-transition:enter="ease-out duration-300" 
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        class="bg-white rounded-[3.5rem] shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden relative z-10 flex flex-col"
    >
        <!-- Modal Header -->
        <div class="px-10 py-8 border-b border-slate-50 flex items-center justify-between bg-white sticky top-0 z-20">
            <div>
                <p class="text-[10px] font-black text-[#d90d8b] uppercase tracking-widest mb-1">E-Voting Literasia</p>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight" x-text="votingElection?.judul"></h2>
            </div>
            <button @click="voteModalOpen = false" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all">
                <i class="material-icons">close</i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-10 overflow-y-auto custom-scrollbar flex-grow bg-slate-50/30">
            <div class="text-center mb-10">
                <h3 class="text-lg font-bold text-slate-700">Tentukan Pilihanmu!</h3>
                <p class="text-slate-400 text-sm mt-1">Gunakan hak suaramu untuk menentukan masa depan organisasi.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="candidate in votingElection?.candidates" :key="candidate.id">
                    <div 
                        @click="selectedCandidate = candidate.id"
                        :class="selectedCandidate === candidate.id ? 'border-[#ba80e8] ring-4 ring-purple-50 bg-white' : 'border-slate-100 bg-white hover:border-slate-200'"
                        class="relative p-6 rounded-[2.5rem] border-2 transition-all cursor-pointer group"
                    >
                        <!-- Selection Checkmark -->
                        <div 
                            x-show="selectedCandidate === candidate.id"
                            class="absolute -top-3 -right-3 w-8 h-8 bg-[#ba80e8] text-white rounded-full flex items-center justify-center shadow-lg shadow-purple-200 z-10"
                        >
                            <i class="material-icons text-sm font-bold">check</i>
                        </div>

                        <!-- Candidate Info -->
                        <div class="flex flex-col items-center text-center space-y-4">
                            <!-- Candidate Number -->
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-xl font-black text-slate-400 group-hover:text-[#ba80e8] transition-colors" x-text="candidate.no_urut"></div>
                            
                            <!-- Avatar/Photo Placeholder -->
                            <div class="w-24 h-24 rounded-3xl bg-slate-50 border-2 border-slate-100 flex items-center justify-center overflow-hidden">
                                <i class="material-icons text-4xl text-slate-200" x-show="!candidate.student.foto">person</i>
                                <img :src="'/storage/' + candidate.student.foto" x-show="candidate.student.foto" class="w-full h-full object-cover">
                            </div>

                            <div>
                                <h4 class="font-black text-slate-800 tracking-tight" x-text="candidate.student.nama_lengkap"></h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">NOMOR URUT <span x-text="candidate.no_urut"></span></p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-10 py-6 border-t border-slate-100 flex justify-center bg-white sticky bottom-0 z-20">
            <button 
                @click="submitVote()" 
                :disabled="!selectedCandidate"
                :class="selectedCandidate ? 'bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] opacity-100 scale-100' : 'bg-slate-200 opacity-50 scale-95 cursor-not-allowed'"
                class="flex items-center gap-3 px-12 py-4 text-white rounded-2xl text-sm font-black shadow-xl shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all"
            >
                <i class="material-icons text-xl">how_to_reg</i>
                KIRIM SUARA SEKARANG
            </button>
        </div>
    </div>
</div>
