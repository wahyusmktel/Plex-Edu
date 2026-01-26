<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    Illuminate\Http\Request::capture()
);

try {
    echo "Default disk: " . config('filesystems.default') . "\n";
    $disk = Illuminate\Support\Facades\Storage::disk();
    echo "Disk driver: " . get_class($disk->getDriver()) . "\n";
    
    echo "Testing URL generation...\n";
    $url = Illuminate\Support\Facades\Storage::url('test.png');
    echo "Generated URL: " . $url . "\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
