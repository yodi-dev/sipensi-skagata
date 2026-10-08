<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GuruPamongSeeder extends Seeder
{
    public function run()
    {
        $this->db->disableForeignKeyChecks();
        $this->db->table('guru_pamong')->truncate();
        $this->db->enableForeignKeyChecks();

        // Cari user guru
        $febriyana = $this->db->table('users')->where('username', 'febriyana')->get()->getRowArray();
        $jumari    = $this->db->table('users')->where('username', 'jumari')->get()->getRowArray();

        $data = [];

        if ($febriyana) {
            $data[] = [
                'guru_id'    => (int) $febriyana['id'],
                'jurusan_id' => 1,
                'jurusan'    => 'Informatika',
                'keterangan' => 'Guru Pamong Pembimbing Mahasiswa PPL/PK Jurusan Informatika',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            // Sinkronkan juga users.jurusan
            $this->db->table('users')->where('id', $febriyana['id'])->update(['jurusan' => 'Informatika']);
        }

        if ($jumari) {
            $data[] = [
                'guru_id'    => (int) $jumari['id'],
                'jurusan_id' => 4,
                'jurusan'    => 'Teknik Ketenagalistrikan',
                'keterangan' => 'Guru Pamong Pembimbing Mahasiswa PPL/PK Jurusan Teknik Ketenagalistrikan',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            // Sinkronkan juga users.jurusan
            $this->db->table('users')->where('id', $jumari['id'])->update(['jurusan' => 'Teknik Ketenagalistrikan']);
        }

        if (!empty($data)) {
            $this->db->table('guru_pamong')->insertBatch($data);
        }
    }
}

