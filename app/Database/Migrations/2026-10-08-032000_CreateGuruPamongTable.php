<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGuruPamongTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'guru_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'jurusan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'jurusan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('guru_id');
        $this->forge->addUniqueKey(['guru_id', 'jurusan']);
        $this->forge->createTable('guru_pamong', true);
    }

    public function down()
    {
        $this->forge->dropTable('guru_pamong', true);
    }
}
