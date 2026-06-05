<?php
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
    
    echo "=== SQLITE TABLES ===\n";
    foreach ($tables as $table) {
        $count = $db->query("SELECT COUNT(*) FROM \"$table\"")->fetchColumn();
        if ($count > 0) {
            echo "  $table: $count rows\n";
        } else {
            echo "  $table: 0 rows\n";
        }
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
