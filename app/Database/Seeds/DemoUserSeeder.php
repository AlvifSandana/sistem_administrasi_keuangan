<?php

namespace App\Database\Seeds;

use App\Models\UserModel;
use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class DemoUserSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now('Asia/Jakarta', 'en_US');
        $data = [
            [
                'nama' => 'demo',
                'username' => 'demo',
                'email' => 'demo@example.com',
                'password' => password_hash('12345678', PASSWORD_DEFAULT),
                'user_level' => 'admin',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];
        // $this->db->query("INSERT INTO `user` (nama, username, email, password, user_level, created_at, updated_at) VALUES (:nama:, :username:, :email:, :password:, :user_level:, :created_at:, :updated_at:)", $data);
        $this->db->table('user')->insertBatch($data);
    }
}
