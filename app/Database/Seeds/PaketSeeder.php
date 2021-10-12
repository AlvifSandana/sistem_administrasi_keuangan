<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class PaketSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now('Asia/Jakarta', 'en_US');
        $data = [
            [
                'nama_paket' => 'Paket D3 - Semester 1',
                'keterangan_paket' => 'Paket D3 Semester 1',
                'semester_id' => 1,
                'created_at' => $now,
                'created_at' => $now,
            ],
            [
                'nama_paket' => 'Paket D3 - Semester 2',
                'keterangan_paket' => 'Paket D3 Semester 2',
                'semester_id' => 2,
                'created_at' => $now,
                'created_at' => $now,
            ],
            [
                'nama_paket' => 'Paket D3 - Semester 3',
                'keterangan_paket' => 'Paket D3 Semester 3',
                'semester_id' => 3,
                'created_at' => $now,
                'created_at' => $now,
            ],
            [
                'nama_paket' => 'Paket D3 - Semester 4',
                'keterangan_paket' => 'Paket D3 Semester 4',
                'semester_id' => 4,
                'created_at' => $now,
                'created_at' => $now,
            ],
            [
                'nama_paket' => 'Paket D3 - Semester 5',
                'keterangan_paket' => 'Paket D3 Semester 5',
                'semester_id' => 5,
                'created_at' => $now,
                'created_at' => $now,
            ],
            [
                'nama_paket' => 'Paket D3 - Semester 6',
                'keterangan_paket' => 'Paket D3 Semester 6',
                'semester_id' => 6,
                'created_at' => $now,
                'created_at' => $now,
            ],
            [
                'nama_paket' => 'Paket D3 - Semester 7',
                'keterangan_paket' => 'Paket D3 Semester 7',
                'semester_id' => 7,
                'created_at' => $now,
                'created_at' => $now,
            ],
        ];
        for ($i=0; $i < count($data); $i++) { 
            $this->db->table('paket')->insert($data[$i]);
        }
    }
}
