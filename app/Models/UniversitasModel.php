<?php

namespace App\Models;

use CodeIgniter\Model;

class UniversitasModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'universitas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['kode_universitas', 'nama_universitas', 'alamat', 'telepon', 'created_at', 'updated_at'];
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'kode_universitas' => 'required|min_length[2]|max_length[20]|is_unique[universitas.kode_universitas,id,{id}]',
        'nama_universitas' => 'required|min_length[3]|max_length[150]',
    ];

    protected $validationMessages = [
        'kode_universitas' => [
            'required'   => 'Kode universitas wajib diisi.',
            'min_length' => 'Kode universitas minimal 2 karakter.',
            'max_length' => 'Kode universitas maksimal 20 karakter.',
            'is_unique'  => 'Kode universitas sudah terdaftar, gunakan kode lain.',
        ],
        'nama_universitas' => [
            'required'   => 'Nama universitas wajib diisi.',
            'min_length' => 'Nama universitas minimal 3 karakter.',
            'max_length' => 'Nama universitas maksimal 150 karakter.',
        ],
    ];

    /**
     * Ambil data universitas beserta total mahasiswa yang terafiliasi.
     */
    public function getUniversitasWithUserCount(?string $keyword = null): array
    {
        $builder = $this->db->table($this->table . ' u')
            ->select('u.*, COUNT(usr.id) AS total_mahasiswa')
            ->join('users usr', "(usr.universitas = u.nama_universitas OR usr.universitas = u.kode_universitas) AND usr.role = 'mahasiswa'", 'left')
            ->groupBy('u.id')
            ->orderBy('u.nama_universitas', 'ASC');

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('u.nama_universitas', $keyword)
                ->orLike('u.kode_universitas', $keyword)
                ->orLike('u.alamat', $keyword)
                ->groupEnd();
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Hitung total pengguna/mahasiswa yang terafiliasi dengan universitas ini.
     */
    public function countPenggunaByUniversitas(int $univId): int
    {
        $univ = $this->find($univId);
        if (!$univ) {
            return 0;
        }

        return $this->db->table('users')
            ->groupStart()
                ->where('universitas', $univ['nama_universitas'])
                ->orWhere('universitas', $univ['kode_universitas'])
            ->groupEnd()
            ->countAllResults();
    }

    /**
     * Ambil daftar nama universitas untuk dropdown form dan filter.
     */
    public function getDaftarNama(): array
    {
        $rows = $this->select('nama_universitas')
            ->orderBy('nama_universitas', 'ASC')
            ->findAll();

        return array_column($rows, 'nama_universitas');
    }
}
