<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPeriodeIdToUsersTable extends Migration
{
    public function up()
    {
        $fields = [
            'periode_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
                'after'      => 'universitas',
            ],
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'periode_id');
    }
}

