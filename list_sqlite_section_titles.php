<?php
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $titles = $db->query("SELECT DISTINCT title FROM plan_sections ORDER BY title")->fetchAll(PDO::FETCH_COLUMN);
    echo "=== UNIQUE SECTION TITLES ===\n";
    foreach ($titles as $title) {
        $count = $db->query("SELECT COUNT(*) FROM plan_sections WHERE title = " . $db->quote($title))->fetchColumn();
        echo "  $title ($count rows)\n";
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
