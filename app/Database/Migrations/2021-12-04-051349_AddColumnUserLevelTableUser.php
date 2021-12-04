<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnUserLevelTableUser extends Migration
{
    public function up()
    {
        // define field
        $field = [
            'user_level' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'password'
            ]
        ];
        // apply changes
        $this->forge->addColumn('user', $field);
    }

    public function down()
    {
        // discard changes
        $this->forge->dropColumn('user', 'user_level');
    }
}
