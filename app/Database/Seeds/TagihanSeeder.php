<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class TagihanSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now('Asia/Jakarta', 'en_US');
        $data = [
            [
                'paket_id' => 1,
                'mahasiswa_id' => 1,
                'tanggal_tagihan' => '2021-10-11',
                'keterangan_tagihan' => 'Tagihan Paket A Semester 1',
                'user_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        for ($i = 0; $i < count($data); $i++) {
            $this->db->table('tagihan')->insert($data[$i]);
        }
    }
}
