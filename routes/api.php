<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SchoolRegistrationController;
use App\Http\Controllers\Api\SchoolManagementController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\StudentAttendanceController;
use App\Http\Controllers\Api\ELearningApiController;
use App\Http\Controllers\Api\BankSoalApiController;
use App\Http\Controllers\Api\ForumApiController;
use App\Http\Controllers\Api\EVotingApiController;
use App\Http\Controllers\Api\BeritaApiController;
use App\Http\Controllers\Api\ERaportApiController;
use App\Http\Controllers\Api\PelanggaranApiController;
use App\Http\Controllers\Api\PengumumanApiController;
use App\Http\Controllers\Api\ELibraryApiController;
use App\Http\Controllers\Api\KalenderApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register-school', [SchoolRegistrationController::class, 'register']);
Route::get('/sliders/image/{filename}', [SliderController::class, 'showImage']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/dashboard-stats', [DashboardController::class, 'index']);
    Route::get('/sliders', [SliderController::class, 'index']);
    
    // Student Attendance
    Route::get('/student/schedule-today', [StudentAttendanceController::class, 'getTodaySchedule']);
    Route::get('/student/schedule-all', [StudentAttendanceController::class, 'getAllSchedules']);
    Route::get('/student/subjects-all', [StudentAttendanceController::class, 'getAllSubjects']);
    Route::get('/student/grades-all', [StudentAttendanceController::class, 'getGrades']);
    Route::post('/student/attendance', [StudentAttendanceController::class, 'submitAttendance']);

    // E-Learning
    Route::get('/student/elearning', [ELearningApiController::class, 'getCourses']);
    Route::get('/student/elearning/{id}', [ELearningApiController::class, 'getCourseDetail']);
    Route::get('/student/elearning/module/{id}', [ELearningApiController::class, 'viewModule']);
    Route::post('/student/elearning/module/{id}/complete', [ELearningApiController::class, 'completeModule']);
    Route::post('/student/elearning/module/{id}/submit', [ELearningApiController::class, 'submitAssignment']);
    Route::get('/student/elearning/module/{id}/submission', [ELearningApiController::class, 'getSubmission']);

    // CBT
    Route::get('/student/cbt/list', [ELearningApiController::class, 'getCbtList']);
    Route::post('/student/cbt/{cbt_id}/start', [ELearningApiController::class, 'startCbtSession']);
    Route::get('/student/cbt/session/{session_id}/questions', [ELearningApiController::class, 'getCbtQuestions']);
    Route::post('/student/cbt/session/{session_id}/answer', [ELearningApiController::class, 'submitCbtAnswer']);
    Route::post('/student/cbt/session/{session_id}/finish', [ELearningApiController::class, 'finishCbtSession']);

    // Bank Soal
    Route::get('/student/bank-soal', [BankSoalApiController::class, 'index']);
    Route::get('/student/bank-soal/{id}', [BankSoalApiController::class, 'show']);

    // Forum
    Route::get('/student/forums', [ForumApiController::class, 'index']);
    Route::get('/student/forums/{id}', [ForumApiController::class, 'show']);
    Route::get('/student/forums/topic/{id}', [ForumApiController::class, 'showTopic']);
    Route::post('/student/forums/topic/{id}/post', [ForumApiController::class, 'storePost']);

    // E-Voting
    Route::get('/student/evoting', [EVotingApiController::class, 'index']);
    Route::get('/student/evoting/{id}', [EVotingApiController::class, 'show']);
    Route::post('/student/evoting/{id}/vote', [EVotingApiController::class, 'vote']);

    // Berita
    Route::get('/student/berita', [BeritaApiController::class, 'index']);
    Route::get('/student/berita/{id}', [BeritaApiController::class, 'show']);

    // E-Raport
    Route::get('/student/eraport', [ERaportApiController::class, 'index']);
    Route::get('/student/eraport/{id}', [ERaportApiController::class, 'show']);

    // Pelanggaran
    Route::get('/student/pelanggaran', [PelanggaranApiController::class, 'index']);

    // Pengumuman
    Route::get('/student/pengumuman', [PengumumanApiController::class, 'index']);

    // E-Library
    Route::get('/student/elibrary/catalog', [ELibraryApiController::class, 'catalog']);
    Route::get('/student/elibrary/item/{id}', [ELibraryApiController::class, 'show']);
    Route::post('/student/elibrary/borrow/{id}', [ELibraryApiController::class, 'borrow']);
    Route::get('/student/elibrary/my-borrowings', [ELibraryApiController::class, 'myBorrowings']);
    Route::get('/student/elibrary/read/{id}', [ELibraryApiController::class, 'read']);

    // Kalender
    Route::get('/student/kalender', [KalenderApiController::class, 'index']);

    // School Management
    Route::prefix('school')->group(function () {
        Route::get('/identity', [SchoolManagementController::class, 'getIdentity']);
        Route::put('/identity', [SchoolManagementController::class, 'updateIdentity']);
        
        Route::get('/settings', [SchoolManagementController::class, 'getSettings']);
        Route::post('/settings', [SchoolManagementController::class, 'storeSetting']);
        Route::put('/settings/{id}/activate', [SchoolManagementController::class, 'activateSetting']);
        Route::delete('/settings/{id}', [SchoolManagementController::class, 'destroySetting']);
    });

    // Jurusan Management
    Route::get('/jurusan', [SchoolManagementController::class, 'getJurusan']);
    Route::post('/jurusan', [SchoolManagementController::class, 'storeJurusan']);
    Route::put('/jurusan/{id}', [SchoolManagementController::class, 'updateJurusan']);
    Route::delete('/jurusan/{id}', [SchoolManagementController::class, 'destroyJurusan']);

    // Kelas Management
    Route::get('/kelas', [SchoolManagementController::class, 'getKelas']);
    Route::get('/kelas/{id}', [SchoolManagementController::class, 'showKelas']);
    Route::post('/kelas', [SchoolManagementController::class, 'storeKelas']);
    Route::put('/kelas/{id}', [SchoolManagementController::class, 'updateKelas']);
    Route::delete('/kelas/{id}', [SchoolManagementController::class, 'destroyKelas']);

    // Regional Data Helper
    Route::get('/regional/{type}/{code?}', [SchoolManagementController::class, 'getRegionalData']);
});
