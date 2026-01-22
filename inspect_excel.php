<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = 'h:\\Project\\Literasia\\literasia-web\\assets\\Adiluwih - UPT SD Negeri 3 Kutawaringin.xlsx';

try {
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray(null, true, true, true);

    $output = "SEARCHING FOR DINANTI:\n";
    $found = false;
    foreach ($rows as $index => $row) {
        if (isset($row['B']) && (stripos($row['B'], 'DINANTI') !== false)) {
            $output .= "ROW $index: ";
            foreach ($row as $key => $value) {
                if (in_array($key, ['B', 'BF', 'BG', 'BH'])) {
                    $output .= "Col $key: " . ($value === null ? "NULL" : "'$value'") . " | ";
                }
            }
            $output .= "\n";
            $found = true;
        }
    }
    if (!$found) $output .= "DINANTI not found in this file.\n";
    file_put_contents('inspect_result.txt', $output);
    echo "Done writing to inspect_result.txt";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
