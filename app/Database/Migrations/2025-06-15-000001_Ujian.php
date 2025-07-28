<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Ujian extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'judul'           => ['type' => 'VARCHAR', 'constraint' => 255],
            'kategori_id'     => ['type' => 'INT', 'unsigned' => true],
            'pembimbing_id'   => ['type' => 'INT', 'unsigned' => true],
            'jumlah_soal'     => ['type' => 'INT', 'unsigned' => true],
            'waktu_menit'     => ['type' => 'INT', 'unsigned' => true],
            'toleransi_menit' => ['type' => 'INT', 'unsigned' => true],
            'acak_soal'       => ['type' => 'BOOLEAN', 'default' => 1],
            'passing_grade'   => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'mulai'           => ['type' => 'DATETIME'],
            'selesai'         => ['type' => 'DATETIME', 'null' => true],
            'status'          => ['type' => 'TINYINT', 'default' => 1], // 1 = aktif, 0 = nonaktif
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('ujian');
    }

    public function down()
    {
        $this->forge->dropTable('ujian');
    }
}
