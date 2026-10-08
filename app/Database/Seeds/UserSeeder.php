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
            ],

            // 2. Akun Guru Pamong / Pembimbing
            [
                'username'    => 'febriyana',
                'nama'        => 'Ibu Febriyana, S.T.',
                'password'    => $defaultPassword,
                'role'        => 'guru',
                'jurusan'     => null,
                'universitas' => null,
            ],
            [
                'username'    => 'jumari',
                'nama'        => 'Bapak Jumari, S.Pd.T., M.Eng.',
                'password'    => $defaultPassword,
                'role'        => 'guru',
                'jurusan'     => null,
                'universitas' => null,
            ],

            // 3. Akun Mahasiswa PPL - Jurusan Informatika
            [
                'username'    => 'awan',
                'nama'        => 'Yodi Irawan',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Informatika',
                'universitas' => 'Universitas Negeri Yogyakarta'
            ],
            [
                'username'    => 'fajar',
                'nama'        => 'Fajar Alamsyah',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Informatika',
                'universitas' => 'Universitas Negeri Yogyakarta'
            ],
            [
                'username'    => 'ina',
                'nama'        => 'Inarotul Qolbiyah',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Informatika',
                'universitas' => 'Universitas Negeri Yogyakarta'
            ],

            // 4. Akun Mahasiswa PPL - Jurusan Bimbingan Konseling (BK)
            [
                'username'    => 'latifah',
                'nama'        => 'Nur Latifah',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Bimbingan Konseling',
                'universitas' => 'Universitas Ahmad Dahlan'
            ],
            [
                'username'    => 'asih',
                'nama'        => 'Nur Asih Wiji Astuti',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Bimbingan Konseling',
                'universitas' => 'Universitas Ahmad Dahlan'
            ],
            [
                'username'    => 'dimas',
                'nama'        => 'Dimas Surya Mahendra',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Bimbingan Konseling',
                'universitas' => 'Universitas Ahmad Dahlan'
            ],

            // 5. Akun Mahasiswa PPL - Jurusan Teknik Listrik (TL)
            [
                'username'    => 'fikriy',
                'nama'        => 'Fikriy Abbad Fauzan',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Teknik Ketenagalistrikan',
                'universitas' => 'Universitas Sarjanawiyata Tamansiswa'
            ],
            [
                'username'    => 'khoerul',
                'nama'        => 'Muhammad Khoerul Umam',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Teknik Ketenagalistrikan',
                'universitas' => 'Universitas Sarjanawiyata Tamansiswa'
            ],

            // 6. Akun Mahasiswa PPL - Jurusan Teknik Otomotif (TO)
            [
                'username'    => 'sendy',
                'nama'        => 'Sendy Diaz Erlangga',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Teknik Otomotif',
                'universitas' => 'Universitas Negeri Yogyakarta'
            ],
            [
                'username'    => 'nuraini',
                'nama'        => 'Nuraini Eka Putri',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Teknik Otomotif',
                'universitas' => 'Universitas Negeri Yogyakarta'
            ],

            // 7. Akun Mahasiswa PPL - Jurusan PJOK
            [
                'username'    => 'afeb',
                'nama'        => 'Afeb Chesa Arianto',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Pendidikan Jasmani Olahraga & Kesehatan',
                'universitas' => 'Universitas PGRI Yogyakarta'
            ],
            [
                'username'    => 'panji',
                'nama'        => 'Panji Agung Nugroho',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Pendidikan Jasmani Olahraga & Kesehatan',
                'universitas' => 'Universitas PGRI Yogyakarta'
            ],
            [
                'username'    => 'putri',
                'nama'        => 'Putri Diang Pawestri',
                'password'    => $defaultPassword,
                'role'        => 'mahasiswa',
                'jurusan'     => 'Pendidikan Jasmani Olahraga & Kesehatan',
                'universitas' => 'Universitas PGRI Yogyakarta'
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
