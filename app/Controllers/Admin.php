<?php

namespace App\Controllers;

use App\Models\JurusanModel;
use App\Models\PeriodeModel;
use App\Models\SettingModel;
use App\Models\UniversitasModel;
use App\Models\UserModel;
use Config\Database;

class Admin extends BaseController
{
    protected UserModel $userModel;
    protected JurusanModel $jurusanModel;
    protected UniversitasModel $universitasModel;
    protected PeriodeModel $periodeModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->jurusanModel = new JurusanModel();
        $this->universitasModel = new UniversitasModel();
        $this->periodeModel = new PeriodeModel();
    }

    public function index()
    {
        $db = Database::connect();
        $builder = $db->table('users');

        // Hitung statistik keseluruhan pengguna
        $totalUsers = (clone $builder)->countAllResults();
        $totalMahasiswa = (clone $builder)->where('role', 'mahasiswa')->countAllResults();
        $totalGuru = (clone $builder)->where('role', 'guru')->countAllResults();
        $totalAdmin = (clone $builder)->where('role', 'admin')->countAllResults();

        // Ambil data presensi hari ini untuk Live Snapshot Feed
        $today = date('Y-m-d');
        $presensiHariIni = $db->table('presensi')
            ->select('presensi.*, users.nama, users.jurusan, users.role')
            ->join('users', 'users.id = presensi.user_id')
            ->where('presensi.tanggal', $today)
            ->orderBy('presensi.jam_masuk', 'DESC')
            ->get()
            ->getResultArray();

        $totalHadirHariIni = 0;
        $totalTerlambatHariIni = 0;
        $totalIzinSakitHariIni = 0;
        foreach ($presensiHariIni as $p) {
            if ($p['status'] === 'hadir') {
                $totalHadirHariIni++;
            } elseif ($p['status'] === 'terlambat') {
                $totalTerlambatHariIni++;
            } elseif (in_array($p['status'], ['izin', 'sakit'], true)) {
                $totalIzinSakitHariIni++;
            }
        }

        // Ambil data admin saat ini untuk modal profil
        $currentAdmin = $this->userModel->find(session()->get('id_user'));

        // Ambil ringkasan pengaturan sistem operasional
        $settings = SettingModel::getAllSettings();
        $config = config('Presensi') ?? new \Config\Presensi();
        $schoolRadius = (int) ($settings['school_radius'] ?? $config->schoolRadius);
        $geofenceActive = isset($settings['geofence_active']) ? ($settings['geofence_active'] === '1' || $settings['geofence_active'] === 'true') : $config->geofenceActive;
        $jamMasukMax = $settings['jam_masuk_max'] ?? $config->jamMasukMax;
        $jamPulangMin = $settings['jam_pulang_min'] ?? $config->jamPulangMin;
        $schoolName = $settings['school_name'] ?? $config->schoolName;

        $data = [
            'current_admin'         => $currentAdmin,
            'totalUsers'            => $totalUsers,
            'totalMahasiswa'        => $totalMahasiswa,
            'totalGuru'             => $totalGuru,
            'totalAdmin'            => $totalAdmin,
            'presensiHariIni'       => $presensiHariIni,
            'totalHadirHariIni'     => $totalHadirHariIni,
            'totalTerlambatHariIni' => $totalTerlambatHariIni,
            'totalIzinSakitHariIni' => $totalIzinSakitHariIni,
            'school_name'           => $schoolName,
            'school_radius'         => $schoolRadius,
            'geofence_active'       => $geofenceActive,
            'jam_masuk_max'         => $jamMasukMax,
            'jam_pulang_min'        => $jamPulangMin,
            'daftar_jurusan'        => $this->jurusanModel->getDaftarNama(),
            'daftar_universitas'    => $this->universitasModel->getDaftarNama(),
            'daftar_periode'        => $this->periodeModel->getDaftarPilihan(),
            'periode_aktif'         => $this->periodeModel->getPeriodeAktif(),
            'title'                 => 'Dashboard Administrator - SIPENSI SKAGATA'
        ];

        return view('admin/index', $data);
    }

    public function pengguna()
    {
        $db = Database::connect();
        $builder = $db->table('users');

        // Parameter pencarian & filter
        $keyword = trim((string) $this->request->getGet('keyword'));
        $roleFilter = $this->request->getGet('role');
        $jurusanFilter = $this->request->getGet('jurusan');
        $universitasFilter = $this->request->getGet('universitas');
        $periodeFilter = $this->request->getGet('periode');

        // Hitung statistik
        $totalMahasiswa = (clone $builder)->where('role', 'mahasiswa')->countAllResults();
        $totalGuru = (clone $builder)->where('role', 'guru')->countAllResults();

        // Terapkan filter query
        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('users.nama', $keyword)
                ->orLike('users.username', $keyword)
                ->groupEnd();
        }

        // Kecualikan akun administrator dari tabel daftar pengguna
        $builder->where('role !=', 'admin');

        if (!empty($roleFilter) && in_array($roleFilter, ['mahasiswa', 'guru'], true)) {
            $builder->where('users.role', $roleFilter);
        }

        if (!empty($jurusanFilter)) {
            $builder->where('users.jurusan', $jurusanFilter);
        }

        if (!empty($universitasFilter)) {
            $builder->where('users.universitas', $universitasFilter);
        }

        if (!empty($periodeFilter)) {
            $builder->where('users.periode_id', (int) $periodeFilter);
        }

        $users = $builder->select('users.*, periode.nama_periode AS nama_periode_relasi')
            ->join('periode', 'periode.id = users.periode_id', 'left')
            ->orderBy('users.role', 'ASC')
            ->orderBy('users.nama', 'ASC')
            ->get()
            ->getResultArray();

        // Ambil data admin saat ini untuk modal ubah profil
        $currentAdmin = $this->userModel->find(session()->get('id_user'));

        // Ambil daftar jurusan dinamis dari database + daftar standar
        $jurusanRaw = $db->table('users')
            ->select('jurusan')
            ->where('jurusan IS NOT NULL')
            ->where('jurusan !=', '')
            ->groupBy('jurusan')
            ->get()
            ->getResultArray();
        $jurusanFromDb = array_filter(array_column($jurusanRaw, 'jurusan'));
        $masterJurusan = $this->jurusanModel->getDaftarNama();
        $defaultJurusan = ['Informatika', 'PJOK', 'BK', 'TL', 'TO'];
        $daftarJurusan = array_values(array_unique(array_merge($defaultJurusan, $masterJurusan, $jurusanFromDb)));
        sort($daftarJurusan);

        // Ambil daftar universitas dinamis dari database
        $univRaw = $db->table('users')
            ->select('universitas')
            ->where('universitas IS NOT NULL')
            ->where('universitas !=', '')
            ->groupBy('universitas')
            ->get()
            ->getResultArray();
        $univFromDb = array_filter(array_column($univRaw, 'universitas'));
        $masterUniversitas = $this->universitasModel->getDaftarNama();
        $daftarUniversitas = array_values(array_unique(array_merge($masterUniversitas, $univFromDb)));
        sort($daftarUniversitas);

        // Ambil daftar periode dan periode aktif
        $daftarPeriode = $this->periodeModel->getDaftarPilihan();
        $periodeAktif = $this->periodeModel->getPeriodeAktif();

        $data = [
            'users'              => $users,
            'current_admin'      => $currentAdmin,
            'totalMahasiswa'     => $totalMahasiswa,
            'totalGuru'          => $totalGuru,
            'keyword'            => $keyword,
            'role_terpilih'      => $roleFilter,
            'jurusan_pilih'      => $jurusanFilter,
            'universitas_pilih'  => $universitasFilter,
            'periode_pilih'      => $periodeFilter,
            'daftar_jurusan'     => $daftarJurusan,
            'daftar_universitas' => $daftarUniversitas,
            'daftar_periode'     => $daftarPeriode,
            'periode_aktif'      => $periodeAktif,
            'title'              => 'Manajemen Pengguna - SIPENSI SKAGATA'
        ];

        return view('admin/pengguna', $data);
    }

    public function tambahUser()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|alpha_numeric|is_unique[users.username]',
            'nama'     => 'required|min_length[2]|max_length[100]',
            'role'     => 'required|in_list[mahasiswa,guru,admin]',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            $errors = implode(' ', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        $username    = trim((string) $this->request->getPost('username'));
        $nama        = trim(strip_tags((string) $this->request->getPost('nama')));
        $role        = (string) $this->request->getPost('role');
        $jurusan     = trim((string) $this->request->getPost('jurusan'));
        $universitas = trim((string) $this->request->getPost('universitas'));
        $periodeId   = $this->request->getPost('periode_id');
        $password    = (string) $this->request->getPost('password');

        if ($role === 'mahasiswa') {
            if (empty($periodeId)) {
                $periodeAktif = $this->periodeModel->getPeriodeAktif();
                $periodeId = $periodeAktif ? (int) $periodeAktif['id'] : null;
            } else {
                $periodeId = (int) $periodeId;
            }
        } else {
            $periodeId = null;
        }

        $this->userModel->insert([
            'username'    => $username,
            'nama'        => $nama,
            'role'        => $role,
            'jurusan'     => !empty($jurusan) ? $jurusan : null,
            'universitas' => ($role === 'mahasiswa' && !empty($universitas)) ? $universitas : null,
            'periode_id'  => $periodeId,
            'password'    => password_hash($password, PASSWORD_BCRYPT)
        ]);

        return redirect()->to('/admin/pengguna')->with('pesan', "Pengguna {$nama} ({$username}) berhasil ditambahkan!");
    }

    public function editUser()
    {
        $userId = $this->request->getPost('id');
        if (empty($userId) || !is_numeric($userId)) {
            return redirect()->back()->with('error', 'ID pengguna tidak valid!');
        }

        $existingUser = $this->userModel->find($userId);
        if (!$existingUser) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan!');
        }

        $rules = [
            'username' => "required|min_length[3]|max_length[50]|alpha_numeric|is_unique[users.username,id,{$userId}]",
            'nama'     => 'required|min_length[2]|max_length[100]',
            'role'     => 'required|in_list[mahasiswa,guru,admin]'
        ];

        if (!$this->validate($rules)) {
            $errors = implode(' ', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        $username    = trim((string) $this->request->getPost('username'));
        $nama        = trim(strip_tags((string) $this->request->getPost('nama')));
        $role        = (string) $this->request->getPost('role');
        $jurusan     = trim((string) $this->request->getPost('jurusan'));
        $universitas = trim((string) $this->request->getPost('universitas'));
        $periodeId   = $this->request->getPost('periode_id');
        $periodeVal  = ($role === 'mahasiswa' && !empty($periodeId)) ? (int) $periodeId : null;

        $this->userModel->update($userId, [
            'username'    => $username,
            'nama'        => $nama,
            'role'        => $role,
            'jurusan'     => !empty($jurusan) ? $jurusan : null,
            'universitas' => ($role === 'mahasiswa' && !empty($universitas)) ? $universitas : null,
            'periode_id'  => $periodeVal,
        ]);

        return redirect()->to('/admin/pengguna')->with('pesan', "Data pengguna {$nama} berhasil diperbarui!");
    }

    public function hapusUser()
    {
        $userId = $this->request->getPost('user_id');
        $currentAdminId = session()->get('id_user');

        if (empty($userId) || !is_numeric($userId)) {
            return redirect()->back()->with('error', 'ID pengguna tidak valid!');
        }

        if ((int) $userId === (int) $currentAdminId) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang aktif login!');
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan!');
        }

        // Hapus data presensi dan piket terkait terlebih dahulu
        $db = Database::connect();
        $db->table('presensi')->where('user_id', $userId)->delete();
        $db->table('piket_kbm')->where('user_id', $userId)->delete();

        // Hapus pengguna
        $this->userModel->delete($userId);

        return redirect()->to('/admin/pengguna')->with('pesan', "Pengguna {$user['nama']} ({$user['username']}) berhasil dihapus!");
    }

    public function resetPassword()
    {
        $userId = $this->request->getPost('user_id');
        $newPassword = $this->request->getPost('new_password');

        if (empty($userId) || !is_numeric($userId)) {
            return redirect()->back()->with('error', 'ID pengguna tidak valid!');
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan!');
        }

        $passwordToSet = !empty($newPassword) ? (string) $newPassword : 'password123';

        if (strlen($passwordToSet) < 6) {
            return redirect()->back()->with('error', 'Password minimal 6 karakter!');
        }

        $this->userModel->update($userId, [
            'password' => password_hash($passwordToSet, PASSWORD_BCRYPT)
        ]);

        return redirect()->to('/admin/pengguna')->with('pesan', "Password untuk pengguna {$user['nama']} berhasil direset!");
    }

    public function pengaturan()
    {
        $config = config('Presensi') ?? new \Config\Presensi();
        $settings = \App\Models\SettingModel::getAllSettings();

        $data = [
            'school_name'      => $settings['school_name'] ?? $config->schoolName,
            'school_latitude'  => (float) ($settings['school_latitude'] ?? $config->schoolLatitude),
            'school_longitude' => (float) ($settings['school_longitude'] ?? $config->schoolLongitude),
            'school_radius'    => (int) ($settings['school_radius'] ?? $config->schoolRadius),
            'jam_masuk_max'    => $settings['jam_masuk_max'] ?? $config->jamMasukMax,
            'jam_pulang_min'   => $settings['jam_pulang_min'] ?? $config->jamPulangMin,
            'geofence_active'  => isset($settings['geofence_active']) ? ($settings['geofence_active'] === '1' || $settings['geofence_active'] === 'true') : $config->geofenceActive,
            'title'            => 'Pengaturan Lokasi & Jam Presensi - Admin'
        ];

        return view('admin/pengaturan', $data);
    }

    public function simpanPengaturan()
    {
        $schoolName = trim(strip_tags((string) $this->request->getPost('school_name')));
        $lat = $this->request->getPost('school_latitude');
        $lng = $this->request->getPost('school_longitude');
        $radius = $this->request->getPost('school_radius');
        $jamMasuk = trim((string) $this->request->getPost('jam_masuk_max'));
        $jamPulang = trim((string) $this->request->getPost('jam_pulang_min'));
        $geofenceActive = $this->request->getPost('geofence_active') ? '1' : '0';

        // Validasi input
        if (empty($schoolName) || mb_strlen($schoolName) < 3) {
            return redirect()->back()->withInput()->with('error', 'Nama institusi/sekolah minimal 3 karakter!');
        }

        if (!is_numeric($lat) || (float) $lat < -90 || (float) $lat > 90) {
            return redirect()->back()->withInput()->with('error', 'Latitude harus berupa angka valid antara -90 dan 90!');
        }

        if (!is_numeric($lng) || (float) $lng < -180 || (float) $lng > 180) {
            return redirect()->back()->withInput()->with('error', 'Longitude harus berupa angka valid antara -180 dan 180!');
        }

        if (!is_numeric($radius) || (int) $radius < 10 || (int) $radius > 5000) {
            return redirect()->back()->withInput()->with('error', 'Radius harus berupa angka antara 10 sampai 5000 meter!');
        }

        // Format jam HH:MM atau HH:MM:SS
        if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/', $jamMasuk)) {
            return redirect()->back()->withInput()->with('error', 'Format Jam Masuk Maksimal tidak valid (HH:MM atau HH:MM:SS)!');
        }
        if (strlen($jamMasuk) === 5) {
            $jamMasuk .= ':00';
        }

        if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/', $jamPulang)) {
            return redirect()->back()->withInput()->with('error', 'Format Jam Pulang Minimal tidak valid (HH:MM atau HH:MM:SS)!');
        }
        if (strlen($jamPulang) === 5) {
            $jamPulang .= ':00';
        }

        // Simpan ke SettingModel
        \App\Models\SettingModel::setSetting('school_name', $schoolName);
        \App\Models\SettingModel::setSetting('school_latitude', (string) ((float) $lat));
        \App\Models\SettingModel::setSetting('school_longitude', (string) ((float) $lng));
        \App\Models\SettingModel::setSetting('school_radius', (string) ((int) $radius));
        \App\Models\SettingModel::setSetting('jam_masuk_max', $jamMasuk);
        \App\Models\SettingModel::setSetting('jam_pulang_min', $jamPulang);
        \App\Models\SettingModel::setSetting('geofence_active', $geofenceActive);

        return redirect()->to('/admin/pengaturan')->with('pesan', 'Pengaturan lokasi presensi dan jam kerja berhasil disimpan!');
    }

    public function jurusan()
    {
        $keyword = trim((string) $this->request->getGet('keyword'));
        $jurusanList = $this->jurusanModel->getJurusanWithUserCount($keyword);

        // Statistik
        $totalJurusan = $this->jurusanModel->countAllResults();
        $db = Database::connect();
        $totalMahasiswa = $db->table('users')->where('role', 'mahasiswa')->where('jurusan IS NOT NULL')->where('jurusan !=', '')->countAllResults();

        $currentAdmin = $this->userModel->find(session()->get('id_user'));

        $data = [
            'title'           => 'Master Data Jurusan - SIPENSI SKAGATA',
            'current_admin'   => $currentAdmin,
            'jurusan_list'    => $jurusanList,
            'total_jurusan'   => $totalJurusan,
            'total_mahasiswa' => $totalMahasiswa,
            'keyword'         => $keyword,
        ];

        return view('admin/jurusan', $data);
    }

    public function tambahJurusan()
    {
        $rules = [
            'kode_jurusan' => 'required|min_length[2]|max_length[20]|is_unique[jurusan.kode_jurusan]',
            'nama_jurusan' => 'required|min_length[3]|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            $errors = implode(' ', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        $kode = strtoupper(trim((string) $this->request->getPost('kode_jurusan')));
        $nama = trim(strip_tags((string) $this->request->getPost('nama_jurusan')));
        $deskripsi = trim(strip_tags((string) $this->request->getPost('deskripsi')));

        $this->jurusanModel->insert([
            'kode_jurusan' => $kode,
            'nama_jurusan' => $nama,
            'deskripsi'    => !empty($deskripsi) ? $deskripsi : null,
        ]);

        return redirect()->to('/admin/jurusan')->with('pesan', "Jurusan {$nama} ({$kode}) berhasil ditambahkan!");
    }

    public function editJurusan()
    {
        $id = $this->request->getPost('id');
        if (empty($id) || !is_numeric($id)) {
            return redirect()->back()->with('error', 'ID jurusan tidak valid!');
        }

        $existing = $this->jurusanModel->find($id);
        if (!$existing) {
            return redirect()->back()->with('error', 'Data jurusan tidak ditemukan!');
        }

        $rules = [
            'kode_jurusan' => "required|min_length[2]|max_length[20]|is_unique[jurusan.kode_jurusan,id,{$id}]",
            'nama_jurusan' => 'required|min_length[3]|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            $errors = implode(' ', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        $kode = strtoupper(trim((string) $this->request->getPost('kode_jurusan')));
        $nama = trim(strip_tags((string) $this->request->getPost('nama_jurusan')));
        $deskripsi = trim(strip_tags((string) $this->request->getPost('deskripsi')));

        $this->jurusanModel->update($id, [
            'kode_jurusan' => $kode,
            'nama_jurusan' => $nama,
            'deskripsi'    => !empty($deskripsi) ? $deskripsi : null,
        ]);

        // Jika nama jurusan diubah, perbarui nilai pada pengguna terkait agar tetap konsisten
        if ($existing['nama_jurusan'] !== $nama) {
            $db = Database::connect();
            $db->table('users')->where('jurusan', $existing['nama_jurusan'])->update(['jurusan' => $nama]);
        }

        return redirect()->to('/admin/jurusan')->with('pesan', "Perubahan jurusan {$nama} ({$kode}) berhasil disimpan!");
    }

    public function hapusJurusan()
    {
        $id = $this->request->getPost('id');
        if (empty($id) || !is_numeric($id)) {
            return redirect()->back()->with('error', 'ID jurusan tidak valid!');
        }

        $existing = $this->jurusanModel->find($id);
        if (!$existing) {
            return redirect()->back()->with('error', 'Data jurusan tidak ditemukan!');
        }

        // Cek apakah masih ada pengguna/mahasiswa yang menggunakan jurusan ini
        $countPengguna = $this->jurusanModel->countPenggunaByJurusan((int) $id);
        if ($countPengguna > 0) {
            return redirect()->to('/admin/jurusan')->with('error', "Jurusan '{$existing['nama_jurusan']}' tidak dapat dihapus karena masih digunakan oleh {$countPengguna} mahasiswa/pengguna. Silakan alihkan atau ubah jurusan pengguna terkait terlebih dahulu!");
        }

        $this->jurusanModel->delete($id);

        return redirect()->to('/admin/jurusan')->with('pesan', "Jurusan {$existing['nama_jurusan']} ({$existing['kode_jurusan']}) berhasil dihapus!");
    }

    public function universitas()
    {
        $keyword = trim((string) $this->request->getGet('keyword'));
        $universitasList = $this->universitasModel->getUniversitasWithUserCount($keyword);

        // Statistik
        $totalUniversitas = $this->universitasModel->countAllResults();
        $db = Database::connect();
        $totalMahasiswa = $db->table('users')->where('role', 'mahasiswa')->where('universitas IS NOT NULL')->where('universitas !=', '')->countAllResults();

        $currentAdmin = $this->userModel->find(session()->get('id_user'));

        $data = [
            'title'             => 'Master Data Asal Universitas - SIPENSI SKAGATA',
            'current_admin'     => $currentAdmin,
            'universitas_list'  => $universitasList,
            'total_universitas' => $totalUniversitas,
            'total_mahasiswa'   => $totalMahasiswa,
            'keyword'           => $keyword,
        ];

        return view('admin/universitas', $data);
    }

    public function tambahUniversitas()
    {
        $rules = [
            'kode_universitas' => 'required|min_length[2]|max_length[20]|is_unique[universitas.kode_universitas]',
            'nama_universitas' => 'required|min_length[3]|max_length[150]',
        ];

        if (!$this->validate($rules)) {
            $errors = implode(' ', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        $kode    = strtoupper(trim((string) $this->request->getPost('kode_universitas')));
        $nama    = trim(strip_tags((string) $this->request->getPost('nama_universitas')));
        $alamat  = trim(strip_tags((string) $this->request->getPost('alamat')));
        $telepon = trim(strip_tags((string) $this->request->getPost('telepon')));

        $this->universitasModel->insert([
            'kode_universitas' => $kode,
            'nama_universitas' => $nama,
            'alamat'           => !empty($alamat) ? $alamat : null,
            'telepon'          => !empty($telepon) ? $telepon : null,
        ]);

        return redirect()->to('/admin/universitas')->with('pesan', "Universitas {$nama} ({$kode}) berhasil ditambahkan!");
    }

    public function editUniversitas()
    {
        $id = $this->request->getPost('id');
        if (empty($id) || !is_numeric($id)) {
            return redirect()->back()->with('error', 'ID universitas tidak valid!');
        }

        $existing = $this->universitasModel->find($id);
        if (!$existing) {
            return redirect()->back()->with('error', 'Data universitas tidak ditemukan!');
        }

        $rules = [
            'kode_universitas' => "required|min_length[2]|max_length[20]|is_unique[universitas.kode_universitas,id,{$id}]",
            'nama_universitas' => 'required|min_length[3]|max_length[150]',
        ];

        if (!$this->validate($rules)) {
            $errors = implode(' ', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        $kode    = strtoupper(trim((string) $this->request->getPost('kode_universitas')));
        $nama    = trim(strip_tags((string) $this->request->getPost('nama_universitas')));
        $alamat  = trim(strip_tags((string) $this->request->getPost('alamat')));
        $telepon = trim(strip_tags((string) $this->request->getPost('telepon')));

        $this->universitasModel->update($id, [
            'kode_universitas' => $kode,
            'nama_universitas' => $nama,
            'alamat'           => !empty($alamat) ? $alamat : null,
            'telepon'          => !empty($telepon) ? $telepon : null,
        ]);

        // Jika nama universitas berubah, sinkronkan data pengguna terkait
        if ($existing['nama_universitas'] !== $nama) {
            $db = Database::connect();
            $db->table('users')->where('universitas', $existing['nama_universitas'])->update(['universitas' => $nama]);
        }

        return redirect()->to('/admin/universitas')->with('pesan', "Perubahan universitas {$nama} ({$kode}) berhasil disimpan!");
    }

    public function hapusUniversitas()
    {
        $id = $this->request->getPost('id');
        if (empty($id) || !is_numeric($id)) {
            return redirect()->back()->with('error', 'ID universitas tidak valid!');
        }

        $existing = $this->universitasModel->find($id);
        if (!$existing) {
            return redirect()->back()->with('error', 'Data universitas tidak ditemukan!');
        }

        // Cek apakah masih ada mahasiswa yang menggunakan universitas ini
        $countPengguna = $this->universitasModel->countPenggunaByUniversitas((int) $id);
        if ($countPengguna > 0) {
            return redirect()->to('/admin/universitas')->with('error', "Universitas '{$existing['nama_universitas']}' tidak dapat dihapus karena masih digunakan oleh {$countPengguna} mahasiswa. Silakan alihkan data mahasiswa terlebih dahulu!");
        }

        $this->universitasModel->delete($id);

        return redirect()->to('/admin/universitas')->with('pesan', "Universitas {$existing['nama_universitas']} ({$existing['kode_universitas']}) berhasil dihapus!");
    }

    public function periode()
    {
        $keyword = trim((string) $this->request->getGet('keyword'));
        $periodeList = $this->periodeModel->getPeriodeWithStats($keyword);

        $totalPeriode = $this->periodeModel->countAllResults();
        $periodeAktif = $this->periodeModel->getPeriodeAktif();
        $totalMahasiswaAktif = $periodeAktif ? $this->periodeModel->countPenggunaByPeriode((int) $periodeAktif['id']) : 0;
        $totalMahasiswa = $this->userModel->where('role', 'mahasiswa')->countAllResults();

        $currentAdmin = $this->userModel->find(session()->get('id_user'));

        $data = [
            'title'                 => 'Master Data Periode PPL / PK - SIPENSI SKAGATA',
            'current_admin'         => $currentAdmin,
            'periode_list'          => $periodeList,
            'total_periode'         => $totalPeriode,
            'periode_aktif'         => $periodeAktif,
            'total_mahasiswa_aktif' => $totalMahasiswaAktif,
            'total_mahasiswa'       => $totalMahasiswa,
            'keyword'               => $keyword,
        ];

        return view('admin/periode', $data);
    }

    public function tambahPeriode()
    {
        $rules = [
            'nama_periode'    => 'required|min_length[3]|max_length[100]',
            'tahun_ajaran'    => 'required|min_length[4]|max_length[20]',
            'semester'        => 'required|in_list[Ganjil,Genap]',
            'tanggal_mulai'   => 'required|valid_date[Y-m-d]',
            'tanggal_selesai' => 'required|valid_date[Y-m-d]',
        ];

        if (!$this->validate($rules)) {
            $errors = implode(' ', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        $nama           = trim(strip_tags((string) $this->request->getPost('nama_periode')));
        $tahunAjaran    = trim(strip_tags((string) $this->request->getPost('tahun_ajaran')));
        $semester       = (string) $this->request->getPost('semester');
        $tanggalMulai   = (string) $this->request->getPost('tanggal_mulai');
        $tanggalSelesai = (string) $this->request->getPost('tanggal_selesai');
        $isAktif        = $this->request->getPost('is_aktif') ? 1 : 0;
        $keterangan     = trim(strip_tags((string) $this->request->getPost('keterangan')));

        if (strtotime($tanggalSelesai) < strtotime($tanggalMulai)) {
            return redirect()->back()->withInput()->with('error', 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai!');
        }

        $newId = $this->periodeModel->insert([
            'nama_periode'    => $nama,
            'tahun_ajaran'    => $tahunAjaran,
            'semester'        => $semester,
            'tanggal_mulai'   => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'is_aktif'        => 0,
            'keterangan'      => !empty($keterangan) ? $keterangan : null,
        ]);

        if ($isAktif === 1 && $newId) {
            $this->periodeModel->setAktif((int) $newId);
        }

        return redirect()->to('/admin/periode')->with('pesan', "Periode '{$nama}' berhasil ditambahkan!");
    }

    public function editPeriode()
    {
        $id = $this->request->getPost('id');
        if (empty($id) || !is_numeric($id)) {
            return redirect()->back()->with('error', 'ID periode tidak valid!');
        }

        $existing = $this->periodeModel->find($id);
        if (!$existing) {
            return redirect()->back()->with('error', 'Data periode tidak ditemukan!');
        }

        $rules = [
            'nama_periode'    => 'required|min_length[3]|max_length[100]',
            'tahun_ajaran'    => 'required|min_length[4]|max_length[20]',
            'semester'        => 'required|in_list[Ganjil,Genap]',
            'tanggal_mulai'   => 'required|valid_date[Y-m-d]',
            'tanggal_selesai' => 'required|valid_date[Y-m-d]',
        ];

        if (!$this->validate($rules)) {
            $errors = implode(' ', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        $nama           = trim(strip_tags((string) $this->request->getPost('nama_periode')));
        $tahunAjaran    = trim(strip_tags((string) $this->request->getPost('tahun_ajaran')));
        $semester       = (string) $this->request->getPost('semester');
        $tanggalMulai   = (string) $this->request->getPost('tanggal_mulai');
        $tanggalSelesai = (string) $this->request->getPost('tanggal_selesai');
        $isAktif        = $this->request->getPost('is_aktif') ? 1 : 0;
        $keterangan     = trim(strip_tags((string) $this->request->getPost('keterangan')));

        if (strtotime($tanggalSelesai) < strtotime($tanggalMulai)) {
            return redirect()->back()->withInput()->with('error', 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai!');
        }

        $this->periodeModel->update($id, [
            'nama_periode'    => $nama,
            'tahun_ajaran'    => $tahunAjaran,
            'semester'        => $semester,
            'tanggal_mulai'   => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'keterangan'      => !empty($keterangan) ? $keterangan : null,
        ]);

        if ($isAktif === 1) {
            $this->periodeModel->setAktif((int) $id);
        }

        return redirect()->to('/admin/periode')->with('pesan', "Perubahan periode '{$nama}' berhasil disimpan!");
    }

    public function setAktifPeriode()
    {
        $id = $this->request->getPost('id');
        if (empty($id) || !is_numeric($id)) {
            return redirect()->back()->with('error', 'ID periode tidak valid!');
        }

        $existing = $this->periodeModel->find($id);
        if (!$existing) {
            return redirect()->back()->with('error', 'Data periode tidak ditemukan!');
        }

        $this->periodeModel->setAktif((int) $id);

        return redirect()->to('/admin/periode')->with('pesan', "Periode '{$existing['nama_periode']}' berhasil ditetapkan sebagai periode aktif saat ini!");
    }

    public function hapusPeriode()
    {
        $id = $this->request->getPost('id');
        if (empty($id) || !is_numeric($id)) {
            return redirect()->back()->with('error', 'ID periode tidak valid!');
        }

        $existing = $this->periodeModel->find($id);
        if (!$existing) {
            return redirect()->back()->with('error', 'Data periode tidak ditemukan!');
        }

        // Cek apakah masih ada mahasiswa yang menggunakan periode ini
        $countPengguna = $this->periodeModel->countPenggunaByPeriode((int) $id);
        if ($countPengguna > 0) {
            return redirect()->to('/admin/periode')->with('error', "Periode '{$existing['nama_periode']}' tidak dapat dihapus karena masih digunakan oleh {$countPengguna} mahasiswa praktikan. Silakan alihkan data mahasiswa terlebih dahulu!");
        }

        $this->periodeModel->delete($id);

        return redirect()->to('/admin/periode')->with('pesan', "Periode '{$existing['nama_periode']}' berhasil dihapus!");
    }

    public function updateProfil()
    {
        $userId = session()->get('id_user');
        $user = $this->userModel->find($userId);
        if (!$user || $user['role'] !== 'admin') {
            return redirect()->back()->with('error', 'Akses tidak sah atau akun bukan administrator!');
        }

        $nama = trim(strip_tags((string) $this->request->getPost('nama')));
        $username = trim((string) $this->request->getPost('username'));
        $passwordBaru = (string) $this->request->getPost('password_baru');
        $passwordLama = (string) $this->request->getPost('password_lama');

        if (empty($nama) || mb_strlen($nama) < 2) {
            return redirect()->back()->with('error', 'Nama lengkap minimal 2 karakter!');
        }

        if (empty($username) || mb_strlen($username) < 3) {
            return redirect()->back()->with('error', 'Username minimal 3 karakter!');
        }

        // Cek jika username diganti, pastikan tidak bentrok dengan akun lain
        if ($username !== $user['username']) {
            $cek = $this->userModel->where('username', $username)->where('id !=', $userId)->first();
            if ($cek) {
                return redirect()->back()->with('error', "Username '{$username}' sudah digunakan oleh pengguna lain!");
            }
        }

        $updateData = [
            'nama'     => $nama,
            'username' => $username
        ];

        // Jika ingin ganti password
        if (!empty($passwordBaru)) {
            if (strlen($passwordBaru) < 6) {
                return redirect()->back()->with('error', 'Password baru minimal 6 karakter!');
            }
            if (empty($passwordLama)) {
                return redirect()->back()->with('error', 'Masukkan password saat ini untuk memverifikasi perubahan password!');
            }
            if (!password_verify($passwordLama, $user['password'])) {
                return redirect()->back()->with('error', 'Password saat ini yang Anda masukkan salah!');
            }
            $updateData['password'] = password_hash($passwordBaru, PASSWORD_BCRYPT);
        }

        $this->userModel->update($userId, $updateData);

        // Update data session
        session()->set([
            'nama'     => $nama,
            'username' => $username
        ]);

        return redirect()->to('/admin')->with('pesan', 'Profil administrator dan kata sandi berhasil diperbarui!');
    }
}
