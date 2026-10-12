<?php

namespace App\Models;

use CodeIgniter\Model;

class JurusanModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'jurusan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['universitas_id', 'kode_jurusan', 'nama_jurusan', 'deskripsi', 'created_at', 'updated_at'];
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'universitas_id' => 'permit_empty|is_natural_no_zero',
        'kode_jurusan'   => 'required|min_length[2]|max_length[20]|is_unique[jurusan.kode_jurusan,id,{id}]',
        'nama_jurusan'   => 'required|min_length[3]|max_length[100]',
    ];

    protected $validationMessages = [
        'kode_jurusan' => [
            'required'   => 'Kode jurusan wajib diisi.',
            'min_length' => 'Kode jurusan minimal 2 karakter.',
            'max_length' => 'Kode jurusan maksimal 20 karakter.',
            'is_unique'  => 'Kode jurusan sudah terdaftar, gunakan kode lain.',
        ],
        'nama_jurusan' => [
            'required'   => 'Nama jurusan wajib diisi.',
            'min_length' => 'Nama jurusan minimal 3 karakter.',
            'max_length' => 'Nama jurusan maksimal 100 karakter.',
        ],
    ];

    /**
     * Ambil data jurusan beserta total mahasiswa yang terdaftar pada jurusan tersebut dan nama universitas.
     */
    public function getJurusanWithUserCount(?string $keyword = null, ?int $universitasId = null): array
    {
        $builder = $this->db->table($this->table . ' j')
            ->select('j.*, COUNT(u.id) AS total_mahasiswa, univ.nama_universitas, univ.kode_universitas')
            ->join('universitas univ', 'univ.id = j.universitas_id', 'left')
            ->join('users u', "(u.jurusan = j.nama_jurusan OR u.jurusan = j.kode_jurusan) AND (u.universitas = univ.nama_universitas OR u.universitas = univ.kode_universitas OR j.universitas_id IS NULL) AND u.role = 'mahasiswa'", 'left')
            ->groupBy('j.id')
            ->orderBy('univ.nama_universitas', 'ASC')
            ->orderBy('j.nama_jurusan', 'ASC');

        if (!empty($universitasId)) {
            $builder->where('j.universitas_id', $universitasId);
        }

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('j.nama_jurusan', $keyword)
                ->orLike('j.kode_jurusan', $keyword)
                ->orLike('j.deskripsi', $keyword)
                ->orLike('univ.nama_universitas', $keyword)
                ->orLike('univ.kode_universitas', $keyword)
                ->groupEnd();
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Hitung total pengguna (mahasiswa & guru) yang terafiliasi dengan jurusan ini.
     */
    public function countPenggunaByJurusan(int $jurusanId): int
    {
        $jurusan = $this->find($jurusanId);
        if (!$jurusan) {
            return 0;
        }

        $builder = $this->db->table('users')
            ->groupStart()
                ->where('jurusan', $jurusan['nama_jurusan'])
                ->orWhere('jurusan', $jurusan['kode_jurusan'])
            ->groupEnd();

        if (!empty($jurusan['universitas_id'])) {
            $univ = $this->db->table('universitas')->where('id', $jurusan['universitas_id'])->get()->getRowArray();
            if ($univ) {
                $builder->groupStart()
                    ->where('universitas', $univ['nama_universitas'])
                    ->orWhere('universitas', $univ['kode_universitas'])
                    ->orWhere('universitas IS NULL')
                    ->groupEnd();
            }
        }

        return $builder->countAllResults();
    }

    /**
     * Ambil seluruh master jurusan dengan relasi universitas mitra dan label kampus pembeda.
     */
    public function getJurusanWithUniversitas(): array
    {
        $rows = $this->db->table($this->table . ' j')
            ->select('j.*, univ.nama_universitas, univ.kode_universitas')
            ->join('universitas univ', 'univ.id = j.universitas_id', 'left')
            ->orderBy('univ.nama_universitas', 'ASC')
            ->orderBy('j.nama_jurusan', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($rows as &$r) {
            $univBadge = !empty($r['kode_universitas']) ? $r['kode_universitas'] : (!empty($r['nama_universitas']) ? $r['nama_universitas'] : '');
            $r['display_label'] = !empty($univBadge) ? "{$r['nama_jurusan']} ({$univBadge})" : $r['nama_jurusan'];
        }
        unset($r);

        return $rows;
    }

    /**
     * Ambil daftar nama jurusan untuk dropdown & filter select.
     */
    public function getDaftarNama(): array
    {
        $rows = $this->select('nama_jurusan')
            ->orderBy('nama_jurusan', 'ASC')
            ->findAll();

        return array_column($rows, 'nama_jurusan');
    }

    /**
     * Ambil daftar jurusan di bawah universitas tertentu.
     */
    public function getJurusanByUniversitas(int $universitasId): array
    {
        return $this->where('universitas_id', $universitasId)
            ->orderBy('nama_jurusan', 'ASC')
            ->findAll();
    }

    /**
     * Ambil pemetaan nama universitas ke daftar nama jurusan untuk form dependent dropdown.
     */
    public function getJurusanMappingByUniversitas(): array
    {
        $rows = $this->db->table($this->table . ' j')
            ->select('j.nama_jurusan, univ.nama_universitas')
            ->join('universitas univ', 'univ.id = j.universitas_id', 'left')
            ->orderBy('j.nama_jurusan', 'ASC')
            ->get()
            ->getResultArray();

        $mapping = [];
        foreach ($rows as $r) {
            $univName = $r['nama_universitas'] ?? '';
            if ($univName) {
                if (!isset($mapping[$univName])) {
                    $mapping[$univName] = [];
                }
                $mapping[$univName][] = $r['nama_jurusan'];
            }
        }

        return $mapping;
    }
}
