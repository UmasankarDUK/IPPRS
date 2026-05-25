<?php

namespace Database\Seeders;

use App\Models\HealthInstitution;
use App\Models\EbolaCase;
use Illuminate\Database\Seeder;

class EbolaCaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tdmch = HealthInstitution::where('name', 'like', '%T. D. Medical College%')->first();
        $gh = HealthInstitution::where('name', 'like', '%General Hospital Alappuzha%')->first();

        if (!$tdmch || !$gh) {
            return;
        }

        $names = [
            'Arun Kumar', 'Deepa Nair', 'Rahul Varghese', 'Anitha Kurian', 'Sajith K. R.', 
            'Fathima Shahana', 'Jithin Joseph', 'Meera Pillai', 'Vipin Das', 'Sandra Mathew',
            'Kishore K.', 'Reshma R. Nair', 'Harikrishnan M.', 'Amina Beevi', 'Thomas Kutty',
            'Sreejith S.', 'Aparna Balan', 'Nikhil Raj', 'Divya Chandran', 'Manuel George',
            'Akhil Mohan', 'Anjali Devi', 'Subin K. S.', 'Kavitha Ramachandran', 'Bibin Baby',
            'Syam Kumar', 'Nimisha Joy', 'Gokul G.', 'Priya Sridhar', 'Anto Antony',
            'Shibu George', 'Dhanya P. V.', 'Pranav Rajesh', 'Arya Surendran', 'Mathew V. J.',
            'Vineeth V.', 'Athira Prasad', 'Sachin Dev', 'Jisha K. Nair', 'Raju Kurian',
            'Midhun M.', 'Saritha S. Kumar', 'Amal Joseph', 'Sona Jose', 'Renjith R.',
            'Suraj S. Nair', 'Leela Amma', 'Appu G. Nair', 'Ganga S.', 'Biju Karunakaran',
            'Anandan P.', 'Sujatha Kumari', 'Abhilash A.', 'Radhika Devi', 'Jose Sebastian',
            'Manoj K. P.', 'Sini Sebastian', 'Varun G. Nair', 'Maya Krishnan', 'Sunny Joseph',
            'Devanand S.', 'Karthika R.', 'Pradeep Kumar', 'Sheela Mathew', 'Joyal Joy'
        ];

        $genders = ['Male', 'Female', 'Other'];
        
        $quarantineTypes = [
            'Home Quarantine', 
            'Institutional Quarantine', 
            'Isolation (No O2)', 
            'Isolation (With O2)', 
            'ICU (No O2)', 
            'ICU (With O2)', 
            'ICU (Ventilator)'
        ];

        $dates = [
            '2026-05-17',
            '2026-05-18',
            '2026-05-19',
            '2026-05-20',
            '2026-05-21',
            '2026-05-22',
            '2026-05-23'
        ];

        // Seed 65 patients
        foreach ($names as $index => $name) {
            // Distribute across dates and facilities
            $date = $dates[$index % count($dates)];
            $inst = ($index % 2 == 0) ? $tdmch : $gh;
            
            $gender = $genders[$index % 3];
            $age = rand(18, 78);
            
            // Generate statuses sequentially to make it realistic
            // Suspect, Probable, Confirmed
            if ($index % 5 === 0) {
                $status = 'Confirmed';
                $testStatus = 'Positive';
                $quarantine = $quarantineTypes[rand(3, 6)]; // Confirmed usually in Isolation / ICU
                $outcome = ($index % 25 === 0) ? 'Deceased' : (($index % 10 === 0) ? 'Recovered' : 'Active');
            } elseif ($index % 3 === 0) {
                $status = 'Probable';
                $testStatus = 'Sent for Test';
                $quarantine = $quarantineTypes[rand(1, 4)];
                $outcome = 'Active';
            } else {
                $status = 'Suspect';
                $testStatus = (rand(0, 1) === 0) ? 'Not Tested' : 'Negative';
                $quarantine = $quarantineTypes[rand(0, 1)]; // Suspects usually in Home / Inst Quarantine
                $outcome = ($testStatus === 'Negative') ? 'Recovered' : 'Active';
            }

            EbolaCase::create([
                'patient_name' => $name,
                'age' => $age,
                'gender' => $gender,
                'health_institution_id' => $inst->id,
                'status' => $status,
                'quarantine_type' => $quarantine,
                'test_status' => $testStatus,
                'outcome' => $outcome,
                'date_of_reporting' => $date,
            ]);
        }
    }
}
