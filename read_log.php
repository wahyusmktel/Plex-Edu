<?php
$logPath = 'storage/logs/laravel.log';
if (file_exists($logPath)) {
    $size = filesize($logPath);
    $offset = max(0, $size - 5000);
    $handle = fopen($logPath, 'r');
    fseek($handle, $offset);
    echo fread($handle, 5000);
    fclose($handle);
} else {
    echo "Log file not found.";
}
