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
                'nama_progdi' => 'Gizi',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_progdi' => 'Kebidanan',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_progdi' => 'Farmasi',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        for ($i=0; $i < count($data); $i++) { 
            $this->db->table('progdi')->insert($data[$i]);
        }
    }
}
