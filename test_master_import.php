<?php

use App\Imports\MasterGuruDinasImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$filePath = 'assets/Individu Guru - per Desember 2025.xls';

echo "Testing import from: $filePath\n";

try {
    DB::beginTransaction();
    
    // Use a custom class to wrap the import to capture details
    Excel::import(new MasterGuruDinasImport, $filePath);
    
    $count = DB::table('master_guru_dinas')->count();
    echo "Successfully imported $count records.\n";
    
    // Sample data check
    $sample = DB::table('master_guru_dinas')->first();
    if ($sample) {
        echo "Sample Record:\n";
        echo "Nama: " . $sample->nama . "\n";
        echo "NIK: " . $sample->nik . "\n";
        echo "Sekolah: " . $sample->tempat_tugas . "\n";
    }

    DB::rollback(); // Don't actually commit in test script if just verifying
    echo "Test completed (Rolled back changes).\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
