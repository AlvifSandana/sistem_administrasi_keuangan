<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableMahasiswa extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id_mahasiswa'  => [
                'type'          => 'INT',
                'constraint'    => 5,
                'unsigned'      => true,
                'auto_increment'=> true,
            ],
            'nim'           => [
                'type'          => 'VARCHAR',
                'constraint'    => 20,
                'unique'        => true,
            ],
            'nama_mahasiswa'=> [
                'type'          => 'VARCHAR',
                'constraint'    => 100,
            ],
            'progdi_id'     => [
                'type'          => 'INT',
                'unsigned'      => true,
            ],
            'jurusan_id'     => [
                'type'          => 'INT',
                'unsigned'      => true,
            ],
            'angkatan_id'     => [
                'type'          => 'INT',
                'unsigned'       => true,
            ],
            'created_at' => [
                'type'          => 'TIMESTAMP',
                'null'          => true,

            ],
            'updated_at' => [
                'type'          => 'TIMESTAMP',
                'null'          => true
            ], 
        ]);
        $this->forge->addKey('id_mahasiswa', true);
        $this->forge->addForeignKey('progdi_id', 'progdi', 'id_progdi', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('jurusan_id', 'jurusan', 'id_jurusan', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('angkatan_id', 'angkatan', 'id_angkatan', 'CASCADE', 'CASCADE');
        $this->forge->createTable('mahasiswa');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('mahasiswa', false, true);
    }
}
