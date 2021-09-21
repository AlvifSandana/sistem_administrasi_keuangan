<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class AngkatanSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now('Asia/Jakarta', 'en_US');
        $data = [
            [
                'nama_angkatan' => '2020',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_angkatan' => '2021',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_angkatan' => '2021',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        for ($i=0; $i < count($data); $i++) { 
            $this->db->table('angkatan')->insert($data[$i]);
        }
    }
}
