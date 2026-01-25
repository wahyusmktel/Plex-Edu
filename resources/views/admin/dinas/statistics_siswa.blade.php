@extends('layouts.app')

@section('title', 'Statistik Siswa - Literasia')

@section('content')
<div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div class="flex items-center gap-5">
        <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg shadow-blue-100 flex items-center justify-center text-white">
            <i class="material-icons text-3xl">analytics</i>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Monitoring Siswa</p>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Statistik Siswa Nasional</h1>
        </div>
    </div>
</div>

<!-- Line Chart Section -->
<div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm mb-8" x-data="studentChart()">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8">
        <div>
            <h3 class="text-xl font-extrabold text-slate-800">Tren Registrasi Siswa</h3>
            <p class="text-sm text-slate-400 font-medium">Statistik berdasarkan jenis kelamin (12 bulan terakhir)</p>
        </div>
        
        <!-- Jenjang Filter -->
        <div class="flex flex-wrap gap-2">
            @foreach($jenjangList as $key => $label)
            <button 
                @click="filterJenjang('{{ $key }}')"
                :class="activeJenjang === '{{ $key }}' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-slate-50 text-slate-600 hover:bg-slate-100'"
                class="px-5 py-2.5 text-xs font-bold rounded-xl transition-all duration-300"
            >
                {{ $label }}
            </button>
            @endforeach
        </div>
    </div>
    
    <!-- Legend -->
    <div class="flex items-center gap-6 mb-6">
        <div class="flex items-center gap-2">
            <span class="w-4 h-4 rounded-full bg-gradient-to-r from-blue-400 to-blue-600"></span>
            <span class="text-xs font-bold text-slate-600">Laki-laki</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-4 h-4 rounded-full bg-gradient-to-r from-pink-400 to-pink-600"></span>
            <span class="text-xs font-bold text-slate-600">Perempuan</span>
        </div>
    </div>
    
    <!-- Chart Container -->
    <div class="relative h-[350px]">
        <canvas id="studentLineChart"></canvas>
    </div>
    
    <!-- Stats Summary -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-slate-100">
        <div class="text-center p-4 rounded-2xl bg-blue-50/50">
            <p class="text-2xl font-black text-blue-600" x-text="stats.lakiTotal.toLocaleString()">0</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Laki-laki</p>
        </div>
        <div class="text-center p-4 rounded-2xl bg-pink-50/50">
            <p class="text-2xl font-black text-pink-600" x-text="stats.perempuanTotal.toLocaleString()">0</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Perempuan</p>
        </div>
        <div class="text-center p-4 rounded-2xl bg-emerald-50/50">
            <p class="text-2xl font-black text-emerald-600" x-text="stats.grandTotal.toLocaleString()">0</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Keseluruhan</p>
        </div>
        <div class="text-center p-4 rounded-2xl bg-amber-50/50">
            <p class="text-2xl font-black text-amber-600" x-text="stats.ratio">0%</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Rasio L:P</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Gender Distribution -->
    <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
        <h3 class="text-lg font-extrabold text-slate-800 mb-6">Distribusi Jenis Kelamin</h3>
        <div class="space-y-6">
            @foreach($genderStats as $stat)
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-slate-600">{{ $stat->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        <span class="text-sm font-black text-slate-800">{{ number_format($stat->total) }} Siswa</span>
                    </div>
                    <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full {{ $stat->jenis_kelamin == 'L' ? 'bg-blue-500' : 'bg-pink-500' }} rounded-full transition-all duration-500" style="width: {{ $totalSiswa > 0 ? ($stat->total / $totalSiswa) * 100 : 0 }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Growth -->
    <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
        <h3 class="text-lg font-extrabold text-slate-800 mb-6">Pertumbuhan Registrasi Sekolah</h3>
        <div class="space-y-4 max-h-[300px] overflow-y-auto">
            @foreach($schoolGrowth as $growth)
                <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">
                        +{{ $growth->total }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($growth->month)->translatedFormat('F Y') }}</p>
                        <p class="text-xs text-slate-400 font-medium">Sekolah baru terdaftar</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Stats Per Jenjang -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
    @php
        $jenjangColors = [
            'sd' => ['bg' => 'from-red-400 to-red-600', 'light' => 'red'],
            'smp' => ['bg' => 'from-blue-400 to-blue-600', 'light' => 'blue'],
            'sma_smk' => ['bg' => 'from-slate-500 to-slate-700', 'light' => 'slate'],
        ];
        $jenjangNames = ['sd' => 'SD', 'smp' => 'SMP', 'sma_smk' => 'SMA/SMK'];
    @endphp
    
    @foreach(['sd', 'smp', 'sma_smk'] as $jenjang)
    @php
        $lakiLaki = $siswaPerJenjang->where('jenjang', $jenjang)->where('jenis_kelamin', 'L')->first()->total ?? 0;
        $perempuan = $siswaPerJenjang->where('jenjang', $jenjang)->where('jenis_kelamin', 'P')->first()->total ?? 0;
        $total = $lakiLaki + $perempuan;
    @endphp
    <div class="bg-gradient-to-br {{ $jenjangColors[$jenjang]['bg'] }} rounded-[2rem] p-6 text-white shadow-lg">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                <i class="material-icons">school</i>
            </div>
            <h4 class="text-lg font-black">{{ $jenjangNames[$jenjang] }}</h4>
        </div>
        <p class="text-3xl font-black mb-4">{{ number_format($total) }}</p>
        <div class="flex gap-4 text-sm opacity-90">
            <span><i class="material-icons text-sm align-middle mr-1">male</i>{{ number_format($lakiLaki) }}</span>
            <span><i class="material-icons text-sm align-middle mr-1">female</i>{{ number_format($perempuan) }}</span>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-8 bg-blue-600 rounded-[2rem] p-10 text-white relative overflow-hidden">
    <div class="relative z-10">
        <h2 class="text-3xl font-black mb-2">Total Seluruh Siswa</h2>
        <p class="text-5xl font-black tracking-tighter">{{ number_format($totalSiswa) }}</p>
        <p class="mt-4 opacity-80 font-medium">Data terintegrasi dari seluruh unit pendidikan LITERASIA.</p>
    </div>
    <i class="material-icons absolute -right-10 -bottom-10 text-[200px] opacity-10 rotate-12">groups</i>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
