<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableSemester extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_semester' => [
                'type'              => 'INT',
                'constraint'        => 5,
                'unsigned'          => true,
                'auto_increment'    => true,
            ],
            'nama_semester' => [
                'type'              => 'VARCHAR',
                'constraint'        => 100,
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
        $this->forge->addKey('id_semester', true);
        $this->forge->createTable('semester');
    }

    public function down()
    {
        $this->forge->dropTable('semester');
    }
}
