<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = IOFactory::load('reference - ppp.xlsx');
echo "=== SHEET NAMES ===\n";
foreach ($spreadsheet->getSheetNames() as $name) {
    echo "Sheet: " . $name . "\n";
}

foreach ($spreadsheet->getSheetNames() as $name) {
    $sheet = $spreadsheet->getSheetByName($name);
    echo "\n=== SHEET: $name (First 15 rows) ===\n";
    $highestRow = min($sheet->getHighestRow(), 15);
    $highestColumn = $sheet->getHighestColumn();
    
    for ($row = 1; $row <= $highestRow; $row++) {
        $rowValues = [];
        for ($col = 'A'; $col !== 'K'; $col++) {
            $val = $sheet->getCell($col . $row)->getValue();
            if ($val !== null && trim((string)$val) !== '') {
                $rowValues[] = "$col: $val";
            }
        }
        if (!empty($rowValues)) {
            echo "Row $row: " . implode(" | ", $rowValues) . "\n";
        }
    }
}
