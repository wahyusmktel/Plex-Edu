<table>
    <thead>
        <tr>
            <th colspan="3" style="text-align: center; font-weight: bold;">LAPORAN HASIL VOTE: {{ strtoupper($election->judul) }}</th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: center;">Jenis: {{ $election->jenis }} | Periode: {{ $election->start_date->format('d/m/Y') }} - {{ $election->end_date->format('d/m/Y') }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="background-color: #f1f5f9; font-weight: bold;">NO URUT</th>
            <th style="background-color: #f1f5f9; font-weight: bold;">NAMA KANDIDAT</th>
            <th style="background-color: #f1f5f9; font-weight: bold;">TOTAL SUARA</th>
        </tr>
    </thead>
    <tbody>
        @foreach($results as $res)
        <tr>
            <td>{{ $res['no_urut'] }}</td>
            <td>{{ $res['nama'] }}</td>
            <td>{{ $res['total_suara'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
