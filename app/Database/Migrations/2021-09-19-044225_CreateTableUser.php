<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableUser extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_user' => [
                'type'              => 'INT',
                'constraint'        => 5,
                'unsigned'          => true,
                'auto_increment'    => true,
            ],
            'nama' => [
                'type'              => 'VARCHAR',
                'constraint'        => 100,
            ],
            'username' => [
                'type'              => 'VARCHAR',
                'constraint'        => 100,
                'unique'            => true,
            ],
            'email' => [
                'type'              => 'VARCHAR',
                'constraint'        => 100,
                'unique'            => true,
            ],
            'password' => [
                'type'              => 'VARCHAR',
            ],
            'created_at' => [
                'type'              => 'TIMESTAMP',
            ],
            'updated_at' => [
                'type'              => 'TIMESTAMP',
            ],
        ]);
        $this->forge->addKey('id_user', true);
        $this->forge->createTable('user');
    }

    public function down()
    {
        $this->forge->dropTable('user');
    }
}
