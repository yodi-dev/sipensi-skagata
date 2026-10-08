<?php

namespace App\Models;

use CodeIgniter\Model;

class PresensiModel extends Model
{
    protected $table = 'presensi';
    protected $primaryKey = 'id';
    // Kolom yang diizinkan untuk diisi
    protected $allowedFields    = [
        'user_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'latitude',
        'longitude',
        'status',
        'keterangan',
        'bukti_surat'
    ];

    protected $validationRules = [
        'status' => 'required|in_list[hadir,terlambat,izin,sakit,alpa]'
    ];
}
