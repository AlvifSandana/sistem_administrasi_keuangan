<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableAngkatan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_angkatan' => [
                'type'              => 'INT',
                'constraint'        => 5,
                'unsigned'          => true,
                'auto_increment'    => true,
            ],
            'nama_angkatan' => [
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
        $this->forge->addKey('id_angkatan', true);
        $this->forge->createTable('angkatan');
    }

    public function down()
    {
        $this->forge->dropTable('angkatan');
    }
}
