<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDokumenPembayaranFieldTablePembayaran extends Migration
{
    public function up()
    {
        // define new field
        $field = [
            'is_dokumen_pembayaran' => [
                'type' => 'BOOLEAN',
                'null' => true, 
                'after'=> 'user_id'
            ],
        ];
        // apply changes to table pembayaran
        $this->forge->addColumn('pembayaran', $field);
    }

    public function down()
    {
        // remove changes
        $this->forge->dropColumn('pembayaran', 'is_dokumen_pembayaran');
    }
}
