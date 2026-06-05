<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$schemas = ['geo', 'inventory', 'onehealth', 'org', 'prep', 'ref', 'sec', 'surv', 'training', 'workflow', 'public'];

foreach ($schemas as $schema) {
    echo "--- Schema: $schema ---\n";
    try {
        $tables = DB::select("
            SELECT table_name 
            FROM information_schema.tables 
            WHERE table_schema = ? 
            ORDER BY table_name
        ", [$schema]);
        
        foreach ($tables as $t) {
            $tableName = $t->table_name;
            try {
                $count = DB::table($schema . '.' . $tableName)->count();
                echo "  $tableName: $count rows\n";
            } catch (\Throwable $e) {
                echo "  $tableName: error ({$e->getMessage()})\n";
            }
        }
    } catch (\Throwable $e) {
        echo "Error listing tables in schema $schema: {$e->getMessage()}\n";
    }
}
