<?php
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $types = $db->query("SELECT DISTINCT planable_type FROM plan_sections")->fetchAll(PDO::FETCH_COLUMN);
    echo "=== UNIQUE planable_type VALUES ===\n";
    print_r($types);
    
    // Let's count sections per type
    foreach ($types as $type) {
        $count = $db->query("SELECT COUNT(*) FROM plan_sections WHERE planable_type = '$type'")->fetchColumn();
        echo "  $type: $count rows\n";
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
