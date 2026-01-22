<?php

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use App\Imports\SiswaImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Mock a user with a school_id
$user = User::where('role', 'admin')->first();
if (!$user) {
    echo "No admin user found to mock authentication.\n";
    exit;
}
Auth::login($user);

echo "Testing SiswaImport Logic...\n";

$import = new SiswaImport();

// Mock a row from the Excel (index 0 is Col A, 1 is Col B, etc.)
// Names are from Row 5 mapping
$row = array_fill(0, 70, null);
$row[1] = "TEST STUDENT CODE"; // B - Nama
$row[2] = "NIPD123";           // C - NIPD
$row[4] = "NISN123";           // E - NISN
$row[42] = "KELAS TEST BARU";  // AQ - Rombel Saat Ini (Index 42 = Column AQ)

try {
    DB::beginTransaction();

    echo "Running model() mapping...\n";
    $siswa = $import->model($row);

    if ($siswa) {
        $siswa->save();
        echo "Siswa saved successfully.\n";
        
        // Check if class was created
        $kelas = Kelas::where('nama', "KELAS TEST BARU")->first();
        if ($kelas) {
            echo "Kelas 'KELAS TEST BARU' auto-created successfully. ID: {$kelas->id}\n";
        } else {
            echo "ERROR: Kelas was not created.\n";
        }

        // Check if student has all fields
        $savedSiswa = Siswa::where('nipd', 'NIPD123')->first();
        if ($savedSiswa && $savedSiswa->nama_lengkap === "TEST STUDENT CODE") {
            echo "Student data verified in database.\n";
        } else {
            echo "ERROR: Student data mismatch or not found.\n";
        }
    } else {
        echo "ERROR: model() returned null.\n";
    }

    DB::rollBack();
    echo "Test completed and rolled back.\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "EXCEPTION: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
