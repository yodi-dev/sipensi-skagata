<?php

namespace App\Models;

use CodeIgniter\Model;

class PeriodeModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'periode';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'nama_periode',
        'tahun_ajaran',
        'semester',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_aktif',
        'keterangan',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'nama_periode'    => 'required|min_length[3]|max_length[100]',
        'tahun_ajaran'    => 'required|min_length[4]|max_length[20]',
        'semester'        => 'required|in_list[Ganjil,Genap]',
        'tanggal_mulai'   => 'required|valid_date[Y-m-d]',
        'tanggal_selesai' => 'required|valid_date[Y-m-d]',
    ];

    protected $validationMessages = [
        'nama_periode' => [
            'required'   => 'Nama periode wajib diisi.',
            'min_length' => 'Nama periode minimal 3 karakter.',
            'max_length' => 'Nama periode maksimal 100 karakter.',
        ],
        'tahun_ajaran' => [
            'required'   => 'Tahun ajaran wajib diisi (contoh: 2026/2027).',
            'min_length' => 'Tahun ajaran minimal 4 karakter.',
            'max_length' => 'Tahun ajaran maksimal 20 karakter.',
        ],
        'semester' => [
            'required' => 'Semester wajib dipilih (Ganjil/Genap).',
            'in_list'  => 'Semester harus bernilai Ganjil atau Genap.',
        ],
        'tanggal_mulai' => [
            'required'   => 'Tanggal mulai wajib diisi.',
            'valid_date' => 'Format tanggal mulai harus YYYY-MM-DD.',
        ],
        'tanggal_selesai' => [
            'required'   => 'Tanggal selesai wajib diisi.',
            'valid_date' => 'Format tanggal selesai harus YYYY-MM-DD.',
        ],
    ];

    /**
     * Ambil data seluruh periode beserta kalkulasi jumlah mahasiswa terdaftar dan status timeline.
     */
    public function getPeriodeWithStats(?string $keyword = null): array
    {
        $builder = $this->db->table($this->table . ' p')
            ->select('p.*, COUNT(u.id) AS total_mahasiswa')
            ->join('users u', "u.periode_id = p.id AND u.role = 'mahasiswa'", 'left')
            ->groupBy('p.id')
            ->orderBy('p.is_aktif', 'DESC')
            ->orderBy('p.tanggal_mulai', 'DESC');

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('p.nama_periode', $keyword)
                ->orLike('p.tahun_ajaran', $keyword)
                ->orLike('p.semester', $keyword)
                ->orLike('p.keterangan', $keyword)
                ->groupEnd();
        }

        $list = $builder->get()->getResultArray();
        $today = date('Y-m-d');

        foreach ($list as &$item) {
            $item['total_mahasiswa'] = (int) ($item['total_mahasiswa'] ?? 0);

            if ($today < $item['tanggal_mulai']) {
                $item['timeline_status'] = 'Akan Datang';
                $item['timeline_badge']  = 'info';
            } elseif ($today >= $item['tanggal_mulai'] && $today <= $item['tanggal_selesai']) {
                $item['timeline_status'] = 'Sedang Berlangsung';
                $item['timeline_badge']  = 'success';
            } else {
                $item['timeline_status'] = 'Selesai';
                $item['timeline_badge']  = 'secondary';
            }
        }

        return $list;
    }

    /**
     * Dapatkan periode yang saat ini ditandai aktif.
     */
    public function getPeriodeAktif(): ?array
    {
        return $this->where('is_aktif', 1)->first();
    }

    /**
     * Aktifkan satu periode tertentu dan nonaktifkan periode lainnya.
     */
    public function setAktif(int $id): bool
    {
        $this->db->transStart();
        $this->db->table($this->table)->update(['is_aktif' => 0]);
        $this->db->table($this->table)->where('id', $id)->update(['is_aktif' => 1]);
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * Hitung total mahasiswa yang terdaftar pada periode ini.
     */
    public function countPenggunaByPeriode(int $periodeId): int
    {
        return $this->db->table('users')
            ->where('periode_id', $periodeId)
            ->where('role', 'mahasiswa')
            ->countAllResults();
    }

    /**
     * Ambil daftar pilihan periode untuk form select dan filter.
     */
    public function getDaftarPilihan(): array
    {
        return $this->orderBy('is_aktif', 'DESC')
            ->orderBy('tanggal_mulai', 'DESC')
            ->findAll();
    }
}
