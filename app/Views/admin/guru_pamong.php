<?= $this->extend('layout/template') ?>

<?= $this->section('custom_css') ?>
<style>
    /* Styling Pemetaan Guru Pamong Skagata */
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

    .avatar-initial {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0f5132 0%, #10b981 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.875rem;
        flex-shrink: 0;
    }

    .jurusan-check-card {
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        transition: all 0.15s ease-in-out;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: #f8fafc;
    }

    .jurusan-check-card:hover {
        border-color: #10b981;
        background: #ecfdf5;
    }

    .jurusan-check-card.checked {
        border-color: #0f5132;
        background-color: #ecfdf5;
        box-shadow: 0 0 0 1px #0f5132;
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
                <li class="breadcrumb-item text-muted">Manajemen Pengguna</li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Pemetaan Guru Pamong</li>
            </ol>
        </nav>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-person-lines-fill text-success"></i>
                    <span>Pemetaan Guru Pamong &amp; Isolasi Jurusan</span>
                </h4>
                <p class="text-muted small mt-1 mb-0">Petakan penugasan Guru Pamong ke jurusan binaan untuk menjamin isolasi data monitoring, presensi, dan rekapitulasi bulanan</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="<?= base_url('admin/pengguna') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm d-inline-flex align-items-center gap-1">
                    <i class="bi bi-people"></i>
                    <span>Data Pengguna</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    <?php if (session()->getFlashdata('pesan')): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 d-flex align-items-center gap-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill fs-4 text-success"></i>
            <div>
                <strong>Berhasil!</strong> <?= session()->getFlashdata('pesan') ?>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 d-flex align-items-center gap-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
            <div>
                <strong>Perhatian!</strong> <?= session()->getFlashdata('error') ?>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- 4 Stat Cards Modern -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Guru Pamong -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-modern">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-medium">Total Guru Pamong</div>
                        <div class="fs-3 fw-bold text-dark mt-1"><?= (int) $total_guru ?></div>
                    </div>
                    <div class="stat-icon-wrapper bg-success-subtle text-success">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                </div>
                <div class="mt-3 small text-muted d-flex align-items-center gap-1">
                    <span class="text-success fw-medium">Guru &amp; GTT</span> pembimbing PPL/PK
                </div>
            </div>
        </div>

        <!-- Card 2: Terpetakan -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-modern">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-medium">Terpetakan Jurusan</div>
                        <div class="fs-3 fw-bold text-success mt-1"><?= (int) $guru_terpetakan ?></div>
                    </div>
                    <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success">
                        <i class="bi bi-shield-check"></i>
                    </div>
                </div>
                <div class="mt-3 small text-muted d-flex align-items-center gap-1">
                    <span class="text-success fw-medium"><?= ($total_guru > 0) ? round(($guru_terpetakan / $total_guru) * 100) : 0 ?>%</span> dari total guru pamong
                </div>
            </div>
        </div>

        <!-- Card 3: Belum Dipetakan -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-modern">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-medium">Belum Dipetakan</div>
                        <div class="fs-3 fw-bold <?= ($guru_belum > 0) ? 'text-warning' : 'text-secondary' ?> mt-1"><?= (int) $guru_belum ?></div>
                    </div>
                    <div class="stat-icon-wrapper <?= ($guru_belum > 0) ? 'bg-warning-subtle text-warning' : 'bg-light text-muted' ?>">
                        <i class="bi bi-exclamation-circle-fill"></i>
                    </div>
                </div>
                <div class="mt-3 small text-muted d-flex align-items-center gap-1">
                    <span class="<?= ($guru_belum > 0) ? 'text-warning' : 'text-muted' ?> fw-medium"><?= ($guru_belum > 0) ? 'Perlu dipetakan' : 'Semua terpetakan' ?></span>
                </div>
            </div>
        </div>

        <!-- Card 4: Mahasiswa Terbimbing -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-modern">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-medium">Mahasiswa Terbimbing</div>
                        <div class="fs-3 fw-bold text-primary mt-1"><?= (int) $total_mahasiswa_terbimbing ?></div>
                    </div>
                    <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                </div>
                <div class="mt-3 small text-muted d-flex align-items-center gap-1">
                    <span class="text-primary fw-medium">Praktikan PPL/PK</span> di bawah binaan
                </div>
            </div>
        </div>
    </div>

    <!-- Banner Prinsip Isolasi Data -->
    <div class="alert alert-light border border-success-subtle bg-white rounded-4 shadow-xs p-3 p-md-4 mb-4">
        <div class="d-flex align-items-start gap-3">
            <div class="p-2 rounded-circle bg-success-subtle text-success flex-shrink-0 d-none d-sm-block">
                <i class="bi bi-shield-lock-fill fs-4"></i>
            </div>
            <div>
                <h6 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <i class="bi bi-shield-lock-fill text-success d-sm-none"></i>
                    <span>Prinsip Keamanan &amp; Isolasi Data Guru Pamong</span>
                </h6>
                <p class="text-muted small mb-0 lh-base">
                    Sistem menerapkan <strong>Strict Data Isolation</strong>: Guru Pamong hanya memiliki akses monitoring harian, persetujuan/validasi presensi, laporan piket KBM, dan rekapitulasi bulanan untuk mahasiswa yang jurusannya terdaftar pada pemetaan di bawah ini. Guru Pamong yang belum dipetakan tidak akan melihat mahasiswa dari jurusan lain guna menjaga privasi dan ketertiban administrasi.
                </p>
            </div>
        </div>
    </div>

    <!-- Tabel Pemetaan Guru Pamong Card -->
    <div class="admin-card">
        <div class="card-header-custom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <h6 class="fw-bold text-dark mb-0">Daftar Penugasan Guru Pamong</h6>
                <span class="badge bg-light text-muted border rounded-pill"><?= count($guru_list) ?> Guru</span>
            </div>

            <!-- Quick Filter & Search -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="input-group input-group-sm" style="width: 240px;">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" id="inputCariGuru" class="form-control border-start-0 ps-0" placeholder="Cari nama / jurusan..." onkeyup="filterTabelGuru()">
                </div>
                <select id="filterStatusPemetaan" class="form-select form-select-sm" style="width: 170px;" onchange="filterTabelGuru()">
                    <option value="">Semua Status</option>
                    <option value="terpetakan">Terpetakan</option>
                    <option value="belum">Belum Dipetakan</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-custom align-middle mb-0" id="tabelGuruPamong">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>Guru Pamong</th>
                        <th>Jurusan Bimbingan</th>
                        <th class="text-center" style="width: 140px;">Mahasiswa</th>
                        <th>Catatan / Keterangan</th>
                        <th class="text-center" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($guru_list)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-people text-muted opacity-50" style="font-size: 3rem;"></i>
                                    <h6 class="fw-semibold text-muted mt-3 mb-1">Belum Ada Akun Guru Pamong</h6>
                                    <p class="text-muted small mb-3">Tambahkan akun pengguna dengan role Guru Pamong terlebih dahulu di halaman Data Pengguna.</p>
                                    <a href="<?= base_url('admin/pengguna') ?>" class="btn btn-sm btn-skagata rounded-pill px-3">
                                        <i class="bi bi-person-plus me-1"></i>Kelola Pengguna
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($guru_list as $g): ?>
                            <?php
                            $isMapped = !empty($g['jurusan_list']);
                            $jurusanJson = htmlspecialchars(json_encode($g['jurusan_list']), ENT_QUOTES, 'UTF-8');
                            $keteranganSafe = htmlspecialchars($g['keterangan'] ?? '', ENT_QUOTES, 'UTF-8');
                            ?>
                            <tr class="baris-guru" data-status="<?= $isMapped ? 'terpetakan' : 'belum' ?>" data-search="<?= esc(strtolower($g['nama'] . ' ' . $g['username'] . ' ' . $g['jurusan_label'])) ?>">
                                <td class="text-center text-muted fw-semibold"><?= $no++ ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-initial">
                                            <?= esc(mb_strtoupper(mb_substr($g['nama'], 0, 1))) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark lh-sm"><?= esc($g['nama']) ?></div>
                                            <small class="text-muted font-monospace" style="font-size: 0.78rem;">@<?= esc($g['username']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($isMapped): ?>
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php foreach ($g['jurusan_list'] as $j): ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                                    <i class="bi bi-journal-check me-1"></i><?= esc($j) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2 py-1">
                                            <i class="bi bi-exclamation-circle me-1"></i>Belum Dipetakan
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($g['total_mahasiswa'] > 0): ?>
                                        <span class="badge bg-light text-primary border rounded-pill px-2 py-1 fw-semibold">
                                            <i class="bi bi-mortarboard me-1"></i><?= (int) $g['total_mahasiswa'] ?> Praktikan
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border rounded-pill px-2 py-1">0 Praktikan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($g['keterangan'])): ?>
                                        <small class="text-muted d-block text-truncate" style="max-width: 200px;" title="<?= esc($g['keterangan']) ?>">
                                            <i class="bi bi-info-circle me-1 text-secondary"></i><?= esc($g['keterangan']) ?>
                                        </small>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <!-- Tombol Petakan / Edit Pemetaan -->
                                        <button type="button" class="btn btn-sm btn-light border text-success rounded-pill px-2 py-1 shadow-xs d-inline-flex align-items-center gap-1"
                                                title="Petakan Jurusan"
                                                onclick="bukaModalPemetaan(<?= (int) $g['id'] ?>, '<?= esc($g['nama'], 'js') ?>', '<?= esc($g['username'], 'js') ?>', <?= $jurusanJson ?>, '<?= esc($g['keterangan'] ?? '', 'js') ?>')">
                                            <i class="bi bi-diagram-3-fill"></i>
                                            <span><?= $isMapped ? 'Ubah' : 'Petakan' ?></span>
                                        </button>

                                        <!-- Tombol Reset Pemetaan jika sudah terpetakan -->
                                        <?php if ($isMapped): ?>
                                            <button type="button" class="btn btn-sm btn-light border text-danger rounded-circle p-1" style="width: 30px; height: 30px;"
                                                    title="Reset Pemetaan"
                                                    onclick="konfirmasiReset(<?= (int) $g['id'] ?>, '<?= esc($g['nama'], 'js') ?>')">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        <?php endif; ?>
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

<!-- Modal Pemetaan Guru Pamong Modern -->
<div class="modal fade" id="modalPemetaan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-success text-white py-3">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-diagram-3-fill"></i>
                    <span>Atur Pemetaan Jurusan Guru Pamong</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/guru-pamong/simpan') ?>" method="POST" id="formPemetaan">
                <?= csrf_field() ?>
                <input type="hidden" name="guru_id" id="pemetaanGuruId">

                <div class="modal-body text-start p-4">
                    <!-- Preview Guru Yang Dipetakan -->
                    <div class="p-3 bg-light rounded-3 border d-flex align-items-center gap-3 mb-4">
                        <div class="avatar-initial" id="modalAvatarInitial" style="width: 44px; height: 44px; font-size: 1.1rem;">
                            G
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0" id="modalGuruNama">Nama Guru</h6>
                            <small class="text-muted font-monospace" id="modalGuruUsername">@username</small>
                        </div>
                    </div>

                    <!-- Pilihan Jurusan Checkboxes -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="form-label fw-bold small text-dark mb-0">
                                Pilih Jurusan Binaan / Pembimbingan
                                <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-link text-success p-0 text-decoration-none small fw-medium" onclick="pilihSemuaJurusan()">Pilih Semua</button>
                                <span class="text-muted small">|</span>
                                <button type="button" class="btn btn-sm btn-link text-muted p-0 text-decoration-none small" onclick="kosongkanJurusan()">Hapus Pilihan</button>
                            </div>
                        </div>
                        <p class="text-muted small mb-3">Guru ini hanya akan dapat mengelola dan memvalidasi presensi mahasiswa dari jurusan yang dipilih di bawah ini:</p>

                        <div class="row g-2">
                            <?php if (!empty($daftar_jurusan)): ?>
                                <?php foreach ($daftar_jurusan as $idx => $jrs): ?>
                                    <div class="col-12 col-md-6">
                                        <label class="jurusan-check-card" id="cardJurusan_<?= $idx ?>">
                                            <input class="form-check-input mt-0 check-jurusan" type="checkbox" name="jurusan[]" value="<?= esc($jrs) ?>" id="chk_<?= $idx ?>" onchange="updateCardCheckStyle(this, 'cardJurusan_<?= $idx ?>')">
                                            <span class="small fw-semibold text-dark flex-grow-1"><?= esc($jrs) ?></span>
                                            <i class="bi bi-check2 text-success fs-5 check-indicator d-none"></i>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <div class="alert alert-warning small mb-0">
                                        Belum ada data jurusan yang terdaftar. Tambahkan jurusan terlebih dahulu di Master Data Jurusan.
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Keterangan / SK Catatan -->
                    <div class="mt-4">
                        <label class="form-label fw-semibold small text-dark">Keterangan / Catatan Penugasan (Opsional)</label>
                        <textarea name="keterangan" id="pemetaanKeterangan" class="form-control" rows="2" placeholder="Contoh: SK Pembimbingan PPL Genap 2026/2027"></textarea>
                    </div>
                </div>

                <div class="modal-footer border-0 bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-skagata rounded-pill px-4">
                        <i class="bi bi-check2-circle me-1"></i> Simpan Pemetaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form Tersembunyi untuk Reset Pemetaan Guru via POST + CSRF -->
<form id="formResetPemetaan" action="<?= base_url('admin/guru-pamong/hapus') ?>" method="POST" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="guru_id" id="resetGuruId">
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function updateCardCheckStyle(checkbox, cardId) {
        const card = document.getElementById(cardId);
        if (!card) return;
        const indicator = card.querySelector('.check-indicator');

        if (checkbox.checked) {
            card.classList.add('checked');
            if (indicator) indicator.classList.remove('d-none');
        } else {
            card.classList.remove('checked');
            if (indicator) indicator.classList.add('d-none');
        }
    }

    function bukaModalPemetaan(guruId, nama, username, jurusanList, keterangan) {
        document.getElementById('pemetaanGuruId').value = guruId;
        document.getElementById('modalGuruNama').innerText = nama;
        document.getElementById('modalGuruUsername').innerText = '@' + username;
        document.getElementById('modalAvatarInitial').innerText = nama.trim().charAt(0).toUpperCase();
        document.getElementById('pemetaanKeterangan').value = keterangan || '';

        // Uncheck all checkboxes first
        const checkboxes = document.querySelectorAll('.check-jurusan');
        checkboxes.forEach((cb) => {
            cb.checked = false;
            const parentCard = cb.closest('.jurusan-check-card');
            if (parentCard) {
                parentCard.classList.remove('checked');
                const ind = parentCard.querySelector('.check-indicator');
                if (ind) ind.classList.add('d-none');
            }
        });

        // Check matched jurusans
        if (Array.isArray(jurusanList)) {
            checkboxes.forEach((cb) => {
                if (jurusanList.includes(cb.value)) {
                    cb.checked = true;
                    const parentCard = cb.closest('.jurusan-check-card');
                    if (parentCard) {
                        parentCard.classList.add('checked');
                        const ind = parentCard.querySelector('.check-indicator');
                        if (ind) ind.classList.remove('d-none');
                    }
                }
            });
        }

        const modal = new bootstrap.Modal(document.getElementById('modalPemetaan'));
        modal.show();
    }

    function pilihSemuaJurusan() {
        const checkboxes = document.querySelectorAll('.check-jurusan');
        checkboxes.forEach((cb) => {
            cb.checked = true;
            const parentCard = cb.closest('.jurusan-check-card');
            if (parentCard) {
                parentCard.classList.add('checked');
                const ind = parentCard.querySelector('.check-indicator');
                if (ind) ind.classList.remove('d-none');
            }
        });
    }

    function kosongkanJurusan() {
        const checkboxes = document.querySelectorAll('.check-jurusan');
        checkboxes.forEach((cb) => {
            cb.checked = false;
            const parentCard = cb.closest('.jurusan-check-card');
            if (parentCard) {
                parentCard.classList.remove('checked');
                const ind = parentCard.querySelector('.check-indicator');
                if (ind) ind.classList.add('d-none');
            }
        });
    }

    function konfirmasiReset(guruId, nama) {
        Swal.fire({
            title: 'Reset Pemetaan Pamong?',
            html: `Pemetaan jurusan untuk <strong>${nama}</strong> akan dihapus. Guru pamong ini tidak akan dapat mengakses data mahasiswa sampai dipetakan kembali.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Reset Pemetaan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('resetGuruId').value = guruId;
                document.getElementById('formResetPemetaan').submit();
            }
        });
    }

    function filterTabelGuru() {
        const keyword = document.getElementById('inputCariGuru').value.toLowerCase().trim();
        const status = document.getElementById('filterStatusPemetaan').value;
        const rows = document.querySelectorAll('#tabelGuruPamong tbody tr.baris-guru');

        rows.forEach((row) => {
            const rowSearch = row.getAttribute('data-search') || '';
            const rowStatus = row.getAttribute('data-status') || '';

            const matchKeyword = keyword === '' || rowSearch.includes(keyword);
            const matchStatus = status === '' || rowStatus === status;

            if (matchKeyword && matchStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
<?= $this->endSection() ?>
