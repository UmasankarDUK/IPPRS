<?php
try {
    $db = new PDO('sqlite:database/database.sqlite');
    
    echo "=== PLAN SECTIONS SAMPLE ===\n";
    $sections = $db->query("SELECT * FROM plan_sections LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
    print_r($sections);
    
    echo "\n=== INSTITUTIONS ===\n";
    $insts = $db->query("SELECT * FROM institutions LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
    print_r($insts);

    echo "\n=== CONVERSIONS ===\n";
    $convs = $db->query("SELECT * FROM infrastructure_conversions LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
    print_r($convs);
    
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
