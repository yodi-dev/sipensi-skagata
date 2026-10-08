<?= $this->extend('layout/template') ?>

<?= $this->section('custom_css') ?>
<style>
    /* Custom Styling Master Data Universitas Skagata */
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

    .badge-kode {
        font-family: monospace;
        font-size: 0.85rem;
        padding: 0.35rem 0.65rem;
        letter-spacing: 0.5px;
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
                <li class="breadcrumb-item active text-muted" aria-current="page">Asal Universitas</li>
            </ol>
        </nav>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-buildings-fill text-success"></i>
                    <span>Master Data Asal Universitas / Mitra Kampus</span>
                </h4>
                <p class="text-muted small mt-1 mb-0">Kelola perguruan tinggi mitra kerja sama penempatan mahasiswa praktikan PPL/PK</p>
            </div>
            <div>
                <button type="button" class="btn btn-skagata btn-sm rounded-pill px-3 shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahUniversitas">
                    <i class="bi bi-plus-circle"></i>
                    <span>Tambah Universitas Baru</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Ringkasan Statistik Modern -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card-modern d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">Total Kampus Mitra</span>
                    <h3 class="fw-bold text-dark mb-0"><?= count($universitas_list) ?></h3>
                    <small class="text-success fw-medium"><i class="bi bi-check-circle-fill me-1"></i>Perguruan Tinggi Terdaftar</small>
                </div>
                <div class="stat-icon-wrapper bg-success-subtle text-success">
                    <i class="bi bi-buildings"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
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

        <div class="col-12 col-sm-12 col-lg-4">
            <div class="stat-card-modern d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">Rata-rata Penempatan</span>
                    <h3 class="fw-bold text-dark mb-0">
                        <?= count($universitas_list) > 0 ? round($total_mahasiswa / count($universitas_list), 1) : 0 ?>
                    </h3>
                    <small class="text-muted fw-medium"><i class="bi bi-diagram-3 me-1"></i>Mahasiswa per kampus</small>
                </div>
                <div class="stat-icon-wrapper bg-warning-subtle text-warning-emphasis">
                    <i class="bi bi-pie-chart"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Universitas -->
    <div class="card admin-card">
        <div class="card-header-custom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-list-check text-success"></i>
                    <span>Daftar Perguruan Tinggi Terdaftar</span>
                </h6>
                <small class="text-muted">Total: <?= count($universitas_list) ?> institusi mitra ditemukan</small>
            </div>

            <!-- Toolbar Pencarian -->
            <form action="<?= base_url('admin/universitas') ?>" method="GET" class="filter-wrapper d-flex align-items-center gap-2 m-0 shadow-xs">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-0 text-muted ps-2 pe-1"><i class="bi bi-search text-success"></i></span>
                    <input type="text" name="keyword" class="form-control border-0 bg-transparent fw-medium" placeholder="Cari nama / kode / alamat..." value="<?= esc($keyword ?? '') ?>" style="min-width: 200px;">
                </div>

                <?php if (!empty($keyword)): ?>
                    <a href="<?= base_url('admin/universitas') ?>" class="btn btn-sm btn-link text-muted p-1" title="Reset Pencarian">
                        <i class="bi bi-x-circle-fill"></i>
                    </a>
                <?php endif; ?>

                <button type="submit" class="btn btn-sm btn-light border rounded-pill px-3 py-1 text-success fw-semibold">
                    Cari
                </button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th style="width: 140px;">Kode Kampus</th>
                        <th>Nama Universitas &amp; Alamat</th>
                        <th style="width: 160px;">Kontak / Telp</th>
                        <th class="text-center" style="width: 180px;">Mahasiswa Terdaftar</th>
                        <th class="text-center" style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($universitas_list)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-buildings text-muted opacity-50" style="font-size: 3rem;"></i>
                                    <h6 class="fw-semibold text-muted mt-3 mb-1">Tidak Ada Data Universitas</h6>
                                    <p class="text-muted small mb-3">Tidak ditemukan data universitas yang sesuai dengan kriteria pencarian.</p>
                                    <a href="<?= base_url('admin/universitas') ?>" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                        <i class="bi bi-arrow-clockwise me-1"></i>Muat Ulang Data
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($universitas_list as $u): ?>
                            <tr>
                                <td class="text-center text-muted fw-semibold"><?= $no++ ?></td>
                                <td>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill badge-kode">
                                        <?= esc($u['kode_universitas']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= esc($u['nama_universitas']) ?></div>
                                    <?php if (!empty($u['alamat'])): ?>
                                        <div class="small text-muted text-truncate mt-1" style="max-width: 480px;" title="<?= esc($u['alamat']) ?>">
                                            <i class="bi bi-geo-alt me-1 text-success"></i><?= esc($u['alamat']) ?>
                                        </div>
                                    <?php else: ?>
                                        <small class="text-muted fst-italic">Belum ada data alamat.</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($u['telepon'])): ?>
                                        <span class="small text-dark fw-medium">
                                            <i class="bi bi-telephone me-1 text-primary"></i><?= esc($u['telepon']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php $jml = (int) ($u['total_mahasiswa'] ?? 0); ?>
                                    <?php if ($jml > 0): ?>
                                        <a href="<?= base_url('admin/pengguna?universitas=' . urlencode($u['nama_universitas'])) ?>" class="badge bg-light text-dark border rounded-pill px-3 py-2 text-decoration-none shadow-xs d-inline-flex align-items-center gap-1" title="Lihat Mahasiswa">
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
                                        <button type="button" class="btn btn-sm btn-light border text-primary rounded-circle p-1" style="width: 32px; height: 32px;" title="Edit Universitas" onclick="bukaModalEditUniversitas(<?= (int) $u['id'] ?>, '<?= esc($u['kode_universitas'], 'js') ?>', '<?= esc($u['nama_universitas'], 'js') ?>', '<?= esc($u['alamat'] ?? '', 'js') ?>', '<?= esc($u['telepon'] ?? '', 'js') ?>')">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <button type="button" class="btn btn-sm btn-light border text-danger rounded-circle p-1" style="width: 32px; height: 32px;" title="Hapus Universitas" onclick="konfirmasiHapusUniversitas(<?= (int) $u['id'] ?>, '<?= esc($u['nama_universitas'], 'js') ?>', <?= $jml ?>)">
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

<!-- Modal Tambah Universitas Modern -->
<div class="modal fade" id="modalTambahUniversitas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-success text-white py-3">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-buildings"></i>
                    <span>Tambah Universitas Baru</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/universitas/tambah') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body text-start p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Kode Singkatan Kampus <span class="text-danger">*</span></label>
                        <input type="text" name="kode_universitas" class="form-control text-uppercase" placeholder="Contoh: UNY, UAD, UGM, UST" maxlength="20" required>
                        <small class="text-muted" style="font-size: 0.75rem;">Singkatan unik pengenal perguruan tinggi (2-20 karakter).</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Nama Lengkap Universitas <span class="text-danger">*</span></label>
                        <input type="text" name="nama_universitas" class="form-control" placeholder="Contoh: Universitas Negeri Yogyakarta" maxlength="150" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Nomor Telepon / Kontak Kampus (Opsional)</label>
                        <input type="text" name="telepon" class="form-control" placeholder="Contoh: (0274) 586168" maxlength="30">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Alamat Kampus (Opsional)</label>
                        <textarea name="alamat" class="form-control" rows="3" placeholder="Contoh: Jl. Colombo No. 1, Caturtunggal, Depok, Sleman, D.I. Yogyakarta"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-skagata rounded-pill px-4">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Universitas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Universitas Modern -->
<div class="modal fade" id="modalEditUniversitas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square"></i>
                    <span>Edit Data Universitas</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/universitas/edit') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="editUniversitasId">
                <div class="modal-body text-start p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Kode Singkatan Kampus <span class="text-danger">*</span></label>
                        <input type="text" name="kode_universitas" id="editKodeUniversitas" class="form-control text-uppercase" maxlength="20" required>
                        <small class="text-muted" style="font-size: 0.75rem;">Singkatan unik pengenal kampus.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Nama Lengkap Universitas <span class="text-danger">*</span></label>
                        <input type="text" name="nama_universitas" id="editNamaUniversitas" class="form-control" maxlength="150" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Nomor Telepon / Kontak Kampus (Opsional)</label>
                        <input type="text" name="telepon" id="editTeleponUniversitas" class="form-control" maxlength="30">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Alamat Kampus (Opsional)</label>
                        <textarea name="alamat" id="editAlamatUniversitas" class="form-control" rows="3"></textarea>
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

<!-- Form Tersembunyi untuk Hapus Universitas -->
<form id="formHapusUniversitas" action="<?= base_url('admin/universitas/hapus') ?>" method="POST" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="id" id="hapusUniversitasId">
</form>

<script>
    function bukaModalEditUniversitas(id, kode, nama, alamat, telepon) {
        document.getElementById('editUniversitasId').value = id;
        document.getElementById('editKodeUniversitas').value = kode;
        document.getElementById('editNamaUniversitas').value = nama;
        document.getElementById('editAlamatUniversitas').value = alamat || '';
        document.getElementById('editTeleponUniversitas').value = telepon || '';
        var modal = new bootstrap.Modal(document.getElementById('modalEditUniversitas'));
        modal.show();
    }

    function konfirmasiHapusUniversitas(id, nama, totalMahasiswa) {
        if (totalMahasiswa > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Tidak Dapat Menghapus!',
                html: `Universitas <strong>${nama}</strong> masih memiliki <strong>${totalMahasiswa} mahasiswa</strong> terdaftar.<br><small class="text-muted mt-2 d-block">Silakan alihkan atau ubah asal universitas mahasiswa terkait terlebih dahulu sebelum menghapus.</small>`,
                confirmButtonColor: '#0f5132',
                confirmButtonText: 'Mengerti'
            });
            return;
        }

        Swal.fire({
            title: 'Hapus Data Universitas?',
            html: `Apakah Anda yakin ingin menghapus data universitas <strong>${nama}</strong>?<br><small class="text-muted">Data yang telah dihapus tidak dapat dipulihkan kembali.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('hapusUniversitasId').value = id;
                document.getElementById('formHapusUniversitas').submit();
            }
        });
    }
</script>
<?= $this->endSection() ?>
