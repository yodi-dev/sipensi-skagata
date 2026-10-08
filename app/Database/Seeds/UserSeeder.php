<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Nonaktifkan foreign key checks sementara untuk pembersihan bersih
        $this->db->disableForeignKeyChecks();
        $this->db->table('users')->truncate();
        $this->db->enableForeignKeyChecks();

        $defaultPassword = password_hash('secret', PASSWORD_BCRYPT);
        $adminPassword   = password_hash('admin123', PASSWORD_BCRYPT);

        $data = [
            // 1. Akun Administrator Sistem
            [
                'username'    => 'admin',
                'nama'        => 'Administrator Sistem',
                'password'    => $adminPassword,
                'role'        => 'admin',
                'jurusan'     => null,
                'universitas' => null,
                'periode_id'  => null,
            ],

            // 2. Akun Guru Pamong / Pembimbing
            [
                'username'    => 'febriyana',
                'nama'        => 'Ibu Febriyana, S.T.',
                'password'    => $defaultPassword,
                'role'        => 'guru',
                'jurusan'     => 'Informatika',
                'universitas' => null,
                'periode_id'  => null,
            ],
            [
                'username'    => 'jumari',
                'nama'        => 'Bapak Jumari, S.Pd.T., M.Eng.',
                'password'    => $defaultPassword,
                'role'        => 'guru',
                'jurusan'     => 'Teknik Ketenagalistrikan',
                'universitas' => null,
                'periode_id'  => null,
            ],

            // 3. Akun Mahasiswa PPL - Jurusan Informatika
            [
                'username'    => 'awan',
                'nama'        => 'Yodi Irawan',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Informatika',
                'universitas' => 'Universitas Negeri Yogyakarta',
                'periode_id'  => 1,
            ],
            [
                'username'    => 'fajar',
                'nama'        => 'Fajar Alamsyah',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Informatika',
                'universitas' => 'Universitas Negeri Yogyakarta',
                'periode_id'  => 1,
            ],
            [
                'username'    => 'ina',
                'nama'        => 'Inarotul Qolbiyah',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Informatika',
                'universitas' => 'Universitas Negeri Yogyakarta',
                'periode_id'  => 1,
            ],

            // 4. Akun Mahasiswa PPL - Jurusan Bimbingan Konseling (BK)
            [
                'username'    => 'latifah',
                'nama'        => 'Nur Latifah',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Bimbingan Konseling',
                'universitas' => 'Universitas Ahmad Dahlan',
                'periode_id'  => 1,
            ],
            [
                'username'    => 'asih',
                'nama'        => 'Nur Asih Wiji Astuti',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Bimbingan Konseling',
                'universitas' => 'Universitas Ahmad Dahlan',
                'periode_id'  => 1,
            ],
            [
                'username'    => 'dimas',
                'nama'        => 'Dimas Surya Mahendra',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Bimbingan Konseling',
                'universitas' => 'Universitas Ahmad Dahlan',
                'periode_id'  => 1,
            ],

            // 5. Akun Mahasiswa PPL - Jurusan Teknik Listrik (TL)
            [
                'username'    => 'fikriy',
                'nama'        => 'Fikriy Abbad Fauzan',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Teknik Ketenagalistrikan',
                'universitas' => 'Universitas Sarjanawiyata Tamansiswa',
                'periode_id'  => 1,
            ],
            [
                'username'    => 'khoerul',
                'nama'        => 'Muhammad Khoerul Umam',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Teknik Ketenagalistrikan',
                'universitas' => 'Universitas Sarjanawiyata Tamansiswa',
                'periode_id'  => 1,
            ],

            // 6. Akun Mahasiswa PPL - Jurusan Teknik Otomotif (TO)
            [
                'username'    => 'sendy',
                'nama'        => 'Sendy Diaz Erlangga',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Teknik Otomotif',
                'universitas' => 'Universitas Negeri Yogyakarta',
                'periode_id'  => 1,
            ],
            [
                'username'    => 'nuraini',
                'nama'        => 'Nuraini Eka Putri',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Teknik Otomotif',
                'universitas' => 'Universitas Negeri Yogyakarta',
                'periode_id'  => 1,
            ],

            // 7. Akun Mahasiswa PPL - Jurusan PJOK
            [
                'username'    => 'afeb',
                'nama'        => 'Afeb Chesa Arianto',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Pendidikan Jasmani Olahraga & Kesehatan',
                'universitas' => 'Universitas PGRI Yogyakarta',
                'periode_id'  => 1,
            ],
            [
                'username'    => 'panji',
                'nama'        => 'Panji Agung Nugroho',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Pendidikan Jasmani Olahraga & Kesehatan',
                'universitas' => 'Universitas PGRI Yogyakarta',
                'periode_id'  => 1,
            ],
            [
                'username'    => 'putri',
                'nama'        => 'Putri Diang Pawestri',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Pendidikan Jasmani Olahraga & Kesehatan',
                'universitas' => 'Universitas PGRI Yogyakarta',
                'periode_id'  => 1,
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
