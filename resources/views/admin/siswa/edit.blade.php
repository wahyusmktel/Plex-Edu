@extends('layouts.app')

@section('title', 'Edit Siswa - Literasia')

@push('styles')
<style>
    #pickerMap { cursor: crosshair; background-color: #f8fafc; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
@endpush

@section('content')
<div x-data="siswaEditPage()" x-init="init()" class="space-y-8">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('siswa.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-[#d90d8b] hover:border-[#d90d8b]/30 transition-all">
                    <i class="material-icons">arrow_back</i>
                </a>
                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Kembali ke Daftar</span>
            </div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Edit Data Siswa</h1>
            <p class="text-slate-500 font-medium mt-1">Lengkapi seluruh detail informasi untuk <span class="text-[#d90d8b] font-bold">{{ $siswa->nama_lengkap }}</span></p>
        </div>
        <div class="flex gap-3">
            <button @click="saveData()" class="flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl text-sm font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                <i class="material-icons">save</i> SIMPAN PERUBAHAN
            </button>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden flex flex-col md:flex-row min-h-[600px]">
        
        <!-- Sidebar Tabs -->
        <div class="w-full md:w-72 bg-slate-50/50 border-r border-slate-100 p-6 space-y-2">
            <button @click="activeTab = 'dasar'" :class="activeTab === 'dasar' ? 'bg-white text-[#d90d8b] shadow-sm border-slate-200' : 'text-slate-400 hover:bg-white/50 border-transparent'" class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl text-sm font-bold border transition-all text-left">
                <i class="material-icons text-[20px]">account_circle</i> Informasi Dasar
            </button>
            <button @click="activeTab = 'diri'" :class="activeTab === 'diri' ? 'bg-white text-[#d90d8b] shadow-sm border-slate-200' : 'text-slate-400 hover:bg-white/50 border-transparent'" class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl text-sm font-bold border transition-all text-left">
                <i class="material-icons text-[20px]">badge</i> Data Diri Detail
            </button>
            <button @click="activeTab = 'alamat'" :class="activeTab === 'alamat' ? 'bg-white text-[#d90d8b] shadow-sm border-slate-200' : 'text-slate-400 hover:bg-white/50 border-transparent'" class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl text-sm font-bold border transition-all text-left">
                <i class="material-icons text-[20px]">location_on</i> Alamat & Lokasi
            </button>
            <button @click="activeTab = 'keluarga'" :class="activeTab === 'keluarga' ? 'bg-white text-[#d90d8b] shadow-sm border-slate-200' : 'text-slate-400 hover:bg-white/50 border-transparent'" class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl text-sm font-bold border transition-all text-left">
                <i class="material-icons text-[20px]">family_restroom</i> Orang Tua / Wali
            </button>
            <button @click="activeTab = 'akademik'" :class="activeTab === 'akademik' ? 'bg-white text-[#d90d8b] shadow-sm border-slate-200' : 'text-slate-400 hover:bg-white/50 border-transparent'" class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl text-sm font-bold border transition-all text-left">
                <i class="material-icons text-[20px]">school</i> Akademik & Lainnya
            </button>
        </div>

        <!-- Form Area -->
        <div class="flex-grow p-10">
            <form id="siswaForm">
                @csrf
                <!-- Tab: Informasi Dasar -->
                <div x-show="activeTab === 'dasar'" x-transition class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form-input label="Nama Lengkap" name="nama_lengkap" value="{{ $siswa->nama_lengkap }}" />
                        <x-form-input label="NIS" name="nis" value="{{ $siswa->nis }}" />
                        <x-form-input label="NISN" name="nisn" value="{{ $siswa->nisn }}" />
                        <x-form-input label="NIPD" name="nipd" value="{{ $siswa->nipd }}" />
                        
                        <div class="space-y-1.5">
                            <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Kelas</label>
                            <select name="kelas_id" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all outline-none">
                                <option value="">Pilih Kelas</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ $siswa->kelas_id == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all outline-none">
                                <option value="L" {{ $siswa->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $siswa->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <x-form-input label="Username Login" name="username" value="{{ $siswa->user->username ?? '' }}" />
                        <x-form-input type="password" label="Ganti Password" name="password" placeholder="••••••••" />
                    </div>
                </div>

                <!-- Tab: Data Diri Detail -->
                <div x-show="activeTab === 'diri'" x-transition class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form-input label="NIK" name="nik" value="{{ $siswa->nik }}" />
                        <x-form-input label="Tempat Lahir" name="tempat_lahir" value="{{ $siswa->tempat_lahir }}" />
                        <x-form-input type="date" label="Tanggal Lahir" name="tanggal_lahir" value="{{ $siswa->tanggal_lahir }}" />
                        
                        <div class="space-y-1.5">
                            <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Agama</label>
                            <select name="agama" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all outline-none">
                                @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu', 'Lainya'] as $agm)
                                    <option value="{{ $agm }}" {{ $siswa->agama == $agm ? 'selected' : '' }}>{{ $agm }}</option>
                                @endforeach
                            </select>
                        </div>

                        <x-form-input label="No. HP Siswa" name="no_hp" value="{{ $siswa->no_hp }}" />
                        <x-form-input label="Telepon" name="telepon" value="{{ $siswa->telepon }}" />
                        <x-form-input label="Email" name="email" value="{{ $siswa->email }}" />
                        <x-form-input label="No Registrasi Akta Lahir" name="no_akta_lahir" value="{{ $siswa->no_akta_lahir }}" />
                        <x-form-input label="Anak ke-berapa" name="anak_ke" type="number" value="{{ $siswa->anak_ke }}" />
                        <x-form-input label="Jumlah Saudara Kandung" name="jml_saudara_kandung" type="number" value="{{ $siswa->jml_saudara_kandung }}" />
                        
                        <div class="grid grid-cols-3 gap-4 md:col-span-2">
                            <x-form-input label="Berat Badan (kg)" name="berat_badan" type="number" value="{{ $siswa->berat_badan }}" />
                            <x-form-input label="Tinggi Badan (cm)" name="tinggi_badan" type="number" value="{{ $siswa->tinggi_badan }}" />
                            <x-form-input label="Lingkar Kepala (cm)" name="lingkar_kepala" type="number" value="{{ $siswa->lingkar_kepala }}" />
                        </div>

                        <x-form-input label="Kebutuhan Khusus" name="kebutuhan_khusus" value="{{ $siswa->kebutuhan_khusus }}" />
                    </div>
                </div>

                <!-- Tab: Alamat & Lokasi -->
                <div x-show="activeTab === 'alamat'" x-transition class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-1.5">
                            <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all outline-none">{{ $siswa->alamat }}</textarea>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <x-form-input label="RT" name="rt" value="{{ $siswa->rt }}" />
                            <x-form-input label="RW" name="rw" value="{{ $siswa->rw }}" />
                        </div>
                        
                        <x-form-input label="Dusun" name="dusun" value="{{ $siswa->dusun }}" />
                        <x-form-input label="Desa / Kelurahan" name="kelurahan" value="{{ $siswa->kelurahan }}" />
                        <x-form-input label="Kecamatan" name="kecamatan" value="{{ $siswa->kecamatan }}" />
                        <x-form-input label="Kode Pos" name="kode_pos" value="{{ $siswa->kode_pos }}" />

                        <div class="space-y-1.5">
                            <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Jenis Tinggal</label>
                            <input name="jenis_tinggal" value="{{ $siswa->jenis_tinggal }}" placeholder="Contoh: Bersama orang tua" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all outline-none">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">Alat Transportasi</label>
                            <input name="alat_transportasi" value="{{ $siswa->alat_transportasi }}" placeholder="Contoh: Sepeda motor" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all outline-none">
                        </div>

                        <!-- Map Section -->
                        <div class="md:col-span-2 pt-4">
                            <label class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1 block mb-3">Koordinat Rumah (Map Picker)</label>
                            <div id="pickerMap" class="w-full h-80 bg-slate-50 rounded-3xl border border-slate-100 mb-4 overflow-hidden relative z-10"></div>
                            
                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Lintang (Latitude)</label>
                                    <input type="text" name="lintang" id="latInput" x-model="coords.lat" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-mono font-bold text-slate-600 outline-none">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Bujur (Longitude)</label>
                                    <input type="text" name="bujur" id="lngInput" x-model="coords.lng" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-mono font-bold text-slate-600 outline-none">
                                </div>
                            </div>
                            <p class="text-[11px] font-medium text-slate-400 mt-3 italic px-2">Geser penanda pada peta untuk menyesuaikan titik lokasi rumah siswa.</p>
                        </div>

                        <x-form-input label="Jarak Rumah ke Sekolah (KM)" name="jarak_rumah_km" type="number" step="0.1" value="{{ $siswa->jarak_rumah_km }}" />
                    </div>
                </div>

                <!-- Tab: Orang Tua / Wali -->
                <div x-show="activeTab === 'keluarga'" x-transition class="space-y-10">
                    <!-- Data Ayah -->
                    <div>
                        <h3 class="flex items-center gap-2 text-sm font-black text-slate-800 uppercase tracking-widest mb-6 px-1">
                            <span class="w-2 h-2 rounded-full bg-blue-400"></span> Data Ayah
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-form-input label="Nama Ayah" name="nama_ayah" value="{{ $siswa->nama_ayah }}" />
                            <x-form-input label="NIK Ayah" name="ayah_nik" value="{{ $siswa->ayah_nik }}" />
                            <x-form-input label="Tahun Lahir" name="ayah_tahun_lahir" value="{{ $siswa->ayah_tahun_lahir }}" />
                            <x-form-input label="Pendidikan" name="ayah_pendidikan" value="{{ $siswa->ayah_pendidikan }}" />
                            <x-form-input label="Pekerjaan" name="ayah_pekerjaan" value="{{ $siswa->ayah_pekerjaan }}" />
                            <x-form-input label="Penghasilan" name="ayah_penghasilan" value="{{ $siswa->ayah_penghasilan }}" />
                        </div>
                    </div>

                    <!-- Data Ibu -->
                    <div class="pt-8 border-t border-slate-50">
                        <h3 class="flex items-center gap-2 text-sm font-black text-slate-800 uppercase tracking-widest mb-6 px-1">
                            <span class="w-2 h-2 rounded-full bg-pink-400"></span> Data Ibu
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-form-input label="Nama Ibu" name="nama_ibu" value="{{ $siswa->nama_ibu }}" />
                            <x-form-input label="NIK Ibu" name="ibu_nik" value="{{ $siswa->ibu_nik }}" />
                            <x-form-input label="Tahun Lahir" name="ibu_tahun_lahir" value="{{ $siswa->ibu_tahun_lahir }}" />
                            <x-form-input label="Pendidikan" name="ibu_pendidikan" value="{{ $siswa->ibu_pendidikan }}" />
                            <x-form-input label="Pekerjaan" name="ibu_pekerjaan" value="{{ $siswa->ibu_pekerjaan }}" />
                            <x-form-input label="Penghasilan" name="ibu_penghasilan" value="{{ $siswa->ibu_penghasilan }}" />
                        </div>
                    </div>

                    <!-- Data Wali -->
                    <div class="pt-8 border-t border-slate-50">
                        <h3 class="flex items-center gap-2 text-sm font-black text-slate-800 uppercase tracking-widest mb-6 px-1">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span> Data Wali (Kosongkan jika tidak ada)
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-form-input label="Nama Wali" name="nama_wali" value="{{ $siswa->nama_wali }}" />
                            <x-form-input label="NIK Wali" name="wali_nik" value="{{ $siswa->wali_nik }}" />
                            <x-form-input label="Tahun Lahir" name="wali_tahun_lahir" value="{{ $siswa->wali_tahun_lahir }}" />
                            <x-form-input label="Pendidikan" name="wali_pendidikan" value="{{ $siswa->wali_pendidikan }}" />
                            <x-form-input label="Pekerjaan" name="wali_pekerjaan" value="{{ $siswa->wali_pekerjaan }}" />
                            <x-form-input label="Penghasilan" name="wali_penghasilan" value="{{ $siswa->wali_penghasilan }}" />
                        </div>
                    </div>

                    <x-form-input label="No. HP Orang Tua" name="no_hp_ortu" value="{{ $siswa->no_hp_ortu }}" />
                    <x-form-input label="No KK" name="no_kk" value="{{ $siswa->no_kk }}" />
                </div>

                <!-- Tab: Akademik & Lainnya -->
                <div x-show="activeTab === 'akademik'" x-transition class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form-input label="Sekolah Asal" name="sekolah_asal" value="{{ $siswa->sekolah_asal }}" />
                        <x-form-input label="SKHUN" name="skhun" value="{{ $siswa->skhun }}" />
                        <x-form-input label="No Peserta Ujian Nasional" name="no_peserta_ujian" value="{{ $siswa->no_peserta_ujian }}" />
                        <x-form-input label="No Seri Ijazah" name="no_seri_ijazah" value="{{ $siswa->no_seri_ijazah }}" />
                        
                        <div class="grid grid-cols-2 gap-4">
                            <x-form-input label="Penerima KIP" name="penerima_kip" value="{{ $siswa->penerima_kip }}" />
                            <x-form-input label="Nomor KIP" name="no_kip" value="{{ $siswa->no_kip }}" />
                        </div>
                        
                        <x-form-input label="Nama di KIP" name="nama_di_kip" value="{{ $siswa->nama_di_kip }}" />
                        <x-form-input label="Nomor KKS" name="no_kks" value="{{ $siswa->no_kks }}" />
                        
                        <div class="grid grid-cols-2 gap-4">
                            <x-form-input label="Layak PIP" name="layak_pip" value="{{ $siswa->layak_pip }}" />
                            <x-form-input label="Alasan Layak PIP" name="alasan_layak_pip" value="{{ $siswa->alasan_layak_pip }}" />
                        </div>

                        <div class="md:col-span-2 pt-6 border-t border-slate-50">
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Informasi Rekening Bank</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <x-form-input label="Nama Bank" name="bank" value="{{ $siswa->bank }}" />
                                <x-form-input label="Nomor Rekening" name="no_rekening_bank" value="{{ $siswa->no_rekening_bank }}" />
                                <x-form-input label="Rekening Atas Nama" name="rekening_atas_nama" value="{{ $siswa->rekening_atas_nama }}" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <x-form-input label="Penerima KPS" name="penerima_kps" value="{{ $siswa->penerima_kps }}" />
                            <x-form-input label="No. KPS" name="no_kps" value="{{ $siswa->no_kps }}" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}"></script>
<script>
    function siswaEditPage() {
        return {
            activeTab: 'dasar',
            coords: {
                lat: '{{ $siswa->lintang ?? "-6.175392" }}',
                lng: '{{ $siswa->bujur ?? "106.827153" }}'
            },
            map: null,
            marker: null,

            init() {
                this.$watch('activeTab', value => {
                    if (value === 'alamat') {
                        setTimeout(() => {
                            if (!this.map) {
                                this.initMap();
                            } else {
                                google.maps.event.trigger(this.map, 'resize');
                                this.map.setCenter(this.marker.getPosition());
                            }
                        }, 100);
                    }
                });
            },

            initMap() {
                const initialPos = { 
                    lat: parseFloat(this.coords.lat), 
                    lng: parseFloat(this.coords.lng) 
                };

                this.map = new google.maps.Map(document.getElementById('pickerMap'), {
                    center: initialPos,
                    zoom: 15,
                    mapTypeControl: false,
                    streetViewControl: false,
                });

                this.marker = new google.maps.Marker({
                    position: initialPos,
                    map: this.map,
                    draggable: true,
                    animation: google.maps.Animation.DROP
                });

                this.marker.addListener('dragend', () => {
                    const pos = this.marker.getPosition();
                    this.coords.lat = pos.lat().toFixed(8);
                    this.coords.lng = pos.lng().toFixed(8);
                });

                this.map.addListener('click', (e) => {
                    const pos = e.latLng;
                    this.marker.setPosition(pos);
                    this.coords.lat = pos.lat().toFixed(8);
                    this.coords.lng = pos.lng().toFixed(8);
                });
            },

            saveData() {
                Swal.fire({
                    title: 'Simpan Perubahan?',
                    text: 'Pastikan seluruh data sudah benar.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d90d8b',
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        let formData = $('#siswaForm').serialize();

                        $.ajax({
                            url: `{{ route('siswa.update', $siswa->id) }}`,
                            method: 'POST',
                            data: formData,
                            success: (res) => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: res.success,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = "{{ route('siswa.index') }}";
                                });
                            },
                            error: (err) => {
                                let msg = err.responseJSON?.message || 'Terjadi kesalahan.';
                                if (err.responseJSON?.errors) {
                                    msg = Object.values(err.responseJSON.errors)[0][0];
                                }
                                Swal.fire('Gagal', msg, 'error');
                            }
                        });
                    }
                });
            }
        }
    }
</script>
@endsection
