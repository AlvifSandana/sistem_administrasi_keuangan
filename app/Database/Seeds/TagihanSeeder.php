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
                'keterangan_tagihan' => 'D3 KEBIDANAN - Semester 1',
                'status_tagihan' => 'Belum Lunas',
                'user_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'paket_id' => 2,
                'mahasiswa_id' => 1,
                'tanggal_tagihan' => '2021-10-11',
                'keterangan_tagihan' => 'D3 KEBIDANAN - Semester 2',
                'status_tagihan' => 'Belum Lunas',
                'user_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'paket_id' => 3,
                'mahasiswa_id' => 1,
                'tanggal_tagihan' => '2021-10-11',
                'keterangan_tagihan' => 'D3 KEBIDANAN - Semester 3',
                'status_tagihan' => 'Belum Lunas',
                'user_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'paket_id' => 4,
                'mahasiswa_id' => 1,
                'tanggal_tagihan' => '2021-10-11',
                'keterangan_tagihan' => 'D3 KEBIDANAN - Semester 4',
                'status_tagihan' => 'Belum Lunas',
                'user_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'paket_id' => 5,
                'mahasiswa_id' => 1,
                'tanggal_tagihan' => '2021-10-11',
                'keterangan_tagihan' => 'D3 KEBIDANAN - Semester 5',
                'status_tagihan' => 'Belum Lunas',
                'user_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'paket_id' => 6,
                'mahasiswa_id' => 1,
                'tanggal_tagihan' => '2021-10-11',
                'keterangan_tagihan' => 'D3 KEBIDANAN - Semester 6',
                'status_tagihan' => 'Belum Lunas',
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
