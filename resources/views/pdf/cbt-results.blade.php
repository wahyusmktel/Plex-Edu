<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hasil CBT - {{ $cbt->nama_cbt }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #333; }
        .header h1 { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .header p { font-size: 12px; color: #666; }
        .info-grid { display: table; width: 100%; margin-bottom: 20px; }
        .info-item { display: table-cell; width: 25%; padding: 8px; text-align: center; background: #f5f5f5; border: 1px solid #ddd; }
        .info-item .label { font-size: 9px; text-transform: uppercase; color: #666; margin-bottom: 3px; }
        .info-item .value { font-size: 16px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #333; color: white; font-size: 10px; text-transform: uppercase; }
        tr:nth-child(even) { background: #f9f9f9; }
        .text-center { text-align: center; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REKAP HASIL UJIAN CBT</h1>
        <p>{{ $cbt->nama_cbt }} | {{ $cbt->subject->nama_pelajaran ?? 'Umum' }} | {{ $cbt->tanggal->format('d F Y') }}</p>
    </div>

    <div class="info-grid">
        <div class="info-item">
            <div class="label">Total Peserta</div>
            <div class="value">{{ $cbt->sessions->count() }}</div>
        </div>
        <div class="info-item">
            <div class="label">Selesai</div>
            <div class="value">{{ $cbt->sessions->where('status', 'completed')->count() }}</div>
        </div>
        <div class="info-item">
            <div class="label">Rata-rata Skor</div>
            <div class="value">{{ round($cbt->sessions->avg('skor'), 1) }}</div>
        </div>
        <div class="info-item">
            <div class="label">Skor Tertinggi</div>
            <div class="value">{{ $cbt->sessions->max('skor') ?? 0 }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">No</th>
                <th>Nama Siswa</th>
                <th style="width: 60px;">NIS</th>
                <th style="width: 80px;">Kelas</th>
                <th style="width: 70px;">Jam Mulai</th>
                <th style="width: 70px;">Jam Selesai</th>
                <th class="text-center" style="width: 50px;">Skor</th>
                <th class="text-center" style="width: 70px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cbt->sessions->sortByDesc('skor') as $index => $session)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $session->siswa->nama_lengkap ?? 'N/A' }}</td>
                <td>{{ $session->siswa->nis ?? '-' }}</td>
                <td>{{ $session->siswa->kelas->nama ?? '-' }}</td>
                <td>{{ $session->start_time?->format('H:i:s') }}</td>
                <td>{{ $session->end_time?->format('H:i:s') ?? '-' }}</td>
                <td class="text-center"><strong>{{ $session->skor }}</strong></td>
                <td class="text-center">
                    @if($session->status == 'completed')
                        <span class="badge badge-success">Selesai</span>
                    @else
                        <span class="badge badge-warning">Berlangsung</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Belum ada peserta.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->format('d F Y H:i') }} | Literasia School Management System
    </div>
</body>
</html>
