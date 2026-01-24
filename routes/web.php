<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FungsionarisController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\ERaportController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\SambutanController;
use App\Http\Controllers\EVotingController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\ELearningController;
use App\Http\Controllers\BankSoalController;
use App\Http\Controllers\TeacherCertificateController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\GuruDinasController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/privacy-policy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/tentang-kami', [HomeController::class, 'about'])->name('about');
Route::get('/berita-publik/{id}', [HomeController::class, 'newsShow'])->name('public.berita.show');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register-school', [App\Http\Controllers\Auth\SchoolRegistrationController::class, 'showRegistrationForm'])->name('register.school');
Route::post('/register-school', [App\Http\Controllers\Auth\SchoolRegistrationController::class, 'register']);
Route::get('/register-school/regional/{type}/{code?}', [App\Http\Controllers\Auth\SchoolRegistrationController::class, 'getRegionalData'])->name('register.regional');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notification Routes
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // Fungsionaris Routes
    Route::prefix('fungsionaris')->name('fungsionaris.')->group(function () {
        Route::get('/', [FungsionarisController::class, 'index'])->name('index');
        Route::post('/store', [FungsionarisController::class, 'store'])->name('store');
        Route::get('/show/{id}', [FungsionarisController::class, 'show'])->name('show');
        Route::post('/update/{id}', [FungsionarisController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [FungsionarisController::class, 'destroy'])->name('destroy');
        Route::post('/import', [FungsionarisController::class, 'import'])->name('import');
        Route::get('/download-template', [FungsionarisController::class, 'downloadTemplate'])->name('download-template');
        Route::post('/generate-accounts', [FungsionarisController::class, 'generateAccounts'])->name('generate-accounts');
        Route::post('/{id}/reset-password', [FungsionarisController::class, 'resetPassword'])->name('reset-password');
    });

    // Siswa Routes
    Route::prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/', [SiswaController::class, 'index'])->name('index');
        Route::post('/store', [SiswaController::class, 'store'])->name('store');
        Route::get('/show/{id}', [SiswaController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SiswaController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [SiswaController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [SiswaController::class, 'destroy'])->name('destroy');
        Route::post('/import', [SiswaController::class, 'import'])->name('import');
        Route::get('/download-template', [SiswaController::class, 'downloadTemplate'])->name('download-template');
        Route::post('/generate-accounts', [SiswaController::class, 'generateAccounts'])->name('generate-accounts');
        Route::post('/{id}/reset-password', [SiswaController::class, 'resetPassword'])->name('reset-password');
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
        Route::post('/identity/update', [SekolahController::class, 'updateIdentity'])->name('identity.update');
        Route::get('/regional/{type}/{code?}', [SekolahController::class, 'getRegionalData'])->name('regional');

        Route::post('/jurusan/store', [SekolahController::class, 'storeJurusan'])->name('jurusan.store');
        Route::get('/jurusan/show/{id}', [SekolahController::class, 'showJurusan'])->name('jurusan.show');
        Route::post('/jurusan/update/{id}', [SekolahController::class, 'updateJurusan'])->name('jurusan.update');
        Route::delete('/jurusan/destroy/{id}', [SekolahController::class, 'destroyJurusan'])->name('jurusan.destroy');

        Route::post('/kelas/store', [SekolahController::class, 'storeKelas'])->name('kelas.store');
        Route::get('/kelas/show/{id}', [SekolahController::class, 'showKelas'])->name('kelas.show');
        Route::post('/kelas/update/{id}', [SekolahController::class, 'updateKelas'])->name('kelas.update');
        Route::delete('/kelas/destroy/{id}', [SekolahController::class, 'destroyKelas'])->name('kelas.destroy');
    });

    // Berita & Pengumuman Reader Routes
    Route::get('berita/{id}/read', [BeritaController::class, 'read'])->name('berita.read');
    Route::get('pengumuman/{id}/read', [PengumumanController::class, 'read'])->name('pengumuman.read');

    // Berita Routes
    Route::resource('berita', BeritaController::class);
    Route::resource('pengumuman', PengumumanController::class);
    Route::resource('slider', SliderController::class);
    Route::resource('calendar', CalendarController::class);
    Route::resource('sambutan', SambutanController::class);
    Route::resource('e-voting', EVotingController::class);
    Route::get('absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('absensi/export/class', [AbsensiController::class, 'exportClass'])->name('absensi.export.class');
    Route::get('absensi/export/all', [AbsensiController::class, 'exportAll'])->name('absensi.export.all');
    Route::get('absensi/export/student/{id}', [AbsensiController::class, 'exportStudent'])->name('absensi.export.student');
    Route::get('e-voting/{id}/export/excel', [EVotingController::class, 'exportExcel'])->name('e-voting.export.excel');
    Route::get('e-voting/{id}/export/pdf', [EVotingController::class, 'exportPdf'])->name('e-voting.export.pdf');
    Route::post('e-voting/{id}/vote', [EVotingController::class, 'vote'])->name('e-voting.vote');
    Route::get('api/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.events');

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

    // E-Learning Routes
    Route::prefix('elearning')->name('elearning.')->group(function () {
        Route::get('/', [ELearningController::class, 'index'])->name('index');
        Route::post('/store', [ELearningController::class, 'store'])->name('store');
        Route::get('/{id}', [ELearningController::class, 'show'])->name('show');
        Route::delete('/{id}', [ELearningController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/chapter', [ELearningController::class, 'storeChapter'])->name('chapter.store');
        Route::delete('/chapter/{id}', [ELearningController::class, 'destroyChapter'])->name('chapter.destroy');
        Route::post('/chapter/{chapter_id}/module', [ELearningController::class, 'storeModule'])->name('module.store');
        Route::delete('/module/{id}', [ELearningController::class, 'destroyModule'])->name('module.destroy');
        Route::get('/module/{id}', [ELearningController::class, 'viewModule'])->name('module.view');
        Route::post('/module/{id}/complete', [ELearningController::class, 'completeModule'])->name('module.complete');
        Route::post('/module/{id}/submit', [ELearningController::class, 'submitAssignment'])->name('module.submit');
        Route::post('/submission/{id}/grade', [ELearningController::class, 'gradeSubmission'])->name('module.grade');
    });

    // Bank Soal Routes
    Route::prefix('bank-soal')->name('bank-soal.')->group(function () {
        Route::get('/', [BankSoalController::class, 'index'])->name('index');
        Route::get('/archive', [BankSoalController::class, 'archive'])->name('archive');
        Route::get('/download-template', [BankSoalController::class, 'downloadTemplate'])->name('download-template');
        Route::post('/store', [BankSoalController::class, 'store'])->name('store');
        Route::post('/{id}/import', [BankSoalController::class, 'importQuestions'])->name('import');
        Route::get('/{id}', [BankSoalController::class, 'show'])->name('show');
        Route::post('/{id}/update', [BankSoalController::class, 'update'])->name('update');
        Route::delete('/{id}', [BankSoalController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/question', [BankSoalController::class, 'storeQuestion'])->name('question.store');
        Route::post('/question/{question_id}', [BankSoalController::class, 'updateQuestion'])->name('question.update');
        Route::delete('/question/{question_id}', [BankSoalController::class, 'destroyQuestion'])->name('question.destroy');
    });

    // Teacher Certificate Routes
    Route::prefix('certificates')->name('certificates.')->group(function () {
        Route::get('/', [TeacherCertificateController::class, 'index'])->name('index');
        Route::post('/store', [TeacherCertificateController::class, 'store'])->name('store');
        Route::post('/{id}/update', [TeacherCertificateController::class, 'update'])->name('update');
        Route::delete('/{id}', [TeacherCertificateController::class, 'destroy'])->name('destroy');
    });

    // Forum Routes
    Route::prefix('forum')->name('forum.')->group(function () {
        Route::get('/', [ForumController::class, 'index'])->name('index');
        Route::post('/store', [ForumController::class, 'store'])->name('store');
        Route::get('/{id}', [ForumController::class, 'show'])->name('show');
        Route::post('/{id}/topic', [ForumController::class, 'storeTopic'])->name('topic.store');
        Route::get('/topic/{id}', [ForumController::class, 'showTopic'])->name('topic.show');
        Route::post('/topic/{id}/post', [ForumController::class, 'storePost'])->name('post.store');
        Route::post('/topic/{id}/bookmark', [ForumController::class, 'toggleBookmark'])->name('topic.bookmark');
        Route::post('/topic/{id}/mute', [ForumController::class, 'toggleMute'])->name('topic.mute');
        Route::post('/topic/{id}/moderate', [ForumController::class, 'moderateTopic'])->name('topic.moderate');
        Route::post('/user/{id}/suspend', [ForumController::class, 'suspendUser'])->name('user.suspend');
    });

    // E-Library Routes
    Route::prefix('library')->name('library.')->group(function () {
        Route::get('/', [LibraryController::class, 'index'])->name('index');
        Route::get('/create', [LibraryController::class, 'create'])->name('create');
        Route::post('/store', [LibraryController::class, 'store'])->name('store');
        Route::delete('/{id}', [LibraryController::class, 'destroy'])->name('destroy');
        
        // Loans
        Route::get('/loans', [LibraryController::class, 'loans'])->name('loans');
        Route::post('/loans/store', [LibraryController::class, 'storeLoan'])->name('loans.store');
        Route::post('/loans/{id}/return', [LibraryController::class, 'returnLoan'])->name('loans.return');
        Route::post('/{id}/borrow', [LibraryController::class, 'borrow'])->name('borrow');
    });

    // CBT Routes
    Route::prefix('cbt')->name('cbt.')->group(function () {
        Route::get('/', [App\Http\Controllers\CbtController::class, 'index'])->name('index');
        Route::post('/store', [App\Http\Controllers\CbtController::class, 'store'])->name('store');
        Route::get('/show/{id}', [App\Http\Controllers\CbtController::class, 'show'])->name('show');
        Route::post('/update/{id}', [App\Http\Controllers\CbtController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [App\Http\Controllers\CbtController::class, 'destroy'])->name('destroy');
        
        // Questions
        Route::get('/{id}/questions', [App\Http\Controllers\CbtController::class, 'questions'])->name('questions');
        Route::post('/questions/store', [App\Http\Controllers\CbtController::class, 'storeQuestion'])->name('questions.store');
        Route::post('/{id}/import-bank', [App\Http\Controllers\CbtController::class, 'importFromBankSoal'])->name('questions.import-bank');
        Route::get('/questions/show/{question_id}', [App\Http\Controllers\CbtController::class, 'showQuestion'])->name('questions.show');
        Route::post('/questions/update/{question_id}', [App\Http\Controllers\CbtController::class, 'updateQuestion'])->name('questions.update');
        Route::delete('/questions/destroy/{question_id}', [App\Http\Controllers\CbtController::class, 'destroyQuestion'])->name('questions.destroy');
        Route::get('/{id}/results', [App\Http\Controllers\CbtController::class, 'results'])->name('results');
        Route::get('/{id}/export-excel', [App\Http\Controllers\CbtController::class, 'exportExcel'])->name('exportExcel');
        Route::get('/{id}/export-pdf', [App\Http\Controllers\CbtController::class, 'exportPdf'])->name('exportPdf');
        Route::get('/{id}/analysis', [App\Http\Controllers\CbtController::class, 'analysis'])->name('analysis');
        Route::get('/session/{id}', [App\Http\Controllers\CbtController::class, 'sessionDetail'])->name('session-detail');
        Route::get('/grade-essay/{question_id}', [App\Http\Controllers\CbtController::class, 'gradeEssay'])->name('gradeEssay');
        Route::post('/grade-essay/store', [App\Http\Controllers\CbtController::class, 'storeGrade'])->name('gradeEssay.store');
    });

    // Student Absensi Routes
    Route::prefix('student/absensi')->name('student.absensi.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Student\AbsensiController::class, 'index'])->name('index');
        Route::post('/submit', [\App\Http\Controllers\Student\AbsensiController::class, 'submit'])->name('submit');
    });

    // Student Subjects Routes
    Route::prefix('student/subjects')->name('student.subjects.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Student\SubjectController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\Student\SubjectController::class, 'show'])->name('show');
    });

    // Student Schedule Routes
    Route::get('student/schedule', [\App\Http\Controllers\Student\ScheduleController::class, 'index'])->name('student.schedule.index');

    // Student Grade Routes
    Route::get('student/grades', [\App\Http\Controllers\Student\GradeController::class, 'index'])->name('student.grades.index');

    // Student Bank Soal Routes
    Route::prefix('student/bank-soal')->name('student.bank-soal.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Student\BankSoalController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\Student\BankSoalController::class, 'show'])->name('show');
    });

    // Student Raport Routes
    Route::get('student/raport', [\App\Http\Controllers\Student\RaportController::class, 'index'])->name('student.raport.index');

    // Student Pelanggaran Routes
    Route::get('student/pelanggaran', [\App\Http\Controllers\Student\PelanggaranController::class, 'index'])->name('student.pelanggaran.index');

    // Student CBT Routes
    Route::prefix('test')->name('test.')->group(function () {
        Route::get('/', [App\Http\Controllers\StudentCbtController::class, 'index'])->name('index');
        Route::post('/join', [App\Http\Controllers\StudentCbtController::class, 'join'])->name('join');
        Route::get('/exam/{session_id}', [App\Http\Controllers\StudentCbtController::class, 'exam'])->name('exam');
        Route::post('/exam/{session_id}/save-answer', [App\Http\Controllers\StudentCbtController::class, 'saveAnswer'])->name('saveAnswer');
        Route::post('/exam/{session_id}/submit', [App\Http\Controllers\StudentCbtController::class, 'submit'])->name('submit');
        Route::get('/result/{session_id}', [App\Http\Controllers\StudentCbtController::class, 'result'])->name('result');
    });

    // Dinas Routes
    Route::middleware(['auth', 'role:dinas'])->prefix('dinas')->name('dinas.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DinasController::class, 'index'])->name('index');
        Route::get('/stats', [App\Http\Controllers\Admin\DinasController::class, 'stats'])->name('stats');
        Route::get('/schools', [App\Http\Controllers\Admin\DinasController::class, 'schools'])->name('schools');
        Route::post('/schools/store', [App\Http\Controllers\Admin\DinasController::class, 'storeSchool'])->name('schools.store');
        Route::post('/schools/import', [App\Http\Controllers\Admin\DinasController::class, 'importSchools'])->name('schools.import');
        Route::get('/schools/download-template', [App\Http\Controllers\Admin\DinasController::class, 'downloadSchoolTemplate'])->name('schools.download-template');
        Route::post('/schools/generate-accounts', [App\Http\Controllers\Admin\DinasController::class, 'generateSchoolAccounts'])->name('schools.generate-accounts');
        Route::post('/schools/{school}/reset-password', [App\Http\Controllers\Admin\DinasController::class, 'resetSchoolPassword'])->name('schools.reset-password');
        Route::get('/certificates', [App\Http\Controllers\Admin\DinasController::class, 'certificates'])->name('certificates');
        Route::get('/violations', [App\Http\Controllers\Admin\DinasController::class, 'violations'])->name('violations');
        Route::get('/schools/{school}', [App\Http\Controllers\Admin\DinasController::class, 'show'])->name('schools.show');
        Route::post('/school/{school}/approve', [App\Http\Controllers\Admin\DinasController::class, 'approve'])->name('approve');
        Route::post('/school/{school}/reject', [App\Http\Controllers\Admin\DinasController::class, 'reject'])->name('reject');
        Route::post('/school/{school}/toggle', [App\Http\Controllers\Admin\DinasController::class, 'toggleActive'])->name('toggle');
        
        // Student Management for Dinas
        Route::get('/siswa', [App\Http\Controllers\Admin\DinasController::class, 'siswa'])->name('siswa');
        Route::post('/siswa/import/{school_id}', [App\Http\Controllers\Admin\DinasController::class, 'importSiswaForSchool'])->name('siswa.import');
        Route::post('/siswa/bulk-import', [App\Http\Controllers\Admin\DinasController::class, 'bulkImportSiswa'])->name('siswa.bulk-import');
        Route::delete('/siswa/reset/{school_id}', [App\Http\Controllers\Admin\DinasController::class, 'resetSiswaForSchool'])->name('siswa.reset');
        Route::delete('/siswa/reset-all', [App\Http\Controllers\Admin\DinasController::class, 'resetAllSiswa'])->name('siswa.reset-all');
        
        // E-Library for Dinas
        Route::get('/library', [App\Http\Controllers\Admin\DinasController::class, 'library'])->name('library');
        
        // App Settings
        Route::get('/settings', [App\Http\Controllers\Admin\DinasController::class, 'settings'])->name('settings');
        Route::post('/settings', [App\Http\Controllers\Admin\DinasController::class, 'updateSettings'])->name('settings.update');

        // Guru Dinas Routes
        Route::prefix('master-guru')->name('master-guru.')->group(function () {
            Route::get('/', [GuruDinasController::class, 'index'])->name('index');
            Route::get('/sync', [GuruDinasController::class, 'sync'])->name('sync');
            Route::post('/sync', [GuruDinasController::class, 'processSync'])->name('sync.process');
            Route::get('/{id}', [GuruDinasController::class, 'show'])->name('show');
            Route::post('/import', [GuruDinasController::class, 'import'])->name('import');
            Route::delete('/{id}', [GuruDinasController::class, 'destroy'])->name('destroy');
            Route::post('/clear', [GuruDinasController::class, 'clear'])->name('clear');
        });

        // Documentation
        Route::get('/docs', [App\Http\Controllers\Admin\DinasController::class, 'docs'])->name('docs');
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
});
