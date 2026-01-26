<?php

namespace App\Jobs;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class GenerateStudentAccountsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $studentIds;
    protected $trackingId;

    /**
     * Create a new job instance.
     *
     * @param array $studentIds
     * @param string $trackingId
     */
    public function __construct(array $studentIds, string $trackingId)
    {
        $this->studentIds = $studentIds;
        $this->trackingId = $trackingId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $total = count($this->studentIds);
        $current = 0;
        $generated = 0;
        $errors = [];

        Log::info("GenerateStudentAccountsJob started for tracking_id: {$this->trackingId} with {$total} students.");

        foreach ($this->studentIds as $studentId) {
            $current++;
            $siswa = Siswa::find($studentId);
            
            if (!$siswa) {
                $errors[] = "Siswa ID {$studentId} not found.";
                $this->updateProgress($current, $total, $generated, $errors);
                continue;
            }

            try {
                $email = $siswa->nisn . '@siswa.literasia.org';

                // Skip if email already exists
                if (User::where('email', $email)->exists()) {
                    $errors[] = "NISN {$siswa->nisn} ({$siswa->nama_lengkap}): Email sudah terdaftar";
                } else {
                    $user = User::create([
                        'school_id' => $siswa->school_id,
                        'name' => $siswa->nama_lengkap,
                        'email' => $email,
                        'username' => $siswa->nisn,
                        'password' => Hash::make($siswa->nisn),
                        'role' => 'siswa',
                    ]);
                    
                    $siswa->update(['user_id' => $user->id]);
                    $generated++;
                }
            } catch (\Exception $e) {
                $errors[] = "{$siswa->nama_lengkap}: " . $e->getMessage();
            }

            $this->updateProgress($current, $total, $generated, $errors, $siswa->nama_lengkap, $siswa->nisn);
        }

        $this->finishProgress($current, $total, $generated, $errors);
    }

    protected function updateProgress($current, $total, $generated, $errors, $studentName = '', $nisn = '')
    {
        $progress = $total > 0 ? round(($current / $total) * 100) : 0;
        Log::info("Progress update for {$this->trackingId}: {$current}/{$total} ({$progress}%)");
        
        Cache::put("account_gen_progress_{$this->trackingId}", [
            'type' => 'progress',
            'current' => $current,
            'total' => $total,
            'progress' => $progress,
            'student_name' => $studentName,
            'nisn' => $nisn,
            'generated' => $generated,
            'errors' => $errors,
            'status' => 'processing'
        ], now()->addHours(1));
    }

    protected function finishProgress($current, $total, $generated, $errors)
    {
        Log::info("Student account generation complete for {$this->trackingId}. Generated: {$generated}, Errors: " . count($errors));

        Cache::put("account_gen_progress_{$this->trackingId}", [
            'type' => 'complete',
            'current' => $current,
            'total' => $total,
            'progress' => 100,
            'generated' => $generated,
            'errors' => $errors,
            'status' => 'completed',
            'message' => "{$generated} akun siswa berhasil di-generate dari {$total} data."
        ], now()->addHours(1));
    }

    /**
     * Handle job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        Log::error("GenerateStudentAccountsJob failed: " . $exception->getMessage());
        
        Cache::put("account_gen_progress_{$this->trackingId}", [
            'type' => 'error',
            'status' => 'failed',
            'message' => 'Terjadi kesalahan sistem saat memproses akun siswa: ' . $exception->getMessage()
        ], now()->addHours(1));
    }
}