function studentChart() {
    return {
        chart: null,
        activeJenjang: 'all',
        rawData: @json($monthlyStudentData),
        siswaPerJenjang: @json($siswaPerJenjang),
        stats: {
            lakiTotal: 0,
            perempuanTotal: 0,
            grandTotal: 0,
            ratio: '0%'
        },
        
        init() {
            this.calculateStats();
            this.renderChart();
        },
        
        calculateStats() {
            let data = this.siswaPerJenjang;
            if (this.activeJenjang !== 'all') {
                data = data.filter(d => d.jenjang === this.activeJenjang);
            }
            
            this.stats.lakiTotal = data.filter(d => d.jenis_kelamin === 'L').reduce((sum, d) => sum + d.total, 0);
            this.stats.perempuanTotal = data.filter(d => d.jenis_kelamin === 'P').reduce((sum, d) => sum + d.total, 0);
            this.stats.grandTotal = this.stats.lakiTotal + this.stats.perempuanTotal;
            
            if (this.stats.grandTotal > 0) {
                const lakiPercent = Math.round((this.stats.lakiTotal / this.stats.grandTotal) * 100);
                const perempuanPercent = 100 - lakiPercent;
                this.stats.ratio = `${lakiPercent}:${perempuanPercent}`;
            }
        },
        
        getChartData() {
            let filtered = this.rawData;
            if (this.activeJenjang !== 'all') {
                filtered = filtered.filter(d => d.jenjang === this.activeJenjang);
            }
            
            // Get unique months sorted
            const months = [...new Set(filtered.map(d => d.month))].sort();
            
            // Prepare data arrays
            const lakiData = months.map(month => {
                const items = filtered.filter(d => d.month === month && d.jenis_kelamin === 'L');
                return items.reduce((sum, d) => sum + d.total, 0);
            });
            
            const perempuanData = months.map(month => {
                const items = filtered.filter(d => d.month === month && d.jenis_kelamin === 'P');
                return items.reduce((sum, d) => sum + d.total, 0);
            });
            
            // Format month labels
            const labels = months.map(m => {
                const date = new Date(m + '-01');
                return date.toLocaleDateString('id-ID', { month: 'short', year: '2-digit' });
            });
            
            return { labels, lakiData, perempuanData };
        },
        
        renderChart() {
            const ctx = document.getElementById('studentLineChart').getContext('2d');
            const data = this.getChartData();
            
            if (this.chart) {
                this.chart.destroy();
            }
            
            this.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Laki-laki',
                            data: data.lakiData,
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointBackgroundColor: '#3B82F6',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                        },
                        {
                            label: 'Perempuan',
                            data: data.perempuanData,
                            borderColor: '#EC4899',
                            backgroundColor: 'rgba(236, 72, 153, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointBackgroundColor: '#EC4899',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            titleFont: { size: 13, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 12,
                            cornerRadius: 12,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y.toLocaleString() + ' siswa';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { 
                                font: { size: 11, weight: 'bold' },
                                color: '#94A3B8'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { 
                                color: 'rgba(148, 163, 184, 0.1)',
                                drawBorder: false
                            },
                            ticks: { 
                                font: { size: 11, weight: 'bold' },
                                color: '#94A3B8',
                                callback: function(value) {
                                    return value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        },
        
        filterJenjang(jenjang) {
            this.activeJenjang = jenjang;
            this.calculateStats();
            this.renderChart();
        }
    }
}
</script>
@endpush
@endsection
