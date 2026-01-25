<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Changes NIK and NIP from globally unique to unique per school.
     * This allows the same teacher (with same NIK/NIP) to be registered
     * at multiple schools.
     */
    public function up(): void
    {
        Schema::table('fungsionaris', function (Blueprint $table) {
            // Drop old global unique indexes
            $table->dropUnique(['nik']);
            $table->dropUnique(['nip']);
            
            // Add composite unique indexes (unique per school)
            $table->unique(['school_id', 'nik'], 'fungsionaris_school_nik_unique');
            $table->unique(['school_id', 'nip'], 'fungsionaris_school_nip_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fungsionaris', function (Blueprint $table) {
            // Drop composite unique indexes
            $table->dropUnique('fungsionaris_school_nik_unique');
            $table->dropUnique('fungsionaris_school_nip_unique');
            
            // Restore global unique indexes
            $table->unique('nik');
            $table->unique('nip');
        });
    }
};
