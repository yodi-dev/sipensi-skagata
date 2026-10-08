<?= $this->extend('layout/template') ?>

<?= $this->section('custom_css') ?>
<style>
    /* Custom Styling Master Data Periode Skagata */
    .admin-card {
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        background: #ffffff;
        overflow: hidden;
    }

    .card-header-custom {
        background-color: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.25rem 1.5rem;
    }

    .stat-card-modern {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.07);
    }

    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .table-custom thead th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.85rem 1.25rem;
    }

    .table-custom tbody td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
        color: #334155;
    }

    .table-custom tbody tr:hover {
        background-color: #f8fafc;
    }

    .filter-wrapper {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 9999px;
        padding: 0.35rem 0.65rem;
    }

    .btn-skagata {
        background-color: #0f5132;
        color: #ffffff;
        border: none;
    }

    .btn-skagata:hover {
        background-color: #0b3d26;
        color: #ffffff;
    }

    .badge-aktif {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
    }

    .badge-arsip {
        background-color: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
    }

    .pulse-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background-color: #10b981;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulseAnimation 1.8s infinite;
    }

    @keyframes pulseAnimation {
        0% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }
        70% {
            box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 py-4">

    <!-- Breadcrumb & Top Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>" class="text-success text-decoration-none"><i class="bi bi-grid-1x2 me-1"></i>Beranda</a></li>
                <li class="breadcrumb-item text-muted">Master Data</li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Periode PPL / PK</li>
            </ol>
        </nav>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-calendar-range-fill text-success"></i>
                    <span>Master Data Periode &amp; Gelombang PPL / PK</span>
                </h4>
                <p class="text-muted small mt-1 mb-0">Kelola tahun ajaran, semester, rentang tanggal pelaksanaan, dan penetapan gelombang aktif mahasiswa praktikan</p>
            </div>
            <div>
                <button type="button" class="btn btn-skagata btn-sm rounded-pill px-3 shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahPeriode">
                    <i class="bi bi-plus-circle"></i>
                    <span>Tambah Periode Baru</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Ringkasan Statistik Modern -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card-modern d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">Total Periode</span>
                    <h3 class="fw-bold text-dark mb-0"><?= $total_periode ?></h3>
                    <small class="text-success fw-medium"><i class="bi bi-collection me-1"></i>Gelombang Terdata</small>
                </div>
                <div class="stat-icon-wrapper bg-success-subtle text-success">
                    <i class="bi bi-calendar3"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card-modern d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">Periode Aktif</span>
                    <h5 class="fw-bold text-dark mb-0 text-truncate" style="max-width: 170px;" title="<?= esc($periode_aktif['nama_periode'] ?? 'Tidak Ada') ?>">
                        <?= esc($periode_aktif['tahun_ajaran'] ?? '-') ?>
                    </h5>
                    <small class="text-success fw-medium">
                        <i class="bi bi-check-circle-fill me-1"></i><?= esc($periode_aktif ? 'Semester ' . $periode_aktif['semester'] : 'Belum Dipilih') ?>
                    </small>
                </div>
                <div class="stat-icon-wrapper bg-emerald-subtle text-success" style="background-color: #d1fae5;">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card-modern d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">Mahasiswa Periode Aktif</span>
                    <h3 class="fw-bold text-dark mb-0"><?= $total_mahasiswa_aktif ?></h3>
                    <small class="text-primary fw-medium"><i class="bi bi-mortarboard me-1"></i>Praktikan Berjalan</small>
                </div>
                <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                    <i class="bi bi-person-check-fill"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card-modern d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">Total Semua Praktikan</span>
                    <h3 class="fw-bold text-dark mb-0"><?= $total_mahasiswa ?></h3>
                    <small class="text-muted fw-medium"><i class="bi bi-people-fill me-1"></i>Akumulasi Keseluruhan</small>
                </div>
                <div class="stat-icon-wrapper bg-secondary-subtle text-secondary">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Notifikasi Flash -->
    <?php if (session()->getFlashdata('pesan')): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div><?= session()->getFlashdata('pesan') ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div><?= session()->getFlashdata('error') ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Kartu Tabel Utama Master Data Periode -->
    <div class="admin-card">
        <div class="card-header-custom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h5 class="fw-bold text-dark mb-0">Daftar Gelombang &amp; Tahun Ajaran</h5>
                <small class="text-muted">Total <?= count($periode_list) ?> periode tercatat dalam pangkalan data</small>
            </div>

            <!-- Toolbar Pencarian -->
            <div class="d-flex align-items-center gap-2">
                <form action="<?= base_url('admin/periode') ?>" method="GET" class="d-flex align-items-center">
                    <div class="filter-wrapper d-flex align-items-center shadow-xs">
                        <i class="bi bi-search text-muted ms-2 me-1"></i>
                        <input type="text" name="keyword" class="form-control form-control-sm border-0 bg-transparent shadow-none" style="width: 220px;" placeholder="Cari nama, tahun, semester..." value="<?= esc($keyword ?? '') ?>">
                        <?php if (!empty($keyword)): ?>
                            <a href="<?= base_url('admin/periode') ?>" class="btn btn-sm text-muted p-0 me-2" title="Reset Pencarian">
                                <i class="bi bi-x-circle-fill"></i>
                            </a>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-sm btn-skagata rounded-pill px-3 py-1">Cari</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th style="width: 260px;">Nama Periode &amp; Status</th>
                        <th style="width: 150px;">Tahun Ajaran</th>
                        <th style="width: 220px;">Rentang Waktu &amp; Timeline</th>
                        <th class="text-center" style="width: 150px;">Mahasiswa</th>
                        <th>Keterangan</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($periode_list)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-calendar-x text-muted opacity-50" style="font-size: 3rem;"></i>
                                    <h6 class="fw-semibold text-muted mt-3 mb-1">Tidak Ada Data Periode</h6>
                                    <p class="text-muted small mb-3">Tidak ditemukan data periode yang sesuai dengan kriteria pencarian.</p>
                                    <a href="<?= base_url('admin/periode') ?>" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                        <i class="bi bi-arrow-clockwise me-1"></i>Muat Ulang Data
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($periode_list as $p): ?>
                            <tr>
                                <td class="text-center text-muted fw-semibold"><?= $no++ ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= esc($p['nama_periode']) ?></div>
                                    <div class="mt-1">
                                        <?php if ((int)$p['is_aktif'] === 1): ?>
                                            <span class="badge-aktif">
                                                <span class="pulse-dot"></span>
                                                <span>Periode Aktif</span>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge-arsip">
                                                <i class="bi bi-archive me-1"></i>Arsip / Nonaktif
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark d-block"><?= esc($p['tahun_ajaran']) ?></span>
                                    <small class="badge bg-light text-secondary border">Semester <?= esc($p['semester']) ?></small>
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark">
                                        <i class="bi bi-calendar-event me-1 text-success"></i>
                                        <?= date('d M Y', strtotime($p['tanggal_mulai'])) ?> - <?= date('d M Y', strtotime($p['tanggal_selesai'])) ?>
                                    </div>
                                    <div class="mt-1">
                                        <?php
                                            $badgeClass = 'bg-secondary';
                                            if ($p['timeline_badge'] === 'success') {
                                                $badgeClass = 'bg-success';
                                            } elseif ($p['timeline_badge'] === 'info') {
                                                $badgeClass = 'bg-info text-dark';
                                            }
                                        ?>
                                        <span class="badge <?= $badgeClass ?> rounded-pill" style="font-size: 0.7rem;">
                                            <?= esc($p['timeline_status']) ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php $jml = (int) ($p['total_mahasiswa'] ?? 0); ?>
                                    <?php if ($jml > 0): ?>
                                        <a href="<?= base_url('admin/pengguna?periode=' . $p['id']) ?>" class="badge bg-light text-dark border rounded-pill px-3 py-2 text-decoration-none shadow-xs d-inline-flex align-items-center gap-1" title="Lihat Mahasiswa">
                                            <i class="bi bi-mortarboard text-success"></i>
                                            <strong><?= $jml ?></strong> Mahasiswa
                                        </a>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border rounded-pill px-3 py-2">
                                            0 Mahasiswa
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($p['keterangan'])): ?>
                                        <div class="small text-muted text-truncate" style="max-width: 250px;" title="<?= esc($p['keterangan']) ?>">
                                            <?= esc($p['keterangan']) ?>
                                        </div>
                                    <?php else: ?>
                                        <small class="text-muted fst-italic">-</small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <!-- Tombol Set Aktif jika belum aktif -->
                                        <?php if ((int)$p['is_aktif'] !== 1): ?>
                                            <button type="button" class="btn btn-sm btn-light border text-success rounded-circle p-1" style="width: 32px; height: 32px;" title="Tetapkan Sebagai Periode Aktif" onclick="konfirmasiSetAktif(<?= (int) $p['id'] ?>, '<?= esc($p['nama_periode'], 'js') ?>')">
                                                <i class="bi bi-check-circle-fill"></i>
                                            </button>
                                        <?php endif; ?>

                                        <!-- Tombol Edit -->
                                        <button type="button" class="btn btn-sm btn-light border text-primary rounded-circle p-1" style="width: 32px; height: 32px;" title="Edit Periode" onclick="bukaModalEditPeriode(<?= (int) $p['id'] ?>, '<?= esc($p['nama_periode'], 'js') ?>', '<?= esc($p['tahun_ajaran'], 'js') ?>', '<?= esc($p['semester'], 'js') ?>', '<?= esc($p['tanggal_mulai'], 'js') ?>', '<?= esc($p['tanggal_selesai'], 'js') ?>', <?= (int)$p['is_aktif'] ?>, '<?= esc($p['keterangan'] ?? '', 'js') ?>')">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <button type="button" class="btn btn-sm btn-light border text-danger rounded-circle p-1" style="width: 32px; height: 32px;" title="Hapus Periode" onclick="konfirmasiHapusPeriode(<?= (int) $p['id'] ?>, '<?= esc($p['nama_periode'], 'js') ?>', <?= $jml ?>, <?= (int)$p['is_aktif'] ?>)">
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
    </div>
