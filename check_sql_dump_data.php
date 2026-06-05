<?php
$lines = file('pmsdb.sql');
$currentTable = null;
$rowCount = 0;
$tableCounts = [];

foreach ($lines as $line) {
    if (preg_match('/^COPY\s+([a-zA-Z0-9_\.]+)\s+\(/i', $line, $matches)) {
        if ($currentTable) {
            $tableCounts[$currentTable] = $rowCount;
        }
        $currentTable = $matches[1];
        $rowCount = 0;
        continue;
    }
    if ($currentTable) {
        if (trim($line) === '\.') {
            $tableCounts[$currentTable] = $rowCount;
            $currentTable = null;
        } else {
            $rowCount++;
        }
    }
}

echo "=== TABLES WITH DATA IN SQL DUMP ===\n";
arsort($tableCounts);
foreach ($tableCounts as $table => $count) {
    if ($count > 0) {
        echo "$table: $count rows\n";
    }
}
