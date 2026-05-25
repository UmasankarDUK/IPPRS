<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = IOFactory::load('Req/Ebola daily bulletin template23.5.26.xlsx');
$sheet = $spreadsheet->getActiveSheet();

// Print merged cell ranges
echo "=== MERGED CELLS ===\n";
foreach ($sheet->getMergeCells() as $range) {
    echo $range . "\n";
}

echo "\n=== ALL CELL VALUES (rows 1-20, cols A-AO) ===\n";

$cols = [];
for ($c = 'A'; $c !== 'AP'; $c++) {
    $cols[] = $c;
}

for ($row = 1; $row <= 20; $row++) {
    echo "\n--- Row $row ---\n";
    foreach ($cols as $col) {
        $cell = $sheet->getCell($col . $row);
        $val = $cell->getValue();
        if ($val !== null && trim((string)$val) !== '') {
            echo "  $col: $val\n";
        }
    }
}

// Also show a few data rows if they exist
echo "\n=== DATA ROWS (21-35) ===\n";
for ($row = 5; $row <= 35; $row++) {
    $rowHasData = false;
    $line = "Row $row: ";
    foreach ($cols as $col) {
        $val = $sheet->getCell($col . $row)->getValue();
        if ($val !== null && trim((string)$val) !== '') {
            $line .= "$col:[$val]  ";
            $rowHasData = true;
        }
    }
    if ($rowHasData) echo $line . "\n";
}
