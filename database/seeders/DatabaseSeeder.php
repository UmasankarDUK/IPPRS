<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create State Administrator
        User::updateOrCreate(
            ['email' => 'admin@ipprs.in'],
            [
                'name' => 'State Health Administrator',
                'password' => Hash::make('password'),
                'role' => 'state',
            ]
        );

        User::updateOrCreate(
            ['email' => 'state@ipprs.in'],
            [
                'name' => 'State Health Administrator',
                'password' => Hash::make('password'),
                'role' => 'state',
            ]
        );

        // 2. Create District Administrator (Alapuzha District Code = 4)
        User::updateOrCreate(
            ['email' => 'district@ipprs.in'],
            [
                'name' => 'Alapuzha District Collector',
                'password' => Hash::make('password'),
                'role' => 'district',
                'district_code' => 4,
            ]
        );

        // 3. Create Block Administrator (Muthukulam Block ID = 39)
        User::updateOrCreate(
            ['email' => 'block@ipprs.in'],
            [
                'name' => 'Muthukulam Block Medical Officer',
                'password' => Hash::make('password'),
                'role' => 'block',
                'block_int_id' => 39,
            ]
        );

        // 4. Create Localbody Administrator (Muthukulam GP ID = 42)
        User::updateOrCreate(
            ['email' => 'gp@ipprs.in'],
            [
                'name' => 'Muthukulam GP Secretary',
                'password' => Hash::make('password'),
                'role' => 'localbody',
                'localbody_id' => 42,
            ]
        );

        // 5. Run Excel Study Importer Seeder
        $this->call(StudyExcelDataSeeder::class);
    }
}
