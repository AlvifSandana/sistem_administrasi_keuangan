<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class SemesterSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now('Asia/Jakarta', 'en_US');
        $data = [
            [
                'nama_semester' => 'Semester 1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_semester' => 'Semester 2',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_semester' => 'Semester 3',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_semester' => 'Semester 4',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_semester' => 'Semester 5',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_semester' => 'Semester 6',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_semester' => 'Semester 7',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_semester' => 'Semester 8',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        for ($i=0; $i < count($data); $i++) { 
            $this->db->table('semester')->insert($data[$i]);
        }
    }
}
