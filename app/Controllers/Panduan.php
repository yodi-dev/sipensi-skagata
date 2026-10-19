<?php

namespace App\Controllers;

use Config\Presensi as PresensiConfig;
use App\Models\SettingModel;
use App\Models\UserModel;

class Panduan extends BaseController
{
    protected PresensiConfig $presensiConfig;
    protected SettingModel $settingModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->presensiConfig = new PresensiConfig();
        $this->settingModel   = new SettingModel();
        $this->userModel      = new UserModel();
    }

    /**
     * Menampilkan halaman panduan pengguna terkunci sesuai role aktif
     */
    public function index()
    {
        $role = session()->get('role') ?? 'mahasiswa';
        if ($role === 'guru_pamong') {
            $role = 'guru';
        }

        // Jika admin, izinkan intip role lain via query param ?role= untuk keperluan supervisi
        $requestedRole = $this->request->getGet('role');
        if ($role === 'admin' && !empty($requestedRole) && in_array($requestedRole, ['admin', 'guru', 'gtt', 'mahasiswa'], true)) {
            $activeRole = $requestedRole;
        } else {
            // Untuk non-admin, DIKUNCI KETAT hanya sesuai role masing-masing
            $activeRole = $role;
        }

        // Ambil data konfigurasi presensi dinamis
        $settingData = [
            'school_name'     => $this->settingModel->getSetting('school_name', $this->presensiConfig->schoolName),
            'school_radius'   => (int) $this->settingModel->getSetting('school_radius', $this->presensiConfig->schoolRadius),
            'work_start'      => $this->settingModel->getSetting('work_start', $this->presensiConfig->workStart),
            'work_end'        => $this->settingModel->getSetting('work_end', $this->presensiConfig->workEnd),
            'geofence_active' => (bool) $this->settingModel->getSetting('geofence_active', $this->presensiConfig->geofenceActive),
            'school_lat'      => $this->settingModel->getSetting('school_latitude', $this->presensiConfig->schoolLatitude),
            'school_lng'      => $this->settingModel->getSetting('school_longitude', $this->presensiConfig->schoolLongitude),
        ];

        // Format label role
        $roleTitles = [
            'mahasiswa' => 'Mahasiswa Praktikan (PPL / PK)',
            'guru'      => 'Guru Pamong (Pembimbing Lapangan)',
            'gtt'       => 'Guru Tidak Tetap (GTT)',
            'admin'     => 'Administrator Sistem'
        ];

        $data = [
            'title'       => 'Pusat Panduan & Bantuan - SIPENSI SKAGATA',
            'userRole'    => $role,
            'activeRole'  => $activeRole,
            'roleTitle'   => $roleTitles[$activeRole] ?? 'Pengguna',
            'config'      => $settingData,
            'userName'    => session()->get('nama') ?? 'Pengguna',
        ];

        return view('panduan/index', $data);
    }
}

