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
        if (!Schema::hasColumn('master_pelanggarans', 'school_id')) {
            Schema::table('master_pelanggarans', function (Blueprint $table) {
                $table->uuid('school_id')->nullable()->after('id');
                $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('pelanggaran_siswas', 'school_id')) {
            Schema::table('pelanggaran_siswas', function (Blueprint $table) {
                $table->uuid('school_id')->nullable()->after('id');
                $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('pelanggaran_pegawais', 'school_id')) {
            Schema::table('pelanggaran_pegawais', function (Blueprint $table) {
                $table->uuid('school_id')->nullable()->after('id');
                $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_pelanggarans', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });

        Schema::table('pelanggaran_siswas', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });

        Schema::table('pelanggaran_pegawais', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });
    }
};
