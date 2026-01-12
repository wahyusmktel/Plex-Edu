<!-- Modal Container -->
<div 
    x-show="openModal" 
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:p-0"
>
    <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="openModal = false"></div>

    <div 
        x-show="openModal" 
        x-transition:enter="ease-out duration-300" 
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-2xl overflow-hidden relative z-10 flex flex-col"
    >
        <!-- Modal Header -->
        <div class="px-10 py-8 flex items-center justify-between border-b border-slate-50">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">
                    <span x-text="editMode ? 'Edit ' : 'Tambah '"></span>
                    <span x-show="modalType === 'master'">Master Pelanggaran</span>
                    <span x-show="modalType === 'siswa'">Catatan Pelanggaran Siswa</span>
                    <span x-show="modalType === 'pegawai'">Catatan Pelanggaran Pegawai</span>
                </h2>
                <p class="text-slate-400 font-medium text-sm mt-1">Lengkapi form di bawah ini dengan benar.</p>
            </div>
            <button @click="openModal = false" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all">
                <i class="material-icons">close</i>
            </button>
        </div>

        <div class="p-10 space-y-6 overflow-y-auto max-h-[70vh]">
            
            <!-- Form: Master Pelanggaran -->
            <div x-show="modalType === 'master'" class="space-y-6">
                <div class="space-y-1.5">
                    <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Nama Pelanggaran</label>
                    <input type="text" x-model="masterData.nama" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-100 transition-all">
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Kategori</label>
                        <select x-model="masterData.jenis" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-100 transition-all">
                            <option value="siswa">Siswa</option>
                            <option value="pegawai">Pegawai</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Poin</label>
                        <input type="number" x-model="masterData.poin" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-100 transition-all">
                    </div>
                </div>
            </div>

            <!-- Form: Pelanggaran Siswa -->
            <div x-show="modalType === 'siswa'" class="space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Tanggal</label>
                        <input type="date" x-model="siswaData.tanggal" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Siswa</label>
                        <select x-model="siswaData.siswa_id" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700">
                            <option value="">Pilih Siswa</option>
                            @foreach($siswas as $s)
                                <option value="{{ $s->id }}">{{ $s->nama_lengkap }} ({{ $s->kelas->nama ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Jenis Pelanggaran</label>
                    <select x-model="siswaData.master_pelanggaran_id" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700">
                        <option value="">Pilih Pelanggaran</option>
                        @foreach($masterSiswa as $m)
                            <option value="{{ $m->id }}">{{ $m->nama }} ({{ $m->poin }} Poin)</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Deskripsi</label>
                    <textarea x-model="siswaData.deskripsi" rows="3" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700"></textarea>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Tindak Lanjut</label>
                    <textarea x-model="siswaData.tindak_lanjut" rows="2" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700"></textarea>
                </div>
            </div>

            <!-- Form: Pelanggaran Pegawai -->
            <div x-show="modalType === 'pegawai'" class="space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Tanggal</label>
                        <input type="date" x-model="pegawaiData.tanggal" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Pegawai / Guru</label>
                        <select x-model="pegawaiData.fungsionaris_id" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700">
                            <option value="">Pilih Pegawai</option>
                            @foreach($pegawais as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }} ({{ strtoupper($p->jabatan) }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Jenis Pelanggaran</label>
                    <select x-model="pegawaiData.master_pelanggaran_id" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700">
                        <option value="">Pilih Pelanggaran</option>
                        @foreach($masterPegawai as $m)
                            <option value="{{ $m->id }}">{{ $m->nama }} ({{ $m->poin }} Poin)</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Deskripsi</label>
                    <textarea x-model="pegawaiData.deskripsi" rows="3" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700"></textarea>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Tindak Lanjut</label>
                    <textarea x-model="pegawaiData.tindak_lanjut" rows="2" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700"></textarea>
                </div>
            </div>

        </div>

        <!-- Modal Footer -->
        <div class="px-10 py-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
            <button @click="openModal = false" class="px-8 py-3.5 rounded-2xl text-sm font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-all">Batal</button>
            <button 
                @click="modalType === 'master' ? saveMaster() : (modalType === 'siswa' ? saveSiswa() : savePegawai())" 
                class="px-10 py-3.5 rounded-2xl bg-slate-800 text-white text-sm font-bold shadow-lg shadow-slate-100 hover:scale-[1.02] active:scale-[0.98] transition-all"
            >
                Simpan Data
            </button>
        </div>
    </div>
</div>
