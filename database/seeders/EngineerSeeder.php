<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Engineer;

class EngineerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $engineers = [
            [
                'engineer_code'  => 'ENG-001',
                'name'           => 'Ramesh Kumar',
                'email'          => 'ramesh.engineer@hindustan.com',
                'phone'          => '9876543210',
                'designation'    => 'Senior Civil Engineer',
                'specialization' => 'Structural Construction',
                'is_active'      => true,
            ],
            [
                'engineer_code'  => 'ENG-002',
                'name'           => 'Suresh Naik',
                'email'          => 'suresh.engineer@hindustan.com',
                'phone'          => '9876543211',
                'designation'    => 'Site Engineer',
                'specialization' => 'Quality Control & RA Bills',
                'is_active'      => true,
            ],
            [
                'engineer_code'  => 'ENG-003',
                'name'           => 'Anand Patel',
                'email'          => 'anand.patel@hindustan.com',
                'phone'          => '9876543212',
                'designation'    => 'Project Engineer',
                'specialization' => 'MEP & Finishing',
                'is_active'      => true,
            ],
        ];

        foreach ($engineers as $eng) {
            Engineer::updateOrCreate(['engineer_code' => $eng['engineer_code']], $eng);
        }
    }
}
