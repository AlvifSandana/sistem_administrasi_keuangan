<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableProgdi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_progdi' => [
                'type'              => 'INT',
                'constraint'        => 5,
                'unsigned'          => true,
                'auto_increment'    => true,
            ],
            'nama_progdi' => [
                'type'              => 'VARCHAR',
                'constraint'        => 100,
            ],
            'created_at' => [
                'type'              => 'TIMESTAMP',
            ],
            'updated_at' => [
                'type'              => 'TIMESTAMP',
            ],
        ]);
        $this->forge->addKey('id_progdi', true);
        $this->forge->createTable('progdi');
    }

    public function down()
    {
        $this->forge->dropTable('progdi');
    }
}
