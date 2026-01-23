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
        Schema::table('fungsionaris', function (Blueprint $table) {
            $table->string('nip')->nullable()->change();
            $table->string('nik')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fungsionaris', function (Blueprint $table) {
            // Reverting to non-nullable might fail if there are null values,
            // so we handle with caution or keep it nullable in down for safety if needed.
            // Usually we just revert if possible.
            $table->string('nip')->nullable(false)->change();
            $table->string('nik')->nullable(false)->change();
        });
    }
};
