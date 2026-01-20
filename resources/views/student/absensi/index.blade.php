@extends('layouts.app')

@section('title', 'Absensi Kehadiran - Literasia')

@section('content')
<div x-data="absensiPage()" class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Absensi Kehadiran</h1>
            <p class="text-slate-500 font-medium mt-1">Konfirmasi kehadiranmu pada jam pelajaran yang sedang berlangsung</p>
        </div>
        <div class="px-6 py-3 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-pink-50 text-[#d90d8b] flex items-center justify-center">
                <i class="material-icons">calendar_today</i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">{{ $todayName }}</p>
                <p class="text-sm font-bold text-slate-700 mt-1">{{ now()->format('d F Y') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Current Active Session -->
        <div class="lg:col-span-2 space-y-6">
            @if($activeSession)
            <div class="bg-gradient-to-br from-[#ba80e8] to-[#d90d8b] rounded-[3rem] p-10 text-white shadow-xl shadow-purple-100 relative overflow-hidden">
                <!-- Decorative Circle -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-8">
                        <span class="px-4 py-1.5 bg-white/20 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-widest">Sesi Aktif Sekarang</span>
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                        <div>
                            <h2 class="text-3xl font-black tracking-tight mb-2">{{ $activeSession->subject->nama_pelajaran }}</h2>
                            <div class="flex items-center gap-6 text-purple-50 font-medium">
                                <div class="flex items-center gap-2">
                                    <i class="material-icons text-lg">person</i>
                                    <span>{{ $activeSession->subject->guru->nama_lengkap ?? 'Guru Pengampu' }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="material-icons text-lg">schedule</i>
                                    <span>{{ $activeSession->jam->jam_mulai->format('H:i') }} - {{ $activeSession->jam->jam_selesai->format('H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        @if($hasAttended)
                        <div class="flex flex-col items-center gap-3 px-8 py-6 bg-white/10 backdrop-blur-xl border border-white/20 rounded-[2rem]">
                            <i class="material-icons text-4xl text-emerald-300">verified</i>
                            <span class="text-sm font-black uppercase tracking-widest text-white">SUDAH ABSEN</span>
                        </div>
                        @else
                        <button 
                            @click="submitAbsensi('{{ $activeSession->subject_id }}')"
                            class="px-10 py-5 bg-white text-[#d90d8b] rounded-[2rem] font-black text-sm uppercase tracking-widest shadow-lg shadow-black/10 hover:scale-105 active:scale-95 transition-all cursor-pointer"
                        >
                            KIRIM KEHADIRAN
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white rounded-[3rem] border-2 border-dashed border-slate-100 p-16 flex flex-col items-center text-center">
                <div class="w-24 h-24 bg-slate-50 text-slate-200 rounded-[2.5rem] flex items-center justify-center mb-8">
                    <i class="material-icons text-5xl">event_busy</i>
                </div>
                <h3 class="text-xl font-black text-slate-400 uppercase tracking-widest">Tidak Ada Jam Pelajaran</h3>
                <p class="text-slate-400 font-medium mt-2 max-w-sm">Saat ini tidak ada sesi mata pelajaran yang aktif. Silakan cek kembali sesuai jadwal kelasmu.</p>
            </div>
            @endif

            <!-- Today's Schedule Overview -->
            <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm p-10">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center">
                        <i class="material-icons">list_alt</i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800 tracking-tight">Jadwal Kelas Hari Ini</h3>
                        <p class="text-xs text-slate-400 font-medium tracking-tight">Daftar pelajaran untuk kelasmu hari ini</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($schedules as $s)
                    <div class="flex items-center justify-between p-6 bg-slate-50/50 rounded-3xl border border-transparent transition-all {{ $activeSession && $activeSession->id === $s->id ? 'border-primary-100 bg-white ring-2 ring-primary-50' : '' }}">
                        <div class="flex items-center gap-6">
                            <div class="w-12 h-12 bg-white rounded-2xl flex flex-col items-center justify-center shadow-sm border border-slate-100">
                                <span class="text-[9px] font-black text-slate-300 leading-none">JAM</span>
                                <span class="text-base font-black text-slate-700 leading-none mt-1">{{ $loop->iteration }}</span>
                            </div>
                            <div>
                                <h4 class="font-black text-slate-700 tracking-tight">{{ $s->subject->nama_pelajaran }}</h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                    {{ $s->jam->jam_mulai->format('H:i') }} - {{ $s->jam->jam_selesai->format('H:i') }}
                                </p>
                            </div>
                        </div>
                        @if($activeSession && $activeSession->id === $s->id)
                            <span class="px-4 py-1 bg-[#d90d8b] text-white text-[8px] font-black uppercase tracking-widest rounded-full">AKTIF</span>
                        @endif
                    </div>
                    @empty
                    <p class="text-center text-slate-300 font-bold py-10 uppercase tracking-widest text-xs">Jadwal tidak tersedia</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right: Attendance History -->
        <div class="space-y-6">
            <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm p-8">
                <h3 class="text-base font-black text-slate-800 tracking-tight mb-6">Status Kehadiran Hari Ini</h3>
                
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-emerald-50 p-6 rounded-[2rem] flex flex-col items-center">
                        <span class="text-[20px] font-black text-emerald-500 leading-none">{{ $history->where('status', 'H')->count() }}</span>
                        <span class="text-[9px] font-black text-emerald-400 uppercase tracking-widest mt-2">HADIR</span>
                    </div>
                    <div class="bg-rose-50 p-6 rounded-[2rem] flex flex-col items-center">
                        <span class="text-[20px] font-black text-rose-500 leading-none">{{ $history->where('status', 'A')->count() }}</span>
                        <span class="text-[9px] font-black text-rose-400 uppercase tracking-widest mt-2">ALFA</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest ml-2">RIWAYAT ABSENSI</p>
                    @forelse($history as $log)
                    <div class="flex items-center justify-between p-4 bg-white border border-slate-100 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center">
                                <i class="material-icons text-sm">check</i>
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-700 leading-tight">{{ $log->subject->nama_pelajaran }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $log->created_at->format('H:i') }} WIB</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-8 text-center bg-slate-50/50 rounded-2xl border border-dashed border-slate-100">
                        <p class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">Belum ada riwayat</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function absensiPage() {
        return {
            submitAbsensi(subjectId) {
                Swal.fire({
                    title: 'Konfirmasi Hadir',
                    text: 'Kirim kehadiran untuk mata pelajaran ini sekarang?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d90d8b',
                    confirmButtonText: 'Ya, Hadir!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({ title: 'Mengirim...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                        $.ajax({
                            url: '{{ route("student.absensi.submit") }}',
                            method: 'POST',
                            data: {
                                subject_id: subjectId,
                                _token: '{{ csrf_token() }}'
                            },
                            success: (res) => {
                                Swal.fire({ icon: 'success', title: 'Berhasil', text: res.success, timer: 2000, showConfirmButton: false }).then(() => {
                                    location.reload();
                                });
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
