<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class ProgdiSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now('Asia/Jakarta', 'en_US');
        $data = [
            [
                'nama_progdi' => 'D3 GIZI',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_progdi' => 'D3 KEBIDANAN',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_progdi' => 'S1 FARMASI',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_progdi' => 'S1 KEPERAWATAN',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        for ($i=0; $i < count($data); $i++) { 
            $this->db->table('progdi')->insert($data[$i]);
        }
    }
}
