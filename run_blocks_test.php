<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Block;
use Illuminate\Support\Facades\DB;

try {
    $count = Block::count();
    echo "Eloquent Block::count() = $count\n";

    $blocks = Block::with('district')->withCount('localbodies')->orderBy('block_name_en')->get();
    echo "Eloquent query results count = " . $blocks->count() . "\n";
    if ($blocks->count() > 0) {
        echo "First block: " . $blocks->first()->block_name_en . "\n";
    }
} catch (\Exception $e) {
    echo "Query Failed with error: " . $e->getMessage() . "\n";
}
