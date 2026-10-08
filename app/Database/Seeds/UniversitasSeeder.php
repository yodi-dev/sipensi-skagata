<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UniversitasSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $data = [
            [
                'kode_universitas' => 'UNY',
                'nama_universitas' => 'Universitas Negeri Yogyakarta',
                'alamat'           => 'Jl. Colombo No. 1, Karang Malang, Caturtunggal, Depok, Sleman, D.I. Yogyakarta 55281',
                'telepon'          => '(0274) 586168',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'kode_universitas' => 'UAD',
                'nama_universitas' => 'Universitas Ahmad Dahlan',
                'alamat'           => 'Jl. Kapas No. 9, Semaki, Umbulharjo, Kota Yogyakarta, D.I. Yogyakarta 55166',
                'telepon'          => '(0274) 563515',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'kode_universitas' => 'UST',
                'nama_universitas' => 'Universitas Sarjanawiyata Tamansiswa',
                'alamat'           => 'Jl. Batikan, Tahunan, Umbulharjo, Kota Yogyakarta, D.I. Yogyakarta 55167',
                'telepon'          => '(0274) 551586',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'kode_universitas' => 'UPY',
                'nama_universitas' => 'Universitas PGRI Yogyakarta',
                'alamat'           => 'Jl. PGRI I No. 117, Sonosewu, Bantul, D.I. Yogyakarta 55182',
                'telepon'          => '(0274) 376808',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'kode_universitas' => 'UTY',
                'nama_universitas' => 'Universitas Teknologi Yogyakarta',
                'alamat'           => 'Jl. Ringroad Utara, Jombor, Sleman, D.I. Yogyakarta 55285',
                'telepon'          => '(0274) 623310',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'kode_universitas' => 'USD',
                'nama_universitas' => 'Universitas Sanata Dharma',
                'alamat'           => 'Jl. Affandi, Mrican, Tromol Pos 29, Sleman, D.I. Yogyakarta 55002',
                'telepon'          => '(0274) 513301',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'kode_universitas' => 'UGM',
                'nama_universitas' => 'Universitas Gadjah Mada',
                'alamat'           => 'Bulaksumur, Caturtunggal, Depok, Sleman, D.I. Yogyakarta 55281',
                'telepon'          => '(0274) 6492800',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
        ];

        foreach ($data as $item) {
            $existing = $this->db->table('universitas')->where('kode_universitas', $item['kode_universitas'])->get()->getRowArray();
            if ($existing) {
                $this->db->table('universitas')->where('id', $existing['id'])->update($item);
            } else {
                $this->db->table('universitas')->insert($item);
            }
        }
    }
}
