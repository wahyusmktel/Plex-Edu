<!DOCTYPE html>
<html>
<head>
    <title>Laporan Hasil Vote</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #1e293b; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #ba80e8; padding-bottom: 15px; }
        .title { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
        .subtitle { font-size: 14px; color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f8fafc; color: #64748b; text-transform: uppercase; font-size: 10px; letter-spacing: 0.1em; padding: 12px; border: 1px solid #f1f5f9; }
        td { padding: 12px; border: 1px solid #f1f5f9; font-size: 12px; }
        .footer { margin-top: 50px; text-align: right; font-size: 10px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN HASIL VOTE</div>
        <div class="subtitle">{{ $election->judul }}</div>
        <div class="subtitle">Jenis: {{ $election->jenis }} | Periode: {{ $election->start_date->format('d M Y') }} - {{ $election->end_date->format('d M Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">No Urut</th>
                <th>Nama Kandidat</th>
                <th width="20%">Total Suara</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $res)
            <tr>
                <td align="center">{{ $res['no_urut'] }}</td>
                <td>{{ $res['nama'] }}</td>
                <td align="center"><b>{{ $res['total_suara'] }}</b></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
