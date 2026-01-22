<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = 'assets/Adiluwih - UPT SD Negeri 3 Kutawaringin.xlsx';

try {
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    
    $results = [];
    for ($row = 1; $row <= 15; $row++) {
        $rowData = [];
        for ($col = 'B'; $col <= 'BN'; $col = nextColumn($col)) {
            $value = $sheet->getCell($col . $row)->getValue();
            $rowData[$col] = $value;
            if ($col === 'BN') break;
        }
        $results["Row $row"] = $rowData;
    }
    
    file_put_contents('excel_rows_dump.json', json_encode($results, JSON_PRETTY_PRINT));
    echo "Dump successful\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

function nextColumn($col) {
    $index = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($col);
    return \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
}
