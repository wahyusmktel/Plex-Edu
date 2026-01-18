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
        Schema::table('forums', function (Blueprint $table) {
            $table->enum('visibility', ['all', 'school', 'class', 'specific_schools'])->default('school')->after('is_active');
            $table->uuid('class_id')->nullable()->after('visibility');

            $table->foreign('class_id')->references('id')->on('kelas')->onDelete('set null');
        });

        Schema::create('forum_allowed_schools', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('forum_id');
            $table->uuid('school_id');
            $table->timestamps();

            $table->unique(['forum_id', 'school_id']);
            $table->foreign('forum_id')->references('id')->on('forums')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_allowed_schools');

        Schema::table('forums', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropColumn(['visibility', 'class_id']);
        });
    }
};
