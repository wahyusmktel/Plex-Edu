@extends('layouts.app')

@section('title', 'E-Voting Sekolah - Literasia')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .tab-active {
        color: #ba80e8;
        border-bottom: 3px solid #ba80e8;
    }
    .widget-gradient {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    }
</style>
@endsection

@section('content')
<div x-data="evotingPage()" x-init="init()" class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">E-Voting Sekolah</h1>
            <p class="text-slate-500 font-medium mt-1">Kelola ajang pemilihan dan pantau hasil suara secara real-time</p>
        </div>
        @if(auth()->user()->role !== 'siswa')
        <div class="flex flex-wrap items-center gap-3">
            <button @click="openCreateModal()" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl text-sm font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                <i class="material-icons text-[20px]">add_circle</i> Tambah Pemilihan
            </button>
        </div>
        @endif
    </div>

    <!-- Tabs Navigation -->
    <div class="flex items-center gap-8 border-b border-slate-100">
        <button 
            @click="activeTab = 'selection'" 
            :class="activeTab === 'selection' ? 'tab-active' : 'text-slate-400'"
            class="pb-4 text-sm font-black uppercase tracking-widest px-2 transition-all"
        >
            {{ auth()->user()->role === 'siswa' ? 'Daftar Pemilihan' : 'Pengaturan Pemilihan' }}
        </button>
        <button 
            @click="activeTab = 'results'" 
            :class="activeTab === 'results' ? 'tab-active' : 'text-slate-400'"
            class="pb-4 text-sm font-black uppercase tracking-widest px-2 transition-all"
        >
            Hasil Vote
        </button>
    </div>

    <!-- Selection Tab Content -->
    <div x-show="activeTab === 'selection'" x-transition class="space-y-6">
        <!-- Search Area -->
        <div class="w-full md:w-96 relative group">
            <form action="{{ route('e-voting.index') }}" method="GET">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search }}" 
                    placeholder="Cari pemilihan..." 
                    class="w-full bg-white border border-slate-200 rounded-2xl pl-12 pr-6 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-100 transition-all outline-none"
                >
                <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-slate-600 transition-colors">search</i>
            </form>
        </div>

        <!-- Elections Table -->
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Judul & Jenis</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Periode</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kandidat</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($elections as $item)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-5">
                                <p class="font-black text-slate-800 tracking-tight">{{ $item->judul }}</p>
                                <p class="text-xs text-slate-400 font-bold mt-0.5 tracking-wide">{{ $item->jenis }}</p>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-slate-600 italic tracking-tight">{{ $item->start_date->format('d M Y, H:i') }}</span>
                                    <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest mt-0.5">s/d</span>
                                    <span class="text-xs font-black text-slate-600 italic tracking-tight">{{ $item->end_date->format('d M Y, H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex -space-x-2">
                                    @foreach($item->candidates->take(3) as $c)
                                    <div class="w-8 h-8 rounded-full bg-[#ba80e8] border-2 border-white flex items-center justify-center text-[10px] font-black text-white" title="{{ $c->student->nama_lengkap }}">
                                        {{ strtoupper(substr($c->student->nama_lengkap, 0, 1)) }}
                                    </div>
                                    @endforeach
                                    @if($item->candidates->count() > 3)
                                    <div class="w-8 h-8 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-[8px] font-black text-slate-400">
                                        +{{ $item->candidates->count() - 3 }}
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                @php
                                    $now = now();
                                    $status = 'Aktif';
                                    $color = 'bg-emerald-50 text-emerald-500';
                                    if ($item->start_date > $now) {
                                        $status = 'Akan Datang';
                                        $color = 'bg-blue-50 text-blue-500';
                                    } elseif ($item->end_date < $now) {
                                        $status = 'Selesai';
                                        $color = 'bg-slate-100 text-slate-400';
                                    }
                                @endphp
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $color }}">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if(auth()->user()->role === 'siswa')
                                        @if($status === 'Aktif')
                                            @if($item->has_voted)
                                                <span class="px-4 py-2 bg-emerald-50 text-emerald-500 rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center gap-1">
                                                    <i class="material-icons text-sm">check_circle</i> SUDAH VOTE
                                                </span>
                                            @else
                                                <button @click="openVoteModal('{{ $item->id }}')" class="px-6 py-2.5 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-pink-50 hover:scale-105 transition-all">
                                                    VOTE SEKARANG
                                                </button>
                                            @endif
                                        @elseif($status === 'Selesai')
                                            <span class="px-4 py-2 bg-slate-100 text-slate-400 rounded-xl text-[10px] font-black uppercase tracking-widest">
                                                SELESAI
                                            </span>
                                        @else
                                            <span class="px-4 py-2 bg-blue-50 text-blue-400 rounded-xl text-[10px] font-black uppercase tracking-widest">
                                                BELUM DIMULAI
                                            </span>
                                        @endif
                                    @else
                                        <button @click="editElection('{{ $item->id }}')" class="p-2 text-blue-500 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors cursor-pointer" title="Edit">
                                            <i class="material-icons text-lg">edit</i>
                                        </button>
                                        <button @click="deleteElection('{{ $item->id }}')" class="p-2 text-rose-500 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors cursor-pointer" title="Hapus">
                                            <i class="material-icons text-lg">delete</i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mx-auto mb-4">
                                    <i class="material-icons text-3xl">how_to_vote</i>
                                </div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tidak ada data pemilihan ditemukan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $elections->links() }}
        </div>
    </div>

    <!-- Results Tab Content -->
    <div x-show="activeTab === 'results'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @forelse($results as $item)
        <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm p-10 widget-gradient flex flex-col items-center text-center">
            <h3 class="text-xl font-black text-slate-800 tracking-tight">{{ $item['judul'] }}</h3>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Total Suara: {{ $item['total_votes'] }}</p>
            
            <!-- Chart Container -->
            <div class="w-full max-w-[250px] aspect-square my-8 relative flex items-center justify-center">
                <canvas id="chart-{{ $item['id'] }}"></canvas>
            </div>

            <!-- Export Buttons -->
            <div class="grid grid-cols-2 gap-4 w-full mt-auto">
                <button @click="exportElection('{{ $item['id'] }}', 'excel')" class="flex items-center justify-center gap-2 px-6 py-3 bg-emerald-50 text-emerald-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-100 transition-all">
                    <i class="material-icons text-sm">description</i> Excel
                </button>
                <button @click="exportElection('{{ $item['id'] }}', 'pdf')" class="flex items-center justify-center gap-2 px-6 py-3 bg-rose-50 text-rose-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-100 transition-all">
                    <i class="material-icons text-sm">picture_as_pdf</i> PDF
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full py-32 flex flex-col items-center justify-center">
            <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-300 mb-6 shadow-inner">
                <i class="material-icons text-5xl">pie_chart</i>
            </div>
            <p class="font-black text-slate-400 text-lg uppercase tracking-widest">Belum ada statistik tersedia</p>
        </div>
        @endforelse
    </div>

    <!-- Modals -->
    @include('admin.e-voting.modals')

