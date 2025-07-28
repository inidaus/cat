<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UjianPeserta extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'auto_increment' => true],
            'ujian_id'      => ['type' => 'INT'],
            'peserta_id'    => ['type' => 'INT'],
            'status'        => ['type' => 'ENUM', 'constraint' => ['belum_mulai', 'sedang_ujian', 'selesai'], 'default' => 'belum_mulai'],
            'nilai'         => ['type' => 'INT', 'null' => true],
            'mulai'         => ['type' => 'DATETIME', 'null' => true],
            'selesai'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('ujian_peserta');
    }

    public function down()
    {
        $this->forge->dropTable('ujian_peserta');
    }
}
