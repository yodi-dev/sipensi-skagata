<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class JurusanSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $data = [
            [
                'kode_jurusan' => 'IF',
                'nama_jurusan' => 'Informatika',
                'deskripsi'    => 'Konsentrasi keahlian Teknik Informatika, Rekayasa Perangkat Lunak, dan Sistem Komputasi.',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'kode_jurusan' => 'BK',
                'nama_jurusan' => 'Bimbingan Konseling',
                'deskripsi'    => 'Layanan bimbingan pribadi, sosial, belajar, dan karir peserta didik.',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'kode_jurusan' => 'PJOK',
                'nama_jurusan' => 'Pendidikan Jasmani Olahraga & Kesehatan',
                'deskripsi'    => 'Pendidikan jasmani, kebugaran jasmani, dan kesehatan jasmani-rohani.',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'kode_jurusan' => 'TL',
                'nama_jurusan' => 'Teknik Ketenagalistrikan',
                'deskripsi'    => 'Teknik Instalasi Tenaga Listrik, instalasi penerangan, dan otomasi kelistrikan.',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'kode_jurusan' => 'TO',
                'nama_jurusan' => 'Teknik Otomotif',
                'deskripsi'    => 'Teknik Kendaraan Ringan Otomotif, pemeliharaan mesin, sasis, dan kelistrikan mobil.',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'kode_jurusan' => 'TKJ',
                'nama_jurusan' => 'Teknik Komputer dan Jaringan',
                'deskripsi'    => 'Infrastruktur jaringan, routing & switching, server administrasi, dan sistem telekomunikasi.',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'kode_jurusan' => 'TP',
                'nama_jurusan' => 'Teknik Pemesinan',
                'deskripsi'    => 'Pemesinan bubut, frais, CNC presisi tinggi, dan fabrikasi logam manufaktur.',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'kode_jurusan' => 'DPIB',
                'nama_jurusan' => 'Desain Pemodelan dan Informasi Bangunan',
                'deskripsi'    => 'Gambar kerja konstruksi gedung, pemodelan BIM 3D, dan estimasi biaya bangunan sipil.',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ];

        foreach ($data as $item) {
            $existing = $this->db->table('jurusan')->where('kode_jurusan', $item['kode_jurusan'])->get()->getRowArray();
            if ($existing) {
                $this->db->table('jurusan')->where('id', $existing['id'])->update($item);
            } else {
                $this->db->table('jurusan')->insert($item);
            }
        }
    }
}

