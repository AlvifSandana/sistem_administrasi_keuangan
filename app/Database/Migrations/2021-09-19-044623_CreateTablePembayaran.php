<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTablePembayaran extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id_pembayaran' => [
                'type'          => 'INT',
                'constraint'    => 10,
                'unsigned'      => true,
                'auto_increment'=> true
            ],
            'paket_id' => [
                'type'          => 'INT',
                'unsigned'          => true,
            ],
            'item_id'  => [
                'type'          => 'INT',
                'unsigned'          => true,
            ],
            'mahasiswa_id' => [
                'type'          => 'INT',
                'unsigned'          => true,
            ],
            'tanggal_pembayaran' => [
                'type'          => 'DATE'
            ],
            'nominal_pembayaran' => [
                'type'          => 'INT'
            ],
            'keterangan_pembayaran' => [
                'type'          => 'TEXT',
                'null'          => true,
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
        $this->forge->addKey('id_pembayaran', true);
        $this->forge->addForeignKey('paket_id', 'paket', 'id_paket', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('item_id', 'item_paket', 'id_item', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('mahasiswa_id', 'mahasiswa', 'id_mahasiswa', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'user', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pembayaran');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('pembayaran', true, true);
    }
}
