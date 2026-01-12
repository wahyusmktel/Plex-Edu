<table>
    <thead>
        <tr>
            <th colspan="6" style="text-align: center; font-weight: bold; font-size: 14pt;">REKAPITULASI ABSENSI SISWA</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center;">Periode: {{ $startDate }} s/d {{ $endDate }}</th>
        </tr>
        @if($selectedClass)
        <tr>
            <th colspan="6" style="text-align: center;">Kelas: {{ $selectedClass->nama }}</th>
        </tr>
        @endif
        <tr></tr>
        <tr>
            <th style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #000000;">NAMA LENGKAP</th>
            <th style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #000000;">KELAS</th>
            <th style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #000000; text-align: center;">HADIR (H)</th>
            <th style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #000000; text-align: center;">ALFA (A)</th>
            <th style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #000000; text-align: center;">SAKIT (S)</th>
            <th style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #000000; text-align: center;">IZIN (I)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($recap as $item)
        <tr>
            <td style="border: 1px solid #000000;">{{ $item['nama'] }}</td>
            <td style="border: 1px solid #000000;">{{ $item['kelas'] }}</td>
            <td style="border: 1px solid #000000; text-align: center;">{{ $item['H'] }}</td>
            <td style="border: 1px solid #000000; text-align: center;">{{ $item['A'] }}</td>
            <td style="border: 1px solid #000000; text-align: center;">{{ $item['S'] }}</td>
            <td style="border: 1px solid #000000; text-align: center;">{{ $item['I'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