</div>

<!-- Modal Tambah Periode Modern -->
<div class="modal fade" id="modalTambahPeriode" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-success text-white py-3">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-calendar-plus"></i>
                    <span>Tambah Periode PPL / PK Baru</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/periode/tambah') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body text-start p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Nama Periode / Gelombang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_periode" class="form-control" placeholder="Contoh: PPL Semester Gasal 2026/2027" maxlength="100" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-sm-7">
                            <label class="form-label fw-semibold small text-dark">Tahun Ajaran <span class="text-danger">*</span></label>
                            <input type="text" name="tahun_ajaran" class="form-control" placeholder="Contoh: 2026/2027" maxlength="20" required>
                        </div>
                        <div class="col-sm-5">
                            <label class="form-label fw-semibold small text-dark">Semester <span class="text-danger">*</span></label>
                            <select name="semester" class="form-select" required>
                                <option value="Ganjil">Ganjil / Gasal</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small text-dark">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mulai" class="form-control" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small text-dark">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_selesai" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_aktif" value="1" id="tambahIsAktif">
                            <label class="form-check-label fw-semibold small text-dark" for="tambahIsAktif">
                                Tetapkan sebagai Periode Aktif saat ini
                            </label>
                        </div>
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Jika dicentang, periode lain akan otomatis dinonaktifkan.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Keterangan / Catatan Tambahan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Gelombang 1 penempatan mahasiswa praktikan dari UNY, UAD, UST."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-skagata rounded-pill px-4">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Periode
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Periode Modern -->
<div class="modal fade" id="modalEditPeriode" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square"></i>
                    <span>Edit Data Periode PPL / PK</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/periode/edit') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="editPeriodeId">
                <div class="modal-body text-start p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Nama Periode / Gelombang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_periode" id="editNamaPeriode" class="form-control" maxlength="100" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-sm-7">
                            <label class="form-label fw-semibold small text-dark">Tahun Ajaran <span class="text-danger">*</span></label>
                            <input type="text" name="tahun_ajaran" id="editTahunAjaran" class="form-control" maxlength="20" required>
                        </div>
                        <div class="col-sm-5">
                            <label class="form-label fw-semibold small text-dark">Semester <span class="text-danger">*</span></label>
                            <select name="semester" id="editSemester" class="form-select" required>
                                <option value="Ganjil">Ganjil / Gasal</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small text-dark">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mulai" id="editTanggalMulai" class="form-control" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small text-dark">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_selesai" id="editTanggalSelesai" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_aktif" value="1" id="editIsAktif">
                            <label class="form-check-label fw-semibold small text-dark" for="editIsAktif">
                                Tetapkan sebagai Periode Aktif saat ini
                            </label>
                        </div>
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Jika dicentang, periode lain akan otomatis dinonaktifkan.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Keterangan / Catatan Tambahan</label>
                        <textarea name="keterangan" id="editKeterangan" class="form-control" rows="3"></textarea>
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

