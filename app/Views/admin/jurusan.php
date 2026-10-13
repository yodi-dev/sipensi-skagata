<?= $this->extend('layout/template') ?>

<?= $this->section('styles') ?>
<style>
    /* Custom Styling Master Data Jurusan Skagata */
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

    .btn-skagata {
        background-color: #0f5132;
        color: #ffffff;
        border: none;
    }

    .btn-skagata:hover {
        background-color: #0b3d26;
        color: #ffffff;
    }

    .badge-kode {
        font-family: monospace;
        font-size: 0.85rem;
        padding: 0.35rem 0.65rem;
        letter-spacing: 0.5px;
    }

    /* Mobile Jurusan Card */
    .jurusan-item-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.875rem;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(15, 81, 50, 0.04);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .jurusan-item-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(15, 81, 50, 0.08);
    }
    .jurusan-item-card:active {
        transform: scale(0.99);
    }

    .shadow-xs {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
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
                <li class="breadcrumb-item active text-muted" aria-current="page">Data Jurusan</li>
            </ol>
        </nav>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-journal-bookmark-fill text-success"></i>
                    <span>Master Data Jurusan / Konsentrasi Keahlian</span>
                </h4>
                <p class="text-muted small mt-1 mb-0">Kelola bidang kejuruan SMK Negeri 3 Yogyakarta dan penempatan mahasiswa PPL/PK</p>
            </div>
            <div>
                <button type="button" class="btn btn-skagata btn-sm rounded-pill px-3 py-1.5 shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahJurusan">
                    <i class="bi bi-plus-circle"></i>
                    <span>Tambah Jurusan Baru</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Ringkasan Statistik Modern -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card-modern d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">Total Jurusan Aktif</span>
                    <h3 class="fw-bold text-dark mb-0"><?= count($jurusan_list) ?></h3>
                    <small class="text-success fw-medium"><i class="bi bi-check-circle-fill me-1"></i>Tersedia di sistem</small>
                </div>
                <div class="stat-icon-wrapper bg-success-subtle text-success">
                    <i class="bi bi-journal-bookmark"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card-modern d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">Universitas Mitra</span>
                    <h3 class="fw-bold text-dark mb-0"><?= count($daftar_universitas) ?></h3>
                    <small class="text-info fw-medium"><i class="bi bi-buildings me-1"></i>Institusi Kampus</small>
                </div>
                <div class="stat-icon-wrapper bg-info-subtle text-info-emphasis">
                    <i class="bi bi-buildings"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card-modern d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">Mahasiswa Terdistribusi</span>
                    <h3 class="fw-bold text-dark mb-0"><?= $total_mahasiswa ?></h3>
                    <small class="text-primary fw-medium"><i class="bi bi-mortarboard me-1"></i>Praktikan PPL/PK</small>
                </div>
                <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card-modern d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">Rata-rata Penempatan</span>
                    <h3 class="fw-bold text-dark mb-0">
                        <?= count($jurusan_list) > 0 ? round($total_mahasiswa / count($jurusan_list), 1) : 0 ?>
                    </h3>
                    <small class="text-warning-emphasis fw-medium"><i class="bi bi-diagram-3 me-1"></i>Mahasiswa per jurusan</small>
                </div>
                <div class="stat-icon-wrapper bg-warning-subtle text-warning-emphasis">
                    <i class="bi bi-pie-chart"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Jurusan -->
    <div class="card admin-card">
        <div class="card-header-custom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-list-check text-success"></i>
                        <span>Daftar Jurusan Terdaftar</span>
                    </h6>
                    <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1 fw-semibold" id="counterJurusanAktif" style="font-size: 0.75rem;">
                        <?= count($jurusan_list) ?> Jurusan
                    </span>
                </div>
                <small class="text-muted mt-1 d-block">Bidang kejuruan prodi universitas mitra di SMK Negeri 3 Yogyakarta</small>
            </div>

            <!-- Toolbar Pencarian & Filter Instan (Tanpa Tombol Submit) -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Input Pencarian Real-time -->
                <div class="input-group input-group-sm shadow-xs" style="width: 250px;">
                    <span class="input-group-text bg-white border-end-0 text-muted ps-2.5 pe-1">
                        <i class="bi bi-search text-success"></i>
                    </span>
                    <input type="text" id="inputCariJurusan" class="form-control border-start-0 ps-1 fw-medium" placeholder="Cari nama, kode, deskripsi..." oninput="filterTabelJurusan()" value="<?= esc($keyword ?? '') ?>">
                    <button type="button" id="btnResetCariJurusan" class="btn btn-sm btn-link text-muted p-1 border-0 pe-2" onclick="resetCariJurusan()" title="Hapus Pencarian" style="<?= empty($keyword) ? 'display: none;' : '' ?>">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>

                <!-- Filter Universitas Dropdown -->
                <?php if (!empty($daftar_universitas)): ?>
                    <select id="filterUniversitasJurusan" class="form-select form-select-sm fw-medium shadow-xs" style="min-width: 195px;" onchange="filterTabelJurusan()">
                        <option value="">Semua Universitas Mitra</option>
                        <?php foreach ($daftar_universitas as $univ): ?>
                            <option value="<?= (int) $univ['id'] ?>" <?= ((string)($universitas_filter ?? '') === (string)$univ['id']) ? 'selected' : '' ?>>
                                <?= esc($univ['nama_universitas']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </div>
        </div>

        <?php if (empty($jurusan_list)): ?>
            <div class="text-center py-5">
                <div class="py-4">
                    <i class="bi bi-journal-x text-muted opacity-50" style="font-size: 3rem;"></i>
                    <h6 class="fw-semibold text-muted mt-3 mb-1">Tidak Ada Data Jurusan</h6>
                    <p class="text-muted small mb-3">Belum ada data jurusan yang terdaftar di sistem.</p>
                    <a href="<?= base_url('admin/jurusan') ?>" class="btn btn-sm btn-outline-success rounded-pill px-3">
                        <i class="bi bi-arrow-clockwise me-1"></i>Muat Ulang Data
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- ============================================== -->
            <!-- 1. MOBILE VIEW: Interactive Jurusan Cards (< 768px) -->
            <!-- ============================================== -->
            <div class="d-block d-md-none p-3">
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($jurusan_list as $j): ?>
                        <?php
                            $jml = (int) ($j['total_mahasiswa'] ?? 0);
                            $univId = (int) ($j['universitas_id'] ?? 0);
                            $searchKeywords = strtolower(
                                ($j['kode_jurusan'] ?? '') . ' ' .
                                ($j['nama_jurusan'] ?? '') . ' ' .
                                ($j['nama_universitas'] ?? '') . ' ' .
                                ($j['deskripsi'] ?? '')
                            );
                        ?>
                        <div class="jurusan-item-card baris-jurusan-mobile" data-universitas="<?= $univId ?>" data-search="<?= esc($searchKeywords) ?>">
                            <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                                <div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill badge-kode mb-1">
                                        <?= esc($j['kode_jurusan']) ?>
                                    </span>
                                    <div class="fw-bold text-dark mt-1" style="font-size: 0.95rem; line-height: 1.35;"><?= esc($j['nama_jurusan']) ?></div>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <?php if ($jml > 0): ?>
                                        <a href="<?= base_url('admin/pengguna?jurusan=' . urlencode($j['nama_jurusan'])) ?>" class="badge bg-light text-dark border rounded-pill px-2.5 py-1.5 text-decoration-none shadow-xs d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;" title="Lihat Mahasiswa">
                                            <i class="bi bi-mortarboard text-success"></i>
                                            <strong><?= $jml ?></strong> Mhs
                                        </a>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border rounded-pill px-2 py-1" style="font-size: 0.72rem;">0 Mhs</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Asal Kampus Mitra -->
                            <div class="mb-2">
                                <?php if (!empty($j['nama_universitas'])): ?>
                                    <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1 shadow-xs d-inline-flex align-items-center gap-1.5" style="font-size: 0.75rem;">
                                        <i class="bi bi-buildings text-success"></i>
                                        <span><?= esc($j['nama_universitas']) ?></span>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted border rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">
                                        <i class="bi bi-buildings me-1"></i>Umum / Belum Ditautkan
                                    </span>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($j['deskripsi'])): ?>
                                <div class="p-2.5 bg-light rounded-3 small text-muted mb-3" style="font-size: 0.8rem; line-height: 1.45;">
                                    <?= esc($j['deskripsi']) ?>
                                </div>
                            <?php else: ?>
                                <div class="mb-3 small text-muted fst-italic" style="font-size: 0.75rem;">Belum ada deskripsi.</div>
                            <?php endif; ?>

                            <!-- Tombol Aksi Mobile Touch-Friendly -->
                            <div class="d-flex align-items-center gap-2 pt-2 border-top">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill flex-fill py-1.5 d-flex align-items-center justify-content-center gap-1.5 shadow-xs" style="font-size: 0.82rem; min-height: 38px;"
                                        onclick="bukaModalEditJurusan(<?= (int) $j['id'] ?>, '<?= esc($j['kode_jurusan'], 'js') ?>', '<?= esc($j['nama_jurusan'], 'js') ?>', '<?= esc($j['deskripsi'] ?? '', 'js') ?>', '<?= esc($j['universitas_id'] ?? '', 'js') ?>')">
                                    <i class="bi bi-pencil-square"></i>
                                    <span>Edit</span>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill flex-fill py-1.5 d-flex align-items-center justify-content-center gap-1.5 shadow-xs" style="font-size: 0.82rem; min-height: 38px;"
                                        onclick="konfirmasiHapusJurusan(<?= (int) $j['id'] ?>, '<?= esc($j['nama_jurusan'], 'js') ?>', <?= $jml ?>)">
                                    <i class="bi bi-trash"></i>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Pesan Tidak Ditemukan Mobile -->
                    <div id="emptySearchMobile" class="text-center py-5 bg-white rounded-3 border" style="display: none;">
                        <i class="bi bi-search text-muted opacity-50 d-block fs-2 mb-2"></i>
                        <h6 class="fw-semibold text-muted mb-1">Jurusan Tidak Ditemukan</h6>
                        <p class="text-muted small mb-0">Tidak ada jurusan yang sesuai dengan kata kunci "<span id="textSearchMobile" class="fw-bold text-dark"></span>".</p>
                    </div>
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
                            <th style="width: 120px;">Kode Jurusan</th>
                            <th>Nama Jurusan &amp; Keterangan</th>
                            <th style="width: 230px;">Asal Universitas Mitra</th>
                            <th class="text-center" style="width: 170px;">Mahasiswa Terdaftar</th>
                            <th class="text-center" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($jurusan_list as $j): ?>
                            <?php
                                $jml = (int) ($j['total_mahasiswa'] ?? 0);
                                $univId = (int) ($j['universitas_id'] ?? 0);
                                $searchKeywords = strtolower(
                                    ($j['kode_jurusan'] ?? '') . ' ' .
                                    ($j['nama_jurusan'] ?? '') . ' ' .
                                    ($j['nama_universitas'] ?? '') . ' ' .
                                    ($j['deskripsi'] ?? '')
                                );
                            ?>
                            <tr class="baris-jurusan-desktop" data-universitas="<?= $univId ?>" data-search="<?= esc($searchKeywords) ?>">
                                <td class="text-center text-muted fw-semibold nomor-urut"><?= $no++ ?></td>
                                <td>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill badge-kode">
                                        <?= esc($j['kode_jurusan']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= esc($j['nama_jurusan']) ?></div>
                                    <?php if (!empty($j['deskripsi'])): ?>
                                        <div class="small text-muted text-truncate mt-1" style="max-width: 440px;" title="<?= esc($j['deskripsi']) ?>">
                                            <?= esc($j['deskripsi']) ?>
                                        </div>
                                    <?php else: ?>
                                        <small class="text-muted fst-italic">Belum ada deskripsi.</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($j['nama_universitas'])): ?>
                                        <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1.5 shadow-xs d-inline-flex align-items-center gap-1.5" style="font-size: 0.8rem;">
                                            <i class="bi bi-buildings text-success"></i>
                                            <span class="text-truncate" style="max-width: 180px;" title="<?= esc($j['nama_universitas']) ?>"><?= esc($j['nama_universitas']) ?></span>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1" style="font-size: 0.75rem;">Umum / Belum Ditautkan</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($jml > 0): ?>
                                        <a href="<?= base_url('admin/pengguna?jurusan=' . urlencode($j['nama_jurusan'])) ?>" class="badge bg-light text-dark border rounded-pill px-3 py-2 text-decoration-none shadow-xs d-inline-flex align-items-center gap-1.5" title="Lihat Mahasiswa">
                                            <i class="bi bi-mortarboard text-success"></i>
                                            <strong><?= $jml ?></strong> Mahasiswa
                                        </a>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border rounded-pill px-3 py-2">
                                            0 Mahasiswa
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <!-- Tombol Edit -->
                                        <button type="button" class="btn btn-sm btn-light border text-primary rounded-circle p-1 shadow-xs" style="width: 32px; height: 32px;" title="Edit Jurusan" onclick="bukaModalEditJurusan(<?= (int) $j['id'] ?>, '<?= esc($j['kode_jurusan'], 'js') ?>', '<?= esc($j['nama_jurusan'], 'js') ?>', '<?= esc($j['deskripsi'] ?? '', 'js') ?>', '<?= esc($j['universitas_id'] ?? '', 'js') ?>')">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <button type="button" class="btn btn-sm btn-light border text-danger rounded-circle p-1 shadow-xs" style="width: 32px; height: 32px;" title="Hapus Jurusan" onclick="konfirmasiHapusJurusan(<?= (int) $j['id'] ?>, '<?= esc($j['nama_jurusan'], 'js') ?>', <?= $jml ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <!-- Pesan Tidak Ditemukan Desktop -->
                        <tr id="emptySearchDesktop" style="display: none;">
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-search text-muted opacity-50 d-block fs-2 mb-2"></i>
                                <h6 class="fw-semibold text-muted mb-1">Jurusan Tidak Ditemukan</h6>
                                <p class="text-muted small mb-0">Tidak ada data jurusan yang cocok dengan kriteria pencarian "<span id="textSearchDesktop" class="fw-bold text-dark"></span>".</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Tambah Jurusan Modern -->
<div class="modal fade" id="modalTambahJurusan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form action="<?= base_url('admin/jurusan/tambah') ?>" method="POST" id="formTambahJurusan" class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <?= csrf_field() ?>
            <div class="modal-header bg-success text-white py-3 flex-shrink-0">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-journal-plus"></i>
                    <span>Tambah Jurusan Baru</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Asal Universitas Mitra <span class="text-danger">*</span></label>
                    <select name="universitas_id" id="tambahUniversitasId" class="form-select" required>
                        <option value="">-- Pilih Universitas Mitra --</option>
                        <?php if (!empty($daftar_universitas)): ?>
                            <?php foreach ($daftar_universitas as $univ): ?>
                                <option value="<?= $univ['id'] ?>">
                                    <?= esc($univ['nama_universitas']) ?> (<?= esc($univ['kode_universitas']) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small class="text-muted" style="font-size: 0.75rem;">Kampus mitra yang membuka program studi ini.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Kode Jurusan <span class="text-danger">*</span></label>
                    <input type="text" name="kode_jurusan" class="form-control text-uppercase" placeholder="Contoh: TKJ, RPL, TL, TO" maxlength="20" required>
                    <small class="text-muted" style="font-size: 0.75rem;">Singkatan unik pengenal jurusan (2-20 karakter).</small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Nama Lengkap Jurusan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_jurusan" class="form-control" placeholder="Contoh: Pendidikan Teknik Informatika" maxlength="100" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Deskripsi / Keterangan (Opsional)</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi program studi atau catatan bidang kerja sama..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light py-2 px-4 flex-shrink-0">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-skagata rounded-pill px-4" id="btnSubmitTambahJurusan">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Jurusan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Jurusan Modern -->
<div class="modal fade" id="modalEditJurusan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form action="<?= base_url('admin/jurusan/edit') ?>" method="POST" id="formEditJurusan" class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="editJurusanId">
            <div class="modal-header bg-success text-white py-3 flex-shrink-0">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square"></i>
                    <span>Edit Data Jurusan</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Asal Universitas Mitra <span class="text-danger">*</span></label>
                    <select name="universitas_id" id="editUniversitasId" class="form-select" required>
                        <option value="">-- Pilih Universitas Mitra --</option>
                        <?php if (!empty($daftar_universitas)): ?>
                            <?php foreach ($daftar_universitas as $univ): ?>
                                <option value="<?= $univ['id'] ?>">
                                    <?= esc($univ['nama_universitas']) ?> (<?= esc($univ['kode_universitas']) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small class="text-muted" style="font-size: 0.75rem;">Kampus mitra yang membuka program studi ini.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Kode Jurusan <span class="text-danger">*</span></label>
                    <input type="text" name="kode_jurusan" id="editKodeJurusan" class="form-control text-uppercase" maxlength="20" required>
                    <small class="text-muted" style="font-size: 0.75rem;">Singkatan unik pengenal jurusan.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Nama Lengkap Jurusan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_jurusan" id="editNamaJurusan" class="form-control" maxlength="100" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Deskripsi / Keterangan (Opsional)</label>
                    <textarea name="deskripsi" id="editDeskripsiJurusan" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light py-2 px-4 flex-shrink-0">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-skagata rounded-pill px-4" id="btnSubmitEditJurusan">
                    <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Form Tersembunyi untuk Hapus Jurusan -->
<form id="formHapusJurusan" action="<?= base_url('admin/jurusan/hapus') ?>" method="POST" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="id" id="hapusJurusanId">
</form>

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

        bindSubmitSpinner('formTambahJurusan', 'btnSubmitTambahJurusan', 'Menyimpan...');
        bindSubmitSpinner('formEditJurusan', 'btnSubmitEditJurusan', 'Menyimpan...');

        // Inisialisasi filter jika terdapat query parameter dari URL
        const input = document.getElementById('inputCariJurusan');
        const filterUniv = document.getElementById('filterUniversitasJurusan');
        if ((input && input.value.trim() !== '') || (filterUniv && filterUniv.value.trim() !== '')) {
            filterTabelJurusan();
        }
    });

    function bukaModalEditJurusan(id, kode, nama, deskripsi, universitasId) {
        document.getElementById('editJurusanId').value = id;
        document.getElementById('editKodeJurusan').value = kode;
        document.getElementById('editNamaJurusan').value = nama;
        document.getElementById('editDeskripsiJurusan').value = deskripsi || '';
        if (document.getElementById('editUniversitasId')) {
            document.getElementById('editUniversitasId').value = universitasId || '';
        }
        var modal = new bootstrap.Modal(document.getElementById('modalEditJurusan'));
        modal.show();
    }

    function konfirmasiHapusJurusan(id, nama, totalMahasiswa) {
        if (totalMahasiswa > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Tidak Dapat Menghapus!',
                html: `Jurusan <strong>${nama}</strong> masih memiliki <strong>${totalMahasiswa} mahasiswa</strong> terdaftar.<br><small class="text-muted mt-2 d-block">Silakan alihkan atau ubah jurusan mahasiswa terkait terlebih dahulu sebelum menghapus.</small>`,
                confirmButtonColor: '#0f5132',
                confirmButtonText: 'Mengerti'
            });
            return;
        }

        Swal.fire({
            title: 'Hapus Data Jurusan?',
            html: `Apakah Anda yakin ingin menghapus data jurusan <strong>${nama}</strong>?<br><small class="text-muted">Data yang telah dihapus tidak dapat dipulihkan kembali.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('hapusJurusanId').value = id;
                document.getElementById('formHapusJurusan').submit();
            }
        });
    }

    // Filter Instan Real-time Tabel & Mobile Cards Tanpa Tombol Cari
    function filterTabelJurusan() {
        const input = document.getElementById('inputCariJurusan');
        const btnReset = document.getElementById('btnResetCariJurusan');
        const filterUniv = document.getElementById('filterUniversitasJurusan');

        const keyword = input ? input.value.toLowerCase().trim() : '';
        const univId = filterUniv ? filterUniv.value.trim() : '';

        if (btnReset) {
            btnReset.style.display = (keyword.length > 0 || univId.length > 0) ? 'inline-block' : 'none';
        }

        const rows = document.querySelectorAll('.baris-jurusan-desktop');
        const cards = document.querySelectorAll('.baris-jurusan-mobile');

        // Filter Baris Desktop
        let visibleDesktop = 0;
        rows.forEach((row) => {
            const rowSearch = row.getAttribute('data-search') || '';
            const rowUniv = row.getAttribute('data-universitas') || '';

            const matchKeyword = keyword === '' || rowSearch.includes(keyword);
            const matchUniv = univId === '' || rowUniv === univId;

            if (matchKeyword && matchUniv) {
                row.style.display = '';
                visibleDesktop++;
                const numCell = row.querySelector('.nomor-urut');
                if (numCell) numCell.textContent = visibleDesktop;
            } else {
                row.style.display = 'none';
            }
        });

        // Update Counter Badges
        const counterBadge = document.getElementById('counterJurusanAktif');
        if (counterBadge) {
            counterBadge.textContent = `${visibleDesktop} Jurusan`;
        }

        // Empty State Desktop
        const emptyDesktop = document.getElementById('emptySearchDesktop');
        const textDesktop = document.getElementById('textSearchDesktop');
        if (emptyDesktop) {
            if (visibleDesktop === 0 && (keyword !== '' || univId !== '')) {
                emptyDesktop.style.display = '';
                if (textDesktop) {
                    let label = keyword;
                    if (univId !== '' && filterUniv) {
                        const univText = filterUniv.options[filterUniv.selectedIndex]?.text || '';
                        label = keyword ? `${keyword} (${univText})` : univText;
                    }
                    textDesktop.textContent = label;
                }
            } else {
                emptyDesktop.style.display = 'none';
            }
        }

        // Filter Kartu Mobile
        let visibleMobile = 0;
        cards.forEach((card) => {
            const cardSearch = card.getAttribute('data-search') || '';
            const cardUniv = card.getAttribute('data-universitas') || '';

            const matchKeyword = keyword === '' || cardSearch.includes(keyword);
            const matchUniv = univId === '' || cardUniv === univId;

            if (matchKeyword && matchUniv) {
                card.style.display = '';
                visibleMobile++;
            } else {
                card.style.display = 'none';
            }
        });

        // Empty State Mobile
        const emptyMobile = document.getElementById('emptySearchMobile');
        const textMobile = document.getElementById('textSearchMobile');
        if (emptyMobile) {
            if (visibleMobile === 0 && (keyword !== '' || univId !== '')) {
                emptyMobile.style.display = 'block';
                if (textMobile) {
                    let label = keyword;
                    if (univId !== '' && filterUniv) {
                        const univText = filterUniv.options[filterUniv.selectedIndex]?.text || '';
                        label = keyword ? `${keyword} (${univText})` : univText;
                    }
                    textMobile.textContent = label;
                }
            } else {
                emptyMobile.style.display = 'none';
            }
        }
    }

    function resetCariJurusan() {
        const input = document.getElementById('inputCariJurusan');
        const filterUniv = document.getElementById('filterUniversitasJurusan');
        if (input) input.value = '';
        if (filterUniv) filterUniv.value = '';
        filterTabelJurusan();
        if (input) input.focus();
    }
</script>
<?= $this->endSection() ?>
