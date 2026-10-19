<?= $this->extend('layout/template') ?>

<?= $this->section('styles') ?>
<style>
    .panduan-hero {
        background: linear-gradient(135deg, #0f5132 0%, #15803d 100%);
        color: #ffffff;
        border-radius: 1rem;
        padding: 2.25rem 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(15, 81, 50, 0.25);
    }

    .panduan-hero::after {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        pointer-events: none;
    }

    .card-panduan {
        border-radius: 0.85rem;
        border: 1px solid #e2e8f0;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        background-color: #ffffff;
    }

    .card-panduan:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }

    .accordion-button:not(.collapsed) {
        background-color: #ecfdf5;
        color: #065f46;
        box-shadow: none;
    }

    .accordion-button:focus {
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
        border-color: #a7f3d0;
    }

    .step-number-badge {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        background-color: #0f5132;
        color: #ffffff;
        flex-shrink: 0;
    }

    .topic-icon-circle {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .quick-card {
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
        padding: 1.25rem;
        height: 100%;
        transition: all 0.2s ease;
    }

    .quick-card:hover {
        border-color: #10b981;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-3 px-md-4 py-4">

    <!-- Hero Banner -->
    <div class="panduan-hero mb-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <span class="badge bg-white bg-opacity-25 text-white rounded-pill px-3 py-1.5 fw-semibold mb-2" style="font-size: 0.8rem;">
                    <i class="bi bi-shield-check me-1"></i> Pusat Panduan Resmi SIPENSI SKAGATA
                </span>
                <h3 class="fw-bold mb-1">Panduan Pengguna: <?= esc($roleTitle) ?></h3>
                <p class="text-white text-opacity-90 small mb-0" style="max-width: 650px; line-height: 1.5;">
                    Petunjuk operasional lengkap, alur presensi harian, tata cara penggunaan modul aplikasi, dan solusi pemecahan kendala lapangan di SMK Negeri 3 Yogyakarta.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <!-- Search input live filter -->
                <div class="bg-white p-2 rounded-3 shadow-sm d-flex align-items-center gap-2">
                    <i class="bi bi-search text-success ps-2"></i>
                    <input type="text" id="inputCariPanduan" class="form-control form-control-sm border-0 shadow-none" placeholder="Cari topik (GPS, jam pulang, kamera)..." oninput="filterPanduan()">
                    <button type="button" id="btnResetPanduan" class="btn btn-sm btn-link text-muted p-0 pe-2 border-0" onclick="resetCariPanduan()" style="display: none;">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>
            </div>
        </div>

        <?php if ($userRole === 'admin'): ?>
            <!-- Admin Switcher Toolbar (Khusus Role Admin untuk melihat sudut pandang role lain) -->
            <div class="mt-3 pt-3 border-top border-white border-opacity-25 d-flex align-items-center flex-wrap gap-2">
                <span class="small text-white text-opacity-75 me-1"><i class="bi bi-eye me-1"></i>Pratinjau Sudut Pandang Role:</span>
                <a href="<?= base_url('panduan?role=admin') ?>" class="btn btn-sm rounded-pill px-3 <?= ($activeRole === 'admin') ? 'btn-light text-success fw-bold shadow-xs' : 'btn-outline-light' ?>">
                    Administrator
                </a>
                <a href="<?= base_url('panduan?role=guru') ?>" class="btn btn-sm rounded-pill px-3 <?= ($activeRole === 'guru') ? 'btn-light text-success fw-bold shadow-xs' : 'btn-outline-light' ?>">
                    Guru Pamong
                </a>
                <a href="<?= base_url('panduan?role=mahasiswa') ?>" class="btn btn-sm rounded-pill px-3 <?= ($activeRole === 'mahasiswa') ? 'btn-light text-success fw-bold shadow-xs' : 'btn-outline-light' ?>">
                    Mahasiswa PPL
                </a>
                <a href="<?= base_url('panduan?role=gtt') ?>" class="btn btn-sm rounded-pill px-3 <?= ($activeRole === 'gtt') ? 'btn-light text-success fw-bold shadow-xs' : 'btn-outline-light' ?>">
                    Guru GTT
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Alert Status Parameter Presensi Dinamis -->
    <div class="alert alert-light border border-success-subtle bg-success-subtle bg-opacity-10 rounded-3 p-3 mb-4 shadow-xs">
        <div class="d-flex align-items-start gap-2">
            <i class="bi bi-info-circle-fill text-success fs-5 flex-shrink-0 mt-0.5"></i>
            <div class="small text-secondary w-100">
                <strong class="text-dark">Ketentuan Sistem Aktif di <?= esc($config['school_name']) ?>:</strong>
                <div class="row g-2 mt-1">
                    <div class="col-6 col-md-3">
                        <span class="d-block text-muted" style="font-size: 0.72rem;">Radius Geofencing</span>
                        <strong class="text-dark"><?= esc($config['school_radius']) ?> Meter</strong>
                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.65rem;">
                            <?= $config['geofence_active'] ? 'GPS Wajib Aktif' : 'Mode Toleransi' ?>
                        </span>
                    </div>
                    <div class="col-6 col-md-3">
                        <span class="d-block text-muted" style="font-size: 0.72rem;">Batas Jam Masuk</span>
                        <strong class="text-dark"><?= esc($config['work_start']) ?> WIB</strong>
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" style="font-size: 0.65rem;">Terlambat setelah jam ini</span>
                    </div>
                    <div class="col-6 col-md-3">
                        <span class="d-block text-muted" style="font-size: 0.72rem;">Batas Jam Pulang</span>
                        <strong class="text-dark"><?= esc($config['work_end']) ?> WIB</strong>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.65rem;">Pulang sebelum jam ini ditolak</span>
                    </div>
                    <div class="col-6 col-md-3">
                        <span class="d-block text-muted" style="font-size: 0.72rem;">Siklus Hari Kerja</span>
                        <strong class="text-dark">Senin s.d. Jumat</strong>
                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" style="font-size: 0.65rem;">5 Hari Kerja Efektif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Flow Cards Sesuai Role -->
    <div class="mb-4">
        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-lightning-charge-fill text-warning"></i>
            <span>Ringkasan Alur Cepat Harian</span>
        </h6>
        <div class="row g-3">
            <?php if ($activeRole === 'mahasiswa' || $activeRole === 'gtt'): ?>
                <div class="col-12 col-md-4">
                    <div class="quick-card">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="step-number-badge">1</span>
                            <h6 class="fw-bold text-dark mb-0">Presensi Datang</h6>
                        </div>
                        <p class="text-muted small mb-0">
                            Aktifkan GPS HP di radius <?= esc($config['school_radius']) ?>m sebelum pukul <?= esc($config['work_start']) ?> WIB, lalu ketuk tombol <strong>Presensi Masuk</strong>.
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="quick-card">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="step-number-badge">2</span>
                            <h6 class="fw-bold text-dark mb-0">Dokumentasi Piket KBM</h6>
                        </div>
                        <p class="text-muted small mb-0">
                            Buka menu <strong>Piket KBM</strong>, izinkan akses kamera, lalu foto kegiatan saat bertugas di ruang piket / lingkungan sekolah.
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="quick-card">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="step-number-badge">3</span>
                            <h6 class="fw-bold text-dark mb-0">Presensi Pulang</h6>
                        </div>
                        <p class="text-muted small mb-0">
                            Setelah pukul <?= esc($config['work_end']) ?> WIB, kembali ke beranda presensi dan tekan tombol <strong>Presensi Pulang</strong> untuk menyelesaikan presensi harian.
                        </p>
                    </div>
                </div>
            <?php elseif ($activeRole === 'guru'): ?>
                <div class="col-12 col-md-4">
                    <div class="quick-card">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="step-number-badge">1</span>
                            <h6 class="fw-bold text-dark mb-0">Pantau Mahasiswa Binaan</h6>
                        </div>
                        <p class="text-muted small mb-0">
                            Masuk ke dashboard Guru Pamong untuk memantau status hadir, terlambat, izin, sakit, maupun alpa mahasiswa sesuai jurusan binaan.
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="quick-card">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="step-number-badge">2</span>
                            <h6 class="fw-bold text-dark mb-0">Verifikasi Piket &amp; Izin</h6>
                        </div>
                        <p class="text-muted small mb-0">
                            Tinjau dokumentasi foto piket mahasiswa di menu <strong>Laporan Piket</strong> dan sesuaikan status presensi secara manual jika ada izin resmi.
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="quick-card">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="step-number-badge">3</span>
                            <h6 class="fw-bold text-dark mb-0">Ekspor Laporan Excel</h6>
                        </div>
                        <p class="text-muted small mb-0">
                            Buka menu <strong>Rekapitulasi Bulanan</strong> untuk mencetak atau mengunduh lembar rekapitulasi presensi bulanan berformat Excel resmi.
                        </p>
                    </div>
                </div>
            <?php else: /* Admin */ ?>
                <div class="col-12 col-md-4">
                    <div class="quick-card">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="step-number-badge">1</span>
                            <h6 class="fw-bold text-dark mb-0">Atur Periode Gelombang</h6>
                        </div>
                        <p class="text-muted small mb-0">
                            Aktifkan periode PPL/PK yang sedang berlangsung di menu <strong>Periode PPL</strong> agar mahasiswa terikat ke gelombang yang benar.
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="quick-card">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="step-number-badge">2</span>
                            <h6 class="fw-bold text-dark mb-0">Impor Data Excel .xlsx</h6>
                        </div>
                        <p class="text-muted small mb-0">
                            Gunakan tombol <strong>Impor Mahasiswa</strong> dengan template Excel resmi untuk mendaftarkan puluhan mahasiswa praktikan dalam sekali klik.
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="quick-card">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="step-number-badge">3</span>
                            <h6 class="fw-bold text-dark mb-0">Pemetaan Guru Pamong</h6>
                        </div>
                        <p class="text-muted small mb-0">
                            Petakan guru pamong ke jurusan mitra spesifik di menu <strong>Pemetaan Pamong</strong> agar mahasiswa terbimbing dengan tepat sasaran.
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modul Panduan Rinci & Interaktif (Accordion) -->
    <div class="mb-5">
        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-journal-text text-success"></i>
            <span>Daftar Modul Panduan &amp; Prosedur Operasional</span>
        </h6>

        <div class="accordion" id="accordionPanduan">

            <?php if ($activeRole === 'mahasiswa'): ?>
                <!-- 1. Presensi Datang Mahasiswa -->
                <div class="accordion-item card-panduan mb-3 item-panduan" data-keywords="presensi datang masuk gps lokasi geofencing radius hadir terlambat">
                    <h2 class="accordion-header" id="headingMhs1">
                        <button class="accordion-button collapsed fw-bold rounded-top" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMhs1" aria-expanded="false" aria-controls="collapseMhs1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Modul 1</span>
                                <span>Tata Cara Presensi Datang (Pagi)</span>
                            </div>
                        </button>
                    </h2>
                    <div id="collapseMhs1" class="accordion-collapse collapse" aria-labelledby="headingMhs1" data-bs-parent="#accordionPanduan">
                        <div class="accordion-body text-secondary small">
                            <ol class="ps-3 mb-3">
                                <li class="mb-2">Pastikan fitur <strong>GPS / Lokasi</strong> pada smartphone Anda telah aktif dalam mode akurasi tinggi.</li>
                                <li class="mb-2">Buka browser (Google Chrome disarankan) lalu akses laman <code>SIPENSI SKAGATA</code>. Jika browser meminta izin akses lokasi, pilih <strong>Izinkan (Allow)</strong>.</li>
                                <li class="mb-2">Sistem akan otomatis mengukur jarak koordinat Anda ke titik pusat <strong><?= esc($config['school_name']) ?></strong>.</li>
                                <li class="mb-2">Pastikan jarak berada di dalam batas radius <strong><?= esc($config['school_radius']) ?> meter</strong>.</li>
                                <li class="mb-2">Tekan tombol hijau <strong>"Presensi Masuk"</strong>.</li>
                            </ol>
                            <div class="alert alert-light border border-warning-subtle py-2 px-3 rounded-3 mb-0">
                                <i class="bi bi-clock-history text-warning me-1"></i>
                                <strong>Ketentuan Jam Datang:</strong> Presensi sebelum pukul <strong><?= esc($config['work_start']) ?> WIB</strong> berstatus <em>Hadir Tepat Waktu</em>. Presensi setelah pukul <strong><?= esc($config['work_start']) ?> WIB</strong> otomatis berstatus <em>Terlambat</em>.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Presensi Pulang Mahasiswa -->
                <div class="accordion-item card-panduan mb-3 item-panduan" data-keywords="presensi pulang sore selesai kerja efektif">
                    <h2 class="accordion-header" id="headingMhs2">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMhs2" aria-expanded="false" aria-controls="collapseMhs2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Modul 2</span>
                                <span>Tata Cara Presensi Pulang (Sore)</span>
                            </div>
                        </button>
                    </h2>
                    <div id="collapseMhs2" class="accordion-collapse collapse" aria-labelledby="headingMhs2" data-bs-parent="#accordionPanduan">
                        <div class="accordion-body text-secondary small">
                            <p class="mb-2">Presensi pulang digunakan untuk mencatat kepulangan setelah menyelesaikan jam kerja efektif 5 hari kerja di sekolah:</p>
                            <ol class="ps-3 mb-3">
                                <li class="mb-2">Tombol <strong>"Presensi Pulang"</strong> hanya dapat ditekan pada atau setelah pukul <strong><?= esc($config['work_end']) ?> WIB</strong>.</li>
                                <li class="mb-2">Jika Anda mencoba menekan tombol pulang sebelum jam <?= esc($config['work_end']) ?> WIB, sistem akan menolak dan memberikan notifikasi peringatan.</li>
                                <li class="mb-2">Setelah menekan tombol pulang, status kepulangan Anda akan tercatat lengkap dengan timestamp resmi.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- 3. Pengajuan Izin / Sakit -->
                <div class="accordion-item card-panduan mb-3 item-panduan" data-keywords="izin sakit surat dokter lampiran bukti tidak masuk berhalangan">
                    <h2 class="accordion-header" id="headingMhs3">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMhs3" aria-expanded="false" aria-controls="collapseMhs3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Modul 3</span>
                                <span>Pengajuan Izin atau Sakit &amp; Upload Bukti</span>
                            </div>
                        </button>
                    </h2>
                    <div id="collapseMhs3" class="accordion-collapse collapse" aria-labelledby="headingMhs3" data-bs-parent="#accordionPanduan">
                        <div class="accordion-body text-secondary small">
                            <ol class="ps-3 mb-3">
                                <li class="mb-2">Jika berhalangan hadir karena sakit atau ada keperluan izin mendesak dari kampus/instansi, tekan tombol <strong>"Izin / Sakit"</strong> di dashboard.</li>
                                <li class="mb-2">Pilih jenis status: <strong>Sakit</strong> atau <strong>Izin</strong>.</li>
                                <li class="mb-2">Tuliskan keterangan alasan yang jelas pada kolom teks.</li>
                                <li class="mb-2">Unggah foto surat keterangan dokter (jika sakit) atau surat izin resmi. Format yang didukung: JPG, PNG, atau PDF.</li>
                                <li class="mb-2">Data pengajuan akan langsung tercatat dan dapat ditinjau oleh Guru Pamong pembimbing Anda.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- 4. Dokumentasi Piket KBM -->
                <div class="accordion-item card-panduan mb-3 item-panduan" data-keywords="piket kbm kamera foto selfie ruang piket dokumentasi mengajar">
                    <h2 class="accordion-header" id="headingMhs4">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMhs4" aria-expanded="false" aria-controls="collapseMhs4">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Modul 4</span>
                                <span>Presensi &amp; Dokumentasi Piket KBM</span>
                            </div>
                        </button>
                    </h2>
                    <div id="collapseMhs4" class="accordion-collapse collapse" aria-labelledby="headingMhs4" data-bs-parent="#accordionPanduan">
                        <div class="accordion-body text-secondary small">
                            <ol class="ps-3 mb-3">
                                <li class="mb-2">Buka menu <strong>Piket KBM</strong> melalui navigasi bawah (mobile) atau sidebar desktop.</li>
                                <li class="mb-2">Izinkan browser mengakses kamera smartphone Anda.</li>
                                <li class="mb-2">Arahkan kamera ke wajah Anda dengan latar belakang meja piket/ruang piket KBM SMK Negeri 3 Yogyakarta.</li>
                                <li class="mb-2">Tekan tombol <strong>"Ambil Foto"</strong>, tinjau preview hasil jepretan, lalu tekan <strong>"Simpan Dokumentasi Piket"</strong>.</li>
                                <li class="mb-2">Dokumentasi ini membuktikan kehadiran Anda saat menjalankan tugas piket di sekolah.</li>
                            </ol>
                        </div>
                    </div>
                </div>

            <?php elseif ($activeRole === 'guru'): ?>
                <!-- 1. Monitoring Binaan -->
                <div class="accordion-item card-panduan mb-3 item-panduan" data-keywords="monitoring mahasiswa bimbingan jurusan pamong hadir terlambat">
                    <h2 class="accordion-header" id="headingGuru1">
                        <button class="accordion-button collapsed fw-bold rounded-top" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGuru1" aria-expanded="false" aria-controls="collapseGuru1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Modul 1</span>
                                <span>Monitoring Presensi Harian Mahasiswa Binaan</span>
                            </div>
                        </button>
                    </h2>
                    <div id="collapseGuru1" class="accordion-collapse collapse" aria-labelledby="headingGuru1" data-bs-parent="#accordionPanduan">
                        <div class="accordion-body text-secondary small">
                            <p class="mb-2">Dashboard Guru Pamong secara otomatis mengisolasi data mahasiswa sesuai konsentrasi keahlian / jurusan bimbingan yang telah dipetakan oleh Administrator:</p>
                            <ol class="ps-3 mb-0">
                                <li class="mb-2">Gunakan dropdown filter tanggal dan filter jurusan untuk memeriksa kehadiran mahasiswa pada hari tertentu.</li>
                                <li class="mb-2">Perhatikan badge status: <strong>Hadir</strong> (hijau), <strong>Terlambat</strong> (kuning), <strong>Izin</strong> (biru), <strong>Sakit</strong> (oranye), dan <strong>Alpa</strong> (merah).</li>
                                <li class="mb-2">Jam datang dan jam pulang mahasiswa terekam transparan beserta koordinat lokasi presensi.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- 2. Update Status Manual -->
                <div class="accordion-item card-panduan mb-3 item-panduan" data-keywords="update status koreksi presensi izin sakit hadir alpa manual">
                    <h2 class="accordion-header" id="headingGuru2">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGuru2" aria-expanded="false" aria-controls="collapseGuru2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Modul 2</span>
                                <span>Verifikasi &amp; Koreksi Status Kehadiran Mahasiswa</span>
                            </div>
                        </button>
                    </h2>
                    <div id="collapseGuru2" class="accordion-collapse collapse" aria-labelledby="headingGuru2" data-bs-parent="#accordionPanduan">
                        <div class="accordion-body text-secondary small">
                            <ol class="ps-3 mb-0">
                                <li class="mb-2">Jika mahasiswa melapor izin resmi atau sakit dengan surat dokter, Guru Pamong berhak memperbarui status presensi mahasiswa bersangkutan.</li>
                                <li class="mb-2">Pada tabel monitoring harian, pilih baris nama mahasiswa lalu ubah dropdown status presensi ke status yang sesuai (Hadir, Terlambat, Izin, Sakit, atau Alpa).</li>
                                <li class="mb-2">Setiap pembaruan manual akan dicatat oleh sistem dengan stempel nama Guru Pamong yang melakukan koreksi.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- 3. Rekapitulasi Bulanan & Ekspor Excel -->
                <div class="accordion-item card-panduan mb-3 item-panduan" data-keywords="laporan rekapitulasi bulanan ekspor excel unduh cetak tanda tangan">
                    <h2 class="accordion-header" id="headingGuru3">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGuru3" aria-expanded="false" aria-controls="collapseGuru3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Modul 3</span>
                                <span>Rekapitulasi Bulanan &amp; Ekspor Laporan Excel</span>
                            </div>
                        </button>
                    </h2>
                    <div id="collapseGuru3" class="accordion-collapse collapse" aria-labelledby="headingGuru3" data-bs-parent="#accordionPanduan">
                        <div class="accordion-body text-secondary small">
                            <ol class="ps-3 mb-0">
                                <li class="mb-2">Buka menu <strong>Rekapitulasi Bulanan</strong>.</li>
                                <li class="mb-2">Pilih bulan dan tahun laporan yang ingin dievaluasi.</li>
                                <li class="mb-2">Tabel rekapitulasi akan menghitung otomatis total hari Hadir, Terlambat, Izin, Sakit, Alpa, serta persentase kehadiran efektif berdasarkan siklus 5 hari kerja.</li>
                                <li class="mb-2">Tekan tombol hijau <strong>"Ekspor Excel"</strong> untuk mengunduh rekapitulasi resmi lengkap dengan judul instansi, jurusan bimbingan, dan lembar tanda tangan Guru Pamong.</li>
                            </ol>
                        </div>
                    </div>
                </div>

            <?php elseif ($activeRole === 'gtt'): ?>
                <!-- Panduan Guru GTT -->
                <div class="accordion-item card-panduan mb-3 item-panduan" data-keywords="gtt guru tidak tetap presensi mandiri skagata piket kbm">
                    <h2 class="accordion-header" id="headingGtt1">
                        <button class="accordion-button collapsed fw-bold rounded-top" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGtt1" aria-expanded="false" aria-controls="collapseGtt1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Modul 1</span>
                                <span>Alur Presensi Mandiri Guru Tidak Tetap (GTT)</span>
                            </div>
                        </button>
                    </h2>
                    <div id="collapseGtt1" class="accordion-collapse collapse" aria-labelledby="headingGtt1" data-bs-parent="#accordionPanduan">
                        <div class="accordion-body text-secondary small">
                            <p class="mb-2">Guru Tidak Tetap (GTT) merupakan tenaga pendidik honorer internal SMK Negeri 3 Yogyakarta:</p>
                            <ol class="ps-3 mb-0">
                                <li class="mb-2"><strong>Presensi Masuk</strong>: Dilakukan mandiri melalui smartphone di area sekolah (radius <?= esc($config['school_radius']) ?> meter) sebelum pukul <?= esc($config['work_start']) ?> WIB.</li>
                                <li class="mb-2"><strong>Piket KBM</strong>: Mengunggah dokumentasi foto saat menjalankan tugas piket pengawasan kegiatan belajar mengajar di sekolah.</li>
                                <li class="mb-2"><strong>Presensi Pulang</strong>: Dilakukan pada atau setelah pukul <?= esc($config['work_end']) ?> WIB.</li>
                                <li class="mb-2">Akun GTT tidak terikat pada universitas mitra atau konsentrasi keahlian tertentu karena berstatus warga internal SMKN 3 Yogyakarta.</li>
                            </ol>
                        </div>
                    </div>
                </div>

            <?php else: /* Admin */ ?>
                <!-- 1. Konfigurasi Sistem -->
                <div class="accordion-item card-panduan mb-3 item-panduan" data-keywords="pengaturan geofencing radius jam kerja toleransi koordinat skagata sekolah">
                    <h2 class="accordion-header" id="headingAdm1">
                        <button class="accordion-button collapsed fw-bold rounded-top" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdm1" aria-expanded="false" aria-controls="collapseAdm1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Modul 1</span>
                                <span>Pengaturan Geofencing, Jam Kerja, &amp; Toleransi</span>
                            </div>
                        </button>
                    </h2>
                    <div id="collapseAdm1" class="accordion-collapse collapse" aria-labelledby="headingAdm1" data-bs-parent="#accordionPanduan">
                        <div class="accordion-body text-secondary small">
                            <p class="mb-2">Buka menu <strong>Pengaturan Presensi</strong> untuk mengelola parameter operasional:</p>
                            <ul class="ps-3 mb-0">
                                <li class="mb-2"><strong>Radius Presensi</strong>: Menentukan batas jarak maksimal (dalam meter) yang diizinkan untuk presensi GPS.</li>
                                <li class="mb-2"><strong>Mode Geofencing</strong>: Jika dinonaktifkan, presensi dapat dilakukan dalam mode toleransi tanpa pemblokiran radius.</li>
                                <li class="mb-2"><strong>Jam Masuk &amp; Jam Pulang</strong>: Mengatur cut-off waktu keterlambatan dan syarat jam pulang efektif.</li>
                                <li class="mb-2"><strong>Koordinat Sekolah</strong>: Titik lintang dan bujur SMKN 3 Yogyakarta (Latitude: <code><?= esc($config['school_lat']) ?></code>, Longitude: <code><?= esc($config['school_lng']) ?></code>).</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 2. Periode PPL -->
                <div class="accordion-item card-panduan mb-3 item-panduan" data-keywords="master data periode gelombang ppl pk tahun ajaran semester aktif">
                    <h2 class="accordion-header" id="headingAdm2">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdm2" aria-expanded="false" aria-controls="collapseAdm2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Modul 2</span>
                                <span>Manajemen Master Data Periode (Gelombang PPL/PK)</span>
                            </div>
                        </button>
                    </h2>
                    <div id="collapseAdm2" class="accordion-collapse collapse" aria-labelledby="headingAdm2" data-bs-parent="#accordionPanduan">
                        <div class="accordion-body text-secondary small">
                            <ol class="ps-3 mb-0">
                                <li class="mb-2">Buka menu <strong>Periode PPL / PK</strong> untuk membuat gelombang baru setiap semester/tahun ajaran.</li>
                                <li class="mb-2">Hanya boleh ada <strong>1 Periode Aktif</strong> pada satu waktu yang sama. Mengaktifkan periode baru akan otomatis menonaktifkan periode sebelumnya.</li>
                                <li class="mb-2">Disediakan tombol <strong>Nonaktifkan</strong> jika ingin mengosongkan periode aktif sementara di masa libur semester.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- 3. Impor Massal Excel -->
                <div class="accordion-item card-panduan mb-3 item-panduan" data-keywords="impor excel xlsx massal mahasiswa gtt template unduh kolom">
                    <h2 class="accordion-header" id="headingAdm3">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdm3" aria-expanded="false" aria-controls="collapseAdm3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Modul 3</span>
                                <span>Impor Massal Pengguna via File Excel Asli (.xlsx)</span>
                            </div>
                        </button>
                    </h2>
                    <div id="collapseAdm3" class="accordion-collapse collapse" aria-labelledby="headingAdm3" data-bs-parent="#accordionPanduan">
                        <div class="accordion-body text-secondary small">
                            <ol class="ps-3 mb-0">
                                <li class="mb-2">Buka menu <strong>Data Mahasiswa</strong> lalu tekan tombol <strong>"Impor Mahasiswa"</strong>.</li>
                                <li class="mb-2">Unduh template Excel resmi (<code>.xlsx</code>) yang telah diformat kolom terpisah:
                                    <span class="d-block mt-1 font-monospace bg-light p-1.5 rounded">Kolom A: NIM | Kolom B: Nama | Kolom C: Universitas | Kolom D: Jurusan | Kolom E: Password (Opsional)</span>
                                </li>
                                <li class="mb-2">Isi data mahasiswa lalu unggah file <code>.xlsx</code> tersebut. Password boleh dikosongkan (otomatis menggunakan NIM sebagai password awal).</li>
                                <li class="mb-2">Sistem otomatis mendeteksi dan melewati username yang sudah terdaftar guna mencegah data duplikat.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- 4. Pemetaan Guru Pamong -->
                <div class="accordion-item card-panduan mb-3 item-panduan" data-keywords="pemetaan guru pamong jurusan mitra bimbingan multi kampus uny uad">
                    <h2 class="accordion-header" id="headingAdm4">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdm4" aria-expanded="false" aria-controls="collapseAdm4">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Modul 4</span>
                                <span>Pemetaan Guru Pamong Multi-Kampus</span>
                            </div>
                        </button>
                    </h2>
                    <div id="collapseAdm4" class="accordion-collapse collapse" aria-labelledby="headingAdm4" data-bs-parent="#accordionPanduan">
                        <div class="accordion-body text-secondary small">
                            <p class="mb-2">Menu <strong>Pemetaan Pamong</strong> digunakan untuk menugaskan guru pembimbing ke jurusan kampus mitra spesifik:</p>
                            <ol class="ps-3 mb-0">
                                <li class="mb-2">Setiap guru pamong dapat membimbing satu atau beberapa jurusan dari universitas yang berbeda (misal: Elektro UNY dan Elektro UAD).</li>
                                <li class="mb-2">Mahasiswa dari jurusan yang tidak dipetakan tidak akan bisa dilihat oleh guru pamong lain demi menjaga privasi dan akuntabilitas evaluasi.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- Section FAQ & Troubleshooting Kendala Lapangan -->
    <div class="mb-4">
        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-question-circle-fill text-primary"></i>
            <span>Tanya Jawab &amp; Solusi Kendala Teknis (Troubleshooting)</span>
        </h6>

        <div class="row g-3">
            <!-- FAQ 1: GPS -->
            <div class="col-12 col-md-6 item-panduan" data-keywords="gps lokasi error ditolak merah di luar radius jarak akurasi izin chrome safari">
                <div class="card h-100 card-panduan p-3">
                    <div class="d-flex align-items-start gap-2.5">
                        <div class="topic-icon-circle bg-danger-subtle text-danger">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">GPS Berwarna Merah / Di Luar Radius</h6>
                            <p class="text-secondary small mb-2">
                                Mengapa tombol presensi tidak bisa ditekan dan muncul pesan di luar jangkauan?
                            </p>
                            <div class="bg-light p-2 rounded small text-dark" style="font-size: 0.78rem;">
                                <strong>Solusi:</strong>
                                <ol class="ps-3 mb-0 mt-1">
                                    <li>Pastikan GPS HP aktif pada mode <em>Akurasi Tinggi</em>.</li>
                                    <li>Pada browser Chrome, ketuk ikon gembok di sebelah URL &gt; <strong>Izin Situs</strong> &gt; setel <strong>Lokasi ke "Izinkan"</strong>.</li>
                                    <li>Pastikan Anda berada di dalam area sekolah (radius <?= esc($config['school_radius']) ?> meter).</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ 2: Kamera Piket -->
            <div class="col-12 col-md-6 item-panduan" data-keywords="kamera piket hitam tidak bisa dibuka izin webcam permission">
                <div class="card h-100 card-panduan p-3">
                    <div class="d-flex align-items-start gap-2.5">
                        <div class="topic-icon-circle bg-warning-subtle text-warning-emphasis">
                            <i class="bi bi-camera-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Kamera Piket Tidak Mau Terbuka</h6>
                            <p class="text-secondary small mb-2">
                                Layar kamera piket KBM hitam atau muncul peringatan izin kamera ditolak.
                            </p>
                            <div class="bg-light p-2 rounded small text-dark" style="font-size: 0.78rem;">
                                <strong>Solusi:</strong>
                                <ol class="ps-3 mb-0 mt-1">
                                    <li>Buka setelan browser di HP &gt; Cari izin <strong>Kamera</strong> &gt; pilih <strong>Izinkan</strong>.</li>
                                    <li>Tutup aplikasi kamera lain yang mungkin sedang berjalan di latar belakang.</li>
                                    <li>Refresh / muat ulang halaman piket KBM.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ 3: Jam Pulang -->
            <div class="col-12 col-md-6 item-panduan" data-keywords="jam pulang sore tombol pulang merah ditolak jam kerja">
                <div class="card h-100 card-panduan p-3">
                    <div class="d-flex align-items-start gap-2.5">
                        <div class="topic-icon-circle bg-info-subtle text-info-emphasis">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Kapan Tombol Pulang Dapat Ditekan?</h6>
                            <p class="text-secondary small mb-2">
                                Mengapa tombol presensi pulang ditolak saat ditekan di siang hari?
                            </p>
                            <div class="bg-light p-2 rounded small text-dark" style="font-size: 0.78rem;">
                                <strong>Solusi:</strong> Sesuai aturan kerja 5 hari kerja efektif, presensi pulang baru dibuka tepat pada pukul <strong><?= esc($config['work_end']) ?> WIB</strong>.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ 4: Ubah Password -->
            <div class="col-12 col-md-6 item-panduan" data-keywords="password kata sandi lupa ganti ubah profil akun login">
                <div class="card h-100 card-panduan p-3">
                    <div class="d-flex align-items-start gap-2.5">
                        <div class="topic-icon-circle bg-success-subtle text-success">
                            <i class="bi bi-key-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Cara Mengganti Kata Sandi (Password)</h6>
                            <p class="text-secondary small mb-2">
                                Bagaimana cara mengamankan akun dan mengganti password bawaan?
                            </p>
                            <div class="bg-light p-2 rounded small text-dark" style="font-size: 0.78rem;">
                                <strong>Solusi:</strong> Masuk ke menu <strong>Profil &amp; Keamanan</strong>, centang opsi <em>"Ganti Kata Sandi"</em>, masukkan password baru minimal 6 karakter, lalu tekan Simpan.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function filterPanduan() {
        const input = document.getElementById('inputCariPanduan');
        const btnReset = document.getElementById('btnResetPanduan');
        const query = input ? input.value.toLowerCase().trim() : '';

        if (btnReset) {
            btnReset.style.display = (query.length > 0) ? 'inline-block' : 'none';
        }

        const items = document.querySelectorAll('.item-panduan');
        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            const keywords = item.getAttribute('data-keywords') ? item.getAttribute('data-keywords').toLowerCase() : '';

            if (query === '' || text.includes(query) || keywords.includes(query)) {
                item.style.display = '';
                // Jika sedang mencari kata kunci, buka accordion yang cocok otomatis
                if (query !== '' && item.classList.contains('accordion-item')) {
                    const collapseEl = item.querySelector('.accordion-collapse');
                    if (collapseEl && !collapseEl.classList.contains('show')) {
                        const bsCollapse = new bootstrap.Collapse(collapseEl, { toggle: true });
                    }
                }
            } else {
                item.style.display = 'none';
            }
        });
    }

    function resetCariPanduan() {
        const input = document.getElementById('inputCariPanduan');
        if (input) {
            input.value = '';
            filterPanduan();
            input.focus();
        }
    }
</script>
<?= $this->endSection() ?>

