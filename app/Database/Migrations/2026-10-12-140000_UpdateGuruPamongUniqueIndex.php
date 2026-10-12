<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateGuruPamongUniqueIndex extends Migration
{
    public function up()
    {
        // 1. Hapus unique index lama (guru_id, jurusan) jika ada
        $indexes = $this->db->query("SHOW INDEX FROM guru_pamong WHERE Key_name = 'guru_id_jurusan'")->getResultArray();
        if (!empty($indexes)) {
            $this->db->query("ALTER TABLE guru_pamong DROP INDEX guru_id_jurusan");
        }

        // 2. Tambahkan unique index baru pada (guru_id, jurusan_id)
        $uniqueIdIndexes = $this->db->query("SHOW INDEX FROM guru_pamong WHERE Key_name = 'unique_guru_jurusan_id'")->getResultArray();
        if (empty($uniqueIdIndexes)) {
            $this->db->query("ALTER TABLE guru_pamong ADD UNIQUE KEY unique_guru_jurusan_id (guru_id, jurusan_id)");
        }

        // 3. Tambahkan index biasa pada (guru_id, jurusan) untuk optimasi query pencarian berbasis string
        $idxJurusan = $this->db->query("SHOW INDEX FROM guru_pamong WHERE Key_name = 'idx_guru_jurusan'")->getResultArray();
        if (empty($idxJurusan)) {
            $this->db->query("ALTER TABLE guru_pamong ADD KEY idx_guru_jurusan (guru_id, jurusan)");
        }
    }

    public function down()
    {
        // 1. Hapus index baru
        $idxJurusan = $this->db->query("SHOW INDEX FROM guru_pamong WHERE Key_name = 'idx_guru_jurusan'")->getResultArray();
        if (!empty($idxJurusan)) {
            $this->db->query("ALTER TABLE guru_pamong DROP INDEX idx_guru_jurusan");
        }

        $uniqueIdIndexes = $this->db->query("SHOW INDEX FROM guru_pamong WHERE Key_name = 'unique_guru_jurusan_id'")->getResultArray();
        if (!empty($uniqueIdIndexes)) {
            $this->db->query("ALTER TABLE guru_pamong DROP INDEX unique_guru_jurusan_id");
        }

        // 2. Kembalikan unique key lama
        $indexes = $this->db->query("SHOW INDEX FROM guru_pamong WHERE Key_name = 'guru_id_jurusan'")->getResultArray();
        if (empty($indexes)) {
            $this->db->query("ALTER TABLE guru_pamong ADD UNIQUE KEY guru_id_jurusan (guru_id, jurusan)");
        }
    }
}

