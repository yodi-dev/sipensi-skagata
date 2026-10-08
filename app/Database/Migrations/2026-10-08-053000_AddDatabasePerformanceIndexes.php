<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDatabasePerformanceIndexes extends Migration
{
    public function up()
    {
        // 1. Indeks pada tabel presensi:
        // - unique_user_tanggal: Mencegah entri presensi ganda pada tanggal yang sama (anti race-condition / dobel submit)
        // - idx_presensi_tanggal: Mempercepat filter tanggal pada dashboard dan laporan bulanan
        $this->db->query("CREATE UNIQUE INDEX unique_user_tanggal ON presensi (user_id, tanggal)");
        $this->db->query("CREATE INDEX idx_presensi_tanggal ON presensi (tanggal)");

        // 2. Indeks pada tabel users:
        // - unique_username: Menjamin keunikan username di tingkat database engine dan mempercepat query login
        // - idx_users_role: Mempercepat filtering berdasarkan role (mahasiswa / guru)
        // - idx_users_jurusan: Mempercepat isolasi data mahasiswa berdasarkan jurusan pamong
        $this->db->query("CREATE UNIQUE INDEX unique_username ON users (username)");
        $this->db->query("CREATE INDEX idx_users_role ON users (role)");
        $this->db->query("CREATE INDEX idx_users_jurusan ON users (jurusan)");

        // 3. Indeks pada tabel piket_kbm:
        // - idx_piket_tanggal: Mempercepat filtering jadwal piket harian
        $this->db->query("CREATE INDEX idx_piket_tanggal ON piket_kbm (tanggal)");
    }

    public function down()
    {
        $driver = strtolower($this->db->DBDriver);

        if ($driver === 'sqlite3') {
            $this->db->query("DROP INDEX IF EXISTS unique_user_tanggal");
            $this->db->query("DROP INDEX IF EXISTS idx_presensi_tanggal");
            $this->db->query("DROP INDEX IF EXISTS unique_username");
            $this->db->query("DROP INDEX IF EXISTS idx_users_role");
            $this->db->query("DROP INDEX IF EXISTS idx_users_jurusan");
            $this->db->query("DROP INDEX IF EXISTS idx_piket_tanggal");
        } else {
            // MySQL / MariaDB
            $this->db->query("ALTER TABLE presensi DROP INDEX unique_user_tanggal");
            $this->db->query("ALTER TABLE presensi DROP INDEX idx_presensi_tanggal");
            $this->db->query("ALTER TABLE users DROP INDEX unique_username");
            $this->db->query("ALTER TABLE users DROP INDEX idx_users_role");
            $this->db->query("ALTER TABLE users DROP INDEX idx_users_jurusan");
            $this->db->query("ALTER TABLE piket_kbm DROP INDEX idx_piket_tanggal");
        }
    }
}
