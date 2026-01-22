<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

$filePath = 'assets/Adiluwih - UPT SD Negeri 3 Kutawaringin.xlsx';

try {
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    
    $startCol = Coordinate::columnIndexFromString('B');
    $endCol = Coordinate::columnIndexFromString('BN');
    
    $results = [];
    for ($row = 5; $row <= 7; $row++) {
        $rowData = [];
        for ($c = $startCol; $c <= $endCol; $c++) {
            $colString = Coordinate::stringFromColumnIndex($c);
            $value = $sheet->getCell($colString . $row)->getValue();
            if ($value !== null) {
                $rowData[$colString] = $value;
            }
        }
        $results["Row $row"] = $rowData;
    }
    
    file_put_contents('excel_rows_full_dump.json', json_encode($results, JSON_PRETTY_PRINT));
    echo "Full dump successful\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
