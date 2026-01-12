<!-- Detail Modal -->
<div 
    x-show="openDetailModal" 
    x-cloak
    class="fixed inset-0 z-[60] flex items-center justify-center px-4 py-6 sm:p-0"
>
    <div x-show="openDetailModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md" @click="openDetailModal = false"></div>

    <div 
        x-show="openDetailModal" 
        x-transition:enter="ease-out duration-300" 
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        class="bg-white rounded-[3rem] shadow-2xl w-full max-w-xl overflow-hidden relative z-10"
    >
        <!-- Modal Content -->
        <template x-if="detailData">
            <div>
                <!-- Header with Type Badge -->
                <div class="relative h-32 bg-slate-900 flex items-end px-10 pb-6">
                    <div class="absolute top-6 right-6">
                        <button @click="openDetailModal = false" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/10 text-white/60 hover:text-white hover:bg-white/20 transition-all">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-white shadow-lg flex items-center justify-center text-slate-900 border-4 border-slate-900 overflow-hidden">
                            <i class="material-icons text-3xl" x-text="modalType === 'siswa' ? 'person' : 'badge'"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-white leading-tight" x-text="modalType === 'siswa' ? detailData.siswa.nama_lengkap : detailData.fungsionaris.nama"></h2>
                            <p class="text-white/50 text-xs font-bold uppercase tracking-widest mt-1" x-text="modalType === 'siswa' ? 'Siswa / ' + (detailData.siswa.kelas ? detailData.siswa.kelas.nama : '-') : 'Pegawai / ' + detailData.fungsionaris.jabatan"></p>
                        </div>
                    </div>
                </div>

                <div class="p-10 space-y-8">
                    <!-- Violation Primary Info -->
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Jenis Pelanggaran</p>
                            <h3 class="text-xl font-black text-slate-800" x-text="detailData.master_pelanggaran.nama"></h3>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Poin</p>
                            <span class="px-4 py-1.5 bg-rose-50 text-rose-600 rounded-xl font-black text-sm" x-text="'+' + detailData.master_pelanggaran.poin"></span>
                        </div>
                    </div>

                    <!-- Date and Details -->
                    <div class="grid grid-cols-2 gap-8 py-6 border-y border-slate-50">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tanggal Kejadian</p>
                            <div class="flex items-center gap-2 text-slate-700">
                                <i class="material-icons text-slate-300 text-lg">calendar_today</i>
                                <span class="font-bold text-sm" x-text="new Date(detailData.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })"></span>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Pencatat</p>
                            <div class="flex items-center gap-2 text-slate-700">
                                <i class="material-icons text-slate-300 text-lg">edit_note</i>
                                <span class="font-bold text-sm text-slate-400 italic">Sistem Administrasi</span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-3">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Deskripsi Kejadian</p>
                        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 italic text-slate-600 font-medium text-sm leading-relaxed" x-text="detailData.deskripsi || 'Tidak ada deskripsi tambahan.'"></div>
                    </div>

                    <!-- Follow Up -->
                    <div class="space-y-3">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tindak Lanjut</p>
                        <div class="p-6 bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-100 font-bold text-sm leading-relaxed" x-text="detailData.tindak_lanjut || 'Menunggu tindak lanjut.'"></div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-10 py-6 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                    <button @click="openDetailModal = false" class="px-6 py-3 rounded-xl text-sm font-bold text-slate-500 hover:text-slate-800 transition-all">Tutup</button>
                    <a :href="(modalType === 'siswa' ? '{{ url('pelanggaran/pdf-siswa') }}/' : '{{ url('pelanggaran/pdf-pegawai') }}/') + detailData.id" target="_blank" class="flex items-center gap-2 px-8 py-3 bg-slate-800 text-white rounded-xl text-sm font-bold shadow-lg shadow-slate-200 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        <i class="material-icons text-[20px]">picture_as_pdf</i> Cetak Laporan
                    </a>
                </div>
            </div>
        </template>
    </div>
</div>
