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

echo "Testing SiswaImport Logic (User School ID: " . Auth::user()->school_id . ")...\n";

$import = new SiswaImport();

// Mock a row from the Excel (index 0 is Col A, 1 is Col B, etc.)
$row = array_fill(0, 70, null);
$row[1] = "TEST STUDENT CODE " . time(); // B - Nama
$row[2] = "NIPD-" . time();             // C - NIPD
$row[4] = "NISN-" . time();             // E - NISN
$row[42] = "KELAS TEST " . rand(1, 100); // AQ - Rombel Saat Ini (Index 42 = Column AQ)

try {
    DB::beginTransaction();

    echo "Running model() mapping for row: " . $row[1] . " | Class: " . $row[42] . "\n";
    $siswa = $import->model($row);

    if ($siswa) {
        // Explicitly set school_id if trait doesn't pick it up in CLI
        if (!$siswa->school_id) $siswa->school_id = Auth::user()->school_id;
        
        if ($siswa->save()) {
            echo "Siswa saved successfully.\n";
            
            // Check if class was created
            $kelas = Kelas::where('nama', $row[42])->first();
            if ($kelas) {
                echo "Kelas '{$row[42]}' verified. ID: {$kelas->id}\n";
            } else {
                echo "ERROR: Kelas was not created.\n";
            }

            // Check if student has all fields
            $savedSiswa = Siswa::find($siswa->id);
            if ($savedSiswa && $savedSiswa->nama_lengkap === $row[1]) {
                echo "Student data verified: " . $savedSiswa->nama_lengkap . " (ID: " . $savedSiswa->id . ")\n";
            } else {
                echo "ERROR: Student data mismatch or not found.\n";
            }
        } else {
            echo "ERROR: Failed to save Siswa model.\n";
        }
    } else {
        echo "ERROR: model() returned null.\n";
    }

    DB::rollBack();
    echo "Test completed and rolled back successfully.\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "EXCEPTION: " . $e->getMessage() . "\n";
}
