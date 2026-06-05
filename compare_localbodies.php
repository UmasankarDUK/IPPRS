<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $sqliteDb = new PDO('sqlite:database/database.sqlite');
    $sqliteLbs = $sqliteDb->query("SELECT * FROM localbodies")->fetchAll(PDO::FETCH_ASSOC);
    
    echo "=== SQLITE LOCALBODIES ===\n";
    foreach ($sqliteLbs as $slb) {
        $name = $slb['name'];
        $id = $slb['id'];
        
        // Find in PostgreSQL
        $pgLb = DB::table('geo.master_local_body')
            ->where('localbody_name_en', 'like', $name . '%')
            ->first();
            
        if ($pgLb) {
            echo "SQLite: $name (ID: $id) => PG: {$pgLb->localbody_name_en} (ID: {$pgLb->localbody_id}, Code: {$pgLb->localbody_code})\n";
        } else {
            echo "SQLite: $name (ID: $id) => PG: NOT FOUND\n";
        }
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
