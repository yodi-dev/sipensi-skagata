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

    /* Stat Cards (Harmonized with Dashboard) */
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

    /* Mobile User Card */
    .user-item-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.875rem;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(15, 81, 50, 0.04);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .user-item-card:active {
        transform: scale(0.99);
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
        border-radius: 0.875rem;
        padding: 0.75rem 1rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.15);
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

    /* Action Buttons */
    .btn-action-circle {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.15s ease;
    }

    /* Pagination Styling Emerald */
    .page-item.active .page-link {
        background-color: #0f5132 !important;
        border-color: #0f5132 !important;
        color: #ffffff !important;
    }

    .page-link {
        color: #0f5132;
        border-radius: 0.375rem;
        margin: 0 2px;
        transition: all 0.2s ease;
    }

    .page-link:hover {
        background-color: #ecfdf5;
        color: #0f5132;
        border-color: #86efac;
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

    <!-- Breadcrumb & Top Header -->
    <div class="mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-mortarboard-fill text-success"></i>
                    <span>Manajemen Data Mahasiswa</span>
                </h4>
                <p class="text-muted small mt-1 mb-0">Kelola akun, penempatan kejuruan, asal universitas mitra, dan periode gelombang mahasiswa praktikan PPL/PK</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-skagata btn-sm rounded-pill px-3 py-2 shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                    <i class="bi bi-person-plus"></i>
                    <span>Tambah Mahasiswa Baru</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Alert Edukasi & Pintasan ke Manajemen Guru Pamong -->
    <div class="alert alert-light border border-success-subtle bg-white rounded-4 shadow-xs p-3 mb-4">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2 rounded-3 bg-success-subtle text-success flex-shrink-0" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem;">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <div>
                    <span class="fw-bold text-dark d-block" style="font-size: 0.88rem;">Pemisahan Manajemen Pamong &amp; Praktikan</span>
                    <small class="text-muted" style="font-size: 0.78rem;">
                        Pengelolaan akun Guru Pamong dan penugasan <strong>Jurusan Bimbingan / Pamong</strong> dikelola terpusat di menu <a href="<?= base_url('admin/guru-pamong') ?>" class="text-success fw-semibold text-decoration-underline">Data Guru Pamong</a>.
                    </small>
                </div>
            </div>
            <a href="<?= base_url('admin/guru-pamong') ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 shadow-xs text-nowrap align-self-start align-self-sm-center" style="font-size: 0.8rem;">
                <i class="bi bi-person-badge me-1"></i>Buka Data Pamong
            </a>
        </div>
    </div>

    <!-- Ringkasan Statistik Modern Khusus Mahasiswa Praktikan -->
    <div class="row g-3 mb-4">
        <!-- 1. Total Mahasiswa PPL/PK -->
        <div class="col-6 col-lg-3">
            <div class="card stat-card-modern p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-medium">Total Mahasiswa</span>
                    <div class="stat-icon-circle" style="background-color: #ccfbf1; color: #0f766e;">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?= $totalMahasiswa ?></h3>
                <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">
                    Praktikan terdaftar di sistem
                </small>
            </div>
        </div>

        <!-- 2. Mahasiswa Gelombang Aktif -->
        <div class="col-6 col-lg-3">
            <div class="card stat-card-modern p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-medium">Gelombang Aktif</span>
                    <div class="stat-icon-circle" style="background-color: #dcfce7; color: #166534;">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?= $totalMahasiswaAktif ?? 0 ?></h3>
                <small class="text-muted mt-1 d-block text-truncate" style="font-size: 0.75rem;" title="<?= !empty($periode_aktif) ? esc($periode_aktif['nama_periode']) : 'Belum Ada Periode Aktif' ?>">
                    <?= !empty($periode_aktif) ? esc($periode_aktif['nama_periode']) : 'Belum Ada Periode Aktif' ?>
                </small>
            </div>
        </div>

        <!-- 3. Jurusan / Konsentrasi Terwakili -->
        <div class="col-6 col-lg-3">
            <div class="card stat-card-modern p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-medium">Jurusan Praktikan</span>
                    <div class="stat-icon-circle" style="background-color: #e0e7ff; color: #3730a3;">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?= $totalJurusanTerwakili ?? count($daftar_jurusan ?? []) ?></h3>
                <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">
                    Bidang keahlian terisi
                </small>
            </div>
        </div>

        <!-- 4. Asal Universitas Mitra -->
        <div class="col-6 col-lg-3">
            <div class="card stat-card-modern p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-medium">Universitas Mitra</span>
                    <div class="stat-icon-circle" style="background-color: #fef3c7; color: #92400e;">
                        <i class="bi bi-buildings-fill"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?= $totalUnivTerwakili ?? count($daftar_universitas ?? []) ?></h3>
                <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">
                    Kampus pengirim kerja sama
                </small>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Pengguna -->
    <div class="card admin-card">
        <div class="card-header-custom">
            <!-- Header Kartu: Judul Tabel & Reset Filter -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                <div>
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-person-lines-fill text-success"></i>
                        <span>Daftar Mahasiswa Praktikan</span>
                    </h6>
                    <small class="text-muted">
                        Menampilkan <span class="fw-semibold text-dark"><?= count($users) ?></span> dari <span class="fw-semibold text-dark"><?= $totalFiltered ?? count($users) ?></span> total mahasiswa
                    </small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <?php if (!empty($keyword) || !empty($role_terpilih) || !empty($jurusan_pilih) || !empty($universitas_pilih) || !empty($periode_pilih)): ?>
                        <a href="<?= base_url('admin/pengguna') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 d-inline-flex align-items-center gap-1.5 shadow-xs" style="font-size: 0.8rem;">
                            <i class="bi bi-arrow-clockwise"></i>
                            <span>Reset Filter</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Toolbar Filter Responsif (Bersih Tanpa Tombol Filter & Tanpa Dropdown Role) -->
            <form action="<?= base_url('admin/pengguna') ?>" method="GET" class="filter-wrapper shadow-xs">
                <input type="hidden" name="per_page" value="<?= (int) ($perPage ?? 10) ?>">
                <?php if (!empty($role_terpilih)): ?>
                    <input type="hidden" name="role" value="<?= esc($role_terpilih) ?>">
                <?php endif; ?>
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted ps-2.5">
                                <i class="bi bi-search text-success"></i>
                            </span>
                            <input type="text" name="keyword" class="form-control border-start-0 ps-1 fw-medium" placeholder="Cari nama / username / NIM..." value="<?= esc($keyword ?? '') ?>">
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-xl-3">
                        <select name="jurusan" class="form-select form-select-sm fw-medium" onchange="this.form.submit()" aria-label="Filter Jurusan">
                            <option value="">Semua Jurusan / Kejuruan</option>
                            <?php if (!empty($daftar_jurusan)): ?>
                                <?php foreach ($daftar_jurusan as $jrs): ?>
                                    <option value="<?= esc($jrs) ?>" <?= (($jurusan_pilih ?? '') === $jrs) ? 'selected' : '' ?>><?= esc($jrs) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-xl-3">
                        <select name="universitas" class="form-select form-select-sm fw-medium" onchange="this.form.submit()" aria-label="Filter Universitas">
                            <option value="">Semua Universitas Mitra</option>
                            <?php if (!empty($daftar_universitas)): ?>
                                <?php foreach ($daftar_universitas as $univ): ?>
                                    <option value="<?= esc($univ) ?>" <?= (($universitas_pilih ?? '') === $univ) ? 'selected' : '' ?>><?= esc($univ) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-xl-2">
                        <select name="periode" class="form-select form-select-sm fw-medium" onchange="this.form.submit()" aria-label="Filter Periode">
                            <option value="">Semua Gelombang</option>
                            <?php if (!empty($daftar_periode)): ?>
                                <?php foreach ($daftar_periode as $prd): ?>
                                    <option value="<?= $prd['id'] ?>" <?= (($periode_pilih ?? '') == $prd['id']) ? 'selected' : '' ?>>
                                        <?= esc($prd['nama_periode']) ?><?= ((int)$prd['is_aktif'] === 1) ? ' (Aktif)' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <?php if (empty($users)): ?>
            <div class="text-center py-5">
                <div class="py-4">
                    <i class="bi bi-mortarboard text-muted opacity-50" style="font-size: 3rem;"></i>
                    <h6 class="fw-semibold text-muted mt-3 mb-1">Tidak Ada Data Mahasiswa</h6>
                    <p class="text-muted small mb-3">Tidak ditemukan mahasiswa praktikan yang cocok dengan kriteria pencarian atau filter.</p>
                    <a href="<?= base_url('admin/pengguna') ?>" class="btn btn-sm btn-outline-success rounded-pill px-3">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reset Filter
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- ============================================== -->
            <!-- 1. MOBILE VIEW: Interactive Mahasiswa Cards (< 768px) -->
            <!-- ============================================== -->
            <div class="d-block d-md-none p-3">
                <div class="d-flex flex-column gap-3">
                    <?php
                    $currentPage = !empty($pager) ? $pager->getCurrentPage() : 1;
                    $noMobile = (($currentPage - 1) * ($perPage ?? 10)) + 1;
                    foreach ($users as $u):
                    ?>
                        <div class="user-item-card">
                            <!-- Header Kartu: Inisial, Nama, Username/NIM -->
                            <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-initial">
                                        <?= esc(getInitials($u['nama'])) ?>
                                    </div>
                                    <div class="lh-sm">
                                        <div class="fw-bold text-dark" style="font-size: 0.92rem;"><?= esc($u['nama']) ?></div>
                                        <small class="text-muted font-monospace" style="font-size: 0.78rem;">@<?= esc($u['username']) ?></small>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                        <i class="bi bi-mortarboard me-1"></i>Mahasiswa
                                    </span>
                                </div>
                            </div>

                            <!-- Detail: Jurusan, Universitas, Periode -->
                            <div class="bg-light p-2.5 rounded-3 mb-3" style="font-size: 0.8rem;">
                                <div class="d-flex align-items-center justify-content-between mb-1.5">
                                    <span class="text-muted">Jurusan:</span>
                                    <span class="fw-semibold text-dark text-end"><?= !empty($u['jurusan']) ? esc($u['jurusan']) : '-' ?></span>
                                </div>
                                <?php if (!empty($u['universitas'])): ?>
                                    <div class="d-flex align-items-center justify-content-between mb-1.5">
                                        <span class="text-muted">Universitas:</span>
                                        <span class="fw-semibold text-dark text-truncate text-end" style="max-width: 190px;" title="<?= esc($u['universitas']) ?>"><?= esc($u['universitas']) ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($u['nama_periode_relasi'])): ?>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Periode:</span>
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle rounded-pill"><?= esc($u['nama_periode_relasi']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Tombol Aksi Mobile Touch-Friendly -->
                            <div class="d-flex align-items-center gap-2 pt-1 border-top">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill flex-fill py-1.5 d-flex align-items-center justify-content-center gap-1" style="font-size: 0.8rem;" onclick="bukaModalEdit(<?= (int) $u['id'] ?>, '<?= esc($u['username'], 'js') ?>', '<?= esc($u['nama'], 'js') ?>', '<?= esc($u['role'], 'js') ?>', '<?= esc($u['jurusan'] ?? '', 'js') ?>', '<?= esc($u['universitas'] ?? '', 'js') ?>', '<?= esc($u['periode_id'] ?? '', 'js') ?>')">
                                    <i class="bi bi-pencil"></i>
                                    <span>Edit</span>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning rounded-pill flex-fill py-1.5 d-flex align-items-center justify-content-center gap-1" style="font-size: 0.8rem;" onclick="bukaModalReset(<?= (int) $u['id'] ?>, '<?= esc($u['nama'], 'js') ?>')">
                                    <i class="bi bi-key"></i>
                                    <span>Reset</span>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill flex-fill py-1.5 d-flex align-items-center justify-content-center gap-1" style="font-size: 0.8rem;" onclick="konfirmasiHapus(<?= (int) $u['id'] ?>, '<?= esc($u['nama'], 'js') ?>')">
                                    <i class="bi bi-trash"></i>
                                    <span>Hapus</span>
                                </button>
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
                            <th class="text-center" style="width: 50px;">No</th>
                            <th>Mahasiswa Praktikan</th>
                            <th>Konsentrasi Keahlian / Jurusan</th>
                            <th>Asal Universitas Mitra</th>
                            <th>Periode Gelombang</th>
                            <th class="text-center" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $currentPage = !empty($pager) ? $pager->getCurrentPage() : 1;
                        $no = (($currentPage - 1) * ($perPage ?? 10)) + 1;
                        foreach ($users as $u):
                        ?>
                            <tr>
                                <td class="text-center text-muted fw-semibold"><?= $no++ ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-initial">
                                            <?= esc(getInitials($u['nama'])) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark lh-sm"><?= esc($u['nama']) ?></div>
                                            <small class="text-muted font-monospace" style="font-size: 0.78rem;">@<?= esc($u['username']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($u['jurusan'])): ?>
                                        <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1 fw-semibold">
                                            <i class="bi bi-journal-bookmark text-success me-1"></i><?= esc($u['jurusan']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic">Belum Ditentukan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($u['universitas'])): ?>
                                        <div class="d-flex align-items-center gap-1.5" title="Asal Universitas: <?= esc($u['universitas']) ?>">
                                            <i class="bi bi-buildings text-success" style="font-size: 0.85rem;"></i>
                                            <span class="fw-medium text-dark text-truncate" style="max-width: 220px;"><?= esc($u['universitas']) ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic">Umum / Mandiri</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($u['nama_periode_relasi'])): ?>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1">
                                            <i class="bi bi-calendar-range me-1"></i><?= esc($u['nama_periode_relasi']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2 py-0.5" style="font-size: 0.75rem;">
                                            Tidak Terikat Gelombang
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <!-- Tombol Edit -->
                                        <button type="button" class="btn btn-sm btn-light border text-primary btn-action-circle shadow-xs" title="Edit Mahasiswa" onclick="bukaModalEdit(<?= (int) $u['id'] ?>, '<?= esc($u['username'], 'js') ?>', '<?= esc($u['nama'], 'js') ?>', '<?= esc($u['role'], 'js') ?>', '<?= esc($u['jurusan'] ?? '', 'js') ?>', '<?= esc($u['universitas'] ?? '', 'js') ?>', '<?= esc($u['periode_id'] ?? '', 'js') ?>')">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <!-- Tombol Reset Password -->
                                        <button type="button" class="btn btn-sm btn-light border text-warning btn-action-circle shadow-xs" title="Reset Password" onclick="bukaModalReset(<?= (int) $u['id'] ?>, '<?= esc($u['nama'], 'js') ?>')">
                                            <i class="bi bi-key"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <button type="button" class="btn btn-sm btn-light border text-danger btn-action-circle shadow-xs" title="Hapus Mahasiswa" onclick="konfirmasiHapus(<?= (int) $u['id'] ?>, '<?= esc($u['nama'], 'js') ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if (!empty($users)): ?>
            <!-- Pagination & Per Page Selector Footer -->
            <div class="card-footer bg-white border-top py-3 px-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="text-muted small">
                        Menampilkan <span class="fw-semibold text-dark"><?= count($users) ?></span> dari <span class="fw-semibold text-dark"><?= $totalFiltered ?? count($users) ?></span> total mahasiswa
                    </div>
                    <!-- Pilihan Baris Per Halaman -->
                    <div class="d-flex align-items-center gap-1.5 small text-muted">
                        <label for="perPageSelect" class="text-nowrap mb-0" style="font-size: 0.8rem;">Tampilkan:</label>
                        <select id="perPageSelect" class="form-select form-select-sm py-0 px-2 shadow-none" style="width: 70px; height: 28px; font-size: 0.8rem;" onchange="changePerPage(this.value)">
                            <option value="5" <?= (($perPage ?? 10) == 5) ? 'selected' : '' ?>>5</option>
                            <option value="10" <?= (($perPage ?? 10) == 10) ? 'selected' : '' ?>>10</option>
                            <option value="25" <?= (($perPage ?? 10) == 25) ? 'selected' : '' ?>>25</option>
                            <option value="50" <?= (($perPage ?? 10) == 50) ? 'selected' : '' ?>>50</option>
                        </select>
                        <span style="font-size: 0.8rem;">baris</span>
                    </div>
                </div>
                <div>
                    <?php if (!empty($pager) && $pager->getPageCount() > 1): ?>
                        <?= $pager->links('default', 'bootstrap') ?>
                    <?php else: ?>
                        <!-- Single page indicator -->
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled"><span class="page-link text-muted opacity-50">&laquo;</span></li>
                            <li class="page-item active"><span class="page-link fw-semibold">1</span></li>
                            <li class="page-item disabled"><span class="page-link text-muted opacity-50">&raquo;</span></li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>

<!-- Modal Tambah Pengguna Modern (Khusus Mahasiswa) -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form action="<?= base_url('admin/tambah-user') ?>" method="POST" id="formTambahUserPengguna" class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <?= csrf_field() ?>
            <div class="modal-header bg-success text-white py-3 flex-shrink-0">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-mortarboard-fill"></i>
                    <span>Tambah Mahasiswa Baru</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" placeholder="Contoh: Ahmad Fauzi" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Username / NIM</label>
                    <input type="text" name="username" class="form-control" placeholder="Contoh: ahmad123 / NIM" required>
                </div>
                <div class="mb-3 d-none">
                    <label class="form-label fw-semibold small text-dark">Role / Hak Akses</label>
                    <select name="role" class="form-select" id="tambahRoleSelect" onchange="toggleJurusanField('tambahRoleSelect', 'tambahJurusanWrapper', 'tambahUniversitasWrapper', 'tambahPeriodeWrapper', 'tambahUniversitas', 'tambahJurusan')" required>
                        <option value="mahasiswa" selected>Mahasiswa Praktikan (PPL/PK)</option>
                        <option value="guru">Guru Pamong / GTT</option>
                    </select>
                </div>
                <div class="mb-3" id="tambahUniversitasWrapper">
                    <label class="form-label fw-semibold small text-dark">Asal Universitas / Kampus</label>
                    <select name="universitas" id="tambahUniversitas" class="form-select" onchange="updateJurusanOptions('tambahUniversitas', 'tambahJurusan')">
                        <option value="">-- Pilih Asal Universitas --</option>
                        <?php if (!empty($daftar_universitas)): ?>
                            <?php foreach ($daftar_universitas as $univ): ?>
                                <option value="<?= esc($univ) ?>"><?= esc($univ) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small class="text-muted" style="font-size: 0.75rem;">Pilih kampus pengirim terlebih dahulu untuk memfilter jurusan/prodi.</small>
                </div>
                <div class="mb-3" id="tambahJurusanWrapper">
                    <label class="form-label fw-semibold small text-dark">Jurusan Mahasiswa</label>
                    <select name="jurusan" id="tambahJurusan" class="form-select">
                        <option value="">-- Pilih Universitas Terlebih Dahulu --</option>
                        <?php if (!empty($daftar_jurusan)): ?>
                            <?php foreach ($daftar_jurusan as $jrs): ?>
                                <option value="<?= esc($jrs) ?>"><?= esc($jrs) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small class="text-muted" style="font-size: 0.75rem;" id="tambahJurusanHelp">Program studi yang dibuka oleh kampus mitra terkait.</small>
                </div>
                <div class="mb-3" id="tambahPeriodeWrapper">
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
                        <input type="password" name="password" id="inputTambahPassword" class="form-control border-end-0" placeholder="Minimal 6 karakter" required>
                        <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" onclick="togglePasswordVisibility('inputTambahPassword', 'iconToggleTambah')">
                            <i class="bi bi-eye" id="iconToggleTambah"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light py-2 px-4 flex-shrink-0">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-skagata rounded-pill px-4" id="btnSubmitTambahUserPengguna">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Mahasiswa
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Pengguna Modern (Khusus Mahasiswa) -->
<div class="modal fade" id="modalEditUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form action="<?= base_url('admin/edit-user') ?>" method="POST" id="formEditUserPengguna" class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="editUserId">
            <div class="modal-header bg-success text-white py-3 flex-shrink-0">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square"></i>
                    <span>Edit Data Mahasiswa</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Nama Lengkap</label>
                    <input type="text" name="nama" id="editNama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Username / NIM</label>
                    <input type="text" name="username" id="editUsername" class="form-control" required>
                </div>
                <div class="mb-3 d-none">
                    <label class="form-label fw-semibold small text-dark">Role / Hak Akses</label>
                    <select name="role" id="editRole" class="form-select" onchange="toggleJurusanField('editRole', 'editJurusanWrapper', 'editUniversitasWrapper', 'editPeriodeWrapper', 'editUniversitas', 'editJurusan')" required>
                        <option value="mahasiswa" selected>Mahasiswa Praktikan (PPL/PK)</option>
                        <option value="guru">Guru Pamong / GTT</option>
                    </select>
                </div>
                <div class="mb-3" id="editUniversitasWrapper">
                    <label class="form-label fw-semibold small text-dark">Asal Universitas / Kampus</label>
                    <select name="universitas" id="editUniversitas" class="form-select" onchange="updateJurusanOptions('editUniversitas', 'editJurusan')">
                        <option value="">-- Pilih Asal Universitas --</option>
                        <?php if (!empty($daftar_universitas)): ?>
                            <?php foreach ($daftar_universitas as $univ): ?>
                                <option value="<?= esc($univ) ?>"><?= esc($univ) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small class="text-muted" style="font-size: 0.75rem;">Pilih kampus pengirim untuk memfilter jurusan/prodi.</small>
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
                    <small class="text-muted" style="font-size: 0.75rem;" id="editJurusanHelp">Program studi yang dibuka oleh kampus mitra terkait.</small>
                </div>
                <div class="mb-3" id="editPeriodeWrapper">
                    <label class="form-label fw-semibold small text-dark">Periode PPL / PK</label>
                    <select name="periode_id" id="editPeriodeSelect" class="form-select">
                        <option value="">-- Pilih Periode PPL / PK --</option>
                        <?php if (!empty($daftar_periode)): ?>
                            <?php foreach ($daftar_periode as $prd): ?>
                                <option value="<?= $prd['id'] ?>">
                                    <?= esc($prd['nama_periode']) ?><?= ((int)$prd['is_aktif'] === 1) ? ' (Aktif)' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light py-2 px-4 flex-shrink-0">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-skagata rounded-pill px-4" id="btnSubmitEditUserPengguna">
                    <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Reset Password Modern (Khusus Mahasiswa) -->
<div class="modal fade" id="modalResetPassword" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form action="<?= base_url('admin/reset-password') ?>" method="POST" id="formResetPassPengguna" class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <?= csrf_field() ?>
            <input type="hidden" name="user_id" id="resetUserId">
            <div class="modal-header bg-warning text-dark py-3 flex-shrink-0">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-key"></i>
                    <span>Reset Password Mahasiswa</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start p-4">
                <div class="alert alert-light border d-flex align-items-center gap-2 py-2 px-3 rounded-3 mb-3">
                    <i class="bi bi-mortarboard text-warning fs-5"></i>
                    <div class="small">
                        Reset password akun mahasiswa: <strong id="resetUserNama" class="text-dark"></strong>
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
            <div class="modal-footer border-0 bg-light py-2 px-4 flex-shrink-0">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-warning rounded-pill px-4" id="btnSubmitResetPassPengguna">
                    <i class="bi bi-arrow-repeat me-1"></i> Reset Password
                </button>
            </div>
        </form>
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
    document.addEventListener('DOMContentLoaded', function() {
        const bindSubmitSpinner = function(formId, btnId, loadingText) {
            const form = document.getElementById(formId);
            const btn = document.getElementById(btnId);
            if (form && btn) {
                const originalHtml = btn.innerHTML;
                form.addEventListener('submit', function() {
                    if (!form.checkValidity()) {
                        return;
                    }
                    setTimeout(function() {
                        btn.disabled = true;
                        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> ${loadingText}`;
                    }, 0);
                });

                const modalEl = form.closest('.modal');
                if (modalEl) {
                    modalEl.addEventListener('hidden.bs.modal', function() {
                        btn.disabled = false;
                        btn.innerHTML = originalHtml;
                    });
                }
            }
        };

        bindSubmitSpinner('formTambahUserPengguna', 'btnSubmitTambahUserPengguna', 'Menyimpan...');
        bindSubmitSpinner('formEditUserPengguna', 'btnSubmitEditUserPengguna', 'Menyimpan...');
        bindSubmitSpinner('formResetPassPengguna', 'btnSubmitResetPassPengguna', 'Memproses...');

        // Inisialisasi awal dropdown jurusan pada modal tambah
        if (document.getElementById('tambahUniversitas') && document.getElementById('tambahJurusan')) {
            updateJurusanOptions('tambahUniversitas', 'tambahJurusan');
        }
    });

    const universitasJurusanMap = <?= json_encode($jurusan_univ_map ?? []) ?>;
    const allJurusans = <?= json_encode($daftar_jurusan ?? []) ?>;
    const daftarJurusanMitra = <?= json_encode($daftar_jurusan_mitra ?? []) ?>;

    function updateJurusanOptions(univSelectId, jurusanSelectId, selectedJurusanVal = '') {
        const univSelect = document.getElementById(univSelectId);
        const jurusanSelect = document.getElementById(jurusanSelectId);
        if (!univSelect || !jurusanSelect) return;

        const selectedUniv = univSelect.value;
        const currentVal = selectedJurusanVal || jurusanSelect.value;

        jurusanSelect.innerHTML = '';

        if (!selectedUniv) {
            const defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.textContent = '-- Pilih Universitas Terlebih Dahulu --';
            jurusanSelect.appendChild(defaultOpt);
            return;
        }

        const availableJurusans = universitasJurusanMap[selectedUniv] || [];
        const defaultOpt = document.createElement('option');
        defaultOpt.value = '';
        defaultOpt.textContent = availableJurusans.length > 0 ? '-- Pilih Jurusan / Prodi --' : '-- Belum Ada Jurusan Khusus Terdaftar --';
        jurusanSelect.appendChild(defaultOpt);

        if (availableJurusans.length > 0) {
            availableJurusans.forEach(jrs => {
                const opt = document.createElement('option');
                opt.value = jrs;
                opt.textContent = jrs;
                if (jrs === currentVal) opt.selected = true;
                jurusanSelect.appendChild(opt);
            });
        } else {
            // Fallback ke seluruh jurusan jika universitas belum dipetakan
            allJurusans.forEach(jrs => {
                const opt = document.createElement('option');
                opt.value = jrs;
                opt.textContent = jrs;
                if (jrs === currentVal) opt.selected = true;
                jurusanSelect.appendChild(opt);
            });
        }

        // Pertahankan nilai jika ada data tersimpan yang belum terdaftar di mapping
        if (currentVal && !Array.from(jurusanSelect.options).some(o => o.value === currentVal)) {
            const opt = document.createElement('option');
            opt.value = currentVal;
            opt.textContent = currentVal;
            opt.selected = true;
            jurusanSelect.appendChild(opt);
        }
    }

    function toggleJurusanField(roleSelectId, jurusanWrapperId, universitasWrapperId, periodeWrapperId, univSelectId, jurusanSelectId) {
        const role = document.getElementById(roleSelectId).value;
        const jWrapper = document.getElementById(jurusanWrapperId);
        const uWrapper = universitasWrapperId ? document.getElementById(universitasWrapperId) : null;
        const pWrapper = periodeWrapperId ? document.getElementById(periodeWrapperId) : null;
        const jLabel = jWrapper ? jWrapper.querySelector('label') : null;
        const jHelp = jWrapper ? jWrapper.querySelector('small') : null;
        const jSelect = jurusanSelectId ? document.getElementById(jurusanSelectId) : (jWrapper ? jWrapper.querySelector('select') : null);

        if (role === 'mahasiswa') {
            if (jWrapper) jWrapper.style.display = 'block';
            if (jLabel) jLabel.textContent = 'Jurusan Mahasiswa';
            if (jHelp) jHelp.textContent = 'Program studi yang dibuka oleh kampus mitra terkait.';
            if (uWrapper) uWrapper.style.display = 'block';
            if (pWrapper) pWrapper.style.display = 'block';

            if (univSelectId && jurusanSelectId) {
                const currentJurVal = jSelect ? jSelect.value : '';
                updateJurusanOptions(univSelectId, jurusanSelectId, currentJurVal);
            }
        } else if (role === 'guru') {
            if (jWrapper) jWrapper.style.display = 'block';
            if (jLabel) jLabel.textContent = 'Jurusan Bimbingan / Pamong';
            if (jHelp) jHelp.innerHTML = 'Pilih program studi mitra binaan. Untuk penugasan multi-kampus dapat diatur di <a href="<?= base_url('admin/guru-pamong') ?>" class="text-success fw-bold text-decoration-none">Pemetaan Pamong</a>.';
            if (uWrapper) uWrapper.style.display = 'none';
            if (pWrapper) pWrapper.style.display = 'none';

            // Untuk pamong, tampilkan daftar jurusan mitra dengan label kampus pembeda
            if (jSelect) {
                const currentVal = jSelect.value;
                jSelect.innerHTML = '<option value="">-- Pilih Jurusan Bimbingan / Pamong --</option>';

                if (daftarJurusanMitra && daftarJurusanMitra.length > 0) {
                    const groups = {};
                    daftarJurusanMitra.forEach(jm => {
                        const grpName = jm.nama_universitas ? `${jm.nama_universitas} (${jm.kode_universitas || ''})` : 'Umum / Lainnya';
                        if (!groups[grpName]) groups[grpName] = [];
                        groups[grpName].push(jm);
                    });

                    for (const [grpName, items] of Object.entries(groups)) {
                        const optgroup = document.createElement('optgroup');
                        optgroup.label = grpName;
                        items.forEach(item => {
                            const opt = document.createElement('option');
                            opt.value = item.id;
                            opt.textContent = item.display_label || `${item.nama_jurusan} (${item.kode_universitas || ''})`;
                            if (currentVal && (currentVal == item.id || currentVal === item.nama_jurusan || currentVal === item.display_label)) {
                                opt.selected = true;
                            }
                            optgroup.appendChild(opt);
                        });
                        jSelect.appendChild(optgroup);
                    }
                } else {
                    allJurusans.forEach(jrs => {
                        const opt = document.createElement('option');
                        opt.value = jrs;
                        opt.textContent = jrs;
                        if (jrs === currentVal) opt.selected = true;
                        jSelect.appendChild(opt);
                    });
                }
            }
        } else {
            if (jWrapper) jWrapper.style.display = 'none';
            if (uWrapper) uWrapper.style.display = 'none';
            if (pWrapper) pWrapper.style.display = 'none';
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

    function bukaModalEdit(id, username, nama, role, jurusan, universitas, periodeId) {
        document.getElementById('editUserId').value = id;
        document.getElementById('editUsername').value = username;
        document.getElementById('editNama').value = nama;
        document.getElementById('editRole').value = role;
        if (document.getElementById('editUniversitas')) {
            document.getElementById('editUniversitas').value = universitas || '';
        }
        if (document.getElementById('editPeriodeSelect')) {
            document.getElementById('editPeriodeSelect').value = periodeId || '';
        }

        toggleJurusanField('editRole', 'editJurusanWrapper', 'editUniversitasWrapper', 'editPeriodeWrapper', 'editUniversitas', 'editJurusan');

        if (role === 'mahasiswa') {
            if (universitas) {
                updateJurusanOptions('editUniversitas', 'editJurusan', jurusan || '');
            } else if (document.getElementById('editJurusan')) {
                document.getElementById('editJurusan').value = jurusan || '';
            }
        } else if (role === 'guru') {
            const jSelect = document.getElementById('editJurusan');
            if (jSelect && jurusan) {
                for (let i = 0; i < jSelect.options.length; i++) {
                    const opt = jSelect.options[i];
                    if (opt.value === jurusan || opt.text === jurusan || opt.text.startsWith(jurusan + ' ') || opt.text.includes(jurusan)) {
                        opt.selected = true;
                        break;
                    }
                }
            }
        }

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

    function changePerPage(val) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', val);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

    function konfirmasiHapus(id, nama) {
        Swal.fire({
            title: 'Hapus Mahasiswa?',
            html: `Akun mahasiswa <strong>${nama}</strong> dan seluruh riwayat presensi terkait akan dihapus permanen.`,
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