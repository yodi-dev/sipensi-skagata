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
     * Ambil detail pemetaan bimbingan guru pamong lengkap dengan identitas jurusan dan universitas mitra.
     */
    public function getAssignmentsByGuru(int $guruId): array
    {
        $rows = $this->db->table($this->table . ' gp')
            ->select('gp.id, gp.guru_id, gp.jurusan_id, COALESCE(j.nama_jurusan, gp.jurusan) AS nama_jurusan, j.kode_jurusan, u.id AS universitas_id, u.nama_universitas, u.kode_universitas, gp.keterangan')
            ->join('jurusan j', 'j.id = gp.jurusan_id', 'left')
            ->join('universitas u', 'u.id = j.universitas_id', 'left')
            ->where('gp.guru_id', $guruId)
            ->orderBy('u.nama_universitas', 'ASC')
            ->orderBy('j.nama_jurusan', 'ASC')
            ->get()
            ->getResultArray();

        // Fallback jika belum ada di tabel guru_pamong, cek users.jurusan
        if (empty($rows)) {
            $user = $this->db->table('users')->where('id', $guruId)->get()->getRowArray();
            if ($user && !empty($user['jurusan'])) {
                $jurusanRecord = $this->db->table('jurusan j')
                    ->select('j.id AS jurusan_id, j.nama_jurusan, j.kode_jurusan, u.id AS universitas_id, u.nama_universitas, u.kode_universitas')
                    ->join('universitas u', 'u.id = j.universitas_id', 'left')
                    ->where('j.nama_jurusan', $user['jurusan'])
                    ->get()
                    ->getRowArray();

                if ($jurusanRecord) {
                    $rows[] = array_merge(['id' => null, 'guru_id' => $guruId, 'keterangan' => null], $jurusanRecord);
                } else {
                    $rows[] = [
                        'id'               => null,
                        'guru_id'          => $guruId,
                        'jurusan_id'       => null,
                        'nama_jurusan'     => $user['jurusan'],
                        'kode_jurusan'     => null,
                        'universitas_id'   => null,
                        'nama_universitas' => null,
                        'kode_universitas' => null,
                        'keterangan'       => null,
                    ];
                }
            }
        }

        foreach ($rows as &$r) {
            $univBadge = !empty($r['kode_universitas']) ? $r['kode_universitas'] : (!empty($r['nama_universitas']) ? $r['nama_universitas'] : '');
            $r['display_label'] = !empty($univBadge) ? "{$r['nama_jurusan']} ({$univBadge})" : $r['nama_jurusan'];
        }
        unset($r);

        return $rows;
    }

    /**
     * Ambil daftar nama jurusan yang diampu/dibimbing oleh seorang Guru Pamong.
     * Mengembalikan array nama jurusan (contoh: ['Informatika']).
     */
    public function getJurusanByGuru(int $guruId): array
    {
        $assignments = $this->getAssignmentsByGuru($guruId);
        if (empty($assignments)) {
            return [];
        }

        $list = [];
        foreach ($assignments as $a) {
            if (!empty($a['nama_jurusan'])) {
                $list[] = $a['nama_jurusan'];
            }
        }

        return array_values(array_unique($list));
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
            $assignments = $this->getAssignmentsByGuru($guruId);
            $jurusanList = $this->getJurusanByGuru($guruId);

            $jurusanIds = [];
            $displayLabels = [];
            foreach ($assignments as $a) {
                if (!empty($a['jurusan_id'])) {
                    $jurusanIds[] = (int) $a['jurusan_id'];
                }
                if (!empty($a['display_label'])) {
                    $displayLabels[] = $a['display_label'];
                }
            }

            $totalMahasiswa = $this->countMahasiswaByGuru($guruId);

            $mapping = $this->where('guru_id', $guruId)->first();
            $keterangan = $mapping['keterangan'] ?? '';

            $result[] = [
                'id'              => $guruId,
                'nama'            => $guru['nama'],
                'username'        => $guru['username'],
                'jurusan_list'    => $jurusanList,
                'jurusan_ids'     => $jurusanIds,
                'assignments'     => $assignments,
                'jurusan_label'   => !empty($displayLabels) ? implode(', ', $displayLabels) : (!empty($jurusanList) ? implode(', ', $jurusanList) : 'Belum Dipetakan'),
                'keterangan'      => $keterangan,
                'total_mahasiswa' => $totalMahasiswa,
            ];
        }

        return $result;
    }

    /**
     * Simpan / perbarui pemetaan jurusan untuk seorang guru pamong.
     * Menerima array ID jurusan (numerik) atau nama jurusan (string untuk backward-compatibility).
     */
    public function assignJurusanToGuru(int $guruId, array $jurusanList, ?string $keterangan = null): bool
    {
        $this->db->transStart();

        // 1. Hapus pemetaan lama guru ini
        $this->where('guru_id', $guruId)->delete();

        // 2. Insert pemetaan baru
        $cleanedInput = array_values(array_unique(array_filter(array_map('trim', $jurusanList))));

        if (!empty($cleanedInput)) {
            $jurusanModel = new JurusanModel();
            $masterJurusan = $jurusanModel->findAll();
            $idMap = [];
            $nameMap = [];
            foreach ($masterJurusan as $mj) {
                $idMap[(int) $mj['id']] = $mj;
                $nameMap[$mj['nama_jurusan']][] = $mj;
            }

            $insertData = [];
            $primaryJurusanName = null;

            foreach ($cleanedInput as $item) {
                if (is_numeric($item)) {
                    // Berupa ID jurusan spesifik
                    $jId = (int) $item;
                    if (isset($idMap[$jId])) {
                        $mj = $idMap[$jId];
                        $insertData[] = [
                            'guru_id'    => $guruId,
                            'jurusan_id' => $jId,
                            'jurusan'    => $mj['nama_jurusan'],
                            'keterangan' => $keterangan,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
                        if ($primaryJurusanName === null) {
                            $primaryJurusanName = $mj['nama_jurusan'];
                        }
                    }
                } else {
                    // Berupa string nama jurusan (misal dari unit test)
                    if (isset($nameMap[$item])) {
                        foreach ($nameMap[$item] as $mj) {
                            $insertData[] = [
                                'guru_id'    => $guruId,
                                'jurusan_id' => (int) $mj['id'],
                                'jurusan'    => $mj['nama_jurusan'],
                                'keterangan' => $keterangan,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            ];
                        }
                    } else {
                        // Nama jurusan tidak terdaftar di master jurusan
                        $insertData[] = [
                            'guru_id'    => $guruId,
                            'jurusan_id' => null,
                            'jurusan'    => $item,
                            'keterangan' => $keterangan,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
                    }
                    if ($primaryJurusanName === null) {
                        $primaryJurusanName = $item;
                    }
                }
            }

            if (!empty($insertData)) {
                $this->insertBatch($insertData);
            }

            // 3. Sinkronkan kolom users.jurusan dengan jurusan primer
            $this->db->table('users')->where('id', $guruId)->update([
                'jurusan' => $primaryJurusanName
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
     * Validasi apakah mahasiswa tertentu berada di bawah pengawasan jurusan dan kampus guru pamong ini.
     */
    public function isMahasiswaSupervisedByGuru(int $guruId, int $mahasiswaId): bool
    {
        $assignments = $this->getAssignmentsByGuru($guruId);
        if (empty($assignments)) {
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

        $mhsJurusan = trim((string) $mahasiswa['jurusan']);
        $mhsUniv = !empty($mahasiswa['universitas']) ? trim((string) $mahasiswa['universitas']) : null;

        foreach ($assignments as $a) {
            // 1. Cek kecocokan jurusan
            $jurusanMatch = (strcasecmp($a['nama_jurusan'], $mhsJurusan) === 0);
            if (!$jurusanMatch) {
                continue;
            }

            // 2. Cek kecocokan universitas
            // Jika assignment tidak memiliki universitas atau mahasiswa belum diisi universitasnya (data legacy): izinkan
            if (empty($a['nama_universitas']) || empty($mhsUniv)) {
                return true;
            }

            // Jika keduanya memiliki universitas: pastikan universitasnya cocok (nama atau kode)
            if (strcasecmp($a['nama_universitas'], $mhsUniv) === 0) {
                return true;
            }
            if (!empty($a['kode_universitas']) && strcasecmp($a['kode_universitas'], $mhsUniv) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Hitung total mahasiswa aktif yang berada di bawah jurusan dan kampus bimbingan guru ini.
     */
    public function countMahasiswaByGuru(int $guruId): int
    {
        $assignments = $this->getAssignmentsByGuru($guruId);
        if (empty($assignments)) {
            return 0;
        }

        $builder = $this->db->table('users')->where('role', 'mahasiswa');
        $builder->groupStart();
        foreach ($assignments as $a) {
            $builder->orGroupStart();
            $builder->where('jurusan', $a['nama_jurusan']);
            if (!empty($a['nama_universitas'])) {
                $builder->groupStart()
                    ->where('universitas', $a['nama_universitas']);
                if (!empty($a['kode_universitas'])) {
                    $builder->orWhere('universitas', $a['kode_universitas']);
                }
                $builder->orWhere('universitas IS NULL')
                    ->orWhere('universitas', '')
                    ->groupEnd();
            }
            $builder->groupEnd();
        }
        $builder->groupEnd();

        return $builder->countAllResults();
    }
}
