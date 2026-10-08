<?php

namespace App\Controllers;

use App\Models\GuruPamongModel;
use App\Models\JurusanModel;
use App\Models\PiketModel;
use App\Models\PresensiModel;
use Config\Database;

class Guru extends BaseController
{
    protected GuruPamongModel $guruPamongModel;

    public function __construct()
    {
        $this->guruPamongModel = new GuruPamongModel();
    }

    public function index()
    {
        $db      = Database::connect();
        $builder = $db->table('users');

        $guruId = session()->get('id_user');
        $isTeacherSession = !empty($guruId) && session()->get('role') === 'guru';
        
        if ($isTeacherSession) {
            $assignedJurusans = $this->guruPamongModel->getJurusanByGuru((int) $guruId);
        } else {
            $jurusanModel = new JurusanModel();
            $assignedJurusans = $jurusanModel->getDaftarNama();
        }

        // Ambil input filter dari URL dengan validasi format
        $tanggalFilter = $this->request->getGet('tanggal');
        $jurusan       = $this->request->getGet('jurusan');

        $tanggalPilih = (is_string($tanggalFilter) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalFilter))
            ? $tanggalFilter
            : date('Y-m-d');

        // 1. Pilih kolom mahasiswa & presensi
        $builder->select('users.id, users.nama, users.jurusan, presensi.id as presensi_id, presensi.status, presensi.jam_masuk, presensi.jam_keluar, presensi.keterangan, presensi.latitude, presensi.longitude, presensi.bukti_surat');

        // 2. JOIN dengan tabel presensi dengan parameterized escaping (aman dari SQL Injection)
        $builder->join('presensi', 'presensi.user_id = users.id AND presensi.tanggal = ' . $db->escape($tanggalPilih), 'left');

        // 3. Filter dasar: Hanya role mahasiswa
        $builder->where('users.role', 'mahasiswa');

        // 4. ISOLASI GURU PAMONG: Batasi hanya pada mahasiswa jurusan bimbingannya
        if ($isTeacherSession && empty($assignedJurusans)) {
            // Guru belum memiliki pemetaan jurusan: kembalikan list kosong demi proteksi privasi
            $presensiData = [];
        } else {
            if (!empty($jurusan) && in_array($jurusan, $assignedJurusans, true)) {
                $builder->where('users.jurusan', $jurusan);
            } elseif (!empty($assignedJurusans)) {
                $builder->whereIn('users.jurusan', $assignedJurusans);
            }
            $presensiData = $builder->orderBy('users.nama', 'ASC')->get()->getResultArray();
        }

        $data = [
            'tanggal'           => $tanggalPilih,
            'presensi'          => $presensiData,
            'jurusan_terpilih'  => $jurusan,
            'daftar_jurusan'    => $assignedJurusans,
            'assigned_jurusans' => $assignedJurusans,
            'title'             => 'Dashboard Guru Pamong - Presensi PPL'
        ];

