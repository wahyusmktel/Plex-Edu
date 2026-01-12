<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pelanggaran Pegawai</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; text-transform: uppercase; }
        .content { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f5f5f5; font-weight: bold; width: 30%; }
        .footer { margin-top: 50px; text-align: right; }
        .piket { margin-top: 50px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Laporan Pelanggaran Pegawai / Guru</div>
        <div>Plex-Edu - Sistem Manajemen Sekolah Digital</div>
    </div>

    <div class="content">
        <table>
            <tr>
                <th>Tanggal</th>
                <td>{{ \Carbon\Carbon::parse($data->tanggal)->format('d F Y') }}</td>
            </tr>
            <tr>
                <th>Nama Pegawai</th>
                <td>{{ $data->fungsionaris->nama }}</td>
            </tr>
            <tr>
                <th>Jabatan / Role</th>
                <td>{{ strtoupper($data->fungsionaris->jabatan) }}</td>
            </tr>
            <tr>
                <th>Jenis Pelanggaran</th>
                <td>{{ $data->masterPelanggaran->nama }}</td>
            </tr>
            <tr>
                <th>Poin Pelanggaran</th>
                <td>{{ $data->masterPelanggaran->poin }}</td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td>{{ $data->deskripsi ?? '-' }}</td>
            </tr>
            <tr>
                <th>Tindak Lanjut</th>
                <td>{{ $data->tindak_lanjut ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        <div class="piket">
            <p>Kepala Sekolah / Yayasan</p>
            <br><br><br>
            <p>( _______________________ )</p>
        </div>
    </div>
</body>
</html>
