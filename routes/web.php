<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FungsionarisController; // Added
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
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

    // Sekolah Routes
    Route::prefix('sekolah')->name('sekolah.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SekolahController::class, 'index'])->name('index');
        Route::post('/settings', [\App\Http\Controllers\SekolahController::class, 'updateSettings'])->name('update-settings');
        
        // Jurusan
        Route::post('/jurusan/store', [\App\Http\Controllers\SekolahController::class, 'storeJurusan'])->name('jurusan.store');
        Route::get('/jurusan/show/{id}', [\App\Http\Controllers\SekolahController::class, 'showJurusan'])->name('jurusan.show');
        Route::post('/jurusan/update/{id}', [\App\Http\Controllers\SekolahController::class, 'updateJurusan'])->name('jurusan.update');
        Route::delete('/jurusan/destroy/{id}', [\App\Http\Controllers\SekolahController::class, 'destroyJurusan'])->name('jurusan.destroy');

        // Kelas
        Route::post('/kelas/store', [\App\Http\Controllers\SekolahController::class, 'storeKelas'])->name('kelas.store');
        Route::get('/kelas/show/{id}', [\App\Http\Controllers\SekolahController::class, 'showKelas'])->name('kelas.show');
        Route::post('/kelas/update/{id}', [\App\Http\Controllers\SekolahController::class, 'updateKelas'])->name('kelas.update');
        Route::delete('/kelas/destroy/{id}', [\App\Http\Controllers\SekolahController::class, 'destroyKelas'])->name('kelas.destroy');
    });

    // Mata Pelajaran Routes
    Route::prefix('mata-pelajaran')->name('mata-pelajaran.')->group(function () {
        Route::get('/', [\App\Http\Controllers\MataPelajaranController::class, 'index'])->name('index');
        
        // Jam Pelajaran
        Route::post('/jam/store', [\App\Http\Controllers\MataPelajaranController::class, 'storeJam'])->name('jam.store');
        Route::delete('/jam/destroy/{id}', [\App\Http\Controllers\MataPelajaranController::class, 'destroyJam'])->name('jam.destroy');

        // Mata Pelajaran
        Route::post('/subject/store', [\App\Http\Controllers\MataPelajaranController::class, 'storeSubject'])->name('subject.store');
        Route::get('/subject/show/{id}', [\App\Http\Controllers\MataPelajaranController::class, 'showSubject'])->name('subject.show');
        Route::post('/subject/update/{id}', [\App\Http\Controllers\MataPelajaranController::class, 'updateSubject'])->name('subject.update');
        Route::delete('/subject/destroy/{id}', [\App\Http\Controllers\MataPelajaranController::class, 'destroySubject'])->name('subject.destroy');

        // Jadwal Pelajaran
        Route::get('/schedule/get-by-kelas/{kelas_id}', [\App\Http\Controllers\MataPelajaranController::class, 'getSchedulesByKelas'])->name('schedule.get-by-kelas');
        Route::post('/schedule/store', [\App\Http\Controllers\MataPelajaranController::class, 'storeSchedule'])->name('schedule.store');
        Route::delete('/schedule/destroy/{id}', [\App\Http\Controllers\MataPelajaranController::class, 'destroySchedule'])->name('schedule.destroy');
    });
});
