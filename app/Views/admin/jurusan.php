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

    /* Mobile Jurusan Card */
    .jurusan-item-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.875rem;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(15, 81, 50, 0.04);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .jurusan-item-card:active {
        transform: scale(0.99);
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
                <button type="button" class="btn btn-skagata btn-sm rounded-pill px-3 shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahJurusan">
                    <i class="bi bi-plus-circle"></i>
                    <span>Tambah Jurusan Baru</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Ringkasan Statistik Modern -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
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
                        <?= count($jurusan_list) > 0 ? round($total_mahasiswa / count($jurusan_list), 1) : 0 ?>
                    </h3>
                    <small class="text-muted fw-medium"><i class="bi bi-diagram-3 me-1"></i>Mahasiswa per jurusan</small>
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
                <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-list-check text-success"></i>
                    <span>Daftar Jurusan Terdaftar</span>
                </h6>
                <small class="text-muted">Total: <?= count($jurusan_list) ?> bidang kejuruan ditemukan</small>
            </div>

            <!-- Toolbar Pencarian -->
            <form action="<?= base_url('admin/jurusan') ?>" method="GET" class="filter-wrapper d-flex align-items-center gap-2 m-0 shadow-xs">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-0 text-muted ps-2 pe-1"><i class="bi bi-search text-success"></i></span>
                    <input type="text" name="keyword" class="form-control border-0 bg-transparent fw-medium" placeholder="Cari nama / kode jurusan..." value="<?= esc($keyword ?? '') ?>" style="min-width: 200px;">
                </div>

                <?php if (!empty($keyword)): ?>
                    <a href="<?= base_url('admin/jurusan') ?>" class="btn btn-sm btn-link text-muted p-1" title="Reset Pencarian">
                        <i class="bi bi-x-circle-fill"></i>
                    </a>
                <?php endif; ?>

                <button type="submit" class="btn btn-sm btn-light border rounded-pill px-3 py-1 text-success fw-semibold">
                    Cari
                </button>
            </form>
        </div>

        <?php if (empty($jurusan_list)): ?>
            <div class="text-center py-5">
                <div class="py-4">
                    <i class="bi bi-journal-x text-muted opacity-50" style="font-size: 3rem;"></i>
                    <h6 class="fw-semibold text-muted mt-3 mb-1">Tidak Ada Data Jurusan</h6>
                    <p class="text-muted small mb-3">Tidak ditemukan data jurusan yang sesuai dengan kriteria pencarian.</p>
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
                    <?php $noMobile = 1; foreach ($jurusan_list as $j): ?>
                        <?php $jml = (int) ($j['total_mahasiswa'] ?? 0); ?>
                        <div class="jurusan-item-card">
                            <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                                <div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill badge-kode mb-1">
                                        <?= esc($j['kode_jurusan']) ?>
                                    </span>
                                    <div class="fw-bold text-dark mt-1" style="font-size: 0.95rem;"><?= esc($j['nama_jurusan']) ?></div>
                                </div>
                                <div class="text-end">
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

                            <?php if (!empty($j['deskripsi'])): ?>
                                <div class="p-2 bg-light rounded-3 small text-muted mb-3" style="font-size: 0.8rem;">
                                    <?= esc($j['deskripsi']) ?>
                                </div>
                            <?php else: ?>
                                <div class="mb-3 small text-muted fst-italic" style="font-size: 0.75rem;">Belum ada deskripsi.</div>
                            <?php endif; ?>

                            <!-- Tombol Aksi Mobile Touch-Friendly -->
                            <div class="d-flex align-items-center gap-2 pt-1 border-top">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill flex-fill py-1.5 d-flex align-items-center justify-content-center gap-1" style="font-size: 0.82rem;"
                                        onclick="bukaModalEditJurusan(<?= (int) $j['id'] ?>, '<?= esc($j['kode_jurusan'], 'js') ?>', '<?= esc($j['nama_jurusan'], 'js') ?>', '<?= esc($j['deskripsi'] ?? '', 'js') ?>')">
                                    <i class="bi bi-pencil-square"></i>
                                    <span>Edit</span>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill flex-fill py-1.5 d-flex align-items-center justify-content-center gap-1" style="font-size: 0.82rem;"
                                        onclick="konfirmasiHapusJurusan(<?= (int) $j['id'] ?>, '<?= esc($j['nama_jurusan'], 'js') ?>', <?= $jml ?>)">
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
                            <th style="width: 140px;">Kode Jurusan</th>
                            <th>Nama Jurusan &amp; Keterangan</th>
                            <th class="text-center" style="width: 180px;">Mahasiswa Terdaftar</th>
                            <th class="text-center" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($jurusan_list as $j): ?>
                            <tr>
                                <td class="text-center text-muted fw-semibold"><?= $no++ ?></td>
                                <td>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill badge-kode">
                                        <?= esc($j['kode_jurusan']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= esc($j['nama_jurusan']) ?></div>
                                    <?php if (!empty($j['deskripsi'])): ?>
                                        <div class="small text-muted text-truncate mt-1" style="max-width: 520px;" title="<?= esc($j['deskripsi']) ?>">
                                            <?= esc($j['deskripsi']) ?>
                                        </div>
                                    <?php else: ?>
                                        <small class="text-muted fst-italic">Belum ada deskripsi.</small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php $jml = (int) ($j['total_mahasiswa'] ?? 0); ?>
                                    <?php if ($jml > 0): ?>
                                        <a href="<?= base_url('admin/pengguna?jurusan=' . urlencode($j['nama_jurusan'])) ?>" class="badge bg-light text-dark border rounded-pill px-3 py-2 text-decoration-none shadow-xs d-inline-flex align-items-center gap-1" title="Lihat Mahasiswa">
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
                                        <button type="button" class="btn btn-sm btn-light border text-primary rounded-circle p-1" style="width: 32px; height: 32px;" title="Edit Jurusan" onclick="bukaModalEditJurusan(<?= (int) $j['id'] ?>, '<?= esc($j['kode_jurusan'], 'js') ?>', '<?= esc($j['nama_jurusan'], 'js') ?>', '<?= esc($j['deskripsi'] ?? '', 'js') ?>')">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <button type="button" class="btn btn-sm btn-light border text-danger rounded-circle p-1" style="width: 32px; height: 32px;" title="Hapus Jurusan" onclick="konfirmasiHapusJurusan(<?= (int) $j['id'] ?>, '<?= esc($j['nama_jurusan'], 'js') ?>', <?= $jml ?>)">
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
    </div>
</div>

<!-- Modal Tambah Jurusan Modern -->
<div class="modal fade" id="modalTambahJurusan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-success text-white py-3">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-journal-plus"></i>
                    <span>Tambah Jurusan Baru</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/jurusan/tambah') ?>" method="POST" id="formTambahJurusan">
                <?= csrf_field() ?>
                <div class="modal-body text-start p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Kode Jurusan <span class="text-danger">*</span></label>
                        <input type="text" name="kode_jurusan" class="form-control text-uppercase" placeholder="Contoh: TKJ, RPL, TL, TO" maxlength="20" required>
                        <small class="text-muted" style="font-size: 0.75rem;">Singkatan unik pengenal jurusan (2-20 karakter).</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Nama Lengkap Jurusan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_jurusan" class="form-control" placeholder="Contoh: Teknik Komputer dan Jaringan" maxlength="100" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Deskripsi / Keterangan (Opsional)</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi konsentrasi keahlian atau catatan bidang kerja sama..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-skagata rounded-pill px-4" id="btnSubmitTambahJurusan">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Jurusan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Jurusan Modern -->
<div class="modal fade" id="modalEditJurusan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square"></i>
                    <span>Edit Data Jurusan</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/jurusan/edit') ?>" method="POST" id="formEditJurusan">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="editJurusanId">
                <div class="modal-body text-start p-4">
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
                <div class="modal-footer border-0 bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-skagata rounded-pill px-4" id="btnSubmitEditJurusan">
                        <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
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
                form.addEventListener('submit', function() {
                    btn.disabled = true;
                    btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> ${loadingText}`;
                });
            }
        };

        bindSubmitSpinner('formTambahJurusan', 'btnSubmitTambahJurusan', 'Menyimpan...');
        bindSubmitSpinner('formEditJurusan', 'btnSubmitEditJurusan', 'Menyimpan...');
    });

    function bukaModalEditJurusan(id, kode, nama, deskripsi) {
        document.getElementById('editJurusanId').value = id;
        document.getElementById('editKodeJurusan').value = kode;
        document.getElementById('editNamaJurusan').value = nama;
        document.getElementById('editDeskripsiJurusan').value = deskripsi || '';
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
</script>
<?= $this->endSection() ?>

