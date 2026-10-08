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
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>" class="text-success text-decoration-none"><i class="bi bi-grid-1x2 me-1"></i>Beranda</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Data Pengguna</li>
            </ol>
        </nav>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-people-fill text-success"></i>
                    <span>Manajemen Data Pengguna</span>
                </h4>
                <p class="text-muted small mt-1 mb-0">Kelola akun Guru Pamong, GTT, dan Mahasiswa Praktikan PPL/PK</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 small">
                    <i class="bi bi-mortarboard me-1"></i> Mahasiswa: <strong><?= $totalMahasiswa ?></strong>
                </span>
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-2 small">
                    <i class="bi bi-person-badge me-1"></i> Guru Pamong: <strong><?= $totalGuru ?></strong>
                </span>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Pengguna -->
    <div class="card admin-card">
        <div class="card-header-custom d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <!-- Sisi Kiri: Judul Tabel + Tombol Tambah Pengguna Tepat di Sampingnya -->
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div>
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-person-lines-fill text-success"></i>
                        <span>Daftar Pengguna Terdaftar</span>
                    </h6>
                    <small class="text-muted">Total: <?= count($users) ?> akun ditemukan</small>
                </div>
                <button type="button" class="btn btn-skagata btn-sm rounded-pill px-3 shadow-sm d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                    <i class="bi bi-person-plus"></i>
                    <span>Tambah Pengguna</span>
                </button>
            </div>

            <!-- Sisi Kanan: Toolbar Filter Otomatis (Onchange Submit ala Sibenka) -->
            <form action="<?= base_url('admin/pengguna') ?>" method="GET" class="filter-wrapper d-flex align-items-center flex-wrap gap-2 m-0 shadow-xs">
                <div class="input-group input-group-sm" style="width: auto;">
                    <span class="input-group-text bg-white border-0 text-muted ps-2 pe-1"><i class="bi bi-search text-success"></i></span>
                    <input type="text" name="keyword" class="form-control border-0 bg-transparent fw-medium" placeholder="Cari nama / username..." value="<?= esc($keyword ?? '') ?>" style="min-width: 170px;">
                </div>

                <div class="vr my-1 text-muted d-none d-sm-block"></div>

                <div class="input-group input-group-sm" style="width: auto;">
                    <select name="role" class="form-select form-select-sm border-0 bg-transparent fw-medium" onchange="this.form.submit()" aria-label="Filter Role">
                        <option value="">Semua Role</option>
                        <option value="mahasiswa" <?= (($role_terpilih ?? '') === 'mahasiswa') ? 'selected' : '' ?>>Mahasiswa Praktikan</option>
                        <option value="guru" <?= (($role_terpilih ?? '') === 'guru') ? 'selected' : '' ?>>Guru Pamong / GTT</option>
                    </select>
                </div>

                <div class="vr my-1 text-muted d-none d-sm-block"></div>

                <div class="input-group input-group-sm" style="width: auto;">
                    <select name="jurusan" class="form-select form-select-sm border-0 bg-transparent fw-medium" onchange="this.form.submit()" aria-label="Filter Jurusan">
                        <option value="">Semua Jurusan</option>
                        <?php if (!empty($daftar_jurusan)): ?>
                            <?php foreach ($daftar_jurusan as $jrs): ?>
                                <option value="<?= esc($jrs) ?>" <?= (($jurusan_pilih ?? '') === $jrs) ? 'selected' : '' ?>><?= esc($jrs) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="vr my-1 text-muted d-none d-sm-block"></div>

                <div class="input-group input-group-sm" style="width: auto;">
                    <select name="universitas" class="form-select form-select-sm border-0 bg-transparent fw-medium" onchange="this.form.submit()" aria-label="Filter Universitas">
                        <option value="">Semua Universitas</option>
                        <?php if (!empty($daftar_universitas)): ?>
                            <?php foreach ($daftar_universitas as $univ): ?>
                                <option value="<?= esc($univ) ?>" <?= (($universitas_pilih ?? '') === $univ) ? 'selected' : '' ?>><?= esc($univ) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="vr my-1 text-muted d-none d-sm-block"></div>

                <div class="input-group input-group-sm" style="width: auto;">
                    <select name="periode" class="form-select form-select-sm border-0 bg-transparent fw-medium" onchange="this.form.submit()" aria-label="Filter Periode">
                        <option value="">Semua Periode</option>
                        <?php if (!empty($daftar_periode)): ?>
                            <?php foreach ($daftar_periode as $prd): ?>
                                <option value="<?= $prd['id'] ?>" <?= (($periode_pilih ?? '') == $prd['id']) ? 'selected' : '' ?>>
                                    <?= esc($prd['nama_periode']) ?><?= ((int)$prd['is_aktif'] === 1) ? ' (Aktif)' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <?php if (!empty($keyword) || !empty($role_terpilih) || !empty($jurusan_pilih) || !empty($universitas_pilih) || !empty($periode_pilih)): ?>
                    <a href="<?= base_url('admin/pengguna') ?>" class="btn btn-sm btn-link text-muted p-1" title="Reset Filter">
                        <i class="bi bi-x-circle-fill"></i>
                    </a>
                <?php endif; ?>

                <button type="submit" class="btn btn-sm btn-light border rounded-pill px-2 py-0 text-success fw-semibold">
                    Cari
                </button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>Pengguna</th>
                        <th>Role Akun</th>
                        <th>Jurusan &amp; Kampus</th>
                        <th class="text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-people text-muted opacity-50" style="font-size: 3rem;"></i>
                                    <h6 class="fw-semibold text-muted mt-3 mb-1">Tidak Ada Data Pengguna</h6>
                                    <p class="text-muted small mb-3">Tidak ditemukan pengguna yang cocok dengan kriteria pencarian atau filter.</p>
                                    <a href="<?= base_url('admin/pengguna') ?>" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                        <i class="bi bi-arrow-clockwise me-1"></i>Reset Filter
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $currentPage = !empty($pager) ? $pager->getCurrentPage() : 1;
                        $no = (($currentPage - 1) * ($perPage ?? 15)) + 1;
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
                                    <?php if ($u['role'] === 'mahasiswa'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                            <i class="bi bi-mortarboard me-1"></i>Mahasiswa
                                        </span>
                                    <?php elseif ($u['role'] === 'guru'): ?>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2 py-1">
                                            <i class="bi bi-person-badge me-1"></i>Guru Pamong
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary rounded-pill px-2 py-1"><?= esc($u['role']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($u['role'] === 'guru'): ?>
                                        <?php if (!empty($u['jurusan'])): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                                <i class="bi bi-shield-check me-1"></i><?= esc($u['jurusan']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2 py-1">
                                                <i class="bi bi-exclamation-circle me-1"></i>Belum Dipetakan
                                            </span>
                                        <?php endif; ?>
                                        <div class="mt-1">
                                            <a href="<?= base_url('admin/guru-pamong') ?>" class="small text-decoration-none text-muted" style="font-size: 0.75rem;">
                                                <i class="bi bi-gear-wide-connected me-1"></i>Atur Pemetaan
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <?php if (!empty($u['jurusan'])): ?>
                                            <span class="badge bg-light text-dark border rounded-pill px-2 py-1">
                                                <?= esc($u['jurusan']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted small">-</span>
                                        <?php endif; ?>

                                        <?php if (!empty($u['universitas'])): ?>
                                            <div class="small text-muted mt-1 d-flex align-items-center gap-1" title="Asal Universitas: <?= esc($u['universitas']) ?>">
                                                <i class="bi bi-buildings text-success" style="font-size: 0.75rem;"></i>
                                                <span class="text-truncate" style="max-width: 170px;"><?= esc($u['universitas']) ?></span>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($u['nama_periode_relasi'])): ?>
                                            <div class="small text-muted mt-1 d-flex align-items-center gap-1" title="Periode: <?= esc($u['nama_periode_relasi']) ?>">
                                                <i class="bi bi-calendar-range text-primary" style="font-size: 0.75rem;"></i>
                                                <span class="text-truncate" style="max-width: 170px;"><?= esc($u['nama_periode_relasi']) ?></span>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <!-- Tombol Edit -->
                                        <button type="button" class="btn btn-sm btn-light border text-primary rounded-circle p-1" style="width: 32px; height: 32px;" title="Edit Pengguna" onclick="bukaModalEdit(<?= (int) $u['id'] ?>, '<?= esc($u['username'], 'js') ?>', '<?= esc($u['nama'], 'js') ?>', '<?= esc($u['role'], 'js') ?>', '<?= esc($u['jurusan'] ?? '', 'js') ?>', '<?= esc($u['universitas'] ?? '', 'js') ?>', '<?= esc($u['periode_id'] ?? '', 'js') ?>')">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <!-- Tombol Reset Password -->
                                        <button type="button" class="btn btn-sm btn-light border text-warning rounded-circle p-1" style="width: 32px; height: 32px;" title="Reset Password" onclick="bukaModalReset(<?= (int) $u['id'] ?>, '<?= esc($u['nama'], 'js') ?>')">
                                            <i class="bi bi-key"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <button type="button" class="btn btn-sm btn-light border text-danger rounded-circle p-1" style="width: 32px; height: 32px;" title="Hapus Pengguna" onclick="konfirmasiHapus(<?= (int) $u['id'] ?>, '<?= esc($u['nama'], 'js') ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if (!empty($pager) && $pager->getPageCount() > 1): ?>
            <div class="card-footer bg-white border-top py-3 px-4 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                <div class="text-muted small">
                    Menampilkan <span class="fw-semibold text-dark"><?= count($users) ?></span> dari <span class="fw-semibold text-dark"><?= $totalFiltered ?? count($users) ?></span> total pengguna
                </div>
                <div>
                    <?= $pager->links('default', 'bootstrap') ?>
                </div>
            </div>
        <?php elseif (!empty($users)): ?>
            <div class="card-footer bg-white border-top py-2 px-4 text-muted small">
                Menampilkan seluruh <span class="fw-semibold text-dark"><?= count($users) ?></span> pengguna
            </div>
        <?php endif; ?>
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
                        <select name="role" class="form-select" id="tambahRoleSelect" onchange="toggleJurusanField('tambahRoleSelect', 'tambahJurusanWrapper', 'tambahUniversitasWrapper', 'tambahPeriodeWrapper')" required>
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
                    <div class="mb-3" id="tambahUniversitasWrapper">
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

<!-- Modal Edit Pengguna Modern -->
<div class="modal fade" id="modalEditUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square"></i>
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
                        <select name="role" id="editRole" class="form-select" onchange="toggleJurusanField('editRole', 'editJurusanWrapper', 'editUniversitasWrapper', 'editPeriodeWrapper')" required>
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
                    <div class="mb-3" id="editUniversitasWrapper">
                        <label class="form-label fw-semibold small text-dark">Asal Universitas / Kampus</label>
                        <select name="universitas" id="editUniversitas" class="form-select">
                            <option value="">-- Pilih Asal Universitas --</option>
                            <?php if (!empty($daftar_universitas)): ?>
                                <?php foreach ($daftar_universitas as $univ): ?>
                                    <option value="<?= esc($univ) ?>"><?= esc($univ) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
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
    function toggleJurusanField(roleSelectId, jurusanWrapperId, universitasWrapperId, periodeWrapperId) {
        const role = document.getElementById(roleSelectId).value;
        const jWrapper = document.getElementById(jurusanWrapperId);
        const uWrapper = universitasWrapperId ? document.getElementById(universitasWrapperId) : null;
        const pWrapper = periodeWrapperId ? document.getElementById(periodeWrapperId) : null;
        const jLabel = jWrapper ? jWrapper.querySelector('label') : null;

        if (role === 'mahasiswa') {
            if (jWrapper) jWrapper.style.display = 'block';
            if (jLabel) jLabel.textContent = 'Jurusan Mahasiswa';
            if (uWrapper) uWrapper.style.display = 'block';
            if (pWrapper) pWrapper.style.display = 'block';
        } else if (role === 'guru') {
            if (jWrapper) jWrapper.style.display = 'block';
            if (jLabel) jLabel.textContent = 'Jurusan Bimbingan / Pamong';
            if (uWrapper) uWrapper.style.display = 'none';
            if (pWrapper) pWrapper.style.display = 'none';
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
        document.getElementById('editJurusan').value = jurusan || '';
        if (document.getElementById('editUniversitas')) {
            document.getElementById('editUniversitas').value = universitas || '';
        }
        if (document.getElementById('editPeriodeSelect')) {
            document.getElementById('editPeriodeSelect').value = periodeId || '';
        }
        toggleJurusanField('editRole', 'editJurusanWrapper', 'editUniversitasWrapper', 'editPeriodeWrapper');

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
