<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDokumenPembayaranNameFieldTablePembayaran extends Migration
{
    public function up()
    {
        // define new field
        $field = [
            'dokumen_pembayaran' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'is_dokumen_pembayaran'
            ],
        ];
        // apply changes to table pembayaran
        $this->forge->addColumn('pembayaran', $field);
    }

    public function down()
    {
        // remove changes
        $this->forge->dropColumn('pembayaran', 'dokumen_pembayaran');
    }
}
