<?= $this->extend('layout/template') ?>

<?= $this->section('styles') ?>
<style>
    .dashboard-card {
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

    /* Mobile Presence Card Styling */
    .student-presence-card {
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        transition: transform 0.15s ease;
    }

    .student-presence-card:active {
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

$tsTanggal = strtotime($tanggal);
$hariTerpilih = $namaHariIndo[date('l', $tsTanggal)] ?? date('l', $tsTanggal);
$bulanTerpilih = $namaBulanIndo[date('m', $tsTanggal)] ?? date('F', $tsTanggal);
$tanggalFormatIndo = $hariTerpilih . ', ' . date('d', $tsTanggal) . ' ' . $bulanTerpilih . ' ' . date('Y', $tsTanggal);
?>
<div class="container py-4">

    <!-- Top Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-3 bg-success-subtle text-success border border-success-subtle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px;">
                <i class="bi bi-calendar2-check-fill fs-4"></i>
            </div>
            <div>
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <h4 class="fw-bold text-dark mb-0" style="letter-spacing: -0.3px;">Monitoring Presensi Harian</h4>
                    <?php if (!empty($assigned_jurusans)): ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fw-semibold small" style="font-size: 0.72rem;">
                            <i class="bi bi-mortarboard-fill me-1"></i>Pamong: <?= esc(implode(', ', $assigned_jurusans)) ?>
                        </span>
                    <?php endif; ?>
                </div>
                <p class="text-muted small mb-0 mt-1">Verifikasi kehadiran dan perizinan mahasiswa praktikan di SMKN 3 Yogyakarta</p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url('guru/laporan_piket') ?>" class="btn btn-outline-success btn-sm rounded-pill px-3 shadow-xs">
                <i class="bi bi-camera me-1"></i> Laporan Piket
            </a>
            <a href="<?= base_url('guru/laporan') ?>" class="btn btn-skagata btn-sm rounded-pill px-3 shadow-sm">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Rekap Bulanan
            </a>
        </div>
    </div>

    <?php if (empty($assigned_jurusans) && session()->get('role') === 'guru'): ?>
        <div class="alert alert-warning rounded-4 border-0 shadow-sm d-flex align-items-center gap-3 mb-4 p-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill text-warning fs-3"></i>
            <div>
                <strong class="d-block text-dark">Akun Guru Pamong Belum Dipetakan</strong>
                <span class="text-muted small">Akun Anda saat ini belum dipetakan ke jurusan mahasiswa manapun. Silakan hubungi Administrator Sistem untuk mengatur pemetaan jurusan bimbingan Anda agar data mahasiswa dapat ditampilkan.</span>
            </div>
        </div>
    <?php endif; ?>

    <div class="card dashboard-card">

        <div class="card-header-custom d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <h6 class="mb-1 fw-bold text-dark">Data Presensi Tanggal Terpilih</h6>
                <p class="text-muted small mb-0">
                    Menampilkan data untuk: <span class="fw-semibold text-success"><?= esc($tanggalFormatIndo) ?></span>
                </p>
            </div>

            <!-- Auto-Filter Form ala Sibenka (Tanpa Tombol Cari Manual) -->
            <form action="<?= base_url('guru') ?>" method="GET" class="d-flex flex-column flex-md-row gap-2 align-items-md-center m-0">

                <?php if (!empty($daftar_jurusan) && count($daftar_jurusan) > 1): ?>
                    <div class="filter-wrapper d-flex align-items-center gap-2">
                        <label for="jurusan" class="fw-semibold text-muted small mb-0 text-nowrap"><i class="bi bi-funnel"></i> Jurusan:</label>
                        <select name="jurusan" id="jurusan" class="form-select form-select-sm border-0 bg-transparent shadow-none" onchange="this.form.submit()">
                            <option value="">-- Semua Jurusan Bimbingan --</option>
                            <?php foreach ($daftar_jurusan as $jrs): ?>
                                <option value="<?= esc($jrs) ?>" <?= ($jurusan_terpilih === $jrs) ? 'selected' : '' ?>><?= esc($jrs) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="filter-wrapper d-flex align-items-center gap-2">
                    <label for="tanggal" class="fw-semibold text-muted small mb-0 text-nowrap"><i class="bi bi-calendar-event"></i> Tanggal:</label>
                    <input type="date" id="tanggal" name="tanggal" class="form-control form-control-sm border-0 bg-transparent shadow-none" value="<?= esc($tanggal) ?>" onchange="this.form.submit()" required>
                    <a href="<?= base_url('guru') ?>" class="btn btn-sm btn-light border text-muted py-0 px-2" title="Reset ke hari ini">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>

            </form>
        </div>

        <?php if (empty($presensi)): ?>
            <!-- Empty State -->
            <div class="text-center py-5 p-4 text-muted">
                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 64px; height: 64px;">
                    <i class="bi bi-inbox fs-2 text-secondary"></i>
                </div>
                <h6 class="fw-semibold text-dark mb-1">Belum Ada Presensi Masuk</h6>
                <p class="small text-muted mb-0">Belum ada data presensi mahasiswa pada tanggal <strong><?= esc($tanggalFormatIndo) ?></strong>.</p>
            </div>
        <?php else: ?>

            <!-- ============================================== -->
            <!-- 1. MOBILE VIEW: Responsive Cards (< 768px)     -->
            <!-- ============================================== -->
            <div class="d-block d-md-none p-3 bg-light bg-opacity-50">
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($presensi as $key => $row): ?>
                        <div class="card student-presence-card p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2 pb-2 border-bottom">
                                <div class="overflow-hidden pe-2">
                                    <span class="fw-bold text-dark d-block text-truncate" style="max-width: 190px;"><?= esc($row['nama']) ?></span>
                                    <small class="text-muted" style="font-size: 0.72rem;">#<?= esc($key + 1) ?></small>
                                </div>
                                <div class="flex-shrink-0">
                                    <?php if ($row['status'] === 'hadir'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">Hadir</span>
                                    <?php elseif ($row['status'] === 'terlambat'): ?>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2 py-1">Terlambat</span>
                                    <?php elseif ($row['status'] === 'izin'): ?>
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill px-2 py-1">Izin</span>
                                    <?php elseif ($row['status'] === 'sakit'): ?>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1">Sakit</span>
                                    <?php elseif ($row['status'] === 'alpa'): ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1">Alpa</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border rounded-pill px-2 py-1">Belum Absen</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Jam Masuk & Pulang Chips -->
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <div class="text-center p-2 rounded bg-light border">
                                        <div class="text-muted small" style="font-size: 0.7rem;"><i class="bi bi-box-arrow-in-right text-success me-1"></i>Datang</div>
                                        <div class="fw-bold mt-1 <?= !empty($row['jam_masuk']) ? ($row['status'] === 'terlambat' ? 'text-warning-emphasis' : 'text-success') : 'text-muted' ?>" style="font-size: 0.9rem;">
                                            <?= esc($row['jam_masuk'] ?: '--:--') ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-2 rounded bg-light border">
                                        <div class="text-muted small" style="font-size: 0.7rem;"><i class="bi bi-box-arrow-right text-warning me-1"></i>Pulang</div>
                                        <div class="fw-bold mt-1 <?= !empty($row['jam_keluar']) ? 'text-dark' : 'text-muted' ?>" style="font-size: 0.9rem;">
                                            <?= esc($row['jam_keluar'] ?: '--:--') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Keterangan jika ada -->
                            <?php if (!empty($row['keterangan'])): ?>
                                <div class="bg-light rounded-3 p-2 small text-muted mb-2" style="font-size: 0.8rem;">
                                    <i class="bi bi-chat-quote me-1 text-secondary"></i><?= esc($row['keterangan']) ?>
                                </div>
                            <?php endif; ?>

                            <!-- Tombol Aksi di Mobile -->
                            <div class="d-flex align-items-center gap-2 pt-2 border-top flex-wrap">
                                <?php if (in_array($row['status'], ['hadir', 'terlambat']) && !empty($row['latitude']) && !empty($row['longitude'])): ?>
                                    <a href="https://www.google.com/maps?q=<?= esc((float) $row['latitude']) ?>,<?= esc((float) $row['longitude']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1" style="font-size: 0.75rem;">
                                        <i class="bi bi-geo-alt me-1"></i>Peta GPS
                                    </a>
                                <?php endif; ?>

                                <?php if (!empty($row['bukti_surat'])): ?>
                                    <a href="<?= base_url('uploads/surat/' . esc($row['bukti_surat'])) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-info rounded-pill px-3 py-1" style="font-size: 0.75rem;">
                                        <i class="bi bi-file-earmark-text me-1"></i>Bukti Surat
                                    </a>
                                <?php endif; ?>

                                <div class="dropdown ms-auto">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle rounded-pill px-3 py-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.75rem;">
                                        <i class="bi bi-pencil-square me-1"></i>Ubah Status
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                        <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="konfirmasiUbahStatus(<?= (int) $row['id'] ?>, <?= htmlspecialchars(json_encode($row['nama']), ENT_QUOTES, 'UTF-8') ?>, 'hadir')"><i class="bi bi-check-circle text-success me-2"></i> Set Hadir</a></li>
                                        <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="konfirmasiUbahStatus(<?= (int) $row['id'] ?>, <?= htmlspecialchars(json_encode($row['nama']), ENT_QUOTES, 'UTF-8') ?>, 'terlambat')"><i class="bi bi-clock-history text-warning me-2"></i> Set Terlambat</a></li>
                                        <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="konfirmasiUbahStatus(<?= (int) $row['id'] ?>, <?= htmlspecialchars(json_encode($row['nama']), ENT_QUOTES, 'UTF-8') ?>, 'izin')"><i class="bi bi-info-circle text-info me-2"></i> Set Izin</a></li>
                                        <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="konfirmasiUbahStatus(<?= (int) $row['id'] ?>, <?= htmlspecialchars(json_encode($row['nama']), ENT_QUOTES, 'UTF-8') ?>, 'sakit')"><i class="bi bi-bandaid text-secondary me-2"></i> Set Sakit</a></li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li><a class="dropdown-item py-2 text-danger" href="javascript:void(0)" onclick="konfirmasiUbahStatus(<?= (int) $row['id'] ?>, <?= htmlspecialchars(json_encode($row['nama']), ENT_QUOTES, 'UTF-8') ?>, 'alpa')"><i class="bi bi-x-circle me-2"></i> Set Alpa</a></li>
                                    </ul>
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
                <table class="table table-hover table-bordered table-custom text-center">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="22%" class="text-start">Nama Mahasiswa</th>
                            <th width="10%">Status</th>
                            <th width="12%">Jam Masuk</th>
                            <th width="12%">Jam Pulang</th>
                            <th width="25%" class="text-start">Keterangan</th>
                            <th width="14%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($presensi as $key => $row): ?>
                            <tr>
                                <td><span class="text-muted fw-semibold"><?= esc($key + 1) ?></span></td>
                                <td class="text-start fw-bold text-dark"><?= esc($row['nama']) ?></td>

                                <td>
                                    <?php if ($row['status'] === 'hadir'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Hadir</span>
                                    <?php elseif ($row['status'] === 'terlambat'): ?>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">Terlambat</span>
                                    <?php elseif ($row['status'] === 'izin'): ?>
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1">Izin</span>
                                    <?php elseif ($row['status'] === 'sakit'): ?>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Sakit</span>
                                    <?php elseif ($row['status'] === 'alpa'): ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Alpa</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border px-2 py-1">Belum Absen</span>
                                    <?php endif; ?>
                                </td>

                                <td><span class="fw-semibold <?= !empty($row['jam_masuk']) ? ($row['status'] === 'terlambat' ? 'text-warning' : 'text-success') : 'text-muted' ?>"><?= esc($row['jam_masuk'] ?: '--:--') ?></span></td>
                                <td><span class="fw-semibold <?= !empty($row['jam_keluar']) ? 'text-dark' : 'text-muted' ?>"><?= esc($row['jam_keluar'] ?: '--:--') ?></span></td>

                                <td class="text-start text-muted small">
                                    <?= !empty($row['keterangan']) ? esc($row['keterangan']) : '<span class="text-muted fst-italic">Tidak ada catatan</span>' ?>
                                </td>

                                <td>
                                    <?php if (in_array($row['status'], ['hadir', 'terlambat']) && !empty($row['latitude']) && !empty($row['longitude'])): ?>
                                        <a href="https://www.google.com/maps?q=<?= esc((float) $row['latitude']) ?>,<?= esc((float) $row['longitude']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-success rounded-pill mb-1" title="Lihat Lokasi GPS">
                                            <i class="bi bi-geo-alt"></i> Map
                                        </a>
                                    <?php endif; ?>

                                    <?php if (!empty($row['bukti_surat'])): ?>
                                        <a href="<?= base_url('uploads/surat/' . esc($row['bukti_surat'])) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-info rounded-pill mb-1" title="Lihat Bukti Surat">
                                            <i class="bi bi-file-earmark-text"></i> Bukti
                                        </a>
                                    <?php endif; ?>

                                    <div class="dropdown d-inline">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle rounded-pill" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-pencil-square"></i> Status
                                        </button>
                                        <ul class="dropdown-menu shadow border-0">
                                            <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="konfirmasiUbahStatus(<?= (int) $row['id'] ?>, <?= htmlspecialchars(json_encode($row['nama']), ENT_QUOTES, 'UTF-8') ?>, 'hadir')"><i class="bi bi-check-circle text-success me-2"></i> Set Hadir</a></li>
                                            <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="konfirmasiUbahStatus(<?= (int) $row['id'] ?>, <?= htmlspecialchars(json_encode($row['nama']), ENT_QUOTES, 'UTF-8') ?>, 'terlambat')"><i class="bi bi-clock-history text-warning me-2"></i> Set Terlambat</a></li>
                                            <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="konfirmasiUbahStatus(<?= (int) $row['id'] ?>, <?= htmlspecialchars(json_encode($row['nama']), ENT_QUOTES, 'UTF-8') ?>, 'izin')"><i class="bi bi-info-circle text-info me-2"></i> Set Izin</a></li>
                                            <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="konfirmasiUbahStatus(<?= (int) $row['id'] ?>, <?= htmlspecialchars(json_encode($row['nama']), ENT_QUOTES, 'UTF-8') ?>, 'sakit')"><i class="bi bi-bandaid text-secondary me-2"></i> Set Sakit</a></li>
                                            <li><hr class="dropdown-divider my-1"></li>
                                            <li><a class="dropdown-item py-2 text-danger" href="javascript:void(0)" onclick="konfirmasiUbahStatus(<?= (int) $row['id'] ?>, <?= htmlspecialchars(json_encode($row['nama']), ENT_QUOTES, 'UTF-8') ?>, 'alpa')"><i class="bi bi-x-circle me-2"></i> Set Alpa</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>
    </div>

</div>

<!-- Form Tersembunyi untuk Update Status via POST + CSRF -->
<form id="formUpdateStatus" action="<?= base_url('guru/update-status') ?>" method="POST" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="user_id" id="statusUserId">
    <input type="hidden" name="status" id="statusPilihan">
    <input type="hidden" name="tanggal" value="<?= esc($tanggal) ?>">
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function konfirmasiUbahStatus(userId, namaMahasiswa, statusBaru) {
        const badgeColors = {
            'hadir': '#0f5132',
            'terlambat': '#d97706',
            'izin': '#0284c7',
            'sakit': '#64748b',
            'alpa': '#dc2626'
        };

        Swal.fire({
            title: 'Ubah Status Presensi?',
            html: `Ubah kehadiran <strong>${namaMahasiswa}</strong> menjadi <span style="color: ${badgeColors[statusBaru] || '#0f5132'}; font-weight: bold;">${statusBaru.toUpperCase()}</span>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0f5132',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Simpan Perubahan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('statusUserId').value = userId;
                document.getElementById('statusPilihan').value = statusBaru;
                document.getElementById('formUpdateStatus').submit();
            }
        });
    }
</script>
<?= $this->endSection() ?>