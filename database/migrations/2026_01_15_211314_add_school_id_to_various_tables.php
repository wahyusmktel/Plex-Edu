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
        if (!Schema::hasColumn('kalender_events', 'school_id')) {
            Schema::table('kalender_events', function (Blueprint $table) {
                $table->foreignUuid('school_id')->nullable()->after('id')->constrained('schools')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('sambutans', 'school_id')) {
            Schema::table('sambutans', function (Blueprint $table) {
                $table->foreignUuid('school_id')->nullable()->after('id')->constrained('schools')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('elections', 'school_id')) {
            Schema::table('elections', function (Blueprint $table) {
                $table->foreignUuid('school_id')->nullable()->after('id')->constrained('schools')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });
        Schema::table('sambutans', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });
        Schema::table('kalender_events', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });
    }
};
