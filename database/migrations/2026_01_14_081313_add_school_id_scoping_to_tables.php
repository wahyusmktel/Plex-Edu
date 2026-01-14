<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'jurusans', 'kelas', 'school_settings', 'jam_pelajarans', 
            'subjects', 'schedules', 'siswas', 'fungsionaris', 
            'e_raports', 'master_pelanggarans', 'pelanggaran_siswas', 
            'pelanggaran_pegawais', 'beritas', 'pengumumans', 'sliders', 
            'kalender_events', 'sambutans', 'elections', 'absensis', 
            'cbts', 'e_learnings', 'bank_soals', 'teacher_certificates', 
            'library_items'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'school_id')) {
                    $table->uuid('school_id')->nullable()->after('id');
                    $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'jurusans', 'kelas', 'school_settings', 'jam_pelajarans', 
            'subjects', 'schedules', 'siswas', 'fungsionaris', 
            'e_raports', 'master_pelanggarans', 'pelanggaran_siswas', 
            'pelanggaran_pegawais', 'beritas', 'pengumumans', 'sliders', 
            'kalender_events', 'sambutans', 'elections', 'absensis', 
            'cbts', 'e_learnings', 'bank_soals', 'teacher_certificates', 
            'library_items'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'school_id')) {
                    $table->dropForeign(['school_id']);
                    $table->dropColumn('school_id');
                }
            });
        }
    }
};
