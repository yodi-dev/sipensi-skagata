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

    /* Table & Filter Toolbar */
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

    .filter-wrapper {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 2rem;
        padding: 0.35rem 0.75rem;
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

<div class="container py-4">

    <!-- Top Header & Aksi Cepat (Termasuk Profil & Logout) -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-shield-lock-fill text-success"></i>
                <span>Beranda Manajemen &amp; Sistem</span>
            </h4>
            <p class="text-muted small mt-1 mb-0">Kelola akun pengguna dan pantau kebijakan presensi di <?= esc($school_name ?? 'SMK Negeri 3 Yogyakarta') ?></p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <button type="button" class="btn btn-skagata btn-sm rounded-pill px-3 shadow-sm d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                <i class="bi bi-person-plus"></i>
                <span>Tambah Pengguna</span>
            </button>
            <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-3 shadow-sm d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalProfilAdmin" title="Kelola Profil & Kata Sandi Administrator">
                <i class="bi bi-person-gear"></i>
                <span>Profil Saya</span>
            </button>
            <a href="<?= base_url('admin/pengaturan') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3 d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-gear"></i>
                <span>Pengaturan Presensi</span>
            </a>
            <a href="<?= base_url('auth/logout') ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm d-inline-flex align-items-center gap-1" title="Keluar dari Sistem">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
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
                    <span class="text-muted small fw-medium">Pengguna Terkelola</span>
                    <div class="stat-icon-circle bg-success-subtle text-success">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?= $totalMahasiswa + $totalGuru ?></h3>
                <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Mahasiswa &amp; Guru</small>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card stat-card-modern p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-medium">Mahasiswa PPL/PK</span>
                    <div class="stat-icon-circle" style="background-color: #ccfbf1; color: #0f766e;">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?= $totalMahasiswa ?></h3>
                <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Praktikan aktif</small>
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
                <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Pamong &amp; pengajar</small>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card stat-card-modern p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-medium">Profil Administrator</span>
                    <div class="stat-icon-circle" style="background-color: #f1f5f9; color: #334155;">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h6 class="fw-bold text-dark mb-0 text-truncate" style="max-width: 140px;"><?= esc($current_admin['nama'] ?? 'Administrator') ?></h6>
                </div>
                <a href="#" class="small text-success text-decoration-none fw-semibold mt-1 d-inline-block" data-bs-toggle="modal" data-bs-target="#modalProfilAdmin">
                    <i class="bi bi-pencil-square me-1"></i>Kelola Profil Saya
                </a>
            </div>
        </div>
    </div>

    <!-- Daftar Pengguna Card (Khusus Mahasiswa & Guru) -->
    <div class="card admin-card">
        <div class="card-header-custom d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-person-lines-fill text-success"></i>
                    <span>Daftar Pengguna Terkelola</span>
                </h6>
                <small class="text-muted">Total terfilter: <?= count($users) ?> orang (Guru &amp; Mahasiswa)</small>
            </div>

            <!-- Toolbar Filter Otomatis (Onchange Submit ala Sibenka) -->
            <form action="<?= base_url('admin') ?>" method="GET" class="filter-wrapper d-flex align-items-center flex-wrap gap-2 m-0 shadow-xs">
                <div class="input-group input-group-sm" style="width: auto;">
                    <span class="input-group-text bg-white border-0 text-muted ps-2 pe-1"><i class="bi bi-search text-success"></i></span>
                    <input type="text" name="keyword" class="form-control border-0 bg-transparent fw-medium" placeholder="Cari nama / username..." value="<?= esc($keyword) ?>" style="min-width: 170px;">
                </div>

                <div class="vr my-1 text-muted d-none d-sm-block"></div>

                <div class="input-group input-group-sm" style="width: auto;">
                    <select name="role" class="form-select form-select-sm border-0 bg-transparent fw-medium" onchange="this.form.submit()" aria-label="Filter Role">
                        <option value="">Semua Role</option>
                        <option value="mahasiswa" <?= ($role_terpilih === 'mahasiswa') ? 'selected' : '' ?>>Mahasiswa Praktikan</option>
                        <option value="guru" <?= ($role_terpilih === 'guru') ? 'selected' : '' ?>>Guru Pamong / GTT</option>
                    </select>
                </div>

                <div class="vr my-1 text-muted d-none d-sm-block"></div>

                <div class="input-group input-group-sm" style="width: auto;">
                    <select name="jurusan" class="form-select form-select-sm border-0 bg-transparent fw-medium" onchange="this.form.submit()" aria-label="Filter Jurusan">
                        <option value="">Semua Jurusan</option>
                        <?php if (!empty($daftar_jurusan)): ?>
                            <?php foreach ($daftar_jurusan as $jrs): ?>
                                <option value="<?= esc($jrs) ?>" <?= ($jurusan_pilih === $jrs) ? 'selected' : '' ?>><?= esc($jrs) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <a href="<?= base_url('admin') ?>" class="btn btn-sm btn-light border rounded-pill text-muted px-2 py-0" title="Reset filter">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered table-custom text-center mb-0 align-middle">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="30%" class="text-start">Pengguna</th>
                        <th width="16%">Username</th>
                        <th width="15%">Role</th>
                        <th width="16%">Jurusan Mahasiswa</th>
                        <th width="18%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 64px; height: 64px;">
                                    <i class="bi bi-people fs-2 text-secondary"></i>
                                </div>
                                <h6 class="fw-semibold text-dark mb-1">Tidak Ada Data Pengguna</h6>
                                <p class="small text-muted mb-0">Tidak ditemukan pengguna yang sesuai dengan kriteria pencarian / filter.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $key => $row): ?>
                            <tr>
                                <td><span class="text-muted"><?= esc($key + 1) ?></span></td>
                                <td class="text-start">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-initial">
                                            <?= esc(getInitials($row['nama'])) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark lh-sm"><?= esc($row['nama']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border px-2 py-1 font-monospace">
                                        @<?= esc($row['username']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($row['role'] === 'guru'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">Guru Pamong / GTT</span>
                                    <?php else: ?>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1">Mahasiswa Praktikan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= !empty($row['jurusan']) ? '<span class="badge bg-light text-dark border rounded-pill px-2 py-1">' . esc($row['jurusan']) . '</span>' : '<span class="text-muted">-</span>' ?>
                                </td>
                                <td>
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-2 py-1 d-inline-flex align-items-center gap-1"
                                            title="Edit Data Pengguna"
                                            onclick="bukaModalEdit(<?= (int) $row['id'] ?>, '<?= esc($row['username']) ?>', '<?= esc(addslashes($row['nama'])) ?>', '<?= esc($row['role']) ?>', '<?= esc($row['jurusan'] ?? '') ?>')">
                                            <i class="bi bi-pencil-square"></i>
                                            <span class="d-none d-xl-inline">Edit</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-2 py-1 d-inline-flex align-items-center gap-1"
                                            title="Reset Password"
                                            onclick="bukaModalReset(<?= (int) $row['id'] ?>, '<?= esc(addslashes($row['nama'])) ?>')">
                                            <i class="bi bi-key"></i>
                                            <span class="d-none d-xl-inline">Reset</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1 d-inline-flex align-items-center gap-1"
                                            title="Hapus Pengguna"
                                            onclick="konfirmasiHapus(<?= (int) $row['id'] ?>, '<?= esc(addslashes($row['nama'])) ?>')">
                                            <i class="bi bi-trash"></i>
                                            <span class="d-none d-xl-inline">Hapus</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Profil Administrator (Ubah Nama, Username, & Password) -->
<div class="modal fade" id="modalProfilAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-success text-white py-3">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-person-gear"></i>
                    <span>Kelola Profil Administrator</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/update-profil') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body text-start p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Nama Administrator</label>
                        <input type="text" name="nama" class="form-control" value="<?= esc($current_admin['nama'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Username Administrator</label>
                        <input type="text" name="username" class="form-control" value="<?= esc($current_admin['username'] ?? '') ?>" required>
                    </div>

                    <hr class="my-3 text-muted">
                    <div class="small fw-bold text-dark mb-2 d-flex align-items-center gap-1">
                        <i class="bi bi-shield-lock text-success"></i>
                        <span>Ubah Kata Sandi (Opsional)</span>
                    </div>
                    <p class="text-muted small mb-3" style="font-size: 0.78rem;">
                        Biarkan kolom kata sandi di bawah kosong jika Anda tidak ingin mengganti password akun admin.
                    </p>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Password Saat Ini</label>
                        <div class="input-group">
                            <input type="password" name="password_lama" id="inputAdminPasswordLama" class="form-control border-end-0" placeholder="Masukkan jika ingin mengganti password">
                            <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" onclick="togglePasswordVisibility('inputAdminPasswordLama', 'iconToggleAdminLama')">
                                <i class="bi bi-eye" id="iconToggleAdminLama"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="password_baru" id="inputAdminPasswordBaru" class="form-control border-end-0" placeholder="Minimal 6 karakter">
                            <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" onclick="togglePasswordVisibility('inputAdminPasswordBaru', 'iconToggleAdminBaru')">
                                <i class="bi bi-eye" id="iconToggleAdminBaru"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-skagata rounded-pill px-4">
                        <i class="bi bi-check2-circle me-1"></i> Simpan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Pengguna Modern -->
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
                        <select name="role" class="form-select" id="tambahRoleSelect" onchange="toggleJurusanField('tambahRoleSelect', 'tambahJurusanWrapper')" required>
                            <option value="mahasiswa" selected>Mahasiswa Praktikan (PPL/PK)</option>
                            <option value="guru">Guru Pamong / GTT</option>
                        </select>
                    </div>
                    <div class="mb-3" id="tambahJurusanWrapper">
                        <label class="form-label fw-semibold small text-dark">Jurusan Mahasiswa</label>
                        <select name="jurusan" class="form-select">
                            <option value="">-- Pilih Jurusan Mahasiswa --</option>
                            <?php if (!empty($daftar_jurusan)): ?>
                                <?php foreach ($daftar_jurusan as $jrs): ?>
                                    <option value="<?= esc($jrs) ?>"><?= esc($jrs) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Password Awal</label>
                        <div class="input-group">
                            <input type="password" name="password" id="inputTambahPassword" class="form-control border-end-0" placeholder="Minimal 6 karakter" required>
                            <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" onclick="togglePasswordVisibility('inputTambahPassword', 'iconToggleTambah')">
                                <i class="bi bi-eye" id="iconToggleTambah"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-skagata rounded-pill px-4">
                        <i class="bi bi-save me-1"></i> Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Pengguna Modern -->
<div class="modal fade" id="modalEditUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-success text-white py-3">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-person-gear"></i>
                    <span>Edit Data Pengguna</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/edit-user') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="editUserId">
                <div class="modal-body text-start p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Nama Lengkap</label>
                        <input type="text" name="nama" id="editNama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Username</label>
                        <input type="text" name="username" id="editUsername" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Role / Hak Akses</label>
                        <select name="role" id="editRole" class="form-select" onchange="toggleJurusanField('editRole', 'editJurusanWrapper')" required>
                            <option value="mahasiswa">Mahasiswa Praktikan (PPL/PK)</option>
                            <option value="guru">Guru Pamong / GTT</option>
                        </select>
                    </div>
                    <div class="mb-3" id="editJurusanWrapper">
                        <label class="form-label fw-semibold small text-dark">Jurusan Mahasiswa</label>
                        <select name="jurusan" id="editJurusan" class="form-select">
                            <option value="">-- Pilih Jurusan Mahasiswa --</option>
                            <?php if (!empty($daftar_jurusan)): ?>
                                <?php foreach ($daftar_jurusan as $jrs): ?>
                                    <option value="<?= esc($jrs) ?>"><?= esc($jrs) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-skagata rounded-pill px-4">
                        <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reset Password Modern -->
<div class="modal fade" id="modalResetPassword" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-warning text-dark py-3">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-key"></i>
                    <span>Reset Password Pengguna</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/reset-password') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="user_id" id="resetUserId">
                <div class="modal-body text-start p-4">
                    <div class="alert alert-light border d-flex align-items-center gap-2 py-2 px-3 rounded-3 mb-3">
                        <i class="bi bi-person-check text-warning fs-5"></i>
                        <div class="small">
                            Reset password akun: <strong id="resetUserNama" class="text-dark"></strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="new_password" id="inputResetPassword" class="form-control border-end-0" placeholder="Minimal 6 karakter" required>
                            <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" onclick="togglePasswordVisibility('inputResetPassword', 'iconToggleReset')">
                                <i class="bi bi-eye" id="iconToggleReset"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-warning rounded-pill px-4">
                        <i class="bi bi-arrow-repeat me-1"></i> Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form Tersembunyi untuk Hapus User via POST + CSRF -->
<form id="formHapusUser" action="<?= base_url('admin/hapus-user') ?>" method="POST" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="user_id" id="hapusUserId">
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function toggleJurusanField(roleSelectId, wrapperId) {
        const role = document.getElementById(roleSelectId).value;
        const wrapper = document.getElementById(wrapperId);
        if (role === 'mahasiswa') {
            wrapper.style.display = 'block';
        } else {
            wrapper.style.display = 'none';
        }
    }

    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
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

    function bukaModalEdit(id, username, nama, role, jurusan) {
        document.getElementById('editUserId').value = id;
        document.getElementById('editUsername').value = username;
        document.getElementById('editNama').value = nama;
        document.getElementById('editRole').value = role;
        document.getElementById('editJurusan').value = jurusan;
        toggleJurusanField('editRole', 'editJurusanWrapper');

        const modal = new bootstrap.Modal(document.getElementById('modalEditUser'));
        modal.show();
    }

    function bukaModalReset(id, nama) {
        document.getElementById('resetUserId').value = id;
        document.getElementById('resetUserNama').innerText = nama;
        const input = document.getElementById('inputResetPassword');
        if (input) input.value = '';

        const modal = new bootstrap.Modal(document.getElementById('modalResetPassword'));
        modal.show();
    }

    function konfirmasiHapus(id, nama) {
        Swal.fire({
            title: 'Hapus Pengguna?',
            html: `Akun <strong>${nama}</strong> dan seluruh riwayat presensi terkait akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus Akun!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('hapusUserId').value = id;
                document.getElementById('formHapusUser').submit();
            }
        });
    }
</script>
<?= $this->endSection() ?>
