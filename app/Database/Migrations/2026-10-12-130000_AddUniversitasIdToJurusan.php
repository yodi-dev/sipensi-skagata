<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUniversitasIdToJurusan extends Migration
{
    public function up()
    {
        $fields = [
            'universitas_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id',
            ],
        ];

        $this->forge->addColumn('jurusan', $fields);

        // Tambahkan Foreign Key & Index jika didukung driver MySQLi
        if ($this->db->DBDriver === 'MySQLi') {
            // Cek apakah foreign key belum ada sebelum menambahkan
            $checkFk = $this->db->query("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'jurusan' AND CONSTRAINT_NAME = 'fk_jurusan_universitas'")->getResultArray();
            if (empty($checkFk)) {
                $this->db->query("ALTER TABLE `jurusan` ADD CONSTRAINT `fk_jurusan_universitas` FOREIGN KEY (`universitas_id`) REFERENCES `universitas`(`id`) ON DELETE SET NULL ON UPDATE CASCADE");
            }
            $checkIdx = $this->db->query("SHOW INDEX FROM `jurusan` WHERE Key_name = 'idx_jurusan_universitas'")->getResultArray();
            if (empty($checkIdx)) {
                $this->db->query("ALTER TABLE `jurusan` ADD INDEX `idx_jurusan_universitas` (`universitas_id`)");
            }
        }
    }

    public function down()
    {
        if ($this->db->DBDriver === 'MySQLi') {
            $checkFk = $this->db->query("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'jurusan' AND CONSTRAINT_NAME = 'fk_jurusan_universitas'")->getResultArray();
            if (!empty($checkFk)) {
                $this->db->query("ALTER TABLE `jurusan` DROP FOREIGN KEY `fk_jurusan_universitas`");
            }
            $checkIdx = $this->db->query("SHOW INDEX FROM `jurusan` WHERE Key_name = 'idx_jurusan_universitas'")->getResultArray();
            if (!empty($checkIdx)) {
                $this->db->query("ALTER TABLE `jurusan` DROP INDEX `idx_jurusan_universitas`");
            }
        }

        $this->forge->dropColumn('jurusan', 'universitas_id');
    }
}

