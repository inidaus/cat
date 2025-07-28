<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['aktif', 'nonaktif'],
                'default' => 'aktif',
                'after' => 'role'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'status');
    }
}
