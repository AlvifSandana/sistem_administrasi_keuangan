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
                'nama_paket' => 'Paket A',
                'keterangan_paket' => 'keterangan Paket X',
                'semester_id' => 1,
                'created_at' => $now,
                'created_at' => $now,
            ],
            [
                'nama_paket' => 'Paket B',
                'keterangan_paket' => 'keterangan Paket B',
                'semester_id' => 2,
                'created_at' => $now,
                'created_at' => $now,
            ],
            [
                'nama_paket' => 'Paket C',
                'keterangan_paket' => 'keterangan Paket C',
                'semester_id' => 3,
                'created_at' => $now,
                'created_at' => $now,
            ],
            [
                'nama_paket' => 'Paket D',
                'keterangan_paket' => 'keterangan Paket D',
                'semester_id' => 4,
                'created_at' => $now,
                'created_at' => $now,
            ],
            [
                'nama_paket' => 'Paket E',
                'keterangan_paket' => 'keterangan Paket E',
                'semester_id' => 5,
                'created_at' => $now,
                'created_at' => $now,
            ],
            [
                'nama_paket' => 'Paket F',
                'keterangan_paket' => 'keterangan Paket F',
                'semester_id' => 6,
                'created_at' => $now,
                'created_at' => $now,
            ],
            [
                'nama_paket' => 'Paket G',
                'keterangan_paket' => 'keterangan Paket G',
                'semester_id' => 7,
                'created_at' => $now,
                'created_at' => $now,
            ],
            [
                'nama_paket' => 'Paket H',
                'keterangan_paket' => 'keterangan Paket H',
                'semester_id' => 8,
                'created_at' => $now,
                'created_at' => $now,
            ],
        ];
        for ($i=0; $i < count($data); $i++) { 
            $this->db->table('paket')->insert($data[$i]);
        }
    }
}
