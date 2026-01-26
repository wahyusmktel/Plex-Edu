<?php

namespace App\Jobs;

use App\Models\School;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class GenerateSchoolAccountsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $schoolIds;
    protected $trackingId;

    /**
     * Create a new job instance.
     *
     * @param array $schoolIds
     * @param string $trackingId
     */
    public function __construct(array $schoolIds, string $trackingId)
    {
        $this->schoolIds = $schoolIds;
        $this->trackingId = $trackingId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $total = count($this->schoolIds);
        $current = 0;
        $generated = 0;
        $errors = [];

        Log::info("GenerateSchoolAccountsJob started for tracking_id: {$this->trackingId} with {$total} schools.");

        foreach ($this->schoolIds as $schoolId) {
            $current++;
            $school = School::find($schoolId);
            
            if (!$school) {
                $errors[] = "School ID {$schoolId} not found.";
                $this->updateProgress($current, $total, $generated, $errors);
                continue;
            }

            try {
                $email = $school->npsn . '@admin.literasia.org';

                // Skip if email already exists
                if (User::where('email', $email)->exists()) {
                    $errors[] = "NPSN {$school->npsn} ({$school->nama_sekolah}): Email sudah terdaftar";
                } else {
                    User::create([
                        'school_id' => $school->id,
                        'name' => $school->nama_sekolah,
                        'email' => $email,
                        'username' => $school->npsn,
                        'password' => Hash::make($school->npsn),
                        'role' => 'admin',
                    ]);
                    $generated++;
                }
            } catch (\Exception $e) {
                $errors[] = "NPSN {$school->npsn} ({$school->nama_sekolah}): " . $e->getMessage();
            }

            $this->updateProgress($current, $total, $generated, $errors, $school->nama_sekolah, $school->npsn);
            
            // Optional: small delay to not overwhelm DB if needed, but in queue it's usually fine
            // usleep(10000); 
        }

        $this->finishProgress($current, $total, $generated, $errors);
    }

    protected function updateProgress($current, $total, $generated, $errors, $schoolName = '', $npsn = '')
    {
        $progress = $total > 0 ? round(($current / $total) * 100) : 0;
        Log::info("Progress update for {$this->trackingId}: {$current}/{$total} ({$progress}%)");
        
        Cache::put("account_gen_progress_{$this->trackingId}", [
            'type' => 'progress',
            'current' => $current,
            'total' => $total,
            'progress' => $progress,
            'school_name' => $schoolName,
            'npsn' => $npsn,
            'generated' => $generated,
            'errors' => $errors,
            'status' => 'processing'
        ], now()->addHours(1));
    }

    protected function finishProgress($current, $total, $generated, $errors)
    {
        Log::info("Account generation complete for {$this->trackingId}. Generated: {$generated}, Errors: " . count($errors));

        Cache::put("account_gen_progress_{$this->trackingId}", [
            'type' => 'complete',
            'current' => $current,
            'total' => $total,
            'progress' => 100,
            'generated' => $generated,
            'errors' => $errors,
            'status' => 'completed',
            'message' => "{$generated} akun admin sekolah berhasil di-generate dari {$total} sekolah."
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
        Log::error("GenerateSchoolAccountsJob failed: " . $exception->getMessage());
        
        Cache::put("account_gen_progress_{$this->trackingId}", [
            'type' => 'error',
            'status' => 'failed',
            'message' => 'Terjadi kesalahan sistem saat memproses akun: ' . $exception->getMessage()
        ], now()->addHours(1));
    }
}
