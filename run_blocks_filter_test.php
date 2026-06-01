<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\District;
use App\Models\Block;

$uuid = '807ef27f-b469-4271-ba69-7ba9489f300c'; // Thiruvananthapuram
$district = District::find($uuid);

if ($district) {
    echo "Found district: {$district->district_name_en}, code = {$district->district_code}\n";
    $blocks = Block::where('distric_int_id', $district->district_code)->get();
    echo "Found " . $blocks->count() . " blocks under district code {$district->district_code}\n";
    foreach ($blocks as $b) {
        echo " - {$b->block_name_en}\n";
    }
} else {
    echo "District with UUID $uuid NOT found!\n";
}