<!-- Form Tersembunyi untuk Set Aktif Periode -->
<form id="formSetAktifPeriode" action="<?= base_url('admin/periode/set-aktif') ?>" method="POST" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="id" id="setAktifPeriodeId">
</form>

<!-- Form Tersembunyi untuk Hapus Periode -->
<form id="formHapusPeriode" action="<?= base_url('admin/periode/hapus') ?>" method="POST" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="id" id="hapusPeriodeId">
</form>

<script>
    function bukaModalEditPeriode(id, nama, tahun, semester, mulai, selesai, isAktif, keterangan) {
        document.getElementById('editPeriodeId').value = id;
        document.getElementById('editNamaPeriode').value = nama;
        document.getElementById('editTahunAjaran').value = tahun;
        document.getElementById('editSemester').value = semester;
        document.getElementById('editTanggalMulai').value = mulai;
        document.getElementById('editTanggalSelesai').value = selesai;
        document.getElementById('editIsAktif').checked = (parseInt(isAktif) === 1);
        document.getElementById('editKeterangan').value = keterangan || '';
        var modal = new bootstrap.Modal(document.getElementById('modalEditPeriode'));
        modal.show();
    }

    function konfirmasiSetAktif(id, nama) {
        Swal.fire({
            title: 'Aktifkan Periode Ini?',
            html: `Apakah Anda ingin menetapkan <strong>${nama}</strong> sebagai periode operasional aktif saat ini?<br><small class="text-muted">Periode lain yang sedang aktif akan otomatis dialihkan menjadi status nonaktif.</small>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0f5132',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Jadikan Aktif!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('setAktifPeriodeId').value = id;
                document.getElementById('formSetAktifPeriode').submit();
            }
        });
    }

    function konfirmasiHapusPeriode(id, nama, totalMahasiswa, isAktif) {
        if (totalMahasiswa > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Tidak Dapat Menghapus!',
                html: `Periode <strong>${nama}</strong> masih memiliki <strong>${totalMahasiswa} mahasiswa</strong> terdaftar.<br><small class="text-muted mt-2 d-block">Silakan alihkan data mahasiswa ke periode lain terlebih dahulu sebelum menghapus.</small>`,
                confirmButtonColor: '#0f5132',
                confirmButtonText: 'Mengerti'
            });
            return;
        }

        if (parseInt(isAktif) === 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Periode Masih Berstatus Aktif!',
                html: `Periode <strong>${nama}</strong> sedang berstatus sebagai <strong>Periode Aktif</strong>.<br><small class="text-muted mt-2 d-block">Silakan aktifkan periode lainnya terlebih dahulu sebelum menghapus periode ini.</small>`,
                confirmButtonColor: '#0f5132',
                confirmButtonText: 'Mengerti'
            });
            return;
        }

        Swal.fire({
            title: 'Hapus Data Periode?',
            html: `Apakah Anda yakin ingin menghapus periode <strong>${nama}</strong>?<br><small class="text-muted">Data yang telah dihapus tidak dapat dipulihkan kembali.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('hapusPeriodeId').value = id;
                document.getElementById('formHapusPeriode').submit();
            }
        });
    }
</script>
<?= $this->endSection() ?>

