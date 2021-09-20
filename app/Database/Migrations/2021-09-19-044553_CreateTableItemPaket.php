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
    }

    public function down()
    {
        //
    }
}
