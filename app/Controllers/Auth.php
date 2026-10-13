<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            $role = session()->get('role');
            if ($role === 'admin') {
                return redirect()->to('/admin');
            }
            return redirect()->to($role === 'guru' ? '/guru' : '/mahasiswa');
        }

        return view('auth/login');
    }

    public function proses_login()
    {
        $session = session();
        $throttler = service('throttler');
        $ip = $this->request->getIPAddress();
        $throttleKey = 'login_' . md5($ip);

        // Proteksi Brute-Force: Batasi maksimal 5 percobaan login per 60 detik per IP
        if ($throttler->check($throttleKey, 5, 60) === false) {
            $seconds = max(1, $throttler->getTokenTime());
            $session->setFlashdata('error', "Terlalu banyak percobaan login. Silakan tunggu {$seconds} detik sebelum mencoba kembali.");
            return redirect()->to('/auth');
        }

        $userModel = new UserModel();

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            $session->setFlashdata('error', 'Username dan password wajib diisi!');
            return redirect()->to('/auth');
        }

        // Cari user di database berdasarkan username
        $user = $userModel->where('username', $username)->first();

        if ($user) {
            // Cek kecocokan password
            if (password_verify($password, $user['password'])) {

                // H1: Regenerasi session ID untuk mencegah serangan Session Fixation
                $session->regenerate();

                // Bersihkan batasan throttle karena kredensial valid
                cache()->delete('throttler_' . $throttleKey);

                // Jika benar, simpan data user ke Session
                $dataSession = [
                    'id_user'    => $user['id'],
                    'nama'       => $user['nama'],
                    'username'   => $user['username'],
                    'role'       => $user['role'],
                    'isLoggedIn' => true,
                    'logged_in'  => true
                ];
                $session->set($dataSession);

                // Arahkan ke halaman sesuai role
                if ($user['role'] === 'admin') {
                    return redirect()->to('/admin');
                } elseif ($user['role'] === 'guru') {
                    return redirect()->to('/guru');
                } else {
                    return redirect()->to('/mahasiswa');
                }
            } else {
                $session->setFlashdata('error', 'Password yang Anda masukkan salah!');
                return redirect()->to('/auth');
            }
        } else {
            $session->setFlashdata('error', 'Username tidak ditemukan!');
            return redirect()->to('/auth');
        }
    }

    // --- METHOD PROFIL & KEAMANAN TERPADU ---
    public function profil()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/auth')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userId = $session->get('id_user');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            $session->destroy();
            return redirect()->to('/auth')->with('error', 'Data pengguna tidak ditemukan. Silakan login kembali.');
        }

        $data = [
            'title'        => 'Profil & Keamanan Akun - SIPENSI SKAGATA',
            'user'         => $user,
            'role'         => $user['role'],
            'namaUser'     => $user['nama'],
            'usernameUser' => $user['username'],
        ];

        // Ambil info kontekstual berdasarkan role
        if ($user['role'] === 'mahasiswa') {
            $periodeModel = new \App\Models\PeriodeModel();
            $periode = !empty($user['periode_id']) ? $periodeModel->find($user['periode_id']) : null;
            $data['periode'] = $periode;
        } elseif ($user['role'] === 'guru') {
            $guruPamongModel = new \App\Models\GuruPamongModel();
            $assignments = $guruPamongModel->getAssignmentsByGuru((int) $user['id']);
            $totalMhs = $guruPamongModel->countMahasiswaByGuru((int) $user['id']);
            $data['assignments'] = $assignments;
            $data['total_mahasiswa'] = $totalMhs;
        }

        return view('auth/profil', $data);
    }

    public function prosesUpdateProfil()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/auth')->with('error', 'Sesi telah berakhir. Silakan login kembali.');
        }

        $userId = $session->get('id_user');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to('/auth')->with('error', 'Data pengguna tidak ditemukan.');
        }

        $nama = trim(strip_tags((string) $this->request->getPost('nama')));
        if (empty($nama) || mb_strlen($nama) < 2) {
            return redirect()->back()->withInput()->with('error', 'Nama lengkap minimal 2 karakter!');
        }

        $updateData = ['nama' => $nama];

        // Khusus Administrator: Boleh mengupdate username
        if ($user['role'] === 'admin') {
            $username = trim((string) $this->request->getPost('username'));
            if (!empty($username) && $username !== $user['username']) {
                if (mb_strlen($username) < 3) {
                    return redirect()->back()->withInput()->with('error', 'Username minimal 3 karakter!');
                }
                // Cek keunikan
                $cek = $userModel->where('username', $username)->where('id !=', $userId)->first();
                if ($cek) {
                    return redirect()->back()->withInput()->with('error', "Username '{$username}' telah digunakan pengguna lain!");
                }
                $updateData['username'] = $username;
            }
        }

        // Cek jika ada pengubahan kata sandi
        $passwordLama = (string) $this->request->getPost('password_lama');
        $passwordBaru = (string) $this->request->getPost('password_baru');
        $konfirmasiPassword = (string) $this->request->getPost('konfirmasi_password');

        if (!empty($passwordBaru) || !empty($passwordLama) || !empty($konfirmasiPassword)) {
            if (empty($passwordLama)) {
                return redirect()->back()->withInput()->with('error', 'Masukkan password saat ini untuk memverifikasi perubahan password!');
            }
            if (!password_verify($passwordLama, $user['password'])) {
                return redirect()->back()->withInput()->with('error', 'Password saat ini yang Anda masukkan tidak sesuai!');
            }
            if (strlen($passwordBaru) < 6) {
                return redirect()->back()->withInput()->with('error', 'Password baru minimal 6 karakter!');
            }
            if ($passwordBaru !== $konfirmasiPassword) {
                return redirect()->back()->withInput()->with('error', 'Konfirmasi password baru tidak cocok!');
            }

            $updateData['password'] = password_hash($passwordBaru, PASSWORD_BCRYPT);
        }

        $userModel->update($userId, $updateData);

        // Perbarui data session yang relevan
        $session->set([
            'nama'     => $updateData['nama'],
            'username' => $updateData['username'] ?? $user['username']
        ]);

        $pesanSukses = (!empty($passwordBaru))
            ? 'Profil dan kata sandi akun Anda berhasil diperbarui!'
            : 'Informasi profil Anda berhasil diperbarui!';

        return redirect()->to('/profil')->with('pesan', $pesanSukses);
    }

    // --- ALIAS BACKWARD-COMPATIBILITY UNTUK RUTE LAMA ---
    public function ubahPasswordView()
    {
        return redirect()->to('/profil');
    }

    public function prosesUbahPassword()
    {
        return $this->prosesUpdateProfil();
    }

    public function logout()
    {
        // Hancurkan session saat logout
        session()->destroy();
        return redirect()->to('/auth')->with('pesan', 'Anda telah berhasil keluar dari sistem.');
    }
}
