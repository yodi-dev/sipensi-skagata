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

    .table-custom thead th {
        background-color: #0f5132;
        color: #ffffff;
        font-weight: 600;
        border-bottom: none;
        padding: 0.9rem;
        white-space: nowrap;
        font-size: 0.85rem;
    }

    .table-custom tbody td {
        padding: 0.85rem;
        vertical-align: middle;
        font-size: 0.875rem;
    }

    .filter-wrapper {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.4rem 0.75rem;
    }

    /* Mobile Piket Card Styling */
    .piket-item-card {
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        transition: transform 0.15s ease;
    }

    .piket-item-card:active {
        transform: scale(0.99);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Helper translasi hari & bulan Bahasa Indonesia
$namaHariIndo = [
    'Sunday'    => 'Minggu',
    'Monday'    => 'Senin',
    'Tuesday'   => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday'  => 'Kamis',
    'Friday'    => 'Jumat',
    'Saturday'  => 'Sabtu'
];

$namaBulanIndo = [
    '01' => 'Januari',   '02' => 'Februari', '03' => 'Maret',
    '04' => 'April',     '05' => 'Mei',      '06' => 'Juni',
    '07' => 'Juli',      '08' => 'Agustus',  '09' => 'September',
    '10' => 'Oktober',   '11' => 'November', '12' => 'Desember'
];

$namaBulanSingkat = [
    '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
    '04' => 'Apr', '05' => 'Mei', '06' => 'Jun',
    '07' => 'Jul', '08' => 'Agu', '09' => 'Sep',
    '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
];

$tsPiket = strtotime($tanggal);
$hariPiket = $namaHariIndo[date('l', $tsPiket)] ?? date('l', $tsPiket);
$bulanPiket = $namaBulanIndo[date('m', $tsPiket)] ?? date('F', $tsPiket);
$tanggalPiketIndo = $hariPiket . ', ' . date('d', $tsPiket) . ' ' . $bulanPiket . ' ' . date('Y', $tsPiket);
?>
<div class="container py-4">
    <!-- Header Halaman Laporan Piket -->
    <div class="mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-3 bg-success-subtle text-success border border-success-subtle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px;">
                <i class="bi bi-camera-fill fs-4"></i>
            </div>
            <div>
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <h4 class="fw-bold text-dark mb-0" style="letter-spacing: -0.3px;">Laporan Piket KBM</h4>
                    <?php if (!empty($assigned_jurusans)): ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fw-semibold small" style="font-size: 0.72rem;">
                            <i class="bi bi-mortarboard-fill me-1"></i>Pamong: <?= esc(implode(', ', $assigned_jurusans)) ?>
                        </span>
                    <?php endif; ?>
                </div>
                <p class="text-muted small mb-0 mt-1">Dokumentasi kegiatan piket KBM mahasiswa praktikan di SMKN 3 Yogyakarta</p>
            </div>
        </div>
    </div>

    <?php if (empty($assigned_jurusans) && session()->get('role') === 'guru'): ?>
        <div class="alert alert-warning rounded-4 border-0 shadow-sm d-flex align-items-center gap-3 mb-4 p-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill text-warning fs-3"></i>
            <div>
                <strong class="d-block text-dark">Akun Guru Pamong Belum Dipetakan</strong>
                <span class="text-muted small">Akun Anda saat ini belum dipetakan ke jurusan mahasiswa manapun. Silakan hubungi Administrator Sistem untuk mengatur pemetaan jurusan bimbingan Anda.</span>
            </div>
        </div>
    <?php endif; ?>

    <div class="card report-card">
        <div class="card-header-custom d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <h6 class="fw-bold text-dark mb-1">Daftar Foto Piket KBM</h6>
                <p class="text-muted small mb-0">Menampilkan rekaman piket pada tanggal: <span class="fw-semibold text-success"><?= esc($tanggalPiketIndo) ?></span></p>
            </div>

            <!-- Auto-Filter Form (onchange submit ala Sibenka) -->
            <form action="<?= base_url('guru/laporan_piket') ?>" method="GET" class="filter-wrapper d-flex align-items-center flex-wrap gap-2 m-0">
                <label for="tanggal" class="fw-semibold text-muted small mb-0"><i class="bi bi-calendar-event me-1"></i>Tanggal:</label>
                <input type="date" id="tanggal" name="tanggal" class="form-control form-control-sm border-0 bg-transparent shadow-none" value="<?= esc($tanggal) ?>" onchange="this.form.submit()" required>
                <a href="<?= base_url('guru/laporan_piket') ?>" class="btn btn-sm btn-light border text-muted py-0 px-2" title="Reset ke hari ini">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            </form>
        </div>
        
        <div class="card-body p-0">
            <?php if (empty($dataPiket)): ?>
                <div class="text-center py-5 p-4 text-muted">
                    <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-camera-video-off fs-2 text-secondary"></i>
                    </div>
                    <h6 class="fw-semibold text-dark mb-1">Belum Ada Dokumentasi Piket</h6>
                    <p class="small text-muted mb-0">Belum ada dokumentasi piket KBM mahasiswa pada tanggal <strong><?= esc($tanggalPiketIndo) ?></strong>.</p>
                </div>
            <?php else: ?>
                <!-- 1. MOBILE VIEW: Card Feed (< 768px) -->
                <div class="d-block d-md-none p-3 bg-light bg-opacity-50">
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($dataPiket as $row): ?>
                            <?php
                            $tsRow = strtotime($row['tanggal']);
                            $tglFmt = date('d', $tsRow) . ' ' . ($namaBulanSingkat[date('m', $tsRow)] ?? date('M', $tsRow)) . ' ' . date('Y', $tsRow);
                            ?>
                            <div class="card piket-item-card p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2 pb-2 border-bottom">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1"><?= esc($row['nama']) ?></h6>
                                        <span class="badge bg-light text-dark border"><?= esc($row['jurusan']) ?></span>
                                    </div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 small fw-semibold">
                                        <i class="bi bi-clock me-1"></i><?= esc(date('H:i', strtotime($row['waktu']))) ?> WIB
                                    </span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between pt-1">
                                    <span class="small text-muted">
                                        <i class="bi bi-calendar-event me-1 text-secondary"></i><?= esc($tglFmt) ?>
                                    </span>
                                    <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3 shadow-xs"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalFoto<?= (int) $row['id'] ?>">
                                        <i class="bi bi-image me-1"></i> Lihat Foto
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 2. DESKTOP VIEW: Full Data Table (>= 768px) -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover table-bordered table-custom text-center mb-0">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Tanggal</th>
                                <th width="12%">Waktu</th>
                                <th width="30%" class="text-start">Nama Mahasiswa</th>
                                <th width="18%">Jurusan Asal</th>
                                <th width="20%">Dokumentasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($dataPiket as $row): ?>
                                <?php
                                $tsRow = strtotime($row['tanggal']);
                                $tglFmt = date('d', $tsRow) . ' ' . ($namaBulanSingkat[date('m', $tsRow)] ?? date('M', $tsRow)) . ' ' . date('Y', $tsRow);
                                ?>
                                <tr>
                                    <td><span class="text-muted fw-semibold"><?= esc($no++) ?></span></td>
                                    <td><?= esc($tglFmt) ?></td>
                                    <td class="fw-semibold text-success"><?= esc(date('H:i', strtotime($row['waktu']))) ?> WIB</td>
                                    <td class="text-start fw-bold text-dark"><?= esc($row['nama']) ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark border"><?= esc($row['jurusan']) ?></span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3 shadow-xs"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalFoto<?= (int) $row['id'] ?>">
                                            <i class="bi bi-image me-1"></i> Lihat Foto
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Modal Foto Bukti (Dapat Diakses dari Mobile Feed & Desktop Table) -->
                <?php foreach ($dataPiket as $row): ?>
                    <div class="modal fade" id="modalFoto<?= (int) $row['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
                                <div class="modal-header border-0 pb-2">
                                    <h6 class="modal-title fw-bold text-dark">
                                        <i class="bi bi-camera text-success me-1"></i> Bukti Piket: <?= esc($row['nama']) ?>
                                    </h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-0 text-center bg-dark">
                                    <img src="<?= base_url('uploads/piket/' . esc($row['foto_bukti'])) ?>"
                                        alt="Bukti Piket KBM" class="img-fluid w-100" style="max-height: 70vh; object-fit: contain;">
                                </div>
                                <div class="modal-footer border-0 py-2 bg-light d-flex justify-content-between">
                                    <?php
                                    $tsRow = strtotime($row['tanggal']);
                                    $tglModal = date('d', $tsRow) . ' ' . ($namaBulanSingkat[date('m', $tsRow)] ?? date('M', $tsRow)) . ' ' . date('Y', $tsRow);
                                    ?>
                                    <small class="text-muted"><?= esc($tglModal) ?> &bull; <?= esc(date('H:i', strtotime($row['waktu']))) ?> WIB</small>
                                    <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>