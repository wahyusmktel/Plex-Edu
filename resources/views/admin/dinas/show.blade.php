@extends('layouts.app')

@section('title', 'Detail Sekolah - ' . $school->nama_sekolah)

@push('styles')
<style>
    #schoolMap { height: 400px; width: 100%; border-radius: 1.5rem; z-index: 10; background-color: #f8fafc; }
</style>
@endpush

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-5">
            <a href="{{ route('dinas.schools') }}" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-[#d90d8b] hover:border-pink-100 transition-all shadow-sm">
                <i class="material-icons">arrow_back</i>
            </a>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Detail Sekolah</p>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ $school->nama_sekolah }}</h1>
            </div>
        </div>
        <div class="flex items-center gap-3">
             <span class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-xs font-black uppercase border border-indigo-100">
                Jenjang: {{ strtoupper($school->jenjang ?? '-') }}
            </span>
             <span class="px-4 py-2 {{ $school->status_sekolah == 'Negeri' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-purple-50 text-purple-600 border-purple-100' }} rounded-xl text-xs font-black uppercase border">
                {{ $school->status_sekolah }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i class="material-icons text-3xl">people</i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total Siswa</p>
                <h4 class="text-xl font-black text-slate-800">{{ number_format($school->total_siswa) }}</h4>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i class="material-icons text-3xl">class</i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total Kelas</p>
                <h4 class="text-xl font-black text-slate-800">{{ number_format($school->total_kelas) }}</h4>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center">
                <i class="material-icons text-3xl">badge</i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total Guru</p>
                <h4 class="text-xl font-black text-slate-800">{{ number_format($school->total_guru) }}</h4>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Informations -->
        <div class="lg:col-span-5 space-y-8">
            <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm space-y-6">
                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                    <i class="material-icons text-[#d90d8b]">info</i> Informasi Umum
                </h3>
                
                <div class="space-y-4">
                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">NPSN</span>
                        <span class="text-sm font-bold text-slate-700">{{ $school->npsn }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Alamat</span>
                        <span class="text-sm font-bold text-slate-700 leading-relaxed">{{ $school->alamat }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kecamatan</span>
                            <span class="text-sm font-bold text-slate-700">{{ $school->kecamatan }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Desa/Kelurahan</span>
                            <span class="text-sm font-bold text-slate-700">{{ $school->desa_kelurahan }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kabupaten/Kota</span>
                            <span class="text-sm font-bold text-slate-700">{{ $school->kabupaten_kota }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Provinsi</span>
                            <span class="text-sm font-bold text-slate-700">{{ $school->provinsi }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm space-y-6">
                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                    <i class="material-icons text-[#d90d8b]">location_on</i> Titik Koordinat
                </h3>
                <div class="grid grid-cols-2 gap-6">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Latitude</span>
                        <span class="text-sm font-mono font-bold text-slate-600">{{ $school->latitude ?? 'N/A' }}</span>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Longitude</span>
                        <span class="text-sm font-mono font-bold text-slate-600">{{ $school->longitude ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm h-full flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                        <i class="material-icons text-[#d90d8b]">map</i> Lokasi Sekolah
                    </h3>
                </div>
                
                @if($school->latitude && $school->longitude)
                <div id="schoolMap" class="flex-grow border border-slate-100"></div>
                
                <div class="mt-6 p-4 bg-pink-50 rounded-2xl border border-pink-100 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white flex flex-shrink-0 items-center justify-center text-[#d90d8b] shadow-sm">
                        <i class="material-icons">info_outline</i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-800 mb-0.5">Legend Sekolah</p>
                        <p class="text-[11px] font-medium text-slate-500 leading-relaxed">
                            <strong>{{ $school->nama_sekolah }}</strong> (NPSN: {{ $school->npsn }})<br>
                            Terletak di wilayah {{ $school->kecamatan }}, {{ $school->kabupaten_kota }}.
                        </p>
                    </div>
                </div>
                @else
                <div class="flex-grow flex flex-col items-center justify-center bg-slate-50 rounded-[1.5rem] border border-dashed border-slate-200 p-8 text-center">
                    <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-4">
                        <i class="material-icons text-4xl">location_off</i>
                    </div>
                    <h4 class="text-slate-800 font-bold mb-1">Koordinat Belum Diatur</h4>
                    <p class="text-sm text-slate-400 max-w-xs">Sekolah ini belum memiliki titik koordinat yang valid di sistem.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($school->latitude && $school->longitude)
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}"></script>
<script>
    function initMap() {
        const schoolPos = { lat: {{ $school->latitude }}, lng: {{ $school->longitude }} };
        const map = new google.maps.Map(document.getElementById('schoolMap'), {
            zoom: 15,
            center: schoolPos,
            mapTypeControl: true,
            streetViewControl: true,
            fullscreenControl: true,
        });

        const markerColor = '{{ $school->jenjang == "sd" ? "#ef4444" : ($school->jenjang == "smp" ? "#3b82f6" : "#475569") }}';

        const marker = new google.maps.Marker({
            position: schoolPos,
            map: map,
            title: '{{ $school->nama_sekolah }}',
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                fillColor: markerColor,
                fillOpacity: 1,
                strokeColor: '#FFFFFF',
                strokeWeight: 2,
                scale: 12
            }
        });

        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="padding: 8px; font-family: sans-serif;">
                    <p style="margin: 0 0 4px 0; font-weight: 800; color: #1e293b; font-size: 14px;">{{ $school->nama_sekolah }}</p>
                    <p style="margin: 0; color: #64748b; font-size: 11px;">{{ $school->alamat }}</p>
                </div>
            `
        });

        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });
    }

    // Initialize map when window loads
    google.maps.event.addDomListener(window, 'load', initMap);
</script>
@endif
@endpush
