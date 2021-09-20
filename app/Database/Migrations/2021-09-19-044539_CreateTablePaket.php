<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTablePaket extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_paket'  => [
                'type'              => 'INT',
                'constraint'        => 5,
                'unsigned'          => true,
                'auto_increment'    => true,
            ],
            'nama_paket'=> [
                'type'              => 'VARCHAR',
                'constraint'        => 100,
            ],
            'keterangan_paket'  => [
                'type'              => 'TEXT',
            ],
            'semester_id'       => [
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
        $this->forge->addKey('id_paket', true);
        $this->forge->addForeignKey('semester_id', 'semester', 'id_semester', 'CASCADE', 'CASCADE');
        $this->forge->createTable('paket');
    }

    public function down()
    {
        $this->forge->dropTable('paket', false, true);
    }
}
