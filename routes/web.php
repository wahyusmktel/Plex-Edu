<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FungsionarisController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\ERaportController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\BeritaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Fungsionaris Routes
    Route::prefix('fungsionaris')->name('fungsionaris.')->group(function () {
        Route::get('/', [FungsionarisController::class, 'index'])->name('index');
        Route::post('/store', [FungsionarisController::class, 'store'])->name('store');
        Route::get('/show/{id}', [FungsionarisController::class, 'show'])->name('show');
        Route::post('/update/{id}', [FungsionarisController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [FungsionarisController::class, 'destroy'])->name('destroy');
        Route::post('/import', [FungsionarisController::class, 'import'])->name('import');
        Route::get('/download-template', [FungsionarisController::class, 'downloadTemplate'])->name('download-template');
    });

    // Siswa Routes
    Route::prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/', [SiswaController::class, 'index'])->name('index');
        Route::post('/store', [SiswaController::class, 'store'])->name('store');
        Route::get('/show/{id}', [SiswaController::class, 'show'])->name('show');
        Route::post('/update/{id}', [SiswaController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [SiswaController::class, 'destroy'])->name('destroy');
        Route::post('/import', [SiswaController::class, 'import'])->name('import');
        Route::get('/download-template', [SiswaController::class, 'downloadTemplate'])->name('download-template');
    });

    // E-Raport Routes
    Route::prefix('e-raport')->name('e-raport.')->group(function () {
        Route::get('/', [ERaportController::class, 'index'])->name('index');
        Route::post('/store', [ERaportController::class, 'store'])->name('store');
        Route::get('/show/{id}', [ERaportController::class, 'show'])->name('show');
        Route::post('/update/{id}', [ERaportController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [ERaportController::class, 'destroy'])->name('destroy');
    });

    // Pelanggaran Routes
    Route::prefix('pelanggaran')->name('pelanggaran.')->group(function () {
        Route::get('/', [PelanggaranController::class, 'index'])->name('index');
        
        // Master
        Route::post('/store-master', [PelanggaranController::class, 'storeMaster'])->name('store-master');
        Route::get('/show-master/{id}', [PelanggaranController::class, 'showMaster'])->name('show-master');
        Route::post('/update-master/{id}', [PelanggaranController::class, 'updateMaster'])->name('update-master');
        Route::delete('/destroy-master/{id}', [PelanggaranController::class, 'destroyMaster'])->name('destroy-master');
        
        // Siswa
        Route::post('/store-siswa', [PelanggaranController::class, 'storeSiswa'])->name('store-siswa');
        Route::get('/show-siswa/{id}', [PelanggaranController::class, 'showSiswa'])->name('show-siswa');
        Route::post('/update-siswa/{id}', [PelanggaranController::class, 'updateSiswa'])->name('update-siswa');
        Route::delete('/destroy-siswa/{id}', [PelanggaranController::class, 'destroySiswa'])->name('destroy-siswa');
        
        // Pegawai
        Route::post('/store-pegawai', [PelanggaranController::class, 'storePegawai'])->name('store-pegawai');
        Route::get('/show-pegawai/{id}', [PelanggaranController::class, 'showPegawai'])->name('show-pegawai');
        Route::post('/update-pegawai/{id}', [PelanggaranController::class, 'updatePegawai'])->name('update-pegawai');
        Route::delete('/destroy-pegawai/{id}', [PelanggaranController::class, 'destroyPegawai'])->name('destroy-pegawai');

        // Reports
        Route::get('/pdf-siswa/{id}', [PelanggaranController::class, 'pdfSiswa'])->name('pdf-siswa');
        Route::get('/pdf-pegawai/{id}', [PelanggaranController::class, 'pdfPegawai'])->name('pdf-pegawai');
        Route::get('/export-excel-siswa', [PelanggaranController::class, 'exportExcelSiswa'])->name('export-excel-siswa');
        Route::get('/export-excel-pegawai', [PelanggaranController::class, 'exportExcelPegawai'])->name('export-excel-pegawai');
    });

    // Sekolah Routes
    Route::prefix('sekolah')->name('sekolah.')->group(function () {
        Route::get('/', [SekolahController::class, 'index'])->name('index');
        Route::post('/settings/update', [SekolahController::class, 'updateSettings'])->name('update-settings');
        Route::post('/settings/activate/{id}', [SekolahController::class, 'activateSettings'])->name('activate-settings');
        Route::delete('/settings/destroy/{id}', [SekolahController::class, 'destroySettings'])->name('destroy-settings');

        Route::post('/jurusan/store', [SekolahController::class, 'storeJurusan'])->name('jurusan.store');
        Route::get('/jurusan/show/{id}', [SekolahController::class, 'showJurusan'])->name('jurusan.show');
        Route::post('/jurusan/update/{id}', [SekolahController::class, 'updateJurusan'])->name('jurusan.update');
        Route::delete('/jurusan/destroy/{id}', [SekolahController::class, 'destroyJurusan'])->name('jurusan.destroy');

        Route::post('/kelas/store', [SekolahController::class, 'storeKelas'])->name('kelas.store');
        Route::get('/kelas/show/{id}', [SekolahController::class, 'showKelas'])->name('kelas.show');
        Route::post('/kelas/update/{id}', [SekolahController::class, 'updateKelas'])->name('kelas.update');
        Route::delete('/kelas/destroy/{id}', [SekolahController::class, 'destroyKelas'])->name('kelas.destroy');
    });

    // Berita Routes
    Route::resource('berita', BeritaController::class);

    // Mata Pelajaran Routes
    Route::prefix('mata-pelajaran')->name('mata-pelajaran.')->group(function () {
        Route::get('/', [MataPelajaranController::class, 'index'])->name('index');
        
        // Subject (Mata Pelajaran)
        Route::prefix('subject')->name('subject.')->group(function () {
            Route::post('/store', [MataPelajaranController::class, 'storeSubject'])->name('store');
            Route::get('/show/{id}', [MataPelajaranController::class, 'showSubject'])->name('show');
            Route::post('/update/{id}', [MataPelajaranController::class, 'updateSubject'])->name('update');
            Route::delete('/destroy/{id}', [MataPelajaranController::class, 'destroySubject'])->name('destroy');
        });

        // Jam Pelajaran
        Route::prefix('jam')->name('jam.')->group(function () {
            Route::post('/store', [MataPelajaranController::class, 'storeJam'])->name('store');
            Route::delete('/destroy/{id}', [MataPelajaranController::class, 'destroyJam'])->name('destroy');
        });

        // Schedule
        Route::prefix('schedule')->name('schedule.')->group(function () {
            Route::post('/store', [MataPelajaranController::class, 'storeSchedule'])->name('store');
            Route::delete('/destroy/{id}', [MataPelajaranController::class, 'destroySchedule'])->name('destroy');
            Route::get('/get-by-kelas/{kelas_id}', [MataPelajaranController::class, 'getSchedulesByKelas'])->name('kelas');
        });
    });
});
