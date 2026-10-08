<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUniversitasToUsersTable extends Migration
{
    public function up()
    {
        $fields = [
            'universitas' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'default'    => null,
                'after'      => 'jurusan',
            ],
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'universitas');
    }
}
