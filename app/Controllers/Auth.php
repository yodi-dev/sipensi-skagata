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
                $session->setFlashdata('error', 'Password salah, sob!');
                return redirect()->to('/auth');
            }
        } else {
            $session->setFlashdata('error', 'Username tidak ditemukan!');
            return redirect()->to('/auth');
        }
    }

    // --- METHOD UNTUK MENAMPILKAN VIEW ---
    public function ubahPasswordView()
    {
        // Pastikan user sudah login (cek session)
        if (!session()->get('nama')) {
            return redirect()->to('/')->with('error', 'Silakan login terlebih dahulu.');
        }

        return view('auth/ubah_password');
    }

    // --- METHOD UNTUK MEMPROSES UBAH PASSWORD ---
    public function prosesUbahPassword()
    {
        $passwordLama = $this->request->getPost('password_lama');
        $passwordBaru = $this->request->getPost('password_baru');
        $konfirmasiPassword = $this->request->getPost('konfirmasi_password');

        // 1. Validasi konfirmasi password dan panjang karakter
        if (strlen((string) $passwordBaru) < 6) {
            return redirect()->back()->with('error', 'Password baru minimal 6 karakter!');
        }

        if ($passwordBaru !== $konfirmasiPassword) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak cocok dengan password baru!');
        }

        // 2. Ambil data session
        $idUser = session()->get('id_user');
        $role   = session()->get('role');

        // 3. Panggil UserModel
        $userModel = new UserModel();

        // 4. Cari data user di database berdasarkan ID session
        $user = $userModel->find($idUser);
        if (!$user) {
            return redirect()->to('/auth')->with('error', 'User tidak ditemukan atau sesi telah berakhir.');
        }

        // 5. Cek apakah password lama sesuai
        if (!password_verify($passwordLama, $user['password'])) {
            return redirect()->back()->with('error', 'Password lama yang kamu masukkan salah!');
        }

        // 6. Enkripsi password baru dan Update ke database
        $userModel->update($idUser, [
            'password' => password_hash($passwordBaru, PASSWORD_DEFAULT)
        ]);

        // 7. Arahkan kembali ke dashboard yang sesuai berdasarkan role
        if ($role === 'admin') {
            $redirectUrl = '/admin';
        } elseif ($role === 'guru') {
            $redirectUrl = '/guru';
        } else {
            $redirectUrl = '/mahasiswa';
        }
        return redirect()->to($redirectUrl)->with('pesan', 'Mantap! Password berhasil diubah.');
    }

    public function logout()
    {
        // Hancurkan session saat logout
        session()->destroy();
        return redirect()->to('/auth')->with('pesan', 'Anda telah berhasil keluar dari sistem.');
    }
}
