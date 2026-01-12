<!DOCTYPE html>
<html>
<head>
    <title>Siswa History Absensi</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #1e293b; font-size: 10pt; }
        .header { border-bottom: 2px solid #ba80e8; padding-bottom: 20px; margin-bottom: 30px; }
        .student-info { margin-top: 10px; }
        .student-name { font-size: 16pt; font-weight: bold; color: #1e293b; }
        .student-class { font-size: 10pt; color: #ba80e8; font-weight: bold; }
        .title { font-size: 12pt; font-weight: bold; margin-bottom: 20px; color: #64748b; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #f8fafc; color: #64748b; text-transform: uppercase; font-size: 8pt; letter-spacing: 0.1em; padding: 10px; border: 1px solid #e2e8f0; text-align: left; }
        td { padding: 10px; border: 1px solid #e2e8f0; }
        .status-H { color: #10b981; font-weight: bold; }
        .status-A { color: #ef4444; font-weight: bold; }
        .status-S { color: #3b82f6; font-weight: bold; }
        .status-I { color: #f59e0b; font-weight: bold; }
        .footer { margin-top: 40px; text-align: right; font-size: 8pt; color: #94a3b8; }
        .summary { margin-top: 30px; display: flex; gap: 20px; }
        .summary-item { display: inline-block; margin-right: 20px; border: 1px solid #e2e8f0; padding: 10px 15px; border-radius: 10px; }
        .summary-label { font-size: 8pt; color: #64748b; font-weight: bold; }
        .summary-value { font-size: 14pt; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="student-info">
            <div class="student-name">{{ strtoupper($siswa->nama_lengkap) }}</div>
            <div class="student-class">{{ $siswa->kelas->nama }} | NIS: {{ $siswa->nis }}</div>
        </div>
    </div>

    <div class="title">Riwayat Kehadiran ({{ $startDate }} - {{ $endDate }})</div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">HADIR</div>
            <div class="summary-value status-H">{{ $recap['H'] }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">ALFA</div>
            <div class="summary-value status-A">{{ $recap['A'] }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">SAKIT</div>
            <div class="summary-value status-S">{{ $recap['S'] }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">IZIN</div>
            <div class="summary-value status-I">{{ $recap['I'] }}</div>
        </div>
    </div>

    <table style="margin-top: 30px;">
        <thead>
            <tr>
                <th width="20%">Tanggal</th>
                <th width="20%">Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($history as $record)
            <tr>
                <td>{{ $record->tanggal->format('d/m/Y') }}</td>
                <td class="status-{{ $record->status }}">
                    @if($record->status == 'H') Hadir
                    @elseif($record->status == 'A') Alfa
                    @elseif($record->status == 'S') Sakit
                    @elseif($record->status == 'I') Izin
                    @endif
                </td>
                <td>{{ $record->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align: center; color: #94a3b8;">Tidak ada data khusus selama periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
