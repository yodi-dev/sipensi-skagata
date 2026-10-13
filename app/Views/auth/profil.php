<?= $this->extend('layout/template') ?>

<?= $this->section('styles') ?>
<style>
    /* ======================================================== */
    /* STYLING HALAMAN PROFIL & KEAMANAN TERPADU SKAGATA        */
    /* ======================================================== */
    .profile-card {
        border: 1px solid #e2e8f0;
        border-radius: 1.1rem;
        background: #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        transition: box-shadow 0.25s ease;
    }

    .profile-header-banner {
        background: linear-gradient(135deg, #0f5132 0%, #10b981 100%);
        padding: 1.6rem 1.25rem 1.35rem 1.25rem;
        color: #ffffff;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    /* Ambient Glow Decoration di Belakang Avatar */
    .profile-header-banner::before {
        content: "";
        position: absolute;
        top: -40%;
        right: -20%;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0) 70%);
        pointer-events: none;
    }

    .avatar-profile-large {
        width: 66px;
        height: 66px;
        border-radius: 50%;
        background-color: #ffffff;
        color: #0f5132;
        font-size: 1.65rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 3.5px solid rgba(255, 255, 255, 0.95);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.18);
        margin-bottom: 0.65rem;
        position: relative;
        z-index: 1;
    }

    @media (min-width: 768px) {
        .profile-header-banner {
            padding: 2.25rem 1.5rem 1.65rem 1.5rem;
        }
        .avatar-profile-large {
            width: 76px;
            height: 76px;
            font-size: 1.95rem;
        }
    }

    .form-section-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 1rem;
    }

    .meta-list-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.65rem 0;
        border-bottom: 1px dashed #e2e8f0;
        font-size: 0.85rem;
    }

    .meta-list-item:last-child {
        border-bottom: none;
    }

    .meta-icon {
        width: 32px;
        height: 32px;
        border-radius: 0.55rem;
        background: #f1f5f9;
        color: #0f5132;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.95rem;
    }

    /* Input & Form Styling Touch-Friendly (Min-Height 46px & 16px font to prevent mobile zoom) */
    .form-control-skagata {
        border-radius: 0.65rem;
        border: 1px solid #cbd5e1;
        padding: 0.65rem 0.95rem;
        font-size: 1rem;
        background-color: #f8fafc;
        transition: all 0.2s ease;
        min-height: 46px;
    }

    .form-control-skagata:focus {
        background-color: #ffffff;
        border-color: #10b981;
        box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.18);
    }

    .form-control-skagata:disabled,
    .form-control-skagata[readonly] {
        background-color: #f1f5f9;
        color: #64748b;
        cursor: not-allowed;
    }

    .btn-toggle-pass {
        border: 1px solid #cbd5e1;
        border-left: none;
        background-color: #f8fafc;
        color: #64748b;
        padding: 0 1rem;
        min-height: 46px;
        min-width: 46px;
        border-top-right-radius: 0.65rem !important;
        border-bottom-right-radius: 0.65rem !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
        -webkit-user-select: none;
    }

    .btn-toggle-pass:hover,
    .btn-toggle-pass:active {
        background-color: #e2e8f0;
        color: #0f5132;
    }

    .requirement-item {
        font-size: 0.78rem;
        padding: 0.35rem 0.6rem;
        border-radius: 0.5rem;
        transition: all 0.25s ease;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .btn-skagata {
        background-color: #0f5132;
        color: #ffffff;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-skagata:hover {
        background-color: #0b3d26;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 81, 50, 0.25);
    }

    .btn-skagata:active {
        transform: translateY(0);
    }

    /* Mobile Quick-Jump Pills */
    .mobile-nav-pill {
        font-size: 0.8rem;
        padding: 0.55rem 0.85rem;
        border-radius: 2rem;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #334155;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        font-weight: 500;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }

    .mobile-nav-pill:active,
    .mobile-nav-pill:hover {
        background: #f1f5f9;
        color: #0f5132;
        border-color: #cbd5e1;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Safe bottom clearance khusus mobile agar tidak terhalang bottom navigation bar -->
<div class="container-fluid px-3 px-sm-4 py-3 py-md-4 pb-5 mb-5 mb-lg-4">

    <!-- Breadcrumb & Header Halaman -->
    <div class="mb-3 mb-md-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small flex-nowrap overflow-x-auto py-1">
                <?php
                $dashboardUrl = ($role === 'admin') ? 'admin' : (($role === 'guru') ? 'guru' : 'mahasiswa');
                ?>
                <li class="breadcrumb-item"><a href="<?= base_url($dashboardUrl) ?>" class="text-success text-decoration-none text-nowrap"><i class="bi bi-grid-1x2 me-1"></i>Beranda</a></li>
                <li class="breadcrumb-item d-none d-sm-inline text-muted text-nowrap">Pengaturan Akun</li>
                <li class="breadcrumb-item active text-muted text-nowrap" aria-current="page">Profil &amp; Keamanan</li>
            </ol>
        </nav>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-1 gap-md-2">
            <div>
                <h4 class="fw-bold text-dark mb-0 fs-5 fs-md-4 d-flex align-items-center gap-2">
                    <i class="bi bi-person-bounding-box text-success"></i>
                    <span>Profil &amp; Keamanan Akun</span>
                </h4>
                <p class="text-muted small mt-1 mb-0" style="font-size: 0.82rem; line-height: 1.45;">
                    Kelola informasi identitas akun resmi dan perlindungan kata sandi Anda di SIPENSI SKAGATA
                </p>
            </div>
        </div>
    </div>

    <!-- Shortcut Quick-Jump Khusus Tampilan Ponsel (Layar < 992px) -->
    <div class="d-flex d-lg-none gap-2 mb-3">
        <a href="#seksiIdentitas" class="mobile-nav-pill flex-fill">
            <i class="bi bi-person-lines-fill text-success"></i>
            <span>Data Identitas</span>
        </a>
        <a href="#seksiKeamanan" class="mobile-nav-pill flex-fill">
            <i class="bi bi-shield-lock-fill text-success"></i>
            <span>Ganti Password</span>
        </a>
    </div>

    <?php
    $roleLabels = [
        'admin'       => 'Administrator Sistem',
        'guru'        => 'Guru Pamong / GTT',
        'guru_pamong' => 'Guru Pamong',
        'gtt'         => 'Guru Pamong / GTT',
        'mahasiswa'   => 'Mahasiswa Praktikan (PPL/PK)'
    ];
    $roleName = $roleLabels[$user['role']] ?? ucfirst($user['role']);
    ?>

    <div class="row g-3 g-md-4">
        <!-- ============================================== -->
        <!-- 1. KOLOM KIRI: IDENTITY OVERVIEW CARD          -->
        <!-- ============================================== -->
        <div class="col-12 col-lg-4 col-xl-4">
            <div class="profile-card mb-3 mb-lg-4">
                <!-- Header Card Banner dengan Avatar Inisial & Identitas Akun -->
                <div class="profile-header-banner">
                    <div class="avatar-profile-large">
                        <?= esc(mb_strtoupper(mb_substr($user['nama'], 0, 1))) ?>
                    </div>
                    <h5 class="fw-bold text-white mb-1 fs-6 fs-md-5 text-truncate" style="max-width: 100%;"><?= esc($user['nama']) ?></h5>
                    <span class="badge bg-white bg-opacity-25 rounded-pill px-3 py-1 text-white border border-white border-opacity-25 mt-0.5" style="font-size: 0.72rem; letter-spacing: 0.2px;">
                        <?= esc($roleName) ?>
                    </span>
                    <div class="small text-white-50 mt-1 font-monospace" style="font-size: 0.8rem;">
                        @<?= esc($user['username']) ?>
                    </div>
                </div>

                <!-- Detail Informasi Kelembagaan -->
                <div class="p-3 p-sm-4">
                    <h6 class="fw-bold text-dark small mb-2.5 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle-fill text-success"></i>
                        <span>Informasi Kelembagaan</span>
                    </h6>

                    <div class="meta-list-group">
                        <!-- Role Akun -->
                        <div class="meta-list-item">
                            <div class="meta-icon bg-success-subtle text-success">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="text-muted d-block small" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600;">Hak Akses / Peran</span>
                                <strong class="text-dark" style="font-size: 0.88rem;"><?= esc($roleName) ?></strong>
                            </div>
                        </div>

                        <?php if ($user['role'] === 'mahasiswa'): ?>
                            <!-- Asal Universitas Mitra Mahasiswa -->
                            <div class="meta-list-item">
                                <div class="meta-icon bg-primary-subtle text-primary">
                                    <i class="bi bi-buildings"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-muted d-block small" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600;">Asal Kampus Mitra</span>
                                    <strong class="text-dark" style="font-size: 0.88rem;"><?= esc($user['universitas'] ?? 'Belum Ditentukan') ?></strong>
                                </div>
                            </div>

                            <!-- Program Studi Mahasiswa -->
                            <div class="meta-list-item">
                                <div class="meta-icon bg-info-subtle text-info">
                                    <i class="bi bi-mortarboard"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-muted d-block small" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600;">Jurusan / Program Studi</span>
                                    <strong class="text-dark" style="font-size: 0.88rem;"><?= esc($user['jurusan'] ?? 'Belum Ditentukan') ?></strong>
                                </div>
                            </div>

                            <!-- Periode Gelombang PPL Mahasiswa -->
                            <div class="meta-list-item">
                                <div class="meta-icon bg-warning-subtle text-warning">
                                    <i class="bi bi-calendar-range"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-muted d-block small" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600;">Periode Gelombang PPL</span>
                                    <strong class="text-dark" style="font-size: 0.88rem;">
                                        <?= !empty($periode) ? esc($periode['nama_periode']) : 'Reguler 2026' ?>
                                    </strong>
                                </div>
                            </div>

                        <?php elseif ($user['role'] === 'guru'): ?>
                            <!-- Jurusan Bimbingan Guru Pamong -->
                            <div class="meta-list-item">
                                <div class="meta-icon bg-success-subtle text-success">
                                    <i class="bi bi-journal-check"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-muted d-block small mb-1" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600;">Jurusan Binaan &amp; Kampus Mitra</span>
                                    <?php if (!empty($assignments)): ?>
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php foreach ($assignments as $a): ?>
                                                <span class="badge bg-light text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                                    <i class="bi bi-shield-check me-1"></i><?= esc($a['display_label'] ?? $a['nama_jurusan']) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php elseif (!empty($user['jurusan'])): ?>
                                        <span class="badge bg-light text-success border rounded-pill"><?= esc($user['jurusan']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic">Belum dipetakan oleh admin</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Total Praktikan Terbimbing -->
                            <div class="meta-list-item">
                                <div class="meta-icon bg-primary-subtle text-primary">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-muted d-block small" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600;">Mahasiswa Terbimbing</span>
                                    <strong class="text-dark" style="font-size: 0.88rem;"><?= (int) ($total_mahasiswa ?? 0) ?> Praktikan Aktif</strong>
                                </div>
                            </div>

                        <?php else: /* Administrator */ ?>
                            <div class="meta-list-item">
                                <div class="meta-icon bg-danger-subtle text-danger">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-muted d-block small" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600;">Tingkat Otoritas</span>
                                    <strong class="text-dark" style="font-size: 0.88rem;">Super Administrator</strong>
                                </div>
                            </div>
                            <div class="meta-list-item">
                                <div class="meta-icon bg-success-subtle text-success">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-muted d-block small" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600;">Satuan Pendidikan</span>
                                    <strong class="text-dark" style="font-size: 0.88rem;">SMK Negeri 3 Yogyakarta</strong>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- 2. KOLOM KANAN: FORMULIR PROFIL & KATA SANDI   -->
        <!-- ============================================== -->
        <div class="col-12 col-lg-8 col-xl-8">
            <div class="profile-card p-3 p-sm-4">
                <form action="<?= base_url('profil/simpan') ?>" method="POST" id="formProfilTerpadu">
                    <?= csrf_field() ?>

                    <!-- SEKSI 1: DATA IDENTITAS AKUN -->
                    <div class="form-section-title" id="seksiIdentitas">
                        <i class="bi bi-person-lines-fill text-success fs-5"></i>
                        <span>Informasi Identitas Diri</span>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label for="inputNamaProfil" class="form-label fw-semibold small text-dark mb-1">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama" id="inputNamaProfil" class="form-control form-control-skagata" value="<?= esc($user['nama']) ?>" required autocomplete="name" placeholder="Masukkan nama lengkap">
                            <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Nama resmi beserta gelar akademik jika ada.</small>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="inputUsernameProfil" class="form-label fw-semibold small text-dark mb-1">
                                Username / Akun
                                <?php if ($role !== 'admin'): ?>
                                    <span class="badge bg-light text-muted border ms-1" style="font-size: 0.68rem;">Terkunci</span>
                                <?php endif; ?>
                            </label>
                            <?php if ($role === 'admin'): ?>
                                <input type="text" name="username" id="inputUsernameProfil" class="form-control form-control-skagata" value="<?= esc($user['username']) ?>" required autocomplete="username" placeholder="Username akun">
                                <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Administrator dapat memperbarui username akun.</small>
                            <?php else: ?>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-skagata border-end-0" value="<?= esc($user['username']) ?>" readonly disabled>
                                    <span class="input-group-text bg-light border-start-0 text-muted" style="border-radius: 0 0.65rem 0.65rem 0;">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Username dikelola permanen oleh Administrator demi integritas data.</small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- SEKSI 2: PENGATURAN KATA SANDI -->
                    <div class="form-section-title mt-2" id="seksiKeamanan">
                        <i class="bi bi-shield-lock-fill text-success fs-5"></i>
                        <span>Perlindungan Kata Sandi (Opsional)</span>
                    </div>

                    <div class="alert alert-light border rounded-3 p-3 mb-3 d-flex align-items-start gap-2.5">
                        <i class="bi bi-info-circle-fill text-success fs-5 flex-shrink-0 mt-0.5"></i>
                        <div class="small text-muted lh-sm">
                            Biarkan seluruh kolom kata sandi di bawah ini <strong>kosong</strong> jika Anda tidak bermaksud mengganti password akun Anda saat ini.
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <label for="inputPassLama" class="form-label fw-semibold small text-dark mb-1">Password Saat Ini</label>
                            <div class="input-group">
                                <input type="password" name="password_lama" id="inputPassLama" class="form-control form-control-skagata border-end-0" placeholder="Masukkan password saat ini untuk verifikasi" autocomplete="current-password">
                                <button class="btn-toggle-pass" type="button" onclick="togglePassVisibility('inputPassLama', 'iconPassLama')" aria-label="Lihat password saat ini">
                                    <i class="bi bi-eye" id="iconPassLama"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="inputPassBaru" class="form-label fw-semibold small text-dark mb-1">Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="password_baru" id="inputPassBaru" class="form-control form-control-skagata border-end-0" placeholder="Minimal 6 karakter" autocomplete="new-password">
                                <button class="btn-toggle-pass" type="button" onclick="togglePassVisibility('inputPassBaru', 'iconPassBaru')" aria-label="Lihat password baru">
                                    <i class="bi bi-eye" id="iconPassBaru"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="inputPassKonfirmasi" class="form-label fw-semibold small text-dark mb-1">Ulangi Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="konfirmasi_password" id="inputPassKonfirmasi" class="form-control form-control-skagata border-end-0" placeholder="Ketik ulang password baru" autocomplete="new-password">
                                <button class="btn-toggle-pass" type="button" onclick="togglePassVisibility('inputPassKonfirmasi', 'iconPassKonfirmasi')" aria-label="Lihat konfirmasi password">
                                    <i class="bi bi-eye" id="iconPassKonfirmasi"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Indikator Live Kriteria Password (Responsive Grid) -->
                    <div class="row g-2 mb-4">
                        <div class="col-12 col-sm-6">
                            <div class="requirement-item d-flex align-items-center gap-1.5 text-muted w-100" id="reqPanjang">
                                <i class="bi bi-circle fs-6 flex-shrink-0" id="iconReqPanjang"></i>
                                <span style="font-size: 0.78rem;">Minimal 6 karakter</span>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="requirement-item d-flex align-items-center gap-1.5 text-muted w-100" id="reqMatch">
                                <i class="bi bi-circle fs-6 flex-shrink-0" id="iconReqMatch"></i>
                                <span id="textReqMatch" style="font-size: 0.78rem;">Ketik ulang untuk konfirmasi</span>
                            </div>
                        </div>
                    </div>

                    <!-- TOMBOL AKSI SUBMIT (Mobile Stack Thumb-Friendly, Desktop Row) -->
                    <div class="d-flex flex-column-reverse flex-sm-row justify-content-end align-items-stretch align-items-sm-center gap-2 pt-3 border-top mt-3">
                        <a href="<?= base_url($dashboardUrl) ?>" class="btn btn-light border rounded-pill px-4 py-2.5 text-center text-secondary fw-medium">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-skagata rounded-pill px-4 py-2.5 d-inline-flex align-items-center justify-content-center gap-2 shadow-sm fw-semibold" id="btnSubmitProfil">
                            <i class="bi bi-check2-circle fs-5"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function togglePassVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!input || !icon) return;
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

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formProfilTerpadu');
        const passLama = document.getElementById('inputPassLama');
        const passBaru = document.getElementById('inputPassBaru');
        const passKonfirmasi = document.getElementById('inputPassKonfirmasi');
        const btnSubmit = document.getElementById('btnSubmitProfil');

        const reqPanjang = document.getElementById('reqPanjang');
        const iconReqPanjang = document.getElementById('iconReqPanjang');
        const reqMatch = document.getElementById('reqMatch');
        const iconReqMatch = document.getElementById('iconReqMatch');
        const textReqMatch = document.getElementById('textReqMatch');

        function validasiLivePassword() {
            const valBaru = passBaru.value;
            const valKonfirm = passKonfirmasi.value;

            // Jika form password sama sekali tidak diisi
            if (!valBaru && !valKonfirm) {
                reqPanjang.className = 'requirement-item d-flex align-items-center gap-1.5 text-muted w-100';
                iconReqPanjang.className = 'bi bi-circle fs-6 flex-shrink-0';
                reqMatch.className = 'requirement-item d-flex align-items-center gap-1.5 text-muted w-100';
                iconReqMatch.className = 'bi bi-circle fs-6 flex-shrink-0';
                textReqMatch.textContent = 'Ketik ulang untuk konfirmasi';
                return;
            }

            // Validasi Panjang Minimal 6 Karakter
            if (valBaru.length >= 6) {
                reqPanjang.className = 'requirement-item d-flex align-items-center gap-1.5 text-success fw-medium w-100';
                iconReqPanjang.className = 'bi bi-check-circle-fill fs-6 text-success flex-shrink-0';
            } else {
                reqPanjang.className = 'requirement-item d-flex align-items-center gap-1.5 text-danger w-100';
                iconReqPanjang.className = 'bi bi-x-circle-fill fs-6 text-danger flex-shrink-0';
            }

            // Validasi Kecocokan Konfirmasi
            if (!valKonfirm) {
                reqMatch.className = 'requirement-item d-flex align-items-center gap-1.5 text-muted w-100';
                iconReqMatch.className = 'bi bi-circle fs-6 flex-shrink-0';
                textReqMatch.textContent = 'Ketik ulang untuk konfirmasi';
            } else if (valBaru === valKonfirm && valBaru.length >= 6) {
                reqMatch.className = 'requirement-item d-flex align-items-center gap-1.5 text-success fw-medium w-100';
                iconReqMatch.className = 'bi bi-check-circle-fill fs-6 text-success flex-shrink-0';
                textReqMatch.textContent = 'Password baru cocok';
            } else {
                reqMatch.className = 'requirement-item d-flex align-items-center gap-1.5 text-danger w-100';
                iconReqMatch.className = 'bi bi-x-circle-fill fs-6 text-danger flex-shrink-0';
                textReqMatch.textContent = 'Password tidak cocok';
            }
        }

        if (passBaru && passKonfirmasi) {
            passBaru.addEventListener('input', validasiLivePassword);
            passKonfirmasi.addEventListener('input', validasiLivePassword);
        }

        if (form && btnSubmit) {
            form.addEventListener('submit', function(e) {
                const valBaru = passBaru.value;
                const valKonfirm = passKonfirmasi.value;
                const valLama = passLama.value;

                // Jika mengisi salah satu field password
                if (valBaru || valKonfirm || valLama) {
                    if (!valLama) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Password Saat Ini Wajib Diisi',
                            text: 'Masukkan password saat ini untuk memverifikasi perubahan kata sandi.',
                            confirmButtonColor: '#0f5132'
                        });
                        passLama.focus();
                        return;
                    }
                    if (valBaru.length < 6) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Password Terlalu Pendek',
                            text: 'Password baru harus memiliki panjang minimal 6 karakter.',
                            confirmButtonColor: '#0f5132'
                        });
                        passBaru.focus();
                        return;
                    }
                    if (valBaru !== valKonfirm) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Konfirmasi Sandi Berbeda',
                            text: 'Konfirmasi password tidak cocok dengan password baru.',
                            confirmButtonColor: '#0f5132'
                        });
                        passKonfirmasi.focus();
                        return;
                    }
                }

                // Loading feedback saat submit valid
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan Perubahan...';
            });
        }
    });
</script>
<?= $this->endSection() ?>