        return view('guru/index', $data);
    }

    public function update_status($userId = null, $status = null)
    {
        // Ambil parameter dari POST (didukung fallback GET untuk keamanan transisi)
        $userId  = $this->request->getPost('user_id') ?? $userId;
        $status  = $this->request->getPost('status') ?? $status;
        $tanggal = $this->request->getPost('tanggal') ?? $this->request->getGet('tgl');

        $tanggalPilih = (is_string($tanggal) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal))
            ? $tanggal
            : date('Y-m-d');

        $allowedStatus = ['hadir', 'terlambat', 'izin', 'sakit', 'alpa'];
        if (!in_array($status, $allowedStatus, true)) {
            return redirect()->back()->with('error', 'Status presensi tidak valid!');
        }

        if (empty($userId) || !is_numeric($userId)) {
            return redirect()->back()->with('error', 'ID Mahasiswa tidak valid!');
        }

        // ISOLASI GURU PAMONG: Validasi hak kepemilikan/pengawasan terhadap mahasiswa
        $guruId = session()->get('id_user');
        if (!empty($guruId) && session()->get('role') === 'guru') {
            if (!$this->guruPamongModel->isMahasiswaSupervisedByGuru((int) $guruId, (int) $userId)) {
                return redirect()->back()->with('error', 'Akses ditolak! Anda hanya berwenang mengelola presensi mahasiswa pada jurusan bimbingan Anda.');
            }
        }

        $presensiModel = new PresensiModel();
        $existing = $presensiModel->where(['user_id' => (int) $userId, 'tanggal' => $tanggalPilih])->first();

        $data = [
            'user_id'    => (int) $userId,
            'tanggal'    => $tanggalPilih,
            'status'     => $status,
            'keterangan' => 'Diupdate manual oleh Guru (' . (session()->get('nama') ?? 'Guru Pamong') . ')'
        ];

        if ($existing) {
            $presensiModel->update($existing['id'], $data);
        } else {
            $presensiModel->insert($data);
        }

        return redirect()->back()->with('pesan', 'Status presensi berhasil diupdate!');
    }

    public function laporan()
    {
        $bulanInput = $this->request->getGet('bulan');
        $tahunInput = $this->request->getGet('tahun');

        $bulan = (is_string($bulanInput) && preg_match('/^(0[1-9]|1[0-2])$/', $bulanInput)) ? $bulanInput : date('m');
        $tahun = (is_string($tahunInput) && preg_match('/^\d{4}$/', $tahunInput)) ? $tahunInput : date('Y');

        $guruId = session()->get('id_user');
        $isTeacherSession = !empty($guruId) && session()->get('role') === 'guru';

        if ($isTeacherSession) {
            $assignedJurusans = $this->guruPamongModel->getJurusanByGuru((int) $guruId);
        } else {
            $jurusanModel = new JurusanModel();
            $assignedJurusans = $jurusanModel->getDaftarNama();
        }

        $db      = Database::connect();
        $builder = $db->table('users');

        $bulanEscaped = (int) $bulan;
        $tahunEscaped = (int) $tahun;
        $startDate    = sprintf('%04d-%02d-01', $tahunEscaped, $bulanEscaped);
        $endDate      = date('Y-m-t', strtotime($startDate));

        if ($isTeacherSession && empty($assignedJurusans)) {
            $laporan = [];
        } else {
            $builder->select("
                    users.id, 
                    users.nama,
                    users.jurusan,
                    SUM(CASE WHEN presensi.status = 'hadir' THEN 1 ELSE 0 END) as total_hadir,
                    SUM(CASE WHEN presensi.status = 'terlambat' THEN 1 ELSE 0 END) as total_terlambat,
                    SUM(CASE WHEN presensi.status = 'izin' THEN 1 ELSE 0 END) as total_izin,
                    SUM(CASE WHEN presensi.status = 'sakit' THEN 1 ELSE 0 END) as total_sakit,
                    SUM(CASE WHEN presensi.status = 'alpa' THEN 1 ELSE 0 END) as total_alpa
                ")
                ->join('presensi', "presensi.user_id = users.id AND presensi.tanggal >= '{$startDate}' AND presensi.tanggal <= '{$endDate}'", 'left')
                ->where('users.role', 'mahasiswa');

            if (!empty($assignedJurusans)) {
                $builder->whereIn('users.jurusan', $assignedJurusans);
            }

            $laporan = $builder->groupBy('users.id')
                ->orderBy('users.nama', 'ASC')
                ->get()
                ->getResultArray();
        }

        $data = [
            'laporan'           => $laporan,
            'bulan_pilih'       => $bulan,
            'tahun_pilih'       => $tahun,
            'assigned_jurusans' => $assignedJurusans,
            'title'             => 'Laporan Bulanan - Presensi PPL'
        ];

        return view('guru/laporan', $data);
    }

    public function exportExcel()
    {
        $bulanInput = $this->request->getGet('bulan');
        $tahunInput = $this->request->getGet('tahun');

        $bulan = (is_string($bulanInput) && preg_match('/^(0[1-9]|1[0-2])$/', $bulanInput)) ? $bulanInput : date('m');
        $tahun = (is_string($tahunInput) && preg_match('/^\d{4}$/', $tahunInput)) ? $tahunInput : date('Y');

        $guruId = session()->get('id_user');
        $isTeacherSession = !empty($guruId) && session()->get('role') === 'guru';

        if ($isTeacherSession) {
            $assignedJurusans = $this->guruPamongModel->getJurusanByGuru((int) $guruId);
        } else {
            $jurusanModel = new JurusanModel();
            $assignedJurusans = $jurusanModel->getDaftarNama();
        }

        $db      = Database::connect();
        $builder = $db->table('users');

        $bulanEscaped = (int) $bulan;
        $tahunEscaped = (int) $tahun;
        $startDate    = sprintf('%04d-%02d-01', $tahunEscaped, $bulanEscaped);
        $endDate      = date('Y-m-t', strtotime($startDate));

        if ($isTeacherSession && empty($assignedJurusans)) {
            $laporan = [];
        } else {
            $builder->select("
                    users.id, 
                    users.nama,
                    users.jurusan,
                    SUM(CASE WHEN presensi.status = 'hadir' THEN 1 ELSE 0 END) as total_hadir,
                    SUM(CASE WHEN presensi.status = 'terlambat' THEN 1 ELSE 0 END) as total_terlambat,
                    SUM(CASE WHEN presensi.status = 'izin' THEN 1 ELSE 0 END) as total_izin,
                    SUM(CASE WHEN presensi.status = 'sakit' THEN 1 ELSE 0 END) as total_sakit,
                    SUM(CASE WHEN presensi.status = 'alpa' THEN 1 ELSE 0 END) as total_alpa
                ")
                ->join('presensi', "presensi.user_id = users.id AND presensi.tanggal >= '{$startDate}' AND presensi.tanggal <= '{$endDate}'", 'left')
                ->where('users.role', 'mahasiswa');

            if (!empty($assignedJurusans)) {
                $builder->whereIn('users.jurusan', $assignedJurusans);
            }

            $laporan = $builder->groupBy('users.id')
                ->orderBy('users.nama', 'ASC')
                ->get()
                ->getResultArray();
        }

        $namaBulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April',   '05' => 'Mei',      '06' => 'Juni',
            '07' => 'Juli',    '08' => 'Agustus',  '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        $labelBulan = $namaBulan[$bulan] ?? $bulan;
        $namaGuru   = session()->get('nama') ?? 'Guru Pamong';
        $labelJurusan = !empty($assignedJurusans) ? implode(', ', $assignedJurusans) : 'Semua Jurusan';

        // Render HTML Spreadsheet
        $output = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        $output .= '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        $output .= '<style>
            table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; }
            th, td { border: 1px solid #000000; padding: 6px 10px; text-align: center; }
            th { background-color: #0f5132; color: #ffffff; font-weight: bold; }
            .sub-header { background-color: #f2f2f2; color: #000000; }
            .text-left { text-align: left; }
            .title { font-size: 16px; font-weight: bold; text-align: center; border: none; }
            .subtitle { font-size: 12px; text-align: center; border: none; margin-bottom: 5px; }
        </style></head><body>';
        $output .= '<table>';
        $output .= '<tr><td colspan="8" class="title">REKAPITULASI LAPORAN PRESENSI MAHASISWA PPL</td></tr>';
        $output .= "<tr><td colspan=\"8\" class=\"subtitle\">Guru Pamong: " . htmlspecialchars($namaGuru, ENT_QUOTES, 'UTF-8') . " | Jurusan Bimbingan: " . htmlspecialchars($labelJurusan, ENT_QUOTES, 'UTF-8') . "</td></tr>";
        $output .= "<tr><td colspan=\"8\" class=\"subtitle\">Periode: {$labelBulan} {$tahun}</td></tr>";
        $output .= '<tr><td colspan="8" style="border:none;"></td></tr>';
        $output .= '<tr>
            <th rowspan="2" style="vertical-align:middle;">No</th>
            <th rowspan="2" style="vertical-align:middle;" class="text-left">Nama Mahasiswa</th>
            <th rowspan="2" style="vertical-align:middle;">Jurusan</th>
            <th colspan="5">Total Kehadiran</th>
        </tr>';
        $output .= '<tr class="sub-header">
            <th>Hadir</th>
            <th>Terlambat</th>
            <th>Izin</th>
            <th>Sakit</th>
            <th>Alpa</th>
        </tr>';

        if (empty($laporan)) {
            $output .= '<tr><td colspan="8">Tidak ada data presensi pada periode ini.</td></tr>';
        } else {
            foreach ($laporan as $idx => $row) {
                $no = $idx + 1;
                $nama = htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8');
                $jurusan = htmlspecialchars($row['jurusan'] ?? '-', ENT_QUOTES, 'UTF-8');
                $output .= "<tr>
                    <td>{$no}</td>
                    <td class=\"text-left\">{$nama}</td>
                    <td>{$jurusan}</td>
                    <td>{$row['total_hadir']}</td>
                    <td>{$row['total_terlambat']}</td>
                    <td>{$row['total_izin']}</td>
                    <td>{$row['total_sakit']}</td>
                    <td>{$row['total_alpa']}</td>
                </tr>";
            }
        }

        $output .= '</table></body></html>';

        $fileName = "laporan_presensi_{$bulan}_{$tahun}.xls";

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($output);
    }

    public function laporanPiket()
    {
        $piketModel = new PiketModel();

        $guruId = session()->get('id_user');
        $isTeacherSession = !empty($guruId) && session()->get('role') === 'guru';

        if ($isTeacherSession) {
            $assignedJurusans = $this->guruPamongModel->getJurusanByGuru((int) $guruId);
        } else {
            $jurusanModel = new JurusanModel();
            $assignedJurusans = $jurusanModel->getDaftarNama();
        }

        $tanggalFilter = $this->request->getGet('tanggal');
        $tanggalPilih = (is_string($tanggalFilter) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalFilter))
            ? $tanggalFilter
            : date('Y-m-d');

        $dataPiket = (!empty($assignedJurusans) || !$isTeacherSession)
            ? $piketModel->getPiketWithFilter($tanggalPilih, $assignedJurusans)
            : [];

        $data = [
            'tanggal'           => $tanggalPilih,
            'dataPiket'         => $dataPiket,
            'assigned_jurusans' => $assignedJurusans,
            'title'             => 'Laporan Piket KBM - Presensi PPL'
        ];

        return view('guru/laporan_piket', $data);
    }
}
