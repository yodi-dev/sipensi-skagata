<?= $this->extend('layout/template') ?>

<?= $this->section('styles') ?>
<style>
    .report-card {
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(15, 81, 50, 0.05);
        background: #ffffff;
        overflow: hidden;
    }

    .card-header-custom {
        background-color: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.25rem 1.5rem;
    }

    .table-custom {
        margin-bottom: 0;
    }

    .table-custom thead tr:first-child th {
        background-color: #0f5132;
        color: #ffffff;
        font-weight: 600;
        border-color: #0a3622;
        padding: 0.9rem;
        vertical-align: middle;
        font-size: 0.85rem;
    }

    .table-custom thead tr:nth-child(2) th {
        background-color: #f8fafc;
        color: #334155;
        font-weight: 600;
        border-color: #e2e8f0;
        padding: 0.7rem;
        font-size: 0.8rem;
    }

    .table-custom tbody td {
        padding: 0.8rem;
        vertical-align: middle;
        font-size: 0.875rem;
    }

    .filter-wrapper {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.4rem 0.75rem;
    }

    /* Mobile Rekap Card Styling */
    .rekap-item-card {
        border-radius: 0.85rem;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    }

    /* Area Kop Surat & Tanda Tangan Cetak (Hanya tampil saat print) */
    .print-only-header,
    .print-only-signature {
        display: none;
    }

    @media print {
        body {
            background-color: #ffffff !important;
            color: #000000 !important;
            font-size: 11pt;
        }

        .btn,
        .filter-wrapper,
        .navbar-skagata,
        .footer-skagata,
        .admin-sidebar,
        .admin-topbar,
        .skagata-bottom-nav,
        .d-print-none,
        nav,
        footer {
            display: none !important;
        }

        .report-card {
            box-shadow: none !important;
            border: none !important;
        }

        .container {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .print-only-header {
            display: block !important;
            margin-bottom: 20px;
        }

        .print-only-signature {
            display: block !important;
            margin-top: 35px;
            page-break-inside: avoid;
        }

        .kop-surat-border {
            border-bottom: 3px double #000000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .table-custom {
            font-size: 10pt !important;
            width: 100% !important;
        }

        .table-custom th,
        .table-custom td {
            border: 1px solid #000000 !important;
            color: #000000 !important;
            padding: 5px !important;
        }

        .table-custom thead tr:first-child th {
            background-color: #e2e8f0 !important;
            color: #000000 !important;
            -webkit-print-color-adjust: exact;
        }

        .table-custom thead tr:nth-child(2) th {
            background-color: #f1f5f9 !important;
            color: #000000 !important;
            -webkit-print-color-adjust: exact;
        }

        .table-responsive {
            overflow: visible !important;
        }

        @page {
            size: landscape;
            margin: 1.2cm;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$namaBulan = [
    '01' => 'Januari',   '02' => 'Februari', '03' => 'Maret',
    '04' => 'April',     '05' => 'Mei',      '06' => 'Juni',
    '07' => 'Juli',      '08' => 'Agustus',  '09' => 'September',
    '10' => 'Oktober',   '11' => 'November', '12' => 'Desember'
];
$bulanPilihText = $namaBulan[$bulan_pilih] ?? $bulan_pilih;
?>
<div class="container py-4">


    <?php if (empty($assigned_jurusans) && session()->get('role') === 'guru'): ?>
        <div class="alert alert-warning rounded-4 border-0 shadow-sm d-flex align-items-center gap-3 mb-4 p-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill text-warning fs-3"></i>
            <div>
                <strong class="d-block text-dark">Akun Guru Pamong Belum Dipetakan</strong>
                <span class="text-muted small">Akun Anda saat ini belum dipetakan ke jurusan mahasiswa manapun. Silakan hubungi Administrator Sistem untuk mengatur pemetaan jurusan bimbingan Anda.</span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Kop Resmi SMK Negeri 3 Yogyakarta (Hanya Muncul Saat Print / Cetak PDF) -->
    <div class="print-only-header text-center">
        <div class="lh-sm">
            <h6 class="text-uppercase mb-0 fw-semibold" style="letter-spacing: 1px; font-size: 11pt;">Pemerintah Daerah Daerah Istimewa Yogyakarta</h6>
            <h6 class="text-uppercase mb-0 fw-semibold" style="letter-spacing: 1px; font-size: 11pt;">Dinas Pendidikan, Pemuda, dan Olahraga</h6>
            <h4 class="text-uppercase mb-0 fw-bold mt-1" style="letter-spacing: 1.5px; font-size: 15pt;">SMK NEGERI 3 YOGYAKARTA</h4>
            <p class="mb-0 small text-muted" style="font-size: 9pt;">Jl. R.W. Monginsidi No. 2, Jetis, Yogyakarta 55233 | Telp: (0274) 513507 | Laman: smkn3jogja.sch.id</p>
        </div>
        <div class="kop-surat-border mt-2"></div>
        <h5 class="fw-bold text-uppercase mt-2 mb-1" style="font-size: 12pt;">REKAPITULASI PRESENSI MAHASISWA PRAKTIKAN</h5>
        <p class="small text-muted mb-0" style="font-size: 10pt;">
            Jurusan Bimbingan: <strong><?= !empty($jurusan_terpilih) ? esc($jurusan_terpilih) : (!empty($assigned_jurusans) ? esc(implode(', ', $assigned_jurusans)) : 'Semua Jurusan') ?></strong> &bull; Periode: <strong><?= esc($bulanPilihText) ?> <?= esc($tahun_pilih) ?></strong> &bull; Basis 5 Hari Kerja Efektif
        </p>
    </div>

    <div class="card report-card">

        <div class="card-header-custom d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h5 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.2px;">Rekapitulasi Presensi Bulanan</h5>
                    <?php if (!empty($assigned_jurusans)): ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-0.5 fw-semibold small" style="font-size: 0.72rem;">
                            <?= esc(implode(', ', $assigned_jurusans)) ?>
                        </span>
                    <?php endif; ?>
                </div>
                <p class="text-muted small mb-0 mt-1">
                    Periode: <span class="fw-semibold text-success"><?= esc($bulanPilihText) ?> <?= esc($tahun_pilih) ?></span> &bull; Basis 5 Hari Kerja Efektif
                </p>
            </div>

            <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
                <!-- Auto-Filter Form (Otomatis reload saat bulan / tahun / jurusan berubah) -->
                <form action="<?= base_url('guru/laporan') ?>" method="GET" class="d-flex flex-column flex-sm-row gap-2 align-items-sm-center m-0">

                    <?php if (!empty($daftar_jurusan) && count($daftar_jurusan) > 1): ?>
                        <div class="filter-wrapper d-flex align-items-center gap-2">
                            <label for="jurusan" class="fw-semibold text-muted small mb-0 text-nowrap"><i class="bi bi-funnel"></i> Jurusan:</label>
                            <select name="jurusan" id="jurusan" class="form-select form-select-sm border-0 bg-transparent shadow-none" onchange="this.form.submit()">
                                <option value="">-- Semua Jurusan Bimbingan --</option>
                                <?php foreach ($daftar_jurusan as $jrs): ?>
                                    <option value="<?= esc($jrs) ?>" <?= (($jurusan_terpilih ?? '') === $jrs) ? 'selected' : '' ?>><?= esc($jrs) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="filter-wrapper d-flex align-items-center gap-2">
                        <label for="bulan" class="fw-semibold text-muted small mb-0 text-nowrap"><i class="bi bi-calendar-month"></i> Bulan:</label>
                        <select name="bulan" id="bulan" class="form-select form-select-sm border-0 bg-transparent shadow-none" onchange="this.form.submit()">
                            <?php foreach ($namaBulan as $angka => $nama): ?>
                                <option value="<?= $angka ?>" <?= ($bulan_pilih == $angka) ? 'selected' : '' ?>>
                                    <?= $nama ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-wrapper d-flex align-items-center gap-2">
                        <label for="tahun" class="fw-semibold text-muted small mb-0 text-nowrap"><i class="bi bi-calendar3"></i> Tahun:</label>
                        <select name="tahun" id="tahun" class="form-select form-select-sm border-0 bg-transparent shadow-none" onchange="this.form.submit()">
                            <?php
                            $tahunSekarang = date('Y');
                            for ($t = $tahunSekarang; $t >= 2023; $t--):
                            ?>
                                <option value="<?= $t ?>" <?= ($tahun_pilih == $t) ? 'selected' : '' ?>>
                                    <?= $t ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                </form>

                <!-- Tombol Aksi Excel & Cetak -->
                <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                    <a href="<?= base_url('guru/laporan/export-excel?bulan=' . esc($bulan_pilih) . '&tahun=' . esc($tahun_pilih) . (!empty($jurusan_terpilih) ? '&jurusan=' . urlencode($jurusan_terpilih) : '')) ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 shadow-xs text-nowrap" title="Unduh File Excel">
                        <i class="bi bi-file-earmark-excel me-1"></i> Excel
                    </a>
                    <button type="button" onclick="window.print()" class="btn btn-skagata btn-sm rounded-pill px-3 shadow-sm text-nowrap" title="Cetak Rekapitulasi Resmi">
                        <i class="bi bi-printer me-1"></i> Cetak
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <?php if (empty($laporan)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-folder-x fs-1 d-block mb-2 text-muted"></i>
                    Tidak ada data presensi pada periode bulan ini.
                </div>
            <?php else: ?>
                <!-- ============================================== -->
                <!-- 1. MOBILE VIEW: Responsive Cards (< 768px)     -->
                <!-- ============================================== -->
                <div class="d-block d-md-none d-print-none p-3 bg-light bg-opacity-50">
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($laporan as $key => $row): ?>
                            <?php
                            $total_masuk = $row['total_hadir'] + $row['total_terlambat'] + $row['total_izin'] + $row['total_sakit'] + $row['total_alpa'];
                            $total_hadir_efektif = $row['total_hadir'] + $row['total_terlambat'];
                            $persen = ($total_masuk > 0) ? round(($total_hadir_efektif / $total_masuk) * 100) : 0;
                            $persenBadgeClass = ($persen >= 90) ? 'bg-success-subtle text-success border-success-subtle' : (($persen >= 75) ? 'bg-warning-subtle text-warning-emphasis border-warning-subtle' : 'bg-danger-subtle text-danger border-danger-subtle');
                            ?>
                            <div class="card rekap-item-card p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2 pb-2 border-bottom">
                                    <div class="overflow-hidden pe-2">
                                        <span class="fw-bold text-dark d-block text-truncate" style="max-width: 190px;"><?= esc($row['nama']) ?></span>
                                        <div class="d-flex align-items-center gap-1 mt-0.5">
                                            <small class="text-muted" style="font-size: 0.72rem;">#<?= esc($key + 1) ?></small>
                                            <?php if (!empty($row['jurusan'])): ?>
                                                <span class="badge bg-light text-muted border rounded-pill px-2 py-0.5" style="font-size: 0.68rem;"><?= esc($row['jurusan']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 text-end">
                                        <span class="badge <?= $persenBadgeClass ?> border rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.78rem;">
                                            <?= $persen ?>% Efektif
                                        </span>
                                    </div>
                                </div>

                                <div class="row g-2 text-center small mt-1">
                                    <div class="col-4">
                                        <div class="p-2 rounded bg-white border">
                                            <div class="text-muted" style="font-size: 0.7rem;">Hadir</div>
                                            <div class="fw-bold text-success mt-0.5"><?= esc($row['total_hadir']) ?></div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 rounded bg-white border">
                                            <div class="text-muted" style="font-size: 0.7rem;">Terlambat</div>
                                            <div class="fw-bold text-warning-emphasis mt-0.5"><?= esc($row['total_terlambat']) ?></div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 rounded bg-white border">
                                            <div class="text-muted" style="font-size: 0.7rem;">Izin</div>
                                            <div class="fw-bold text-info-emphasis mt-0.5"><?= esc($row['total_izin']) ?></div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 rounded bg-white border">
                                            <div class="text-muted" style="font-size: 0.7rem;">Sakit</div>
                                            <div class="fw-bold text-secondary-emphasis mt-0.5"><?= esc($row['total_sakit']) ?></div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 rounded bg-white border">
                                            <div class="text-muted" style="font-size: 0.7rem;">Alpa</div>
                                            <div class="fw-bold text-danger mt-0.5"><?= esc($row['total_alpa']) ?></div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 rounded bg-white border">
                                            <div class="text-muted" style="font-size: 0.7rem;">Total Hari</div>
                                            <div class="fw-bold text-dark mt-0.5"><?= $total_masuk ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- 2. DESKTOP VIEW: Full Data Table (>= 768px)    -->
                <!-- ============================================== -->
                <div class="table-responsive d-none d-md-block d-print-block">
                    <table class="table table-hover table-bordered table-custom text-center mb-0">
                        <thead>
                            <tr>
                                <th width="5%" rowspan="2" class="align-middle border-bottom-0">No</th>
                                <th width="32%" rowspan="2" class="align-middle text-start border-bottom-0">Nama Lengkap Mahasiswa</th>
                                <th colspan="6" class="border-bottom-0 border-start text-center">Akumulasi Kehadiran (Hari)</th>
                            </tr>
                            <tr>
                                <th class="border-start">Hadir</th>
                                <th>Terlambat</th>
                                <th>Izin</th>
                                <th>Sakit</th>
                                <th>Alpa</th>
                                <th>% Efektif</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($laporan as $key => $row): ?>
                                <?php
                                $total_masuk = $row['total_hadir'] + $row['total_terlambat'] + $row['total_izin'] + $row['total_sakit'] + $row['total_alpa'];
                                $total_hadir_efektif = $row['total_hadir'] + $row['total_terlambat'];
                                $persen = ($total_masuk > 0) ? round(($total_hadir_efektif / $total_masuk) * 100) : 0;
                                $persenBadgeClass = ($persen >= 90) ? 'text-success' : (($persen >= 75) ? 'text-warning-emphasis' : 'text-danger');
                                ?>
                                <tr>
                                    <td><span class="text-muted"><?= esc($key + 1) ?></span></td>
                                    <td class="text-start fw-bold text-dark">
                                        <?= esc($row['nama']) ?>
                                        <?php if (!empty($row['jurusan'])): ?>
                                            <span class="badge bg-light text-muted border rounded-pill ms-1 fw-normal" style="font-size: 0.72rem;"><?= esc($row['jurusan']) ?></span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="fw-semibold text-success"><?= $row['total_hadir'] ?></td>
                                    <td class="fw-semibold text-warning-emphasis"><?= $row['total_terlambat'] ?></td>
                                    <td class="fw-semibold text-info-emphasis"><?= $row['total_izin'] ?></td>
                                    <td class="fw-semibold text-secondary-emphasis"><?= $row['total_sakit'] ?></td>
                                    <td class="fw-semibold text-danger"><?= $row['total_alpa'] ?></td>

                                    <td class="fw-bold bg-light <?= $persenBadgeClass ?>">
                                        <?= $persen ?>%
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- Lembar Tanda Tangan Kedinasan (Hanya Muncul Saat Print / PDF) -->
    <div class="print-only-signature">
        <table style="width: 100%; border: none; font-size: 10pt;">
            <tr>
                <td style="width: 50%; text-align: center; vertical-align: top; border: none;">
                    Mengetahui,<br>
                    <strong>Koordinator PK / PPL Perguruan Tinggi</strong>
                    <br><br><br><br><br>
                    (......................................................)<br>
                    NIP / NIDN. ..................................................
                </td>
                <td style="width: 50%; text-align: center; vertical-align: top; border: none;">
                    Yogyakarta, <?= date('d') . ' ' . ($namaBulan[date('m')] ?? date('F')) . ' ' . date('Y') ?><br>
                    <strong>Guru Pamong Pembimbing SMKN 3 Yogyakarta</strong>
                    <br><br><br><br><br>
                    <strong><?= esc(session()->get('nama')) ?></strong><br>
                    NIP. <?= !empty($nip_guru) ? esc($nip_guru) : '..................................................' ?>
                </td>
            </tr>
        </table>
    </div>

</div>
<?= $this->endSection() ?>