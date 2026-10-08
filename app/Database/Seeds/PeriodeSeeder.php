<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PeriodeSeeder extends Seeder
{
    public function run()
    {
        $this->db->disableForeignKeyChecks();
        $this->db->table('periode')->truncate();
        $this->db->enableForeignKeyChecks();

        $data = [
            [
                'id'              => 1,
                'nama_periode'    => 'PPL Semester Gasal 2026/2027',
                'tahun_ajaran'    => '2026/2027',
                'semester'        => 'Ganjil',
                'tanggal_mulai'   => '2026-07-15',
                'tanggal_selesai' => '2026-11-30',
                'is_aktif'        => 1,
                'keterangan'      => 'Praktik Pembelajaran Lapangan (PPL/PK) Mahasiswa Semester Gasal 2026/2027 di SMK Negeri 3 Yogyakarta.',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'id'              => 2,
                'nama_periode'    => 'PPL Semester Genap 2025/2026',
                'tahun_ajaran'    => '2025/2026',
                'semester'        => 'Genap',
                'tanggal_mulai'   => '2026-01-12',
                'tanggal_selesai' => '2026-05-29',
                'is_aktif'        => 0,
                'keterangan'      => 'Arsip periode PPL Semester Genap Tahun Ajaran 2025/2026.',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('periode')->insertBatch($data);
    }
}

