<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = 'assets/Adiluwih - UPT SD Negeri 3 Kutawaringin.xlsx';

try {
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    
    $mapping = [];
    for ($col = 'B'; $col <= 'BN'; $col = nextColumn($col)) {
        $header = $sheet->getCell($col . '6')->getValue();
        $mapping[$col] = $header;
        if ($col === 'BN') break;
    }
    
    echo json_encode($mapping, JSON_PRETTY_PRINT) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

function nextColumn($col) {
    return ++$col;
}
