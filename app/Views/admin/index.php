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

    /* Quick Action Button Card */
    .quick-action-item {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.85rem 1rem;
        border-radius: 0.75rem;
        border: 1px solid #f1f5f9;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s ease;
        background-color: #f8fafc;
    }

    .quick-action-item:hover {
        background-color: #f1f5f9;
        border-color: #e2e8f0;
        transform: translateX(3px);
        color: inherit;
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
?>

<div class="container-fluid px-4 py-4">

    <!-- Top Header: Dashboard Eksekutif -->
    <div class="mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-grid-1x2-fill text-success"></i>
                    <span>Dashboard Administrator</span>
                </h4>
                <p class="text-muted small mt-1 mb-0">Pusat kontrol monitoring kehadiran &amp; sistem operasional di <?= esc($school_name ?? 'SMK Negeri 3 Yogyakarta') ?></p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill small">
                    <i class="bi bi-calendar-event me-1 text-success"></i>
                    <?= date('l, d F Y') ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Quick Status Bar: Kebijakan Operasional Presensi -->
    <div class="operational-banner p-3 p-md-4 mb-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small fw-medium">Status Geofencing:</span>
                    <?php if (!empty($geofence_active)): ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                            <i class="bi bi-geo-alt-fill me-1"></i>Radius <?= esc($school_radius) ?>m Aktif
                        </span>
                    <?php else: ?>
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2 py-1">
                            <i class="bi bi-geo-alt me-1"></i>Bebas Radius (Toleransi Nonaktif)
                        </span>
                    <?php endif; ?>
                </div>

                <div class="vr text-muted d-none d-sm-block my-1"></div>

                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small fw-medium">Jam Masuk Maks.:</span>
                    <span class="badge bg-light text-dark border rounded-pill px-2 py-1 font-monospace">
                        <i class="bi bi-alarm me-1 text-success"></i><?= esc(substr($jam_masuk_max ?? '07:15:00', 0, 5)) ?> WIB
                    </span>
                </div>

                <div class="vr text-muted d-none d-sm-block my-1"></div>

                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small fw-medium">Jam Pulang Min.:</span>
                    <span class="badge bg-light text-dark border rounded-pill px-2 py-1 font-monospace">
                        <i class="bi bi-box-arrow-right me-1 text-warning"></i><?= esc(substr($jam_pulang_min ?? '15:00:00', 0, 5)) ?> WIB
                    </span>
                </div>
            </div>

            <div>
                <a href="<?= base_url('admin/pengaturan') ?>" class="text-decoration-none small fw-semibold text-success d-inline-flex align-items-center gap-1">
                    <span>Ubah Konfigurasi GPS &amp; Jam</span>
                    <i class="bi bi-arrow-right"></i>
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
                <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Praktikan aktif terdaftar</small>
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
                <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Pamong &amp; pembimbing</small>
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

    <!-- Layout Dua Kolom: Live Snapshot Presensi & Quick Action Widgets -->
    <div class="row g-4 mb-4">
        <!-- Kolom Kiri: Live Snapshot Presensi Hari Ini -->
        <div class="col-lg-8">
            <div class="card admin-card h-100">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-activity text-success"></i>
                            <span>Snapshot Presensi Hari Ini</span>
                        </h6>
                        <small class="text-muted">Aktivitas kehadiran praktikan per <?= date('d M Y') ?></small>
                    </div>
                    <span class="badge bg-success rounded-pill px-3 py-1 font-monospace" style="font-size: 0.75rem;">
                        <?= count($presensiHariIni) ?> Tercatat
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Mahasiswa</th>
                                <th>Jurusan</th>
                                <th>Jam Datang</th>
                                <th>Status</th>
                                <th>Jam Pulang</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($presensiHariIni)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="bi bi-cup-hot text-muted opacity-50" style="font-size: 2.5rem;"></i>
                                            <h6 class="fw-semibold text-muted mt-3 mb-1">Belum Ada Presensi Masuk</h6>
                                            <p class="text-muted small mb-0">Belum ada praktikan yang melakukan presensi hari ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach (array_slice($presensiHariIni, 0, 8) as $p): ?>
                                    <tr>
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
                                            <span class="badge bg-light text-dark border rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                                <?= esc($p['jurusan'] ?? '-') ?>
                                            </span>
                                        </td>
                                        <td class="font-monospace fw-medium text-dark" style="font-size: 0.85rem;">
                                            <?= !empty($p['jam_masuk']) ? esc(substr($p['jam_masuk'], 0, 5)) . ' WIB' : '-' ?>
                                        </td>
                                        <td>
                                            <?php if ($p['status'] === 'hadir'): ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                                    <i class="bi bi-check-circle me-1"></i>Hadir
                                                </span>
                                            <?php elseif ($p['status'] === 'terlambat'): ?>
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2 py-1">
                                                    <i class="bi bi-clock me-1"></i>Terlambat
                                                </span>
                                            <?php elseif (in_array($p['status'], ['izin', 'sakit'], true)): ?>
                                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill px-2 py-1">
                                                    <i class="bi bi-info-circle me-1"></i><?= ucfirst(esc($p['status'])) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary rounded-pill px-2 py-1"><?= esc($p['status']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="font-monospace text-muted" style="font-size: 0.85rem;">
                                            <?= !empty($p['jam_keluar']) ? esc(substr($p['jam_keluar'], 0, 5)) . ' WIB' : '<span class="text-muted small">-</span>' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (count($presensiHariIni) > 8): ?>
                    <div class="card-footer bg-light border-0 py-2 px-3 text-center">
                        <small class="text-muted">Menampilkan 8 aktivitas terbaru dari total <?= count($presensiHariIni) ?> presensi hari ini.</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Kolom Kanan: Pintasan Akses Cepat & Status Admin -->
        <div class="col-lg-4">
            <div class="card admin-card mb-4">
                <div class="card-header-custom">
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-lightning-charge-fill text-warning"></i>
                        <span>Aksi Cepat Administrator</span>
                    </h6>
                </div>
                <div class="p-3 d-flex flex-column gap-2">
                    <a href="<?= base_url('admin/pengguna') ?>" class="quick-action-item">
                        <div class="stat-icon-circle bg-success-subtle text-success" style="width: 38px; height: 38px; font-size: 1rem;">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="lh-sm">
                            <span class="d-block fw-bold text-dark" style="font-size: 0.88rem;">Kelola Data Pengguna</span>
                            <small class="text-muted" style="font-size: 0.75rem;">Edit, reset password, dan filter akun</small>
                        </div>
                    </a>

                    <a href="#" class="quick-action-item" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                        <div class="stat-icon-circle" style="width: 38px; height: 38px; font-size: 1rem; background-color: #dbeafe; color: #1e40af;">
                            <i class="bi bi-person-plus-fill"></i>
                        </div>
                        <div class="lh-sm">
                            <span class="d-block fw-bold text-dark" style="font-size: 0.88rem;">Tambah Pengguna Baru</span>
                            <small class="text-muted" style="font-size: 0.75rem;">Registrasi mahasiswa atau guru pamong</small>
                        </div>
                    </a>

                    <a href="<?= base_url('admin/periode') ?>" class="quick-action-item">
                        <div class="stat-icon-circle" style="width: 38px; height: 38px; font-size: 1rem; background-color: #d1fae5; color: #065f46;">
                            <i class="bi bi-calendar-range-fill"></i>
                        </div>
                        <div class="lh-sm">
                            <span class="d-block fw-bold text-dark" style="font-size: 0.88rem;">Periode PPL / PK</span>
                            <small class="text-muted" style="font-size: 0.75rem;">Atur tahun ajaran &amp; gelombang aktif</small>
                        </div>
                    </a>

                    <a href="<?= base_url('admin/pengaturan') ?>" class="quick-action-item">
                        <div class="stat-icon-circle" style="width: 38px; height: 38px; font-size: 1rem; background-color: #fef3c7; color: #92400e;">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div class="lh-sm">
                            <span class="d-block fw-bold text-dark" style="font-size: 0.88rem;">Pengaturan Geofencing</span>
                            <small class="text-muted" style="font-size: 0.75rem;">Kalibrasi GPS &amp; batas jam kerja</small>
                        </div>
                    </a>

                    <a href="#" class="quick-action-item" data-bs-toggle="modal" data-bs-target="#modalProfilAdmin">
                        <div class="stat-icon-circle" style="width: 38px; height: 38px; font-size: 1rem; background-color: #f1f5f9; color: #334155;">
                            <i class="bi bi-person-gear"></i>
                        </div>
                        <div class="lh-sm">
                            <span class="d-block fw-bold text-dark" style="font-size: 0.88rem;">Kelola Profil Saya</span>
                            <small class="text-muted" style="font-size: 0.75rem;">Ubah nama, username, &amp; kata sandi</small>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Card Ringkasan Sister App / Info Skagata -->
            <div class="card admin-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <img src="<?= base_url('logo-skagata.png') ?>" alt="Logo Skagata" style="width: 44px; height: 44px; object-fit: contain;">
                    <div class="lh-sm">
                        <span class="d-block fw-bold text-dark" style="font-size: 0.9rem;">SIPENSI SKAGATA</span>
                        <small class="text-muted d-block" style="font-size: 0.75rem;">SMK Negeri 3 Yogyakarta</small>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill mt-1" style="font-size: 0.65rem;">Sistem Presensi PPL/PK</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal Tambah Pengguna Cepat dari Dashboard -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-success text-white py-3">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-person-plus"></i>
                    <span>Tambah Pengguna Baru</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/tambah-user') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body text-start p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Ahmad Fauzi, S.Pd." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Contoh: ahmad123" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Role / Hak Akses</label>
                        <select name="role" class="form-select" id="tambahRoleSelectDash" onchange="toggleJurusanField('tambahRoleSelectDash', 'tambahJurusanWrapperDash', 'tambahUniversitasWrapperDash', 'tambahPeriodeWrapperDash')" required>
                            <option value="mahasiswa" selected>Mahasiswa Praktikan (PPL/PK)</option>
                            <option value="guru">Guru Pamong / GTT</option>
                        </select>
                    </div>
                    <div class="mb-3" id="tambahJurusanWrapperDash">
                        <label class="form-label fw-semibold small text-dark">Jurusan Mahasiswa</label>
                        <select name="jurusan" class="form-select">
                            <option value="">-- Pilih Jurusan Mahasiswa --</option>
                            <?php if (!empty($daftar_jurusan)): ?>
                                <?php foreach ($daftar_jurusan as $jrs): ?>
                                    <option value="<?= esc($jrs) ?>"><?= esc($jrs) ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="Informatika">Informatika</option>
                                <option value="PJOK">PJOK</option>
                                <option value="BK">BK</option>
                                <option value="TL">TL</option>
                                <option value="TO">TO</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3" id="tambahUniversitasWrapperDash">
                        <label class="form-label fw-semibold small text-dark">Asal Universitas / Kampus</label>
                        <select name="universitas" class="form-select">
                            <option value="">-- Pilih Asal Universitas --</option>
                            <?php if (!empty($daftar_universitas)): ?>
                                <?php foreach ($daftar_universitas as $univ): ?>
                                    <option value="<?= esc($univ) ?>"><?= esc($univ) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3" id="tambahPeriodeWrapperDash">
                        <label class="form-label fw-semibold small text-dark">Periode PPL / PK</label>
                        <select name="periode_id" class="form-select">
                            <option value="">-- Pilih Periode PPL / PK --</option>
                            <?php if (!empty($daftar_periode)): ?>
                                <?php foreach ($daftar_periode as $prd): ?>
                                    <option value="<?= $prd['id'] ?>" <?= ((int)$prd['is_aktif'] === 1) ? 'selected' : '' ?>>
                                        <?= esc($prd['nama_periode']) ?><?= ((int)$prd['is_aktif'] === 1) ? ' (Aktif)' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Password Awal</label>
                        <div class="input-group">
                            <input type="password" name="password" id="inputTambahPasswordDash" class="form-control border-end-0" placeholder="Minimal 6 karakter" required>
                            <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" onclick="togglePasswordVisibility('inputTambahPasswordDash', 'iconToggleTambahDash')">
                                <i class="bi bi-eye" id="iconToggleTambahDash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-skagata rounded-pill px-4">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function toggleJurusanField(roleSelectId, jurusanWrapperId, universitasWrapperId, periodeWrapperId) {
        const role = document.getElementById(roleSelectId).value;
        const jWrapper = document.getElementById(jurusanWrapperId);
        const uWrapper = universitasWrapperId ? document.getElementById(universitasWrapperId) : null;
        const pWrapper = periodeWrapperId ? document.getElementById(periodeWrapperId) : null;
        if (jWrapper) {
            jWrapper.style.display = (role === 'mahasiswa') ? 'block' : 'none';
        }
        if (uWrapper) {
            uWrapper.style.display = (role === 'mahasiswa') ? 'block' : 'none';
        }
        if (pWrapper) {
            pWrapper.style.display = (role === 'mahasiswa') ? 'block' : 'none';
        }
    }

    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!input || !icon) return;
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
<?= $this->endSection() ?>
