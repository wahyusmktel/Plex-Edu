<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = 'assets/Adiluwih - UPT SD Negeri 3 Kutawaringin.xlsx';

try {
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $highestColumn = $sheet->getHighestColumn();
    
    echo "Highest Column: " . $highestColumn . "\n";
    
    // Read row 1-10 to see where headers and data are
    for ($row = 1; $row <= 10; $row++) {
        echo "Row $row: ";
        for ($col = 'A'; $col <= 'BN'; $col++) {
            $value = $sheet->getCell($col . $row)->getValue();
            if ($value) {
                echo "[$col: $value] ";
            }
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
