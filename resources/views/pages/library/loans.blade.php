@extends('layouts.app')

@section('title', 'Transaksi Peminjaman - Literasia')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('library.index') }}" class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-[#d90d8b] hover:border-[#d90d8b] transition-all">
            <i class="material-icons">arrow_back</i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Transaksi Peminjaman</h1>
            <p class="text-slate-500">Kelola peminjaman dan pengembalian koleksi digital oleh siswa.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Borrow Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden sticky top-24">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="material-icons text-[#d90d8b]">add_circle_outline</i> Catat Peminjaman
                    </h3>
                </div>
                <form action="{{ route('library.loans.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Nama Siswa</label>
                        <select name="student_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#d90d8b] focus:ring-0 transition-all outline-none bg-white" required>
                            <option value="">Pilih Siswa...</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->nama_lengkap }} ({{ $student->kelas->nama ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Koleksi Digital</label>
                        <select name="library_item_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#d90d8b] focus:ring-0 transition-all outline-none bg-white" required>
                            <option value="">Pilih Koleksi...</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">[{{ strtoupper($item->category) }}] {{ $item->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Tgl Pinjam</label>
                            <input type="date" name="loan_date" value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#d90d8b] focus:ring-0 transition-all outline-none" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Tgl Kembali</label>
                            <input type="date" name="due_date" value="{{ date('Y-m-d', strtotime('+7 days')) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#d90d8b] focus:ring-0 transition-all outline-none" required>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-3.5 bg-[#d90d8b] text-white font-bold rounded-xl shadow-lg shadow-pink-100 hover:shadow-xl hover:translate-y-[-2px] transition-all">
                            Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Loan History -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Riwayat Peminjaman</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Siswa</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Koleksi</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Durasi</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($loans as $loan)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-700">{{ $loan->student->nama_lengkap }}</div>
                                        <div class="text-xs text-slate-400 uppercase">{{ $loan->student->kelas->nama_kelas ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-slate-600">{{ $loan->item->title }}</div>
                                        <div class="text-[10px] inline-flex px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 font-bold uppercase mt-1">
                                            {{ $loan->item->category }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs text-slate-500">
                                            <span class="font-medium">P:</span> {{ $loan->loan_date->format('d/m/Y') }}<br>
                                            <span class="font-medium text-red-400">K:</span> {{ $loan->due_date->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($loan->status === 'borrowed')
                                            <span class="px-3 py-1 text-[10px] font-bold bg-blue-50 text-blue-500 rounded-full uppercase">Dipinjam</span>
                                        @elseif($loan->status === 'returned')
                                            <span class="px-3 py-1 text-[10px] font-bold bg-green-50 text-green-500 rounded-full uppercase">Dikembalikan</span>
                                        @else
                                            <span class="px-3 py-1 text-[10px] font-bold bg-red-50 text-red-500 rounded-full uppercase">Terlambat</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($loan->status === 'borrowed')
                                            <form action="{{ route('library.loans.return', $loan->id) }}" method="POST">
                                                @csrf
                                                <button class="px-4 py-2 text-xs font-bold bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                                    Kembalikan
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-300 italic">No Action</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic text-sm">Belum ada data transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
