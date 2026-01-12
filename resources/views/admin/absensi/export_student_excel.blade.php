<table>
    <thead>
        <tr>
            <th colspan="3" style="text-align: center; font-weight: bold; font-size: 14pt;">RIWAYAT ABSENSI SISWA</th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: center; font-weight: bold;">{{ strtoupper($siswa->nama_lengkap) }}</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;">
                Kelas: {{ $siswa->kelas->nama }} | 
                Mapel: {{ $selectedSubject ? $selectedSubject->nama_pelajaran . ' (' . ($selectedSubject->guru->nama ?? '-') . ')' : 'Semua Mata Pelajaran' }} | 
                Periode: {{ $startDate }} - {{ $endDate }}
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #000000;">TANGGAL</th>
            @if(!$selectedSubject)
            <th style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #000000;">MATA PELAJARAN</th>
            @endif
            <th style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #000000;">STATUS</th>
            <th style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #000000;">KETERANGAN</th>
        </tr>
    </thead>
    <tbody>
        @foreach($history as $record)
        <tr>
            <td style="border: 1px solid #000000;">{{ $record->tanggal->format('d/m/Y') }}</td>
            @if(!$selectedSubject)
            <td style="border: 1px solid #000000;">{{ $record->subject->nama_pelajaran ?? '-' }}</td>
            @endif
            <td style="border: 1px solid #000000;">
                @if($record->status == 'H') Hadir
                @elseif($record->status == 'A') Alfa
                @elseif($record->status == 'S') Sakit
                @elseif($record->status == 'I') Izin
                @endif
            </td>
            <td style="border: 1px solid #000000;">{{ $record->keterangan ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr></tr>
        <tr>
            <th style="font-weight: bold;">RINGKASAN</th>
            <th style="font-weight: bold;">JUMLAH</th>
        </tr>
        <tr>
            <td>HADIR</td>
            <td>{{ $recap['H'] }}</td>
        </tr>
        <tr>
            <td>ALFA</td>
            <td>{{ $recap['A'] }}</td>
        </tr>
        <tr>
            <td>SAKIT</td>
            <td>{{ $recap['S'] }}</td>
        </tr>
        <tr>
            <td>IZIN</td>
            <td>{{ $recap['I'] }}</td>
        </tr>
    </tfoot>
</table>
