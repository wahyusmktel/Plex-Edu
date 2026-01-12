<!DOCTYPE html>
<html>
<head>
    <title>Rekap Absensi Siswa</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #1e293b; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #ba80e8; padding-bottom: 15px; }
        .title { font-size: 18pt; font-weight: bold; margin-bottom: 5px; color: #1e293b; }
        .subtitle { font-size: 10pt; color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f8fafc; color: #64748b; text-transform: uppercase; font-size: 8pt; letter-spacing: 0.1em; padding: 10px; border: 1px solid #e2e8f0; }
        td { padding: 10px; border: 1px solid #e2e8f0; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .footer { margin-top: 40px; text-align: right; font-size: 8pt; color: #94a3b8; }
        .status-box { display: inline-block; width: 25px; height: 25px; line-height: 25px; text-align: center; border-radius: 5px; font-weight: bold; margin: 2px; }
        .H { background-color: #ecfdf5; color: #10b981; }
        .A { background-color: #fef2f2; color: #ef4444; }
        .S { background-color: #eff6ff; color: #3b82f6; }
        .I { background-color: #fffbeb; color: #f59e0b; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">REKAPITULASI ABSENSI SISWA</div>
        <div class="subtitle">Periode: {{ $startDate }} - {{ $endDate }}</div>
        @if($selectedSubject)
        <div class="subtitle">Mata Pelajaran: {{ $selectedSubject->nama_pelajaran }}</div>
        <div class="subtitle">Guru: {{ $selectedSubject->guru->nama ?? '-' }}</div>
        @endif
        @if($selectedClass)
        <div class="subtitle">Kelas: {{ $selectedClass->nama }}</div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="40%">Nama Lengkap</th>
                <th width="15%">Kelas</th>
                <th width="10%">H</th>
                <th width="10%">A</th>
                <th width="10%">S</th>
                <th width="10%">I</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recap as $item)
            <tr>
                <td class="font-bold">{{ $item['nama'] }}</td>
                <td class="text-center">{{ $item['kelas'] }}</td>
                <td class="text-center"><span class="status-box H">{{ $item['H'] }}</span></td>
                <td class="text-center"><span class="status-box A">{{ $item['A'] }}</span></td>
                <td class="text-center"><span class="status-box S">{{ $item['S'] }}</span></td>
                <td class="text-center"><span class="status-box I">{{ $item['I'] }}</span></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot style="background-color: #f8fafc;">
            <tr>
                <td colspan="2" class="text-center font-bold">TOTAL KESELURUHAN</td>
                <td class="text-center font-bold">{{ collect($recap)->sum('H') }}</td>
                <td class="text-center font-bold">{{ collect($recap)->sum('A') }}</td>
                <td class="text-center font-bold">{{ collect($recap)->sum('S') }}</td>
                <td class="text-center font-bold">{{ collect($recap)->sum('I') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
