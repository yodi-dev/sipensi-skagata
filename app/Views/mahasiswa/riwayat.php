<?= $this->extend('layout/template') ?>

<?= $this->section('styles') ?>
<style>
    /* Card & Container Polish */
    .history-card {
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(15, 81, 50, 0.05);
        background: #ffffff;
        overflow: hidden;
    }

    .summary-card {
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        box-shadow: 0 2px 10px rgba(15, 81, 50, 0.03);
    }

    .rate-pill {
        background-color: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        border-radius: 2rem;
        padding: 0.4rem 0.85rem;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .filter-wrapper {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 2rem;
        padding: 0.35rem 0.75rem;
    }

    /* Desktop Table Styling */
    .table-custom thead th {
        background-color: #0f5132;
        color: #ffffff;
        font-weight: 600;
        border-bottom: none;
        padding: 0.85rem;
        white-space: nowrap;
        font-size: 0.85rem;
        letter-spacing: 0.3px;
    }

    .table-custom tbody td {
        padding: 0.85rem;
        vertical-align: middle;
        font-size: 0.875rem;
    }

    /* Mobile Timeline Card Styling */
    .presence-item-card {
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .presence-item-card:active {
        transform: scale(0.99);
    }

    .time-chip {
        background-color: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 0.75rem;
        padding: 0.5rem 0.75rem;
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

$namaBulanSingkat = [
    '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
    '04' => 'Apr', '05' => 'Mei', '06' => 'Jun',
    '07' => 'Jul', '08' => 'Agu', '09' => 'Sep',
    '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
];

$namaMhs = session()->get('nama') ?? 'Mahasiswa Praktikan';
$jurusanMhs = session()->get('jurusan') ?? ($userData['jurusan'] ?? 'PPL / Magang');
$periodeText = ($namaBulanIndo[$bulan_pilih] ?? $bulan_pilih) . ' ' . $tahun_pilih;
?>

<div class="container py-4">

    <!-- Header Halaman & Tombol Navigasi -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-clock-history text-success"></i>
                <span>Riwayat Presensi Mandiri</span>
            </h4>
            <p class="text-muted small mt-1 mb-0">
                Catatan kehadiran <strong><?= esc($namaMhs) ?></strong> (<?= esc($jurusanMhs) ?>) di SMKN 3 Yogyakarta
            </p>
        </div>
        <div>
            <a href="<?= base_url('mahasiswa') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-arrow-left"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Ringkasan Kehadiran Terpadu (Elegan, Bukan Warna-warni Pelangi) -->
    <div class="card summary-card p-3 p-md-4 mb-4">
        <div class="row align-items-center g-3">
            <!-- Sisi Kiri: Indikator Tingkat Kehadiran -->
            <div class="col-12 col-md-5 border-md-end">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex flex-column">
                        <span class="text-muted small fw-medium">Tingkat Kehadiran Efektif</span>
                        <div class="d-flex align-items-baseline gap-2 mt-1">
                            <h2 class="fw-bold text-success mb-0"><?= $persenKehadiran ?>%</h2>
                            <span class="text-muted small">(<?= $totalHadirFisik ?> dari <?= $totalPresensi ?> hari presensi)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sisi Kanan: Rincian Status dalam Soft Badges Netral -->
            <div class="col-12 col-md-7">
                <div class="d-flex flex-wrap align-items-center justify-content-start justify-content-md-end gap-2">
                    <div class="rate-pill">
                        <i class="bi bi-check-circle-fill text-success"></i>
                        <span>Hadir: <strong><?= $rekap['hadir'] ?></strong></span>
                    </div>
                    <div class="rate-pill" style="background-color: #fffbeb; border-color: #fef3c7; color: #92400e;">
                        <i class="bi bi-alarm-fill text-warning"></i>
                        <span>Terlambat: <strong><?= $rekap['terlambat'] ?></strong></span>
                    </div>
                    <div class="rate-pill" style="background-color: #f0f9ff; border-color: #e0f2fe; color: #075985;">
                        <i class="bi bi-info-circle-fill text-info"></i>
                        <span>Izin: <strong><?= $rekap['izin'] ?></strong></span>
                    </div>
                    <div class="rate-pill" style="background-color: #f8fafc; border-color: #e2e8f0; color: #475569;">
                        <i class="bi bi-heart-pulse-fill text-secondary"></i>
                        <span>Sakit: <strong><?= $rekap['sakit'] ?></strong></span>
                    </div>
                    <div class="rate-pill" style="background-color: #fef2f2; border-color: #fee2e2; color: #991b1b;">
                        <i class="bi bi-x-circle-fill text-danger"></i>
                        <span>Alpa: <strong><?= $rekap['alpa'] ?></strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Periode ala Sibenka & Kontrol Tampilan -->
    <div class="card history-card mb-4">
        <div class="p-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-white">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-semibold">
                    <i class="bi bi-calendar-check me-1"></i> Periode: <?= esc($periodeText) ?>
                </span>
            </div>

            <!-- Form Filter Otomatis (Onchange Submit) -->
            <form action="<?= base_url('mahasiswa/riwayat') ?>" method="GET" class="filter-wrapper d-flex align-items-center flex-wrap gap-2 m-0 shadow-xs">
                <div class="input-group input-group-sm" style="width: auto;">
                    <span class="input-group-text bg-white border-0 text-muted ps-2 pe-1"><i class="bi bi-calendar-month text-success"></i></span>
                    <select name="bulan" class="form-select form-select-sm border-0 bg-transparent fw-medium" onchange="this.form.submit()" aria-label="Pilih Bulan">
                        <?php foreach ($namaBulanIndo as $angka => $nama): ?>
                            <option value="<?= $angka ?>" <?= ($bulan_pilih == $angka) ? 'selected' : '' ?>>
                                <?= $nama ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="vr my-1 text-muted d-none d-sm-block"></div>

                <div class="input-group input-group-sm" style="width: auto;">
                    <span class="input-group-text bg-white border-0 text-muted ps-2 pe-1"><i class="bi bi-calendar-event text-success"></i></span>
                    <select name="tahun" class="form-select form-select-sm border-0 bg-transparent fw-medium" onchange="this.form.submit()" aria-label="Pilih Tahun">
                        <?php
                        $tahunSekarang = date('Y');
                        for ($t = $tahunSekarang; $t >= 2023; $t--):
                        ?>
                            <option value="<?= $t ?>" <?= ($tahun_pilih == $t) ? 'selected' : '' ?>>
                                <?= $t ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </form>
        </div>

        <?php if (empty($riwayat)): ?>
            <!-- Empty State -->
            <div class="text-center py-5 p-4 text-muted">
                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 64px; height: 64px;">
                    <i class="bi bi-calendar-x fs-2 text-secondary"></i>
                </div>
                <h6 class="fw-semibold text-dark mb-1">Belum Ada Presensi Tercatat</h6>
                <p class="small text-muted mb-0">Tidak ditemukan riwayat kehadiran untuk periode <strong><?= esc($periodeText) ?></strong>.</p>
            </div>
        <?php else: ?>

            <!-- ========================================== -->
            <!-- 1. MOBILE VIEW: Timeline Card Feed (< 768px) -->
            <!-- ========================================== -->
            <div class="d-block d-md-none p-3 bg-light bg-opacity-50">
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($riwayat as $key => $row): ?>
                        <?php
                        $ts = strtotime($row['tanggal']);
                        $hariEn = date('l', $ts);
                        $hariId = $namaHariIndo[$hariEn] ?? $hariEn;
                        $tglFmt = date('d', $ts) . ' ' . ($namaBulanSingkat[date('m', $ts)] ?? date('M', $ts)) . ' ' . date('Y', $ts);
                        ?>
                        <div class="card presence-item-card p-3">
                            <!-- Card Header: Hari & Tanggal + Status Badge -->
                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                <div>
                                    <span class="fw-bold text-dark d-block"><?= esc($hariId) ?>, <?= esc($tglFmt) ?></span>
                                    <small class="text-muted" style="font-size: 0.72rem;">#<?= esc($key + 1) ?></small>
                                </div>
                                <div>
                                    <?php if ($row['status'] === 'hadir'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                            <i class="bi bi-check2 me-1"></i>Hadir
                                        </span>
                                    <?php elseif ($row['status'] === 'terlambat'): ?>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2 py-1">
                                            <i class="bi bi-alarm me-1"></i>Terlambat
                                        </span>
                                    <?php elseif ($row['status'] === 'izin'): ?>
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill px-2 py-1">
                                            <i class="bi bi-info-circle me-1"></i>Izin
                                        </span>
                                    <?php elseif ($row['status'] === 'sakit'): ?>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1">
                                            <i class="bi bi-heart-pulse me-1"></i>Sakit
                                        </span>
                                    <?php elseif ($row['status'] === 'alpa'): ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1">
                                            <i class="bi bi-x-circle me-1"></i>Alpa
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark border rounded-pill px-2 py-1"><?= esc($row['status']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Card Body: Grid Jam Masuk & Jam Pulang -->
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <div class="time-chip text-center">
                                        <div class="text-muted small" style="font-size: 0.72rem;">
                                            <i class="bi bi-box-arrow-in-right text-success me-1"></i>Jam Datang
                                        </div>
                                        <div class="fw-bold mt-1 <?= !empty($row['jam_masuk']) ? ($row['status'] === 'terlambat' ? 'text-warning-emphasis' : 'text-success') : 'text-muted' ?>" style="font-size: 0.95rem;">
                                            <?= !empty($row['jam_masuk']) ? esc(substr($row['jam_masuk'], 0, 5)) . ' WIB' : '--:--' ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="time-chip text-center">
                                        <div class="text-muted small" style="font-size: 0.72rem;">
                                            <i class="bi bi-box-arrow-right text-warning me-1"></i>Jam Pulang
                                        </div>
                                        <div class="fw-bold mt-1 <?= !empty($row['jam_keluar']) ? 'text-dark' : 'text-muted' ?>" style="font-size: 0.95rem;">
                                            <?= !empty($row['jam_keluar']) ? esc(substr($row['jam_keluar'], 0, 5)) . ' WIB' : '--:--' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Keterangan (Jika Ada) -->
                            <?php if (!empty($row['keterangan'])): ?>
                                <div class="bg-light rounded-3 p-2 small text-muted mb-2" style="font-size: 0.8rem;">
                                    <i class="bi bi-chat-quote me-1 text-secondary"></i><?= esc($row['keterangan']) ?>
                                </div>
                            <?php endif; ?>

                            <!-- Bukti Surat / Tombol Susulan -->
                            <?php if (!empty($row['bukti_surat'])): ?>
                                <a href="<?= base_url('uploads/surat/' . esc($row['bukti_surat'])) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-success rounded-pill w-100 mt-1 d-flex align-items-center justify-content-center gap-1">
                                    <i class="bi bi-file-earmark-check"></i>
                                    <span>Lihat Bukti Surat</span>
                                </a>
                            <?php elseif (in_array($row['status'], ['izin', 'sakit'], true)): ?>
                                <button type="button" class="btn btn-sm btn-outline-warning rounded-pill w-100 mt-1 d-flex align-items-center justify-content-center gap-1" onclick="bukaModalSusulan(<?= (int) $row['id'] ?>, '<?= esc($row['tanggal']) ?>')">
                                    <i class="bi bi-upload"></i>
                                    <span>Unggah Bukti Susulan</span>
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- 2. DESKTOP VIEW: Full Data Table              -->
            <!-- ============================================== -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover table-bordered table-custom text-center mb-0 align-middle">
                    <thead>
                        <tr>
                            <th width="4%">No</th>
                            <th width="18%">Hari &amp; Tanggal</th>
                            <th width="12%">Status</th>
                            <th width="12%">Jam Masuk</th>
                            <th width="12%">Jam Pulang</th>
                            <th width="24%" class="text-start">Keterangan</th>
                            <th width="18%">Bukti Surat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($riwayat as $key => $row): ?>
                            <?php
                            $ts = strtotime($row['tanggal']);
                            $hariEn = date('l', $ts);
                            $hariId = $namaHariIndo[$hariEn] ?? $hariEn;
                            $tglFmt = date('d', $ts) . ' ' . ($namaBulanSingkat[date('m', $ts)] ?? date('M', $ts)) . ' ' . date('Y', $ts);
                            ?>
                            <tr>
                                <td><span class="text-muted"><?= esc($key + 1) ?></span></td>
                                <td class="fw-semibold text-dark text-nowrap">
                                    <span><?= esc($hariId) ?>, <?= esc($tglFmt) ?></span>
                                </td>

                                <td>
                                    <?php if ($row['status'] === 'hadir'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">Hadir</span>
                                    <?php elseif ($row['status'] === 'terlambat'): ?>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-pill">Terlambat</span>
                                    <?php elseif ($row['status'] === 'izin'): ?>
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1 rounded-pill">Izin</span>
                                    <?php elseif ($row['status'] === 'sakit'): ?>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 rounded-pill">Sakit</span>
                                    <?php elseif ($row['status'] === 'alpa'): ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">Alpa</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark border px-2 py-1 rounded-pill"><?= esc($row['status']) ?></span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="fw-semibold <?= !empty($row['jam_masuk']) ? ($row['status'] === 'terlambat' ? 'text-warning-emphasis' : 'text-success') : 'text-muted' ?>">
                                        <?= !empty($row['jam_masuk']) ? esc(substr($row['jam_masuk'], 0, 5)) . ' WIB' : '--:--' ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="fw-semibold <?= !empty($row['jam_keluar']) ? 'text-dark' : 'text-muted' ?>">
                                        <?= !empty($row['jam_keluar']) ? esc(substr($row['jam_keluar'], 0, 5)) . ' WIB' : '--:--' ?>
                                    </span>
                                </td>

                                <td class="text-start text-muted small">
                                    <?= !empty($row['keterangan']) ? esc($row['keterangan']) : '<span class="text-muted fst-italic">-</span>' ?>
                                </td>

                                <td>
                                    <?php if (!empty($row['bukti_surat'])): ?>
                                        <a href="<?= base_url('uploads/surat/' . esc($row['bukti_surat'])) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                            <i class="bi bi-file-earmark-check me-1"></i> Bukti Surat
                                        </a>
                                    <?php elseif (in_array($row['status'], ['izin', 'sakit'], true)): ?>
                                        <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-3" onclick="bukaModalSusulan(<?= (int) $row['id'] ?>, '<?= esc($row['tanggal']) ?>')">
                                            <i class="bi bi-upload me-1"></i> Upload Susulan
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>
    </div>

</div>

<!-- Modal Upload Bukti Susulan yang Dipoles -->
<div class="modal fade" id="modalSusulan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-success text-white py-3">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <span>Unggah Bukti Surat Susulan</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('mahasiswa/upload-bukti-susulan') ?>" method="POST" enctype="multipart/form-data" id="formSusulan">
                <?= csrf_field() ?>
                <input type="hidden" name="presensi_id" id="susulanPresensiId">
                <div class="modal-body text-start p-4">
                    <div class="alert alert-light border d-flex align-items-center gap-2 py-2 px-3 rounded-3 mb-3">
                        <i class="bi bi-calendar-event text-success fs-5"></i>
                        <div class="small">
                            Presensi Tanggal: <strong id="susulanTanggalText" class="text-dark"></strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Pilih Berkas Bukti (Surat Dokter / Izin)</label>
                        <input type="file" name="bukti_surat" id="inputBuktiSurat" class="form-control" accept=".jpg,.jpeg,.png,.webp,.pdf" required onchange="handleFileChange(this)">
                        <div class="form-text small mt-1">
                            Format didukung: <strong>JPG, PNG, WEBP, atau PDF</strong> (Maks. 2MB).
                        </div>
                        <div id="fileInfoLabel" class="small text-success fw-medium mt-2 d-none">
                            <i class="bi bi-check2-circle me-1"></i>Berkas terpilih: <span id="fileNameDisplay"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-skagata rounded-pill px-4" id="btnSubmitSusulan">
                        <i class="bi bi-upload me-1"></i> Unggah Berkas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function bukaModalSusulan(presensiId, tanggal) {
        document.getElementById('susulanPresensiId').value = presensiId;
        document.getElementById('susulanTanggalText').innerText = tanggal;
        
        // Reset file info
        const fileInput = document.getElementById('inputBuktiSurat');
        if (fileInput) fileInput.value = '';
        const infoLabel = document.getElementById('fileInfoLabel');
        if (infoLabel) infoLabel.classList.add('d-none');

        const modal = new bootstrap.Modal(document.getElementById('modalSusulan'));
        modal.show();
    }

    function handleFileChange(input) {
        const infoLabel = document.getElementById('fileInfoLabel');
        const nameDisplay = document.getElementById('fileNameDisplay');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const sizeMb = file.size / (1024 * 1024);
            if (sizeMb > 2) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Ukuran Terlalu Besar',
                    text: 'Ukuran berkas melebihi batas 2MB. Silakan pilih berkas yang lebih kecil.',
                    confirmButtonColor: '#0f5132'
                });
                input.value = '';
                infoLabel.classList.add('d-none');
                return;
            }
            nameDisplay.innerText = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            infoLabel.classList.remove('d-none');
        } else {
            infoLabel.classList.add('d-none');
        }
    }

    // Submit loading state untuk bukti susulan
    const formSusulan = document.getElementById('formSusulan');
    if (formSusulan) {
        formSusulan.addEventListener('submit', function() {
            const btn = document.getElementById('btnSubmitSusulan');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Mengunggah...';
            }
        });
    }
</script>
<?= $this->endSection() ?>
