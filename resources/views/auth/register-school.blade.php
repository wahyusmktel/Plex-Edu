<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Sekolah Baru - Plex-Edu</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .font-outfit { font-family: 'Outfit', sans-serif; }
        .bg-gradient-main { background: linear-gradient(135deg, #d90d8b 0%, #ba80e8 100%); }
        .text-gradient { background: linear-gradient(135deg, #d90d8b 0%, #ba80e8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .glass-card { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6 relative overflow-x-hidden">
    <!-- Decorative Blurs -->
    <div class="absolute top-0 right-0 -mr-40 -mt-20 w-[600px] h-[600px] bg-pink-100 rounded-full blur-[100px] opacity-60"></div>
    <div class="absolute bottom-0 left-0 -ml-40 -mb-20 w-[500px] h-[500px] bg-purple-100 rounded-full blur-[100px] opacity-60"></div>

    <div x-data="registrationWizard()" class="w-full max-w-4xl relative z-10">
        <!-- Logo & Title -->
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-gradient-main rounded-2xl flex items-center justify-center shadow-lg mx-auto mb-6">
                <i class="material-icons text-white text-3xl">school</i>
            </div>
            <h1 class="text-4xl font-black text-slate-800">Daftarkan <span class="text-gradient">Sekolah Anda</span></h1>
            <p class="text-slate-500 font-medium mt-2">Bergabunglah dengan ekosistem pendidikan digital modern</p>
        </div>

        <!-- Step Indicator -->
        <div class="flex items-center justify-center gap-4 mb-12">
            <template x-for="n in 2" :key="n">
                <div class="flex items-center">
                    <div :class="step >= n ? 'bg-gradient-main text-white' : 'bg-white text-slate-300'" 
                         class="w-10 h-10 rounded-full flex items-center justify-center font-black shadow-sm transition-all duration-500 border border-slate-100">
                        <span x-text="n"></span>
                    </div>
                    <template x-if="n < 2">
                        <div class="w-20 h-1 mx-2 rounded-full overflow-hidden bg-slate-200">
                            <div class="h-full bg-gradient-main transition-all duration-500" :style="step > n ? 'width: 100%' : 'width: 0%'"></div>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        <!-- Form Container -->
        <div class="glass-card rounded-[3rem] p-10 md:p-16 shadow-2xl shadow-slate-200">
            <form @submit.prevent="submitForm">
                <!-- Step 1: School Identity -->
                <div x-show="step === 1" x-transition.opacity>
                    <div class="mb-10">
                        <h2 class="text-2xl font-black text-slate-800 mb-2">Identitas Sekolah</h2>
                        <p class="text-slate-500 font-medium">Informasi dasar mengenai institusi pendidikan anda.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Nama Sekolah</label>
                            <input type="text" x-model="formData.nama_sekolah" required class="w-full px-6 py-4 rounded-2xl bg-white border border-slate-100 focus:border-pink-500 focus:ring-4 focus:ring-pink-50 outline-none transition-all font-medium text-slate-700" placeholder="Contoh: SMA Negeri 1 Jakarta">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">NPSN</label>
                            <input type="text" x-model="formData.npsn" required class="w-full px-6 py-4 rounded-2xl bg-white border border-slate-100 focus:border-pink-500 focus:ring-4 focus:ring-pink-50 outline-none transition-all font-medium text-slate-700" placeholder="Nomor Pokok Sekolah Nasional">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Alamat Lengkap</label>
                            <textarea x-model="formData.alamat" required rows="3" class="w-full px-6 py-4 rounded-2xl bg-white border border-slate-100 focus:border-pink-500 focus:ring-4 focus:ring-pink-50 outline-none transition-all font-medium text-slate-700" placeholder="Jl. Pendidikan No. 123..."></textarea>
                        </div>
                        
                        <!-- Region Selects -->
                        <div class="relative">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Provinsi</label>
                            <div x-show="loading.province" class="absolute inset-0 bg-slate-50/50 animate-pulse rounded-2xl z-10 flex items-center justify-center"><div class="w-5 h-5 border-2 border-pink-500 border-t-transparent rounded-full animate-spin"></div></div>
                            <select x-model="selectedProvinsi" @change="onProvinsiChange" required class="w-full px-6 py-4 rounded-2xl bg-white border border-slate-100 focus:border-pink-500 focus:ring-4 focus:ring-pink-50 outline-none transition-all font-medium text-slate-700">
                                <option value="">Pilih Provinsi</option>
                                <template x-for="p in provinceList" :key="p.code">
                                    <option :value="p.code" x-text="p.name"></option>
                                </template>
                            </select>
                        </div>

                        <div class="relative">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Kabupaten / Kota</label>
                            <div x-show="loading.regency" class="absolute inset-0 bg-slate-50/50 animate-pulse rounded-2xl z-10 flex items-center justify-center"><div class="w-5 h-5 border-2 border-pink-500 border-t-transparent rounded-full animate-spin"></div></div>
                            <select x-model="selectedKabupaten" @change="onKabupatenChange" required :disabled="!selectedProvinsi" class="w-full px-6 py-4 rounded-2xl bg-white border border-slate-100 focus:border-pink-500 focus:ring-4 focus:ring-pink-50 outline-none transition-all font-medium text-slate-700 disabled:opacity-50">
                                <option value="">Pilih Kabupaten/Kota</option>
                                <template x-for="r in regencyList" :key="r.code">
                                    <option :value="r.code" x-text="r.name"></option>
                                </template>
                            </select>
                        </div>

                        <div class="relative">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Kecamatan</label>
                            <div x-show="loading.district" class="absolute inset-0 bg-slate-50/50 animate-pulse rounded-2xl z-10 flex items-center justify-center"><div class="w-5 h-5 border-2 border-pink-500 border-t-transparent rounded-full animate-spin"></div></div>
                            <select x-model="selectedKecamatan" @change="onKecamatanChange" required :disabled="!selectedKabupaten" class="w-full px-6 py-4 rounded-2xl bg-white border border-slate-100 focus:border-pink-500 focus:ring-4 focus:ring-pink-50 outline-none transition-all font-medium text-slate-700 disabled:opacity-50">
                                <option value="">Pilih Kecamatan</option>
                                <template x-for="d in districtList" :key="d.code">
                                    <option :value="d.code" x-text="d.name"></option>
                                </template>
                            </select>
                        </div>

                        <div class="relative">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Desa / Kelurahan</label>
                            <div x-show="loading.village" class="absolute inset-0 bg-slate-50/50 animate-pulse rounded-2xl z-10 flex items-center justify-center"><div class="w-5 h-5 border-2 border-pink-500 border-t-transparent rounded-full animate-spin"></div></div>
                            <select x-model="selectedDesa" @change="onDesaChange" required :disabled="!selectedKecamatan" class="w-full px-6 py-4 rounded-2xl bg-white border border-slate-100 focus:border-pink-500 focus:ring-4 focus:ring-pink-50 outline-none transition-all font-medium text-slate-700 disabled:opacity-50">
                                <option value="">Pilih Desa/Kelurahan</option>
                                <template x-for="v in villageList" :key="v.code">
                                    <option :value="v.code" x-text="v.name"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Status Sekolah</label>
                            <select x-model="formData.status_sekolah" required class="w-full px-6 py-4 rounded-2xl bg-white border border-slate-100 focus:border-pink-500 focus:ring-4 focus:ring-pink-50 outline-none transition-all font-medium text-slate-700">
                                <option value="Swasta">Swasta</option>
                                <option value="Negeri">Negeri</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-12 flex justify-end">
                        <button type="button" @click="nextStep()" class="px-10 py-5 bg-gradient-main text-white rounded-[2rem] font-black shadow-xl shadow-pink-200 hover:scale-105 transition-transform flex items-center gap-2">
                            Lanjut ke Info Admin <i class="material-icons">arrow_forward</i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Admin Info -->
                <div x-show="step === 2" x-transition.opacity>
                    <div class="mb-10 text-center md:text-left">
                        <h2 class="text-2xl font-black text-slate-800 mb-2">Informasi Admin Sekolah</h2>
                        <p class="text-slate-500 font-medium">Buat akun administrator untuk mengelola data sekolah anda.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Nama Lengkap Admin</label>
                            <input type="text" x-model="formData.admin_name" required class="w-full px-6 py-4 rounded-2xl bg-white border border-slate-100 focus:border-pink-500 focus:ring-4 focus:ring-pink-50 outline-none transition-all font-medium text-slate-700" placeholder="Nama lengkap pengelola sekolah">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Email</label>
                            <input type="email" x-model="formData.email" required class="w-full px-6 py-4 rounded-2xl bg-white border border-slate-100 focus:border-pink-500 focus:ring-4 focus:ring-pink-50 outline-none transition-all font-medium text-slate-700" placeholder="email@sekolah.sch.id">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Username</label>
                            <input type="text" x-model="formData.username" required class="w-full px-6 py-4 rounded-2xl bg-white border border-slate-100 focus:border-pink-500 focus:ring-4 focus:ring-pink-50 outline-none transition-all font-medium text-slate-700" placeholder="admin_sekolah_123">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Password</label>
                            <input type="password" x-model="formData.password" required class="w-full px-6 py-4 rounded-2xl bg-white border border-slate-100 focus:border-pink-500 focus:ring-4 focus:ring-pink-50 outline-none transition-all font-medium text-slate-700" placeholder="••••••••">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Konfirmasi Password</label>
                            <input type="password" x-model="formData.password_confirmation" required class="w-full px-6 py-4 rounded-2xl bg-white border border-slate-100 focus:border-pink-500 focus:ring-4 focus:ring-pink-50 outline-none transition-all font-medium text-slate-700" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="mt-12 flex items-center justify-between">
                        <button type="button" @click="step = 1" class="px-8 py-4 bg-slate-100 text-slate-500 rounded-[2rem] font-bold hover:bg-slate-200 transition-all flex items-center gap-2">
                            <i class="material-icons">arrow_back</i> Kembali
                        </button>
                        <button type="submit" :disabled="submitting" class="px-10 py-5 bg-gradient-main text-white rounded-[2rem] font-black shadow-xl shadow-pink-200 hover:scale-105 transition-transform flex items-center gap-2 disabled:opacity-50">
                            <span x-show="!submitting">Daftarkan Sekarang</span>
                            <span x-show="submitting">Memproses...</span>
                            <i x-show="!submitting" class="material-icons">check_circle</i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <p class="text-center mt-10 text-sm font-medium text-slate-400">
            Sudah memiliki akun? <a href="{{ route('login') }}" class="text-pink-600 font-black hover:underline">Masuk di sini</a>
        </p>
    </div>

    <script>
        function registrationWizard() {
            return {
                step: 1,
                submitting: false,
                formData: {
                    nama_sekolah: '',
                    npsn: '',
                    alamat: '',
                    provinsi: '',
                    kabupaten_kota: '',
                    kecamatan: '',
                    desa_kelurahan: '',
                    status_sekolah: 'Swasta',
                    admin_name: '',
                    email: '',
                    username: '',
                    password: '',
                    password_confirmation: ''
                },
                provinceList: [],
                regencyList: [],
                districtList: [],
                villageList: [],
                loading: {
                    province: false,
                    regency: false,
                    district: false,
                    village: false
                },
                selectedProvinsi: '',
                selectedKabupaten: '',
                selectedKecamatan: '',
                selectedDesa: '',

                init() {
                    this.fetchProvinces();
                },

                async fetchProvinces() {
                    this.loading.province = true;
                    try {
                        const response = await fetch('{{ route("register.regional", ["type" => "provinces"]) }}');
                        const data = await response.json();
                        this.provinceList = data.data;
                    } finally {
                        this.loading.province = false;
                    }
                },

                async onProvinsiChange() {
                    const code = this.selectedProvinsi;
                    this.formData.provinsi = this.provinceList.find(p => p.code === code)?.name || '';
                    
                    this.selectedKabupaten = '';
                    this.selectedKecamatan = '';
                    this.selectedDesa = '';
                    this.formData.kabupaten_kota = '';
                    this.formData.kecamatan = '';
                    this.formData.desa_kelurahan = '';
                    
                    this.regencyList = [];
                    this.districtList = [];
                    this.villageList = [];
                    if (!code) return;

                    this.loading.regency = true;
                    try {
                        const response = await fetch(`{{ url('/register-school/regional/regencies') }}/${code}`);
                        const data = await response.json();
                        this.regencyList = data.data;
                    } finally {
                        this.loading.regency = false;
                    }
                },

                async onKabupatenChange() {
                    const code = this.selectedKabupaten;
                    this.formData.kabupaten_kota = this.regencyList.find(r => r.code === code)?.name || '';
                    
                    this.selectedKecamatan = '';
                    this.selectedDesa = '';
                    this.formData.kecamatan = '';
                    this.formData.desa_kelurahan = '';
                    
                    this.districtList = [];
                    this.villageList = [];
                    if (!code) return;

                    this.loading.district = true;
                    try {
                        const response = await fetch(`{{ url('/register-school/regional/districts') }}/${code}`);
                        const data = await response.json();
                        this.districtList = data.data;
                    } finally {
                        this.loading.district = false;
                    }
                },

                async onKecamatanChange() {
                    const code = this.selectedKecamatan;
                    this.formData.kecamatan = this.districtList.find(d => d.code === code)?.name || '';
                    
                    this.selectedDesa = '';
                    this.formData.desa_kelurahan = '';
                    
                    this.villageList = [];
                    if (!code) return;

                    this.loading.village = true;
                    try {
                        const response = await fetch(`{{ url('/register-school/regional/villages') }}/${code}`);
                        const data = await response.json();
                        this.villageList = data.data;
                    } finally {
                        this.loading.village = false;
                    }
                },

                onDesaChange() {
                    const code = this.selectedDesa;
                    this.formData.desa_kelurahan = this.villageList.find(v => v.code === code)?.name || '';
                },

                nextStep() {
                    // Simple validation for step 1
                    if (!this.formData.nama_sekolah || !this.formData.npsn || !this.formData.alamat || 
                        !this.formData.provinsi || !this.formData.desa_kelurahan) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Data Belum Lengkap',
                            text: 'Silahkan isi semua identitas sekolah terlebih dahulu.',
                            confirmButtonColor: '#d90d8b'
                        });
                        return;
                    }
                    this.step = 2;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                async submitForm() {
                    this.submitting = true;
                    try {
                        const response = await fetch('{{ route("register.school") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(this.formData)
                        });

                        const result = await response.json();

                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Pendaftaran Berhasil',
                                text: result.message,
                                confirmButtonColor: '#d90d8b'
                            }).then(() => {
                                window.location.href = '{{ route("login") }}';
                            });
                        } else {
                            throw new Error(result.message || 'Terjadi kesalahan');
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: error.message,
                            confirmButtonColor: '#d90d8b'
                        });
                    } finally {
                        this.submitting = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
