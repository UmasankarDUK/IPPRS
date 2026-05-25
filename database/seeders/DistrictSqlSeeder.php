<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSqlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlPath = base_path('Req/districts.sql');
        
        if (!file_exists($sqlPath)) {
            $this->command->error("districts.sql not found in Req folder.");
            return;
        }

        $lines = file($sqlPath);
        $inData = false;
        $insertRows = [];
        $count = 0;

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (strpos($trimmed, 'COPY public.districts') !== false) {
                $inData = true;
                continue;
            }
            if ($inData) {
                if ($trimmed === '\.') {
                    $inData = false;
                    break;
                }
                $parts = explode("\t", rtrim($line, "\r\n"));
                if (count($parts) >= 4) {
                    $insertRows[] = [
                        'id' => (int)$parts[0],
                        'name' => $parts[1],
                        'state_id' => (int)$parts[2],
                        'is_active' => ($parts[3] === 't' || $parts[3] === '1' || $parts[3] === 'true'),
                        'created_at' => $parts[4] ?? null,
                        'updated_at' => $parts[5] ?? null,
                        'state' => null,
                        'code' => null,
                        'population' => null,
                        'area_sq_km' => null,
                    ];
                    $count++;

                    if (count($insertRows) >= 100) {
                        DB::table('districts')->insert($insertRows);
                        $insertRows = [];
                    }
                }
            }
        }

        if (count($insertRows) > 0) {
            DB::table('districts')->insert($insertRows);
        }

        $this->command->info("Seeded {$count} districts from districts.sql.");

        // Reset PostgreSQL ID sequence to prevent duplicate key violations
        if (config('database.default') === 'pgsql') {
            DB::select("SELECT setval('districts_id_seq', COALESCE((SELECT MAX(id)+1 FROM districts), 1), false)");
            $this->command->info("PostgreSQL districts ID sequence reset successfully.");
        }
    }
}
