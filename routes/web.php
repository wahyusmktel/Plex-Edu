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
});
