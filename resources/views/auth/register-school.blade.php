<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Sekolah Baru - {{ $app_settings->app_name }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .font-outfit { font-family: 'Outfit', sans-serif; }
        .bg-gradient-main { background: linear-gradient(135deg, #ba80e8 0%, #d90d8b 100%); }
        .text-gradient { background: linear-gradient(135deg, #ba80e8 0%, #d90d8b 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .glass-background { background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); }
        .glass-card { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.4); }
    </style>
</head>
<body class="glass-background min-h-screen flex flex-col p-4 md:p-8 relative overflow-x-hidden">
    <!-- Decorative Blurs -->
    <div class="fixed top-0 right-0 -mr-40 -mt-20 w-[600px] h-[600px] bg-pink-100 rounded-full blur-[120px] opacity-40 animate-pulse"></div>
    <div class="fixed bottom-0 left-0 -ml-40 -mb-20 w-[500px] h-[500px] bg-purple-100 rounded-full blur-[120px] opacity-40 animate-pulse" style="animation-delay: 1s;"></div>

    <!-- Navigation Header -->
    <header class="relative z-20 flex justify-between items-center max-w-7xl mx-auto w-full mb-12">
        <div class="flex items-center gap-4">
            @if($app_settings->app_logo)
                <img src="{{ $app_settings->logo_url }}" class="w-10 h-10 rounded-xl object-contain bg-white shadow-md p-2" alt="Logo">
            @else
                <div class="w-10 h-10 rounded-xl bg-gradient-main flex items-center justify-center text-white shadow-md">
                    <i class="material-icons">import_contacts</i>
                </div>
            @endif
            <span class="text-xl font-black tracking-tight text-slate-800 uppercase">{{ $app_settings->app_name }}</span>
        </div>
        <a href="{{ route('login') }}" class="px-6 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-black text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm uppercase tracking-widest">
            Sudah Terdaftar? Masuk
        </a>
    </header>

    <div x-data="registrationWizard()" class="flex-grow w-full max-w-6xl mx-auto relative z-10">
        <div class="flex flex-col lg:flex-row gap-12 items-start">
            <!-- Left: Info & Steps -->
            <div class="w-full lg:w-1/3 lg:sticky lg:top-8">
                <div class="mb-12">
                    <h1 class="text-4xl md:text-5xl font-black text-slate-800 leading-tight mb-6">
                        Daftarkan <br><span class="text-gradient">Sekolah Anda</span>
                    </h1>
                    <p class="text-slate-500 font-medium text-lg leading-relaxed">
                        Lengkapi langkah-langkah di samping untuk bergabung dengan ekosistem manajemen sekolah digital paling modern.
                    </p>
                </div>

                <div class="space-y-10">
                    <div class="flex gap-6 items-center">
                        <div class="relative">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black transition-all duration-500" 
                                 :class="step >= 1 ? 'bg-gradient-main text-white shadow-lg shadow-pink-200 ring-4 ring-pink-50' : 'bg-white text-slate-300 border border-slate-200'">
                                1
                            </div>
                            <div class="absolute top-12 left-1/2 -translate-x-1/2 w-1 h-10 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-main transition-all duration-500" :style="step > 1 ? 'height: 100%' : 'height: 0%'"></div>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs mb-1">Langkah 1</h3>
                            <p class="text-sm font-bold" :class="step >= 1 ? 'text-slate-800' : 'text-slate-400'">Identitas Sekolah</p>
                        </div>
                    </div>

                    <div class="flex gap-6 items-center">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black transition-all duration-500" 
                             :class="step >= 2 ? 'bg-gradient-main text-white shadow-lg shadow-pink-200 ring-4 ring-pink-50' : 'bg-white text-slate-300 border border-slate-200'">
                            2
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs mb-1">Langkah 2</h3>
                            <p class="text-sm font-bold" :class="step >= 2 ? 'text-slate-800' : 'text-slate-400'">Informasi Admin</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonials/Stats Card -->
                <div class="mt-16 p-8 rounded-3xl bg-gradient-main text-white shadow-2xl relative overflow-hidden group">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                    <p class="text-lg font-medium leading-relaxed mb-6">"Platform ini membantu sekolah kami meningkatkan efisiensi administrasi hingga 60% dalam waktu kurun satu semester."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                            <i class="material-icons">person</i>
                        </div>
                        <div>
                            <p class="font-black text-sm uppercase tracking-wider">Kepala Sekolah</p>
                            <p class="text-xs text-white/70">SMK Unggulan Digital</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Form -->
            <div class="w-full lg:w-2/3">
                <div class="glass-card rounded-[3rem] p-8 md:p-14 shadow-2xl shadow-slate-200/50">
                    <form @submit.prevent="submitForm">
                        <!-- Step 1: School Identity -->
                        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-12" x-transition:enter-end="opacity-100 translate-x-0">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-7">
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">Nama Sekolah</label>
                                    <input type="text" x-model="formData.nama_sekolah" required class="w-full px-7 py-4.5 rounded-2xl bg-white border border-slate-200 focus:border-[#d90d8b] focus:ring-4 focus:ring-pink-50 outline-none transition-all font-medium text-slate-700 shadow-sm" placeholder="Contoh: SMA Negeri 1 Kebangsaan">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">NPSN</label>
                                    <input type="text" x-model="formData.npsn" required class="w-full px-7 py-4.5 rounded-2xl bg-white border border-slate-200 focus:border-[#d90d8b] focus:ring-4 focus:ring-pink-50 outline-none transition-all font-medium text-slate-700 shadow-sm" placeholder="8 Digit Angka">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">Status Sekolah</label>
                                    <div class="relative">
                                        <select x-model="formData.status_sekolah" required class="w-full px-7 py-4.5 rounded-2xl bg-white border border-slate-200 focus:border-[#d90d8b] focus:ring-4 focus:ring-pink-50 outline-none transition-all font-bold text-slate-700 shadow-sm appearance-none">
                                            <option value="Swasta">Swasta</option>
                                            <option value="Negeri">Negeri</option>
                                        </select>
                                        <i class="material-icons absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</i>
                                    </div>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">Alamat Lengkap</label>
                                    <textarea x-model="formData.alamat" required rows="3" class="w-full px-7 py-4.5 rounded-2xl bg-white border border-slate-200 focus:border-[#d90d8b] focus:ring-4 focus:ring-pink-50 outline-none transition-all font-medium text-slate-700 shadow-sm resize-none" placeholder="Alamat jalan, nomor, dsb..."></textarea>
                                </div>
                                
                                <!-- Region Selects with custom design -->
                                <div class="relative">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">Provinsi</label>
                                    <div x-show="loading.province" class="absolute bottom-4 right-12 z-10"><div class="w-5 h-5 border-2 border-[#d90d8b] border-t-transparent rounded-full animate-spin"></div></div>
                                    <select x-model="selectedProvinsi" @change="onProvinsiChange" required class="w-full px-7 py-4.5 rounded-2xl bg-white border border-slate-200 focus:border-[#d90d8b] focus:ring-4 focus:ring-pink-50 outline-none transition-all font-bold text-slate-700 shadow-sm appearance-none">
                                        <option value="">Pilih Provinsi</option>
                                        <template x-for="p in provinceList" :key="p.code">
                                            <option :value="p.code" x-text="p.name"></option>
                                        </template>
                                    </select>
                                    <i class="material-icons absolute right-6 top-[3.3rem] -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</i>
                                </div>

                                <div class="relative">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">Kabupaten / Kota</label>
                                    <div x-show="loading.regency" class="absolute bottom-4 right-12 z-10"><div class="w-5 h-5 border-2 border-[#d90d8b] border-t-transparent rounded-full animate-spin"></div></div>
                                    <select x-model="selectedKabupaten" @change="onKabupatenChange" required :disabled="!selectedProvinsi" class="w-full px-7 py-4.5 rounded-2xl bg-white border border-slate-200 focus:border-[#d90d8b] focus:ring-4 focus:ring-pink-50 outline-none transition-all font-bold text-slate-700 shadow-sm disabled:bg-slate-50 appearance-none">
                                        <option value="">Pilih Kabupaten/Kota</option>
                                        <template x-for="r in regencyList" :key="r.code">
                                            <option :value="r.code" x-text="r.name"></option>
                                        </template>
                                    </select>
                                    <i class="material-icons absolute right-6 top-[3.3rem] -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</i>
                                </div>

                                <div class="relative">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">Kecamatan</label>
                                    <div x-show="loading.district" class="absolute bottom-4 right-12 z-10"><div class="w-5 h-5 border-2 border-[#d90d8b] border-t-transparent rounded-full animate-spin"></div></div>
                                    <select x-model="selectedKecamatan" @change="onKecamatanChange" required :disabled="!selectedKabupaten" class="w-full px-7 py-4.5 rounded-2xl bg-white border border-slate-200 focus:border-[#d90d8b] focus:ring-4 focus:ring-pink-50 outline-none transition-all font-bold text-slate-700 shadow-sm disabled:bg-slate-50 appearance-none">
                                        <option value="">Pilih Kecamatan</option>
                                        <template x-for="d in districtList" :key="d.code">
                                            <option :value="d.code" x-text="d.name"></option>
                                        </template>
                                    </select>
                                    <i class="material-icons absolute right-6 top-[3.3rem] -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</i>
                                </div>

                                <div class="relative">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">Desa / Kelurahan</label>
                                    <div x-show="loading.village" class="absolute bottom-4 right-12 z-10"><div class="w-5 h-5 border-2 border-[#d90d8b] border-t-transparent rounded-full animate-spin"></div></div>
                                    <select x-model="selectedDesa" @change="onDesaChange" required :disabled="!selectedKecamatan" class="w-full px-7 py-4.5 rounded-2xl bg-white border border-slate-200 focus:border-[#d90d8b] focus:ring-4 focus:ring-pink-50 outline-none transition-all font-bold text-slate-700 shadow-sm disabled:bg-slate-50 appearance-none">
                                        <option value="">Pilih Desa/Kelurahan</option>
                                        <template x-for="v in villageList" :key="v.code">
                                            <option :value="v.code" x-text="v.name"></option>
                                        </template>
                                    </select>
                                    <i class="material-icons absolute right-6 top-[3.3rem] -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</i>
                                </div>
                            </div>

                            <div class="mt-12 flex justify-end">
                                <button type="button" @click="nextStep()" class="px-10 py-5 bg-gradient-main text-white rounded-2xl font-black shadow-xl shadow-pink-200 hover:scale-[1.03] transition-all flex items-center gap-3 active:scale-95 uppercase tracking-widest text-sm">
                                    Lanjut ke Info Admin <i class="material-icons">east</i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Admin Info -->
                        <div x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-12" x-transition:enter-end="opacity-100 translate-x-0">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-7">
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">Nama Lengkap Admin</label>
                                    <input type="text" x-model="formData.admin_name" required class="w-full px-7 py-4.5 rounded-2xl bg-white border border-slate-200 focus:border-[#d90d8b] focus:ring-4 focus:ring-pink-50 outline-none transition-all font-bold text-slate-700 shadow-sm pr-12" placeholder="Nama pengelola sekolah">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">Email Address</label>
                                    <input type="email" x-model="formData.email" required class="w-full px-7 py-4.5 rounded-2xl bg-white border border-slate-200 focus:border-[#d90d8b] focus:ring-4 focus:ring-pink-50 outline-none transition-all font-bold text-slate-700 shadow-sm" placeholder="email@sekolah.sch.id">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">Username</label>
                                    <input type="text" x-model="formData.username" required class="w-full px-7 py-4.5 rounded-2xl bg-white border border-slate-200 focus:border-[#d90d8b] focus:ring-4 focus:ring-pink-50 outline-none transition-all font-bold text-slate-700 shadow-sm" placeholder="admin_sekolah_utama">
                                </div>
                                <div></div> <!-- Spacer -->
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">Password</label>
                                    <input type="password" x-model="formData.password" required class="w-full px-7 py-4.5 rounded-2xl bg-white border border-slate-200 focus:border-[#d90d8b] focus:ring-4 focus:ring-pink-50 outline-none transition-all font-bold text-slate-700 shadow-sm" placeholder="••••••••">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">Konfirmasi Password</label>
                                    <input type="password" x-model="formData.password_confirmation" required class="w-full px-7 py-4.5 rounded-2xl bg-white border border-slate-200 focus:border-[#d90d8b] focus:ring-4 focus:ring-pink-50 outline-none transition-all font-bold text-slate-700 shadow-sm" placeholder="••••••••">
                                </div>
                            </div>

                            <div class="mt-12 flex items-center justify-between">
                                <button type="button" @click="step = 1" class="px-8 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black hover:bg-slate-200 transition-all flex items-center gap-3 active:scale-95 uppercase tracking-widest text-xs">
                                    <i class="material-icons">west</i> Kembali
                                </button>
                                <button type="submit" :disabled="submitting" class="px-10 py-5 bg-gradient-main text-white rounded-2xl font-black shadow-xl shadow-pink-200 hover:scale-[1.03] transition-all flex items-center gap-3 disabled:opacity-50 active:scale-95 uppercase tracking-widest text-sm">
                                    <span x-show="!submitting">Daftarkan Sekarang</span>
                                    <span x-show="submitting">Memproses...</span>
                                    <i x-show="!submitting" class="material-icons">check_circle</i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Security Note -->
                <div class="mt-8 flex items-center justify-center gap-4 text-slate-400">
                    <i class="material-icons">lock_outline</i>
                    <p class="text-xs font-bold uppercase tracking-widest">Koneksi Aman & Terenkripsi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="relative z-20 mt-12 py-8 border-t border-slate-200 max-w-7xl mx-auto w-full text-center">
        <p class="text-xs font-bold text-slate-400 tracking-[0.2em] uppercase">
            &copy; 2026 {{ $app_settings->app_name }} &bull; Seluruh Hak Cipta Dilindungi &bull; Literasia Team
        </p>
    </footer>

    <style>
        .py-4\.5 { padding-top: 1.125rem; padding-bottom: 1.125rem; }
    </style>

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
