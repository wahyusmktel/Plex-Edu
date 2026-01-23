@extends('layouts.app')

@section('title', 'Detail Guru - ' . $guru->nama)

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('dinas.master-guru.index') }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-400 hover:text-gray-600 transition-all shadow-sm flex items-center justify-center">
                <i class="material-icons">arrow_back</i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $guru->nama }}</h2>
                <p class="text-gray-600">Detail Lengkap Data Guru dari Dinas</p>
            </div>
        </div>
        <div class="flex gap-2">
            <form action="{{ route('dinas.master-guru.destroy', $guru->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-lg flex items-center transition-all">
                    <i class="material-icons mr-2">delete</i> Hapus Data
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Overview -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                <div class="w-24 h-24 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-blue-500">
                    <i class="material-icons text-5xl">person</i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">{{ $guru->nama }}</h3>
                <p class="text-sm text-gray-500 mb-4">{{ $guru->nuptk ? 'NUPTK: ' . $guru->nuptk : 'Belum ada NUPTK' }}</p>
                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $guru->jenis_ptk }}
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Informasi Kontak</h4>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-gray-50 rounded-lg text-gray-400">
                            <i class="material-icons text-sm">phone</i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 block">Nomor HP</p>
                            <p class="text-sm font-medium text-gray-900">{{ $guru->no_hp ?: '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-gray-50 rounded-lg text-gray-400">
                            <i class="material-icons text-sm">place</i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 block">Domisili (Kecamatan)</p>
                            <p class="text-sm font-medium text-gray-900">{{ $guru->kecamatan ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Data Identitas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Data Identitas & Kepegawaian</h4>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">NIK</p>
                        <p class="text-sm font-medium text-gray-900">{{ $guru->nik ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">NIP</p>
                        <p class="text-sm font-medium text-gray-900">{{ $guru->nip ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Jenis Kelamin</p>
                        <p class="text-sm font-medium text-gray-900">{{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : ($guru->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Tempat, Tanggal Lahir</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ $guru->tempat_lahir ?: '-' }}, {{ $guru->tanggal_lahir ? $guru->tanggal_lahir->format('d F Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Status Kepegawaian</p>
                        <p class="text-sm font-medium text-gray-900">{{ $guru->status_kepegawaian ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Pangkat / Golongan</p>
                        <p class="text-sm font-medium text-gray-900">{{ $guru->pangkat_golongan ?: '-' }} (TMT: {{ $guru->tmt_pangkat ? $guru->tmt_pangkat->format('d/m/Y') : '-' }})</p>
                    </div>
                </div>
            </div>

            <!-- Data Tugas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Data Unit Kerja & Tugas</h4>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-400 mb-1">Tempat Tugas (Satuan Pendidikan)</p>
                        <p class="text-sm font-bold text-blue-600">{{ $guru->tempat_tugas }}</p>
                        <p class="text-xs text-gray-500 mt-1">NPSN: {{ $guru->npsn }} | Status: {{ $guru->status_tugas }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Jabatan PTK</p>
                        <p class="text-sm font-medium text-gray-900">{{ $guru->jabatan_ptk ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Jabatan Kepsek</p>
                        <p class="text-sm font-medium text-gray-900">{{ $guru->jabatan_kepsek ?: 'Tidak' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Mata Pelajaran Diajarkan</p>
                        <p class="text-sm font-medium text-gray-900">{{ $guru->mata_pelajaran_diajarkan ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Jam Mengajar Perminggu</p>
                        <p class="text-sm font-medium text-gray-900">{{ $guru->jam_mengajar_perminggu }} Jam</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">TMT Pengangkatan</p>
                        <p class="text-sm font-medium text-gray-900">{{ $guru->tmt_pengangkatan ? $guru->tmt_pengangkatan->format('d F Y') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Masa Kerja</p>
                        <p class="text-sm font-medium text-gray-900">{{ $guru->masa_kerja_tahun }} Tahun, {{ $guru->masa_kerja_bulan }} Bulan</p>
                    </div>
                </div>
            </div>

            <!-- Data Pendidikan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Kualifikasi & Sertifikasi</h4>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Pendidikan Terakhir</p>
                        <p class="text-sm font-medium text-gray-900">{{ $guru->pendidikan ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Bidang Studi Pendidikan</p>
                        <p class="text-sm font-medium text-gray-900">{{ $guru->bidang_studi_pendidikan ?: '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-400 mb-1">Bidang Studi Sertifikasi</p>
                        <p class="text-sm font-medium text-gray-900">{{ $guru->bidang_studi_sertifikasi ?: 'Belum Sertifikasi' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">SK CPNS</p>
                        <p class="text-sm font-medium text-gray-900">{{ $guru->sk_cpns ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Tanggal CPNS</p>
                        <p class="text-sm font-medium text-gray-900">{{ $guru->tanggal_cpns ? $guru->tanggal_cpns->format('d/m/Y') : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
