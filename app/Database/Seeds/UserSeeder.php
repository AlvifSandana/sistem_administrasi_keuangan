<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;
use DateTime;

class UserSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now('Asia/Jakarta', 'en_US');
        $data = [
            [
                'nama' => 'admin',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => password_hash('12345678', PASSWORD_DEFAULT),
                'user_level' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'demo',
                'username' => 'demo',
                'email' => 'demo@example.com',
                'password' => password_hash('12345678', PASSWORD_DEFAULT),
                'user_level' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'read',
                'username' => 'read',
                'email' => 'read@example.com',
                'password' => password_hash('12345678', PASSWORD_DEFAULT),
                'user_level' => 'read',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        $this->db->table('user')->insert($data);
    }
}
