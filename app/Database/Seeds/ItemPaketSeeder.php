<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class ItemPaketSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now('Asia/Jakarta', 'en_US');
        $data = [
            // semester 1
            [
                'paket_id' => 1,
                'nama_item' => 'DPP',
                'nominal_item' => 10000000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 1,
                'nama_item' => 'Seragam & Perlengkapan Praktek',
                'nominal_item' => 1750000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 1,
                'nama_item' => 'Perpustakaan Laboratorium',
                'nominal_item' => 750000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 1,
                'nama_item' => 'BEM',
                'nominal_item' => 300000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 1,
                'nama_item' => 'PPKK',
                'nominal_item' => 600000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 1,
                'nama_item' => 'Semester',
                'nominal_item' => 3000000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 1,
                'nama_item' => 'Buku Modul',
                'nominal_item' => 150000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 1,
                'nama_item' => 'UTS/UAS',
                'nominal_item' => 315000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            // semester 2
            [
                'paket_id' => 2,
                'nama_item' => 'Semester',
                'nominal_item' => 3000000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 2,
                'nama_item' => 'UTS/UAS',
                'nominal_item' => 400000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 2,
                'nama_item' => 'Praktik Klinik',
                'nominal_item' => 2500000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 2,
                'nama_item' => 'PPK',
                'nominal_item' => 300000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            // semester 3
            [
                'paket_id' => 3,
                'nama_item' => 'Kemahasiswaan',
                'nominal_item' => 100000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 3,
                'nama_item' => 'Semester',
                'nominal_item' => 3000000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 3,
                'nama_item' => 'UTS/UAS',
                'nominal_item' => 400000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 3,
                'nama_item' => 'Praktik Klinik',
                'nominal_item' => 2500000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            // semester 4
            [
                'paket_id' => 4,
                'nama_item' => 'Semester',
                'nominal_item' => 3000000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 4,
                'nama_item' => 'UTS/UAS',
                'nominal_item' => 400000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 4,
                'nama_item' => 'Praktik Klinik',
                'nominal_item' => 2550000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 4,
                'nama_item' => 'PPK',
                'nominal_item' => 300000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            // semester 5
            [
                'paket_id' => 5,
                'nama_item' => 'Kemahasiswaan',
                'nominal_item' => 100000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 5,
                'nama_item' => 'Semester',
                'nominal_item' => 3000000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 5,
                'nama_item' => 'UTS/UAS',
                'nominal_item' => 400000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 5,
                'nama_item' => 'Praktik Klinik',
                'nominal_item' => 3750000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            // semester 6
            [
                'paket_id' => 6,
                'nama_item' => 'Semester',
                'nominal_item' => 3000000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 6,
                'nama_item' => 'UTS/UAS',
                'nominal_item' => 400000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 6,
                'nama_item' => 'Praktik Klinik',
                'nominal_item' => 1800000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 6,
                'nama_item' => 'PPK',
                'nominal_item' => 300000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 6,
                'nama_item' => 'Pelatihan Skill',
                'nominal_item' => 3000000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'paket_id' => 6,
                'nama_item' => 'LTA KTI',
                'nominal_item' => 1700000,
                'keterangan_item' => '',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];
        for ($i=0; $i < count($data); $i++) { 
            $this->db->table('item_paket')->insert($data[$i]);
        }
    }
}
