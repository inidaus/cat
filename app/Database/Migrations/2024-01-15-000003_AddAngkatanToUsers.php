<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAngkatanToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'angkatan' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
                'after' => 'nama_lengkap'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'angkatan');
    }
}
