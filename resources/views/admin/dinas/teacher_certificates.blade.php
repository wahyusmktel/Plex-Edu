@extends('layouts.app')

@section('title', 'Monitoring Sertifikat Guru - Literasia')

@section('content')
<div x-data="certificateMonitoring()">
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg shadow-emerald-100 flex items-center justify-center text-white">
                <i class="material-icons text-3xl">workspace_premium</i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Pengembangan Karir</p>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Monitoring Sertifikat Guru</h1>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="bg-white px-6 py-3 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="text-right">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Sertifikat</p>
                    <p class="text-xl font-black text-emerald-600">{{ number_format($totalSertifikat) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center">
                    <i class="material-icons">verified</i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats & Chart Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Chart Column -->
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-lg font-black text-slate-800">Statistik Koleksi Per Sekolah</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Top 10 Sekolah Teraktif</p>
                </div>
                <div class="flex items-center gap-4 text-[10px] font-black uppercase tracking-widest">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="text-slate-500">Bersertifikat</span>
                    </div>
                </div>
            </div>
            <div class="h-[300px] relative">
                <canvas id="certificateChart"></canvas>
            </div>
        </div>

        <!-- Overview Cards -->
        <div class="flex flex-col gap-6">
            <div class="flex-1 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-[2.5rem] p-8 text-white shadow-lg relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-xs font-black uppercase tracking-[0.2em] opacity-80 mb-2">Total Guru</p>
                    <h2 class="text-4xl font-black mb-1">{{ number_format($totalGuru) }}</h2>
                    <p class="text-sm font-medium opacity-70">Guru terdaftar di sistem</p>
                </div>
                <i class="material-icons absolute -right-6 -bottom-6 text-[120px] opacity-10">groups</i>
            </div>
            
            <div class="flex-1 bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center">
                        <i class="material-icons text-2xl">pending_actions</i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Capaian</p>
                        <p class="text-xl font-black text-slate-800">{{ number_format($guruDenganSertifikat) }} Guru</p>
                    </div>
                </div>
                <div class="w-full h-3 bg-slate-50 rounded-full overflow-hidden mb-3">
                    <div class="h-full bg-emerald-500 rounded-full transition-all duration-1000" style="width: {{ $totalGuru > 0 ? ($guruDenganSertifikat / $totalGuru) * 100 : 0 }}%"></div>
                </div>
                <p class="text-xs font-bold text-slate-400 text-center">
                    {{ round($totalGuru > 0 ? ($guruDenganSertifikat / $totalGuru) * 100 : 0, 1) }}% Guru telah mengunggah sertifikat
                </p>
            </div>
        </div>
    </div>

    <!-- Filter & Table Section -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <!-- Toolbar -->
        <div class="p-8 border-b border-slate-50 bg-slate-50/30">
            <form action="{{ route('dinas.certificates') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative group">
                    <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors">search</i>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari Nama Guru atau Nama Sertifikat..."
                        class="w-full pl-12 pr-6 py-4 bg-white border border-slate-200 rounded-2xl text-sm font-medium focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all"
                    >
                </div>
                
                <!-- Searchable School Dropdown -->
                <div class="w-full md:w-80 relative" x-data="schoolDropdown()" @click.away="open = false">
                    <input type="hidden" name="school_id" :value="selectedId">
                    
                    <button 
                        type="button"
                        @click="open = !open"
                        class="w-full flex items-center justify-between pl-5 pr-4 py-4 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all shadow-sm group"
                    >
                        <span x-text="selectedName || 'Pilih/Cari Sekolah...'" class="truncate mr-2 text-slate-700"></span>
                        <i class="material-icons text-slate-400 group-hover:text-emerald-500 transition-colors" x-text="open ? 'expand_less' : 'expand_more'"></i>
                    </button>

                    <div 
                        x-show="open" 
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        class="absolute left-0 right-0 mt-3 p-3 bg-white border border-slate-100 rounded-[2rem] shadow-2xl z-[60] origin-top"
                    >
                        <!-- Dropdown Search -->
                        <div class="relative mb-3 group">
                            <i class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg group-focus-within:text-emerald-500 transition-colors">search</i>
                            <input 
                                type="text" 
                                x-model="search" 
                                placeholder="Cari NPSN atau Nama..." 
                                class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold focus:outline-none focus:border-emerald-500 transition-all"
                                @click.stop
                                x-ref="searchInput"
                            >
                        </div>

                        <!-- Options List -->
                        <div class="max-h-64 overflow-y-auto custom-scrollbar pr-2 space-y-1">
                            <button 
                                type="button"
                                @click="selectSchool(null, 'Semua Sekolah')"
                                class="w-full text-left px-4 py-3 rounded-xl hover:bg-slate-50 transition-colors flex items-center gap-3 group"
                                :class="selectedId == '' ? 'bg-emerald-50' : ''"
                            >
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center" :class="selectedId == '' ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400 group-hover:bg-emerald-500 group-hover:text-white'">
                                    <i class="material-icons text-lg">apps</i>
                                </div>
                                <span class="text-xs font-black uppercase tracking-wider" :class="selectedId == '' ? 'text-emerald-700' : 'text-slate-500 group-hover:text-emerald-700'">Semua Sekolah</span>
                            </button>

                            <template x-for="school in filteredSchools" :key="school.id">
                                <button 
                                    type="button"
                                    @click="selectSchool(school.id, school.npsn + ' - ' + school.nama_sekolah)"
                                    class="w-full text-left px-4 py-3 rounded-xl hover:bg-slate-50 transition-colors flex items-center gap-3 group"
                                    :class="selectedId == school.id ? 'bg-emerald-50' : ''"
                                >
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" :class="selectedId == school.id ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400 group-hover:bg-emerald-500 group-hover:text-white'">
                                        <i class="material-icons text-lg">school</i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1" x-text="school.npsn"></p>
                                        <p class="text-xs font-bold truncate" :class="selectedId == school.id ? 'text-emerald-700' : 'text-slate-700 group-hover:text-emerald-700'" x-text="school.nama_sekolah"></p>
                                    </div>
                                    <div class="shrink-0 text-right">
                                        <p class="text-[10px] font-black text-slate-300 uppercase leading-none mb-1">Guru</p>
                                        <p class="text-xs font-black text-slate-400" x-text="school.total_guru"></p>
                                    </div>
                                </button>
                            </template>

                            <div x-show="filteredSchools.length === 0" class="py-10 text-center">
                                <i class="material-icons text-slate-200 text-4xl mb-2">sentiment_dissatisfied</i>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sekolah tidak ditemukan</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if(request()->anyFilled(['search', 'school_id']))
                <a href="{{ route('dinas.certificates') }}" class="px-6 py-4 bg-slate-100 text-slate-600 rounded-2xl text-sm font-bold hover:bg-slate-200 transition-all flex items-center justify-center">
                    <i class="material-icons text-lg">close</i>
                </a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                        <th class="py-6 px-8">Guru & Sekolah</th>
                        <th class="py-6 px-8">Koleksi Sertifikat</th>
                        <th class="py-6 px-8 text-center">Status</th>
                        <th class="py-6 px-8 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    @forelse($teachers as $teacher)
                    <tr class="group hover:bg-slate-50/50 transition-all">
                        <td class="py-6 px-8">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-slate-400 shrink-0">
                                    <i class="material-icons">person</i>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-base leading-tight">{{ $teacher->name }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">{{ $teacher->school->nama_sekolah ?? 'No School' }}</span>
                                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $teacher->school->npsn ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-6 px-8">
                            @if($teacher->fungsionaris && $teacher->fungsionaris->teacherCertificates->count() > 0)
                                <div class="flex flex-col gap-2">
                                    @foreach($teacher->fungsionaris->teacherCertificates->take(2) as $cert)
                                    <div class="flex items-center gap-2">
                                        <i class="material-icons text-[16px] text-emerald-500">check_circle</i>
                                        <span class="font-bold text-slate-700 truncate max-w-[200px]">{{ $cert->name }}</span>
                                        <span class="text-[10px] font-black text-slate-300 uppercase">({{ $cert->year }})</span>
                                    </div>
                                    @endforeach
                                    @if($teacher->fungsionaris->teacherCertificates->count() > 2)
                                        <span class="text-[10px] font-black text-blue-500 ml-6 uppercase">+{{ $teacher->fungsionaris->teacherCertificates->count() - 2 }} Sertifikat Lainnya</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-xs font-bold text-slate-400 italic">Belum ada data diunggah</span>
                            @endif
                        </td>
                        <td class="py-6 px-8 text-center">
                            @if($teacher->fungsionaris && $teacher->fungsionaris->teacherCertificates->count() > 0)
                                <span class="inline-flex items-center px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-full uppercase border border-emerald-100 italic">
                                    Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 bg-slate-50 text-slate-400 text-[10px] font-black rounded-full uppercase border border-slate-100 italic">
                                    No Data
                                </span>
                            @endif
                        </td>
                        <td class="py-6 px-8 text-right">
                            @if($teacher->fungsionaris && $teacher->fungsionaris->teacherCertificates->count() > 0)
                            <div class="flex items-center justify-end gap-2">
                                @foreach($teacher->fungsionaris->teacherCertificates as $cert)
                                <button 
                                    @click="previewFile('{{ asset('storage/' . $cert->file_path) }}', '{{ $cert->name }}')"
                                    class="w-10 h-10 flex items-center justify-center text-blue-500 bg-blue-50 hover:bg-blue-100 rounded-xl transition-all cursor-pointer group/btn" 
                                    title="Lihat: {{ $cert->name }}"
                                >
                                    <i class="material-icons text-xl group-hover/btn:scale-110 transition-transform">visibility</i>
                                </button>
                                @endforeach
                            </div>
                            @else
                            <button disabled class="w-10 h-10 flex items-center justify-center text-slate-200 bg-slate-50 rounded-xl cursor-not-allowed">
                                <i class="material-icons text-xl">visibility_off</i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mb-4">
                                    <i class="material-icons text-4xl">inventory_2</i>
                                </div>
                                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Tidak ada data guru/sertifikat ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($teachers->hasPages())
        <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/30">
            {{ $teachers->links() }}
        </div>
        @endif
    </div>

    <!-- Preview Modal -->
    <div 
        x-show="modalOpen" 
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4"
    >
        <div 
            x-show="modalOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            @click="closeModal()" 
            class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
        ></div>
        
        <div 
            x-show="modalOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-5xl h-[85vh] flex flex-col relative z-10 overflow-hidden"
        >
            <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black text-slate-800" x-text="previewTitle">Preview Sertifikat</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Peninjauan Dokumen Guru</p>
                </div>
                <button @click="closeModal()" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all">
                    <i class="material-icons">close</i>
                </button>
            </div>
            
            <div class="flex-1 bg-slate-100 relative">
                <template x-if="getFileType() === 'pdf'">
                    <iframe :src="previewUrl" class="w-full h-full border-none"></iframe>
                </template>
                <template x-if="getFileType() === 'image'">
                    <div class="w-full h-full flex items-center justify-center p-8 overflow-auto">
                        <img :src="previewUrl" class="max-w-full max-h-full rounded-xl shadow-lg border-2 border-white object-contain" alt="Certificate Preview">
                    </div>
                </template>
            </div>
            
            <div class="px-8 py-4 border-t border-slate-100 bg-white flex justify-end">
                <a :href="previewUrl" download class="flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100">
                    <i class="material-icons text-sm">download</i> Link Download
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
function certificateMonitoring() {
    return {
        modalOpen: false,
        previewUrl: '',
        previewTitle: '',
        schoolStats: @json($schoolStats->take(10)), // Send top 10 to chart
        
        init() {
            this.renderChart();
        },
        
        renderChart() {
            const ctx = document.getElementById('certificateChart').getContext('2d');
            const labels = this.schoolStats.map(s => s.nama_sekolah.length > 20 ? s.nama_sekolah.substring(0, 20) + '...' : s.nama_sekolah);
            const dataBersertifikat = this.schoolStats.map(s => s.guru_bersertifikat);
            const dataBelum = this.schoolStats.map(s => s.total_guru - s.guru_bersertifikat);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Guru Bersertifikat',
                            data: dataBersertifikat,
                            backgroundColor: '#10b981',
                            borderRadius: 6,
                            barThickness: 15,
                        }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            cornerRadius: 12,
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 11 },
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { 
                                font: { size: 10, weight: 'bold' },
                                color: '#94a3b8'
                            }
                        },
                        y: {
                            grid: { color: 'rgba(241, 245, 249, 1)', drawBorder: false },
                            ticks: { 
                                font: { size: 10, weight: 'bold' },
                                color: '#64748b'
                            }
                        }
                    }
                }
            });
        },
        
        previewFile(url, title) {
            this.previewUrl = url;
            this.previewTitle = title;
            this.modalOpen = true;
            document.body.style.overflow = 'hidden';
        },
        
        closeModal() {
            this.modalOpen = false;
            document.body.style.overflow = 'auto';
        },
        
        getFileType() {
            if (!this.previewUrl) return '';
            const ext = this.previewUrl.split('.').pop().toLowerCase();
            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) return 'image';
            if (ext === 'pdf') return 'pdf';
            return 'other';
        }
    }
}

function schoolDropdown() {
    return {
        open: false,
        search: '',
        selectedId: '{{ $selectedSchoolId }}',
        selectedName: '',
        allSchools: @json($schools),
        
        init() {
            if (this.selectedId) {
                const school = this.allSchools.find(s => s.id == this.selectedId);
                if (school) this.selectedName = school.npsn + ' - ' + school.nama_sekolah;
            } else {
                this.selectedName = 'Semua Sekolah';
            }

            this.$watch('open', value => {
                if (value) {
                    this.$nextTick(() => {
                        this.$refs.searchInput.focus();
                    });
                }
            });
        },
        
        get filteredSchools() {
            if (!this.search) return this.allSchools;
            const s = this.search.toLowerCase();
            return this.allSchools.filter(school => 
                school.nama_sekolah.toLowerCase().includes(s) || 
                school.npsn.includes(s)
            );
        },
        
        selectSchool(id, name) {
            this.selectedId = id || '';
            this.selectedName = name;
            this.open = false;
            // Submit the form
            this.$nextTick(() => {
                this.$el.closest('form').submit();
            });
        }
    }
}
</script>
@endpush
@endsection
