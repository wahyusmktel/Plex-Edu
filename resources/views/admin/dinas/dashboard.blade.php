@extends('layouts.app')

@section('title', 'Dinas Dashboard - Literasia')

@section('content')
<!-- Header Section -->
<div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div class="flex items-center gap-5">
        <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br from-[#ba80e8] to-[#d90d8b] shadow-lg shadow-pink-100 flex items-center justify-center text-white">
            <i class="material-icons text-3xl">account_balance</i>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Dinas Pendidikan</p>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Pusat Data Pendidikan LITERASIA</h1>
        </div>
    </div>
</div>

<!-- Central Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <x-stat-card icon="business" label="Total Sekolah" value="{{ number_format($totalSchools) }}" color="blue" />
    <x-stat-card icon="pending_actions" label="Menunggu Persetujuan" value="{{ number_format($pendingSchools) }}" color="yellow" />
    <x-stat-card icon="verified" label="Sekolah Aktif" value="{{ number_format($activeSchools) }}" color="emerald" />
    <x-stat-card icon="groups" label="Total Siswa Nasional" value="{{ number_format($totalSiswaAcrossSchools) }}" color="pink" />
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Registrasi Terbaru -->
    <div class="lg:col-span-8 bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">Registrasi Sekolah Terbaru</h3>
                <p class="text-sm text-slate-400 font-medium">Monitoring pendaftaran sekolah baru</p>
            </div>
            <a href="{{ route('dinas.index') }}" class="px-6 py-2.5 bg-slate-50 text-[#d90d8b] text-sm font-bold rounded-xl border border-slate-100 hover:bg-pink-50 transition-all">
                Lihat Semua
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">
                        <th class="pb-4 px-2">Sekolah</th>
                        <th class="pb-4 px-2">NPSN</th>
                        <th class="pb-4 px-2">Wilayah</th>
                        <th class="pb-4 px-2">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($latestRegistrations as $school)
                    <tr class="group hover:bg-slate-50/50 transition-all">
                        <td class="py-4 px-2">
                            <p class="font-bold text-slate-700 text-sm">{{ $school->nama_sekolah }}</p>
                            <p class="text-[10px] font-medium text-slate-400">Terdaftar {{ $school->created_at->diffForHumans() }}</p>
                        </td>
                        <td class="py-4 px-2 font-mono text-xs font-bold text-slate-500">{{ $school->npsn }}</td>
                        <td class="py-4 px-2 text-xs font-bold text-slate-600">{{ $school->kabupaten_kota }}, {{ $school->provinsi }}</td>
                        <td class="py-4 px-2">
                            @if($school->status === 'pending')
                                <span class="px-3 py-1 bg-yellow-50 text-yellow-600 text-[10px] font-black rounded-full uppercase border border-yellow-100">Pending</span>
                            @elseif($school->status === 'approved')
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-full uppercase border border-emerald-100">Approved</span>
                            @else
                                <span class="px-3 py-1 bg-rose-50 text-rose-600 text-[10px] font-black rounded-full uppercase border border-rose-100">Rejected</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-slate-400 font-bold italic">Belum ada registrasi baru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Stats Summary & Map -->
    <div class="lg:col-span-4 space-y-6">

        <div class="bg-gradient-to-br from-[#ba80e8] to-[#d90d8b] rounded-[2rem] p-8 text-white shadow-lg shadow-pink-100">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center">
                    <i class="material-icons text-white">person</i>
                </div>
                <div>
                    <h4 class="text-sm font-bold opacity-80">Total Guru Nasional</h4>
                    <p class="text-3xl font-black">{{ number_format($totalGuruAcrossSchools) }}</p>
                </div>
            </div>
            <div class="h-1 w-full bg-white/20 rounded-full overflow-hidden">
                <div class="h-full bg-white rounded-full w-3/4"></div>
            </div>
            <p class="text-[10px] font-bold mt-4 opacity-70 tracking-widest uppercase italic">Updated in real-time</p>
        </div>

        <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
            <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6 border-b border-slate-50 pb-4">Panduan Dinas</h4>
            <div class="space-y-4">
                <div class="flex gap-4 group cursor-pointer">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex flex-shrink-0 items-center justify-center group-hover:bg-blue-500 group-hover:text-white transition-all">
                        <i class="material-icons text-xl">help_outline</i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-700 leading-snug">Cara Approval Sekolah Baru</p>
                    </div>
                </div>
                <div class="flex gap-4 group cursor-pointer">
                    <div class="w-10 h-10 rounded-xl bg-yellow-50 text-yellow-500 flex flex-shrink-0 items-center justify-center group-hover:bg-yellow-500 group-hover:text-white transition-all">
                        <i class="material-icons text-xl">summarize</i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-700 leading-snug">Download Laporan Bulanan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- School Distribution Map Full Width -->
<div class="mt-8">
    <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm" x-data="schoolMapWidget()">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h4 class="text-xl font-extrabold text-slate-800 uppercase tracking-tight">Peta Sebaran Sekolah Nasional</h4>
                <p class="text-sm font-medium text-slate-400">Monitoring distribusi sekolah berdasarkan jenjang pendidikan</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-6 px-6 py-3 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-sm shadow-red-100"></span> SD: <span x-text="counts.sd" class="text-slate-800 ml-1">0</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500 shadow-sm shadow-blue-100"></span> SMP: <span x-text="counts.smp" class="text-slate-800 ml-1">0</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-600 shadow-sm shadow-slate-100"></span> SMA/SMK: <span x-text="counts.sma_smk" class="text-slate-800 ml-1">0</span>
                    </div>
                </div>
                
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="px-6 py-3 bg-white border border-slate-100 text-slate-600 text-sm font-bold rounded-2xl hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm">
                        <i class="material-icons text-lg">filter_list</i>
                        Filter Jenjang
                    </button>
                    <div x-show="open" x-cloak class="absolute right-0 mt-2 w-56 bg-white rounded-[1.5rem] shadow-xl border border-slate-50 p-2 z-[1001]">
                        <button @click="filterJenjang('all'); open = false" class="w-full text-left px-4 py-3 text-xs font-bold rounded-xl transition-colors" :class="activeFilter === 'all' ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50'">
                            <i class="material-icons text-base align-middle mr-2">all_inclusive</i> Semua Jenjang
                        </button>
                        <button @click="filterJenjang('sd'); open = false" class="w-full text-left px-4 py-3 text-xs font-bold rounded-xl transition-colors" :class="activeFilter === 'sd' ? 'bg-red-50 text-red-600' : 'text-slate-600 hover:bg-slate-50'">
                            <i class="material-icons text-base align-middle mr-2 text-red-500">school</i> Sekolah Dasar (SD)
                        </button>
                        <button @click="filterJenjang('smp'); open = false" class="w-full text-left px-4 py-3 text-xs font-bold rounded-xl transition-colors" :class="activeFilter === 'smp' ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50'">
                            <i class="material-icons text-base align-middle mr-2 text-blue-500">history_edu</i> SMP
                        </button>
                        <button @click="filterJenjang('sma_smk'); open = false" class="w-full text-left px-4 py-3 text-xs font-bold rounded-xl transition-colors" :class="activeFilter === 'sma_smk' ? 'bg-slate-100 text-slate-700' : 'text-slate-600 hover:bg-slate-50'">
                            <i class="material-icons text-base align-middle mr-2 text-slate-600">apartment</i> SMA/SMK
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="distributionMap" class="w-full h-[500px] rounded-[1.5rem] border border-slate-100 shadow-inner z-10"></div>
    </div>
</div>

@push('styles')
<style>
    #distributionMap { background-color: #f8fafc; }
</style>
@endpush

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}"></script>
<script>
    function schoolMapWidget() {
        return {
            map: null,
            markers: [],
            activeFilter: 'all',
            counts: { sd: 0, smp: 0, sma_smk: 0 },
            schools: @json($schoolsWithLocation),

            init() {
                this.calculateCounts();
                this.initMap();
                this.renderMarkers();
            },

            calculateCounts() {
                this.counts.sd = this.schools.filter(s => s.jenjang === 'sd').length;
                this.counts.smp = this.schools.filter(s => s.jenjang === 'smp').length;
                this.counts.sma_smk = this.schools.filter(s => s.jenjang === 'sma_smk').length;
            },

            initMap() {
                const defaultCenter = { lat: -2.5489, lng: 118.0149 };
                this.map = new google.maps.Map(document.getElementById('distributionMap'), {
                    center: defaultCenter,
                    zoom: 4,
                    mapTypeControl: true,
                    streetViewControl: false,
                    fullscreenControl: true,
                    styles: [
                        {
                            "featureType": "administrative",
                            "elementType": "geometry",
                            "stylers": [{ "visibility": "off" }]
                        },
                        {
                            "featureType": "poi",
                            "stylers": [{ "visibility": "off" }]
                        }
                    ]
                });
                
                if (this.schools.length > 0) {
                    const bounds = new google.maps.LatLngBounds();
                    this.schools.forEach(s => bounds.extend({ lat: parseFloat(s.latitude), lng: parseFloat(s.longitude) }));
                    this.map.fitBounds(bounds);
                }
            },

            renderMarkers() {
                // Clear existing markers
                this.markers.forEach(m => m.setMap(null));
                this.markers = [];

                this.schools.forEach(school => {
                    if (this.activeFilter !== 'all' && school.jenjang !== this.activeFilter) return;

                    const markerColor = school.jenjang === 'sd' ? '#ef4444' : (school.jenjang === 'smp' ? '#3b82f6' : '#475569');
                    
                    const marker = new google.maps.Marker({
                        position: { lat: parseFloat(school.latitude), lng: parseFloat(school.longitude) },
                        map: this.map,
                        title: school.nama_sekolah,
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            fillColor: markerColor,
                            fillOpacity: 1,
                            strokeColor: '#FFFFFF',
                            strokeWeight: 2,
                            scale: 10
                        }
                    });

                    const infoWindow = new google.maps.InfoWindow({
                        content: `
                            <div style="padding: 8px; font-family: sans-serif;">
                                <p style="margin: 0 0 4px 0; font-weight: 800; color: #1e293b; font-size: 14px;">${school.nama_sekolah}</p>
                                <p style="margin: 0 0 8px 0; color: #64748b; font-size: 11px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">${school.alamat}</p>
                                <a href="/dinas/schools/${school.id}" style="color: #d90d8b; font-weight: 700; font-size: 12px; text-decoration: none;">Lihat Detail</a>
                            </div>
                        `
                    });

                    marker.addListener('click', () => {
                        infoWindow.open(this.map, marker);
                    });

                    this.markers.push(marker);
                });
            },

            filterJenjang(jenjang) {
                this.activeFilter = jenjang;
                this.renderMarkers();
            }
        }
    }
</script>
@endpush
@endsection
