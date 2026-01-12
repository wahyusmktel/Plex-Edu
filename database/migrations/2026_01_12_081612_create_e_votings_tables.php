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
        Schema::dropIfExists('election_votes');
        Schema::dropIfExists('election_candidates');
        Schema::dropIfExists('elections');

        Schema::create('elections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('judul');
            $table->string('jenis');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('election_candidates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('election_id')->constrained('elections')->onDelete('cascade');
            $table->foreignUuid('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->integer('no_urut');
            $table->timestamps();
        });

        Schema::create('election_votes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('election_id')->constrained('elections')->onDelete('cascade');
            $table->foreignUuid('candidate_id')->constrained('election_candidates')->onDelete('cascade');
            $table->foreignUuid('voter_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['election_id', 'voter_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('election_votes');
        Schema::dropIfExists('election_candidates');
        Schema::dropIfExists('elections');
    }
};
