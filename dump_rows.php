<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = 'assets/Adiluwih - UPT SD Negeri 3 Kutawaringin.xlsx';

try {
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    
    for ($row = 5; $row <= 7; $row++) {
        echo "Row $row:\n";
        $data = [];
        for ($col = 'B'; $col <= 'BN'; $col = nextColumn($col)) {
            $value = $sheet->getCell($col . $row)->getValue();
            $data[$col] = $value;
            if ($col === 'BN') break;
        }
        echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

function nextColumn($col) {
    if (strlen($col) == 1) {
        if ($col == 'Z') return 'AA';
        return chr(ord($col) + 1);
    } else {
        $lastChar = substr($col, -1);
        if ($lastChar == 'Z') {
            return chr(ord($col[0]) + 1) . 'A';
        }
        return $col[0] . chr(ord($lastChar) + 1);
    }
}
