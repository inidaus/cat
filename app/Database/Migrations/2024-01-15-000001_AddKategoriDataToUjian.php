<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKategoriDataToUjian extends Migration
{
    public function up()
    {
        $this->forge->addColumn('ujian', [
            'kategori_data' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'kategori_id'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('ujian', 'kategori_data');
    }
}
