@extends('layouts.app')

@section('title', 'Input Absensi Siswa - Literasia')

@section('content')
<div x-data="attendanceInput()" class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Input Absensi</h1>
            <p class="text-slate-500 font-medium mt-1">Lakukan absensi manual untuk siswa yang tidak memiliki perangkat.</p>
        </div>
        <div>
            <a href="{{ route('absensi.index') }}" class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl text-sm font-bold hover:bg-slate-50 transition-all shadow-sm">
                <i class="material-icons text-[20px]">assessment</i> Lihat Rekap
            </a>
        </div>
    </div>

    <!-- Filter/Selection Area -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <!-- Date Selection -->
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Tanggal</label>
                <input type="date" x-model="tanggal" @change="fetchStudents()" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-[#ba80e8]/20 transition-all">
            </div>

            <!-- Class Selection -->
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Kelas</label>
                <div class="relative">
                    <select x-model="selectedKelas" @change="fetchStudents()" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-[#ba80e8]/20 transition-all appearance-none cursor-pointer">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->nama }}</option>
                        @endforeach
                    </select>
                    <i class="material-icons absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</i>
                </div>
            </div>

            <!-- Subject Selection -->
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Mata Pelajaran</label>
                <div class="relative">
                    <select x-model="selectedSubject" @change="fetchStudents()" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-[#ba80e8]/20 transition-all appearance-none cursor-pointer">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">
                            {{ $subject->nama_pelajaran }}
                        </option>
                        @endforeach
                    </select>
                    <i class="material-icons absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">book</i>
                </div>
            </div>

            <div class="flex items-center justify-end">
                <button @click="saveAttendance()" :disabled="students.length === 0 || loading" class="w-full md:w-auto px-10 py-3.5 rounded-2xl bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white text-sm font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-50 disabled:hover:scale-100 h-[52px]">
                    <span x-show="!loading">Simpan Absensi</span>
                    <span x-show="loading" class="flex items-center gap-2"><i class="material-icons animate-spin">autorenew</i> Memproses...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Student List -->
    <template x-if="students.length > 0">
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Siswa</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status Kehadiran</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Keterangan (Opsional)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <template x-for="student in students" :key="student.id">
                            <tr class="hover:bg-slate-50/30 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 font-bold border border-slate-100" x-text="student.nama_lengkap.substring(0, 1)"></div>
                                        <div>
                                            <p class="font-black text-slate-800 tracking-tight" x-text="student.nama_lengkap"></p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase" x-text="'NISN: ' + student.nisn"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center justify-center gap-2">
                                        <template x-for="status in statuses" :key="status.code">
                                            <label class="relative cursor-pointer group">
                                                <input type="radio" :name="'status_' + student.id" :value="status.code" x-model="attendance[student.id].status" class="peer hidden">
                                                <div 
                                                    class="px-4 py-2 rounded-xl text-xs font-black transition-all border-2 border-transparent"
                                                    :class="{
                                                        'bg-emerald-50 text-emerald-600 peer-checked:border-emerald-500 peer-checked:bg-emerald-500 peer-checked:text-white': status.code === 'H',
                                                        'bg-rose-50 text-rose-600 peer-checked:border-rose-500 peer-checked:bg-rose-500 peer-checked:text-white': status.code === 'A',
                                                        'bg-blue-50 text-blue-600 peer-checked:border-blue-500 peer-checked:bg-blue-500 peer-checked:text-white': status.code === 'S',
                                                        'bg-amber-50 text-amber-600 peer-checked:border-amber-500 peer-checked:bg-amber-500 peer-checked:text-white': status.code === 'I'
                                                    }"
                                                    x-text="status.label"
                                                ></div>
                                            </label>
                                        </template>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <input 
                                        type="text" 
                                        x-model="attendance[student.id].keterangan"
                                        placeholder="Tambahkan catatan..." 
                                        class="w-full bg-slate-50 border-none rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-[#ba80e8]/20 transition-all"
                                    >
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </template>

    <template x-if="students.length === 0 && !fetching">
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-20 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-200 mx-auto mb-6">
                <i class="material-icons text-5xl">people_outline</i>
            </div>
            <h3 class="text-xl font-black text-slate-800 mb-2">Pilih Filter Terlebih Dahulu</h3>
            <p class="text-slate-400 font-medium max-w-sm mx-auto">Silakan pilih tanggal, kelas, dan mata pelajaran untuk memunculkan daftar siswa.</p>
        </div>
    </template>
    
    <div x-show="fetching" class="flex flex-col items-center justify-center py-20">
        <i class="material-icons text-4xl text-[#d90d8b] animate-spin">autorenew</i>
        <p class="text-slate-400 font-bold mt-4 uppercase tracking-widest text-xs">Memuat daftar siswa...</p>
    </div>

</div>
@endsection

@section('scripts')
<script>
    function attendanceInput() {
        return {
            tanggal: new Date().toISOString().split('T')[0],
            selectedKelas: '',
            selectedSubject: '',
            students: [],
            attendance: {},
            loading: false,
            fetching: false,
            statuses: [
                { code: 'H', label: 'HADIR' },
                { code: 'A', label: 'ALFA' },
                { code: 'S', label: 'SAKIT' },
                { code: 'I', label: 'IZIN' }
            ],
            
            async fetchStudents() {
                if (!this.selectedKelas || !this.selectedSubject || !this.tanggal) {
                    this.students = [];
                    return;
                }

                this.fetching = true;
                try {
                    const response = await $.get('{{ route("absensi.get-students") }}', {
                        kelas_id: this.selectedKelas,
                        tanggal: this.tanggal,
                        subject_id: this.selectedSubject
                    });
                    
                    this.students = response.students;
                    
                    // Initialize attendance data
                    let newAttendance = {};
                    this.students.forEach(student => {
                        const existing = response.existing[student.id];
                        newAttendance[student.id] = {
                            status: existing ? existing.status : 'H',
                            keterangan: existing ? existing.keterangan : ''
                        };
                    });
                    this.attendance = newAttendance;
                } catch (error) {
                    console.error('Failed to fetch students', error);
                    Swal.fire('Error', 'Gagal memuat daftar siswa.', 'error');
                } finally {
                    this.fetching = false;
                }
            },

            async saveAttendance() {
                this.loading = true;
                try {
                    const response = await $.ajax({
                        url: '{{ route("absensi.store") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            kelas_id: this.selectedKelas,
                            subject_id: this.selectedSubject,
                            tanggal: this.tanggal,
                            attendance: this.attendance
                        }
                    });

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.success,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } catch (error) {
                    console.error('Failed to save attendance', error);
                    let msg = 'Gagal menyimpan absensi.';
                    if (error.responseJSON && error.responseJSON.message) {
                        msg = error.responseJSON.message;
                    }
                    Swal.fire('Error', msg, 'error');
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
@endsection
