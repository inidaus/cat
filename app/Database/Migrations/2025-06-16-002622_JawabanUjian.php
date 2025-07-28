<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class JawabanUjian extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'ujian_id'       => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'peserta_id'     => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'soal_id'        => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'jawaban'        => [
                'type' => 'TEXT',
                'null' => true
            ],
            'created_at'     => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at'     => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('jawaban_ujian');
    }

    public function down()
    {
        $this->forge->dropTable('jawaban_ujian');
    }
}
