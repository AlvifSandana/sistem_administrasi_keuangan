<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStatusFieldTableTagihan extends Migration
{
    public function up()
    {
        // define new field
        $field = [
            'status_tagihan' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'keterangan_tagihan'
            ],
        ];
        // apply changes to table TAGIHAN
        $this->forge->addColumn('tagihan', $field);
    }

    public function down()
    {
        // remove field status_tagihan
        $this->forge->dropColumn('tagihan', 'status_tagihan');
    }
}
