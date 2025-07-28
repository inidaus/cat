<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKategoriDataToUjian extends Migration
{
    public function up()
    {
        // Check if column exists first
        if (!$this->db->fieldExists('kategori_data', 'ujian')) {
            $this->forge->addColumn('ujian', [
                'kategori_data' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'after' => 'kategori_id'
                ]
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('kategori_data', 'ujian')) {
            $this->forge->dropColumn('ujian', 'kategori_data');
        }
    }
}
