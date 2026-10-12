<?= $this->extend('layout/template') ?>

<?= $this->section('styles') ?>
<style>
    /* Admin Container & Cards */
    .admin-card {
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

    /* Operational Status Banner */
    .operational-banner {
        border-radius: 1rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(15, 81, 50, 0.03);
    }

    /* Stat Cards */
    .stat-card-modern {
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
    }

    .stat-card-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(15, 81, 50, 0.06);
    }

    .stat-icon-circle {
        width: 44px;
        height: 44px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    /* Table */
    .table-custom thead th {
        background-color: #0f5132;
        color: #ffffff;
        font-weight: 600;
        border-bottom: none;
        padding: 0.9rem;
        white-space: nowrap;
        font-size: 0.85rem;
        letter-spacing: 0.3px;
    }

    .table-custom tbody td {
        padding: 0.85rem;
        vertical-align: middle;
        font-size: 0.875rem;
    }

    /* User Avatar Circle */
    .avatar-initial {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
        flex-shrink: 0;
    }

    /* Mobile Snapshot Item Card */
    .snapshot-item-card {
        border-radius: 0.85rem;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Helper inisial avatar
function getInitials($name)
{
    $parts = preg_split("/\s+/", trim((string) $name));
    $initials = '';
    foreach ($parts as $p) {
        if (!empty($p)) {
            $initials .= mb_strtoupper(mb_substr($p, 0, 1));
            if (mb_strlen($initials) >= 2) break;
        }
    }
    return $initials ?: 'U';
}

// Format Tanggal Indonesia Formal
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
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember'
];
$hariIni = $namaHariIndo[date('l')] ?? date('l');
$bulanIni = $namaBulanIndo[date('m')] ?? date('F');
$tanggalIndo = $hariIni . ', ' . date('d') . ' ' . $bulanIni . ' ' . date('Y');
$tanggalRingkasIndo = date('d') . ' ' . $bulanIni . ' ' . date('Y');
?>

<div class="container-fluid px-4 py-4">

    <!-- Header Halaman: Pemantauan Presensi & Operasional -->
    <div class="mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-speedometer2 text-success"></i>
                    <span>Ringkasan Presensi &amp; Operasional</span>
                </h4>
                <p class="text-muted small mt-1 mb-0">Pemantauan kehadiran mahasiswa praktikan dan status operasional di <?= esc($school_name ?? 'SMK Negeri 3 Yogyakarta') ?></p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill small">
                    <i class="bi bi-calendar-event me-1 text-success"></i>
                    <?= esc($tanggalIndo) ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Quick Status Bar: Kebijakan Operasional Presensi & Periode Aktif -->
    <div class="operational-banner p-3 p-md-3 mb-4">
        <div class="row g-3 align-items-center">
            <!-- 1. Periode PPL/PK Aktif -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon-circle flex-shrink-0" style="width: 40px; height: 40px; background-color: #dcfce7; color: #166534; font-size: 1.1rem;">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="lh-sm overflow-hidden">
                        <small class="text-muted d-block" style="font-size: 0.72rem;">Periode Aktif</small>
                        <?php if (!empty($periode_aktif)): ?>
                            <a href="<?= base_url('admin/periode') ?>" class="fw-bold text-success text-truncate d-block text-decoration-none" style="font-size: 0.85rem;" title="Kelola Periode (<?= count($daftar_periode ?? []) ?> gelombang terdaftar)">
                                <?= esc($periode_aktif['nama_periode']) ?>
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('admin/periode') ?>" class="fw-bold text-warning-emphasis text-decoration-none small d-block" title="Atur Periode (<?= count($daftar_periode ?? []) ?> gelombang)">
                                Belum Ditentukan
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- 2. Radius Geofencing GPS -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon-circle flex-shrink-0" style="width: 40px; height: 40px; background-color: <?= !empty($geofence_active) ? '#ccfbf1' : '#fef3c7' ?>; color: <?= !empty($geofence_active) ? '#0f766e' : '#92400e' ?>; font-size: 1.1rem;">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div class="lh-sm">
                        <small class="text-muted d-block" style="font-size: 0.72rem;">Geofencing GPS</small>
                        <?php if (!empty($geofence_active)): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5" style="font-size: 0.75rem;">
                                Radius <?= esc($school_radius) ?>m Aktif
                            </span>
                        <?php else: ?>
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2 py-0.5" style="font-size: 0.75rem;">
                                Toleransi Nonaktif
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- 3. Batas Jam Presensi Datang & Pulang -->
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon-circle flex-shrink-0" style="width: 40px; height: 40px; background-color: #f1f5f9; color: #475569; font-size: 1.1rem;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="lh-sm">
                        <small class="text-muted d-block" style="font-size: 0.72rem;">Batas Jam Presensi</small>
                        <div class="d-flex align-items-center gap-1.5 mt-0.5">
                            <span class="badge bg-light text-dark border rounded-pill px-2 py-0.5 font-monospace" style="font-size: 0.75rem;" title="Maksimal Jam Datang">
                                <i class="bi bi-box-arrow-in-right text-success me-1"></i><?= esc(substr($jam_masuk_max ?? '07:15:00', 0, 5)) ?>
                            </span>
                            <span class="text-muted" style="font-size: 0.75rem;">—</span>
                            <span class="badge bg-light text-dark border rounded-pill px-2 py-0.5 font-monospace" style="font-size: 0.75rem;" title="Minimal Jam Pulang">
                                <i class="bi bi-box-arrow-right text-warning me-1"></i><?= esc(substr($jam_pulang_min ?? '15:00:00', 0, 5)) ?> WIB
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Tombol Aksi Kelola Pengaturan -->
            <div class="col-12 col-sm-6 col-xl-2 text-xl-end">
                <a href="<?= base_url('admin/pengaturan') ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1.5 d-inline-flex align-items-center justify-content-center gap-1.5 w-100 w-xl-auto shadow-xs" style="font-size: 0.8rem;">
                    <i class="bi bi-sliders me-1"></i>
                    <span> Kelola Pengaturan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Ringkasan Statistik Modern (Tema Skagata Emerald) -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card stat-card-modern p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-medium">Mahasiswa PPL/PK</span>
                    <div class="stat-icon-circle" style="background-color: #ccfbf1; color: #0f766e;">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?= $totalMahasiswa ?></h3>
                <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">
                    Praktikan (<?= count($daftar_universitas ?? []) ?> universitas mitra)
                </small>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card stat-card-modern p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-medium">Guru Pamong / GTT</span>
                    <div class="stat-icon-circle" style="background-color: #fef3c7; color: #92400e;">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?= $totalGuru ?></h3>
                <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">
                    Pamong (<?= count($daftar_jurusan ?? []) ?> jurusan bimbingan)
                </small>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card stat-card-modern p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-medium">Hadir Tepat Waktu</span>
                    <div class="stat-icon-circle bg-success-subtle text-success">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-success mb-0"><?= $totalHadirHariIni ?></h3>
                <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Hari ini (sebelum batas)</small>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card stat-card-modern p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-medium">Terlambat / Izin</span>
                    <div class="stat-icon-circle" style="background-color: #fee2e2; color: #991b1b;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h3 class="fw-bold text-danger mb-0"><?= $totalTerlambatHariIni ?></h3>
                    <span class="text-muted small">/ <?= $totalIzinSakitHariIni ?> izin</span>
                </div>
                <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Terlambat / Izin &amp; Sakit</small>
            </div>
        </div>
    </div>

    <!-- Live Snapshot Presensi Hari Ini (Full Width) -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card admin-card">
                <div class="card-header-custom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-activity text-success"></i>
                            <span>Snapshot Presensi Hari Ini</span>
                        </h6>
                        <small class="text-muted">Aktivitas kehadiran praktikan per <?= esc($tanggalRingkasIndo) ?></small>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <?php if (!empty($daftar_jurusan)): ?>
                            <div class="d-flex align-items-center gap-1.5 py-1 px-2.5 bg-light rounded-pill border shadow-xs" style="font-size: 0.82rem;">
                                <i class="bi bi-funnel text-success ms-3"></i>
                                <select id="filterJurusanSnapshot" class="form-select form-select-sm border-0 bg-transparent py-0 shadow-none fw-medium" style="font-size: 0.82rem; cursor: pointer; min-width: 140px;" onchange="filterSnapshotByJurusan(this.value)">
                                    <option value="">Semua Jurusan</option>
                                    <?php foreach ($daftar_jurusan as $jrs): ?>
                                        <option value="<?= esc($jrs) ?>"><?= esc($jrs) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>
                        <span class="badge bg-success rounded-pill px-3 py-1.5 font-monospace" style="font-size: 0.75rem;" id="badgeTotalPresensi">
                            <?= count($presensiHariIni) ?> Tercatat
                        </span>
                    </div>
                </div>

                <?php if (empty($presensiHariIni)): ?>
                    <div class="text-center py-5">
                        <div class="py-4">
                            <i class="bi bi-cup-hot text-muted opacity-50" style="font-size: 2.5rem;"></i>
                            <h6 class="fw-semibold text-muted mt-3 mb-1">Belum Ada Presensi Masuk</h6>
                            <p class="text-muted small mb-0">Belum ada praktikan yang melakukan presensi hari ini.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- ============================================== -->
                    <!-- 1. MOBILE VIEW: Responsive Cards (< 768px)     -->
                    <!-- ============================================== -->
                    <div class="d-block d-md-none p-3 bg-light bg-opacity-50">
                        <div class="d-flex flex-column gap-3 snapshot-mobile-cards">
                            <?php foreach ($presensiHariIni as $p): ?>
                                <div class="card snapshot-item-card p-3" data-jurusan="<?= esc(strtolower($p['jurusan'] ?? '')) ?>">
                                    <div class="d-flex justify-content-between align-items-start mb-2 pb-2 border-bottom">
                                        <div class="d-flex align-items-center gap-2 overflow-hidden pe-2">
                                            <div class="avatar-initial" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                <?= esc(getInitials($p['nama'])) ?>
                                            </div>
                                            <div class="overflow-hidden">
                                                <div class="fw-bold text-dark text-truncate" style="font-size: 0.875rem; max-width: 170px;">
                                                    <?= esc($p['nama']) ?>
                                                </div>
                                                <span class="badge bg-light text-dark border rounded-pill px-2 py-0.5 mt-0.5" style="font-size: 0.68rem;">
                                                    <?= esc($p['jurusan'] ?? '-') ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 text-end">
                                            <?php if ($p['status'] === 'hadir'): ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                                    <i class="bi bi-check-circle me-1"></i>Hadir
                                                </span>
                                            <?php elseif ($p['status'] === 'terlambat'): ?>
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                                    <i class="bi bi-clock me-1"></i>Terlambat
                                                </span>
                                            <?php elseif (in_array($p['status'], ['izin', 'sakit'], true)): ?>
                                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                                    <i class="bi bi-info-circle me-1"></i><?= ucfirst(esc($p['status'])) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary rounded-pill px-2 py-1" style="font-size: 0.72rem;"><?= esc($p['status']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Jam Datang & Pulang Mini Chips -->
                                    <div class="row g-2 text-center small">
                                        <div class="col-6">
                                            <div class="p-2 rounded bg-light border">
                                                <div class="text-muted" style="font-size: 0.7rem;"><i class="bi bi-box-arrow-in-right text-success me-1"></i>Datang</div>
                                                <div class="fw-bold text-dark mt-0.5 font-monospace" style="font-size: 0.85rem;">
                                                    <?= !empty($p['jam_masuk']) ? esc(substr($p['jam_masuk'], 0, 5)) . ' WIB' : '--:--' ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 rounded bg-light border">
                                                <div class="text-muted" style="font-size: 0.7rem;"><i class="bi bi-box-arrow-right text-warning me-1"></i>Pulang</div>
                                                <div class="fw-bold text-dark mt-0.5 font-monospace" style="font-size: 0.85rem;">
                                                    <?= !empty($p['jam_keluar']) ? esc(substr($p['jam_keluar'], 0, 5)) . ' WIB' : '--:--' ?>
                                                </div>
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
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover table-custom align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 50px;" class="text-center">No</th>
                                    <th>Mahasiswa</th>
                                    <th>Jurusan</th>
                                    <th>Jam Datang</th>
                                    <th>Status</th>
                                    <th>Jam Pulang</th>
                                </tr>
                            </thead>
                            <tbody id="snapshotTableBody">
                                <?php $no = 1;
                                foreach ($presensiHariIni as $p): ?>
                                    <tr data-jurusan="<?= esc(strtolower($p['jurusan'] ?? '')) ?>">
                                        <td class="text-center text-muted fw-semibold" style="font-size: 0.85rem;"><?= $no++ ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-initial" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                    <?= esc(getInitials($p['nama'])) ?>
                                                </div>
                                                <div class="fw-semibold text-dark lh-sm" style="font-size: 0.85rem;">
                                                    <?= esc($p['nama']) ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1" style="font-size: 0.75rem;">
                                                <?= esc($p['jurusan'] ?? '-') ?>
                                            </span>
                                        </td>
                                        <td class="font-monospace fw-medium text-dark" style="font-size: 0.85rem;">
                                            <?= !empty($p['jam_masuk']) ? esc(substr($p['jam_masuk'], 0, 5)) . ' WIB' : '-' ?>
                                        </td>
                                        <td>
                                            <?php if ($p['status'] === 'hadir'): ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">
                                                    <i class="bi bi-check-circle me-1"></i>Hadir
                                                </span>
                                            <?php elseif ($p['status'] === 'terlambat'): ?>
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2.5 py-1">
                                                    <i class="bi bi-clock me-1"></i>Terlambat
                                                </span>
                                            <?php elseif (in_array($p['status'], ['izin', 'sakit'], true)): ?>
                                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill px-2.5 py-1">
                                                    <i class="bi bi-info-circle me-1"></i><?= ucfirst(esc($p['status'])) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary rounded-pill px-2.5 py-1"><?= esc($p['status']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="font-monospace text-muted" style="font-size: 0.85rem;">
                                            <?= !empty($p['jam_keluar']) ? esc(substr($p['jam_keluar'], 0, 5)) . ' WIB' : '<span class="text-muted small">-</span>' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function filterSnapshotByJurusan(jurusan) {
        const selected = (jurusan || '').trim().toLowerCase();
        let countVisible = 0;

        // Filter tabel desktop
        const rows = document.querySelectorAll('#snapshotTableBody tr');
        rows.forEach(function(row) {
            const rowJurusan = (row.getAttribute('data-jurusan') || '').trim().toLowerCase();
            if (!selected || rowJurusan === selected) {
                row.style.display = '';
                countVisible++;
            } else {
                row.style.display = 'none';
            }
        });

        // Filter kartu mobile
        const cards = document.querySelectorAll('.snapshot-mobile-cards .snapshot-item-card');
        cards.forEach(function(card) {
            const cardJurusan = (card.getAttribute('data-jurusan') || '').trim().toLowerCase();
            if (!selected || cardJurusan === selected) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });

        const badge = document.getElementById('badgeTotalPresensi');
        if (badge) {
            badge.textContent = countVisible + ' Tercatat';
        }
    }
</script>
<?= $this->endSection() ?>