</div>
@endsection

@section('scripts')
<script>
    function evotingPage() {
        return {
            activeTab: 'selection',
            openModal: false,
            editMode: false,
            formData: {
                id: '',
                judul: '',
                jenis: '',
                start_date: '',
                end_date: '',
                candidates: [],
            },
            voteModalOpen: false,
            votingElection: null,
            selectedCandidate: null,
            results: @json($results),

            init() {
                this.$nextTick(() => {
                    this.initCharts();
                });
            },

            initCharts() {
                this.results.forEach(result => {
                    const ctx = document.getElementById(`chart-${result.id}`);
                    if (!ctx) return;

                    const labels = result.chart_data.map(d => d.label);
                    const data = result.chart_data.map(d => d.votes);

                    new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: data,
                                backgroundColor: [
                                    '#ba80e8', '#d90d8b', '#0ea5e9', '#f43f5e', '#10b981', '#f59e0b'
                                ],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        font: {
                                            family: 'Inter',
                                            size: 10,
                                            weight: '900'
                                        },
                                        usePointStyle: true,
                                        pointStyle: 'rectRounded'
                                    }
                                },
                                tooltip: {
                                    backgroundColor: '#1e293b',
                                    padding: 12,
                                    titleFont: { size: 10, weight: 'bold' },
                                    bodyFont: { size: 12, weight: 'black' }
                                }
                            }
                        }
                    });
                });
            },

            openCreateModal() {
                this.editMode = false;
                this.formData = {
                    id: '',
                    judul: '',
                    jenis: 'Ketua OSIS',
                    start_date: new Date().toISOString().slice(0, 16),
                    end_date: new Date().toISOString().slice(0, 16),
                    candidates: [
                        { siswa_id: '', no_urut: 1 },
                        { siswa_id: '', no_urut: 2 },
                    ],
                };
                this.openModal = true;
            },

            addCandidate() {
                this.formData.candidates.push({ 
                    siswa_id: '', 
                    no_urut: this.formData.candidates.length + 1 
                });
            },

            removeCandidate(index) {
                if (this.formData.candidates.length > 2) {
                    this.formData.candidates.splice(index, 1);
                    // Re-index no_urut
                    this.formData.candidates.forEach((c, i) => c.no_urut = i + 1);
                } else {
                    Swal.fire('Oops', 'Minimal harus ada 2 kandidat.', 'warning');
                }
            },

            editElection(id) {
                $.get(`{{ url('e-voting') }}/${id}`, (data) => {
                    this.formData = {
                        id: data.id,
                        judul: data.judul,
                        jenis: data.jenis,
                        start_date: data.start_date.slice(0, 16).replace(' ', 'T'),
                        end_date: data.end_date.slice(0, 16).replace(' ', 'T'),
                        candidates: data.candidates.map(c => ({
                            siswa_id: c.siswa_id,
                            no_urut: c.no_urut
                        })),
                    };
                    this.editMode = true;
                    this.openModal = true;
                });
            },

            saveElection() {
                if (!this.validateForm()) return;

                Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                const url = this.editMode ? `{{ url('e-voting') }}/${this.formData.id}` : `{{ route('e-voting.store') }}`;
                const method = this.editMode ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        ...this.formData,
                        _token: '{{ csrf_token() }}',
                        _method: method
                    },
                    success: (res) => {
                        this.openModal = false;
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.success, timer: 1500, showConfirmButton: false }).then(() => {
                            location.reload();
                        });
                    },
                    error: (err) => {
                        let msg = err.responseJSON?.message || 'Terjadi kesalahan.';
                        if (err.responseJSON?.errors) msg = Object.values(err.responseJSON.errors).join('<br>');
                        Swal.fire('Oops...', msg, 'error');
                    }
                });
            },

            validateForm() {
                if (!this.formData.judul || !this.formData.jenis || !this.formData.start_date || !this.formData.end_date) {
                    Swal.fire('Oops', 'Semua field wajib diisi!', 'warning');
                    return false;
                }
                const hasDuplicate = new Set(this.formData.candidates.map(c => c.siswa_id)).size !== this.formData.candidates.length;
                if (hasDuplicate) {
                    Swal.fire('Oops', 'Kandidat tidak boleh duplikat!', 'warning');
                    return false;
                }
                const emptyCandi = this.formData.candidates.some(c => !c.siswa_id);
                if (emptyCandi) {
                    Swal.fire('Oops', 'Semua slot kandidat harus dipilih!', 'warning');
                    return false;
                }
                return true;
            },

            deleteElection(id) {
                Swal.fire({
                    title: 'Hapus Pemilihan?',
                    text: "Data pemilihan dan semua suara akan terhapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d90d8b',
                    confirmButtonText: 'Ya, Hapus'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('e-voting') }}/${id}`,
                            method: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: (res) => {
                                Swal.fire('Dihapus!', res.success, 'success').then(() => location.reload());
                            }
                        });
                    }
                });
            },

            exportElection(id, format) {
                if (format === 'excel') {
                    window.location.href = `{{ url('e-voting') }}/${id}/export/excel`;
                } else {
                    window.location.href = `{{ url('e-voting') }}/${id}/export/pdf`;
                }
            },

            openVoteModal(id) {
                $.get(`{{ url('e-voting') }}/${id}`, (data) => {
                    this.votingElection = data;
                    this.selectedCandidate = null;
                    this.voteModalOpen = true;
                });
            },

            submitVote() {
                if (!this.selectedCandidate) {
                    Swal.fire('Eitss!', 'Pilih salah satu kandidat dulu ya.', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Yakin dengan pilihanmu?',
                    text: "Pilihan yang sudah dikirim tidak bisa diubah loh.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d90d8b',
                    confirmButtonText: 'Ya, Kirim Suara'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('e-voting') }}/${this.votingElection.id}/vote`,
                            method: 'POST',
                            data: {
                                candidate_id: this.selectedCandidate,
                                _token: '{{ csrf_token() }}'
                            },
                            success: (res) => {
                                this.voteModalOpen = false;
                                Swal.fire('Berhasil!', res.success, 'success').then(() => location.reload());
                            },
                            error: (err) => {
                                Swal.fire('Gagal', err.responseJSON?.error || 'Terjadi kesalahan.', 'error');
                            }
                        });
                    }
                });
            }
        }
    }
</script>
@endsection
