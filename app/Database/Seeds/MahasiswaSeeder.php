<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now('Asia/Jakarta', 'en_US');
        $data = [
            [
                'nim' => '1119101710',
                'nama_mahasiswa' => static::faker()->name(),
                'progdi_id' => 1,
                'angkatan_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ];
        $this->db->table('mahasiswa')->insert($data[0]);
    }
}
