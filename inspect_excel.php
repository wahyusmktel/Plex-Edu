<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = 'assets/Individu Guru - per Desember 2025.xls';

try {
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->rangeToArray('A1:AE20', NULL, TRUE, TRUE, TRUE);

    file_put_contents('inspect_result_full.json', json_encode($data, JSON_PRETTY_PRINT));
    echo "Done. Results written to inspect_result_full.json\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
