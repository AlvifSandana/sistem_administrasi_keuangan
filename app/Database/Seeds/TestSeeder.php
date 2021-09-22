<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestSeeder extends Seeder
{
    public function run()
    {
        $this->call('AngkatanSeeder');
        $this->call('ProgdiSeeder');
        $this->call('SemesterSeeder');
        $this->call('MahasiswaSeeder');
        $this->call('PaketSeeder');
        $this->call('UserSeeder');
    }
}
