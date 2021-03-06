<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableItemPaket extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_item'   => [
                'type'          => 'INT',
                'constraint'    => 10,
                'unsigned'      => true,
                'auto_increment'=> true,
            ],
            'paket_id'     => [
                'type'          => 'INT',
                'unsigned'          => true,
            ],
            'nama_item' => [
                'type'          => 'VARCHAR',
                'constraint'    => 100,
            ],
            'nominal_item' => [
                'type'          => 'INT',
            ],
            'keterangan_item' => [
                'type'          => 'TEXT'
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
        $this->forge->addKey('id_item', true);
        $this->forge->addForeignKey('paket_id', 'paket', 'id_paket', '','CASCADE');
        $this->forge->createTable('item_paket');
    }

    public function down()
    {
        $this->forge->dropTable('item_paket', true, true);
    }
}
