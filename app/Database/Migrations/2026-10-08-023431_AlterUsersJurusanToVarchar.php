<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterUsersJurusanToVarchar extends Migration
{
    public function up()
    {
        $fields = [
            'jurusan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => null,
            ],
        ];
        $this->forge->modifyColumn('users', $fields);
    }

    public function down()
    {
        $fields = [
            'jurusan' => [
                'type'       => 'ENUM',
                'constraint' => ['Informatika', 'PJOK', 'BK', 'TL', 'TO'],
                'null'       => true,
                'default'    => null,
            ],
        ];
        $this->forge->modifyColumn('users', $fields);
    }
}
