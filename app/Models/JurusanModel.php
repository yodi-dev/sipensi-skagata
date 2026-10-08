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
    protected $allowedFields    = ['kode_jurusan', 'nama_jurusan', 'deskripsi', 'created_at', 'updated_at'];
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'kode_jurusan' => 'required|min_length[2]|max_length[20]|is_unique[jurusan.kode_jurusan,id,{id}]',
        'nama_jurusan' => 'required|min_length[3]|max_length[100]',
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
     * Ambil data jurusan beserta total mahasiswa yang terdaftar pada jurusan tersebut.
     */
    public function getJurusanWithUserCount(?string $keyword = null): array
    {
        $builder = $this->db->table($this->table . ' j')
            ->select('j.*, COUNT(u.id) AS total_mahasiswa')
            ->join('users u', "(u.jurusan = j.nama_jurusan OR u.jurusan = j.kode_jurusan) AND u.role = 'mahasiswa'", 'left')
            ->groupBy('j.id')
            ->orderBy('j.nama_jurusan', 'ASC');

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('j.nama_jurusan', $keyword)
                ->orLike('j.kode_jurusan', $keyword)
                ->orLike('j.deskripsi', $keyword)
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

        return $this->db->table('users')
            ->groupStart()
                ->where('jurusan', $jurusan['nama_jurusan'])
                ->orWhere('jurusan', $jurusan['kode_jurusan'])
            ->groupEnd()
            ->countAllResults();
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
}
