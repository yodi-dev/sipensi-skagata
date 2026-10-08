<?php

namespace App\Models;

use CodeIgniter\Model;

class GuruPamongModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'guru_pamong';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'guru_id',
        'jurusan_id',
        'jurusan',
        'keterangan',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    /**
     * Ambil daftar nama jurusan yang diampu/dibimbing oleh seorang Guru Pamong.
     * Mengembalikan array nama jurusan (contoh: ['Informatika']).
     */
    public function getJurusanByGuru(int $guruId): array
    {
        $mappings = $this->where('guru_id', $guruId)->findAll();
        $jurusanList = [];

        if (!empty($mappings)) {
            $jurusanList = array_values(array_filter(array_column($mappings, 'jurusan')));
        }

        // Jika di tabel relasi belum ada, fallback ke kolom users.jurusan
        if (empty($jurusanList)) {
            $user = $this->db->table('users')->where('id', $guruId)->get()->getRowArray();
            if ($user && !empty($user['jurusan'])) {
                $jurusanList = [$user['jurusan']];
            }
        }

        return array_values(array_unique($jurusanList));
    }

    /**
     * Dapatkan semua data guru pamong beserta daftar jurusan bimbingan dan total mahasiswa yang diawasi.
     */
    public function getGuruWithJurusan(): array
    {
        $gurus = $this->db->table('users')
            ->where('role', 'guru')
            ->orderBy('nama', 'ASC')
            ->get()
            ->getResultArray();

        $result = [];
        foreach ($gurus as $guru) {
            $guruId = (int) $guru['id'];
            $jurusanList = $this->getJurusanByGuru($guruId);

            $totalMahasiswa = 0;
            if (!empty($jurusanList)) {
                $totalMahasiswa = $this->db->table('users')
                    ->where('role', 'mahasiswa')
                    ->whereIn('jurusan', $jurusanList)
                    ->countAllResults();
            }

            $mapping = $this->where('guru_id', $guruId)->first();
            $keterangan = $mapping['keterangan'] ?? '';

            $result[] = [
                'id'              => $guruId,
                'nama'            => $guru['nama'],
                'username'        => $guru['username'],
                'jurusan_list'    => $jurusanList,
                'jurusan_label'   => !empty($jurusanList) ? implode(', ', $jurusanList) : 'Belum Dipetakan',
                'keterangan'      => $keterangan,
                'total_mahasiswa' => $totalMahasiswa,
            ];
        }

        return $result;
    }

    /**
     * Simpan / perbarui pemetaan jurusan untuk seorang guru pamong.
     */
    public function assignJurusanToGuru(int $guruId, array $jurusanList, ?string $keterangan = null): bool
    {
        $jurusanList = array_values(array_unique(array_filter(array_map('trim', $jurusanList))));

        $this->db->transStart();

        // 1. Hapus pemetaan lama guru ini
        $this->where('guru_id', $guruId)->delete();

        // 2. Insert pemetaan baru
        if (!empty($jurusanList)) {
            $jurusanModel = new JurusanModel();
            $masterJurusan = $jurusanModel->findAll();
            $jurusanMap = [];
            foreach ($masterJurusan as $mj) {
                $jurusanMap[$mj['nama_jurusan']] = (int) $mj['id'];
            }

            $insertData = [];
            foreach ($jurusanList as $jrs) {
                $insertData[] = [
                    'guru_id'    => $guruId,
                    'jurusan_id' => $jurusanMap[$jrs] ?? null,
                    'jurusan'    => $jrs,
                    'keterangan' => $keterangan,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }
            $this->insertBatch($insertData);

            // 3. Sinkronkan kolom users.jurusan dengan jurusan primer pertama
            $this->db->table('users')->where('id', $guruId)->update([
                'jurusan' => $jurusanList[0]
            ]);
        } else {
            // Jika dikosongkan pemetaannya
            $this->db->table('users')->where('id', $guruId)->update([
                'jurusan' => null
            ]);
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * Validasi apakah mahasiswa tertentu berada di bawah pengawasan jurusan guru pamong ini.
     */
    public function isMahasiswaSupervisedByGuru(int $guruId, int $mahasiswaId): bool
    {
        $assignedJurusans = $this->getJurusanByGuru($guruId);
        if (empty($assignedJurusans)) {
            return false;
        }

        $mahasiswa = $this->db->table('users')
            ->where('id', $mahasiswaId)
            ->where('role', 'mahasiswa')
            ->get()
            ->getRowArray();

        if (!$mahasiswa || empty($mahasiswa['jurusan'])) {
            return false;
        }

        return in_array($mahasiswa['jurusan'], $assignedJurusans, true);
    }

    /**
     * Hitung total mahasiswa aktif yang berada di bawah jurusan bimbingan guru ini.
     */
    public function countMahasiswaByGuru(int $guruId): int
    {
        $assignedJurusans = $this->getJurusanByGuru($guruId);
        if (empty($assignedJurusans)) {
            return 0;
        }

        return $this->db->table('users')
            ->where('role', 'mahasiswa')
            ->whereIn('jurusan', $assignedJurusans)
            ->countAllResults();
    }
}
