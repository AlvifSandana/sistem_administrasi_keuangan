<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableTagihan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_tagihan' => [
                'type'          => 'INT',
                'constraint'    => 10,
                'unsigned'      => true,
                'auto_increment'=> true
            ],
            'paket_id' => [
                'type'          => 'INT',
                'unsigned'          => true,
            ],
            'mahasiswa_id' => [
                'type'          => 'INT',
                'unsigned'          => true,
            ],
            'tanggal_tagihan' => [
                'type'          => 'DATE',
            ],
            'keterangan_tagihan' => [
                'type'          => 'TEXT'
            ],
            'user_id' => [
                'type'          => 'INT',
                'unsigned'          => true,
            ],
            'created_at' => [
                'type'              => 'TIMESTAMP',
                'null'              => true,
            ],
            'updated_at' => [
                'type'              => 'TIMESTAMP',
                'null'              => true,
            ],
        ]);
        $this->forge->addKey('id_tagihan', true);
        $this->forge->addForeignKey('paket_id', 'paket', 'id_paket', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('mahasiswa_id', 'mahasiswa', 'id_mahasiswa', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'user', 'id_user', 'CASCADE');
        $this->forge->createTable('tagihan');
    }

    public function down()
    {
        $this->forge->dropTable('tagihan', true, true);
    }
}
