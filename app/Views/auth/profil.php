<?= $this->extend('layout/template') ?>

<?= $this->section('styles') ?>
<style>
    /* Styling Halaman Profil & Keamanan Terpadu Skagata */
    .profile-card {
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        background: #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .profile-header-banner {
        background: linear-gradient(135deg, #0f5132 0%, #10b981 100%);
        padding: 2rem 1.5rem 1.5rem 1.5rem;
        color: #ffffff;
        text-align: center;
        position: relative;
    }

    .avatar-profile-large {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background-color: #ffffff;
        color: #0f5132;
        font-size: 1.85rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 3px solid rgba(255, 255, 255, 0.85);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
        margin-bottom: 0.75rem;
    }

    .form-section-title {
        font-size: 0.95rem;
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
        width: 28px;
        height: 28px;
        border-radius: 0.5rem;
        background: #f1f5f9;
        color: #0f5132;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.9rem;
    }

    .requirement-item {
        font-size: 0.78rem;
        transition: all 0.2s ease;
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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-3 px-md-4 py-4">

    <!-- Breadcrumb & Page Title -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <?php
                $dashboardUrl = ($role === 'admin') ? 'admin' : (($role === 'guru') ? 'guru' : 'mahasiswa');
                ?>
                <li class="breadcrumb-item"><a href="<?= base_url($dashboardUrl) ?>" class="text-success text-decoration-none"><i class="bi bi-grid-1x2 me-1"></i>Beranda</a></li>
                <li class="breadcrumb-item text-muted">Pengaturan Akun</li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Profil &amp; Keamanan</li>
            </ol>
        </nav>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <div>
                <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-person-bounding-box text-success"></i>
                    <span>Profil &amp; Keamanan Akun</span>
                </h4>
                <p class="text-muted small mt-1 mb-0">Kelola informasi identitas akun resmi dan perlindungan kata sandi Anda di SIPENSI SKAGATA</p>
            </div>
        </div>
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

    <div class="row g-4">
        <!-- ============================================== -->
        <!-- 1. KOLOM KIRI: IDENTITY OVERVIEW CARD          -->
        <!-- ============================================== -->
        <div class="col-12 col-lg-4 col-xl-4">
            <div class="profile-card mb-4">
                <div class="profile-header-banner">
                    <div class="avatar-profile-large">
                        <?= esc(mb_strtoupper(mb_substr($user['nama'], 0, 1))) ?>
                    </div>
                    <h5 class="fw-bold text-white mb-1"><?= esc($user['nama']) ?></h5>
                    <span class="badge bg-white bg-opacity-25 rounded-pill px-3 py-1 text-white border border-white border-opacity-25" style="font-size: 0.75rem;">
                        <?= esc($roleName) ?>
                    </span>
                    <div class="small text-white-50 mt-1 font-monospace">@<?= esc($user['username']) ?></div>
                </div>

                <div class="p-3.5 p-md-4">
                    <h6 class="fw-bold text-dark small mb-2 d-flex align-items-center gap-2">
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
                                <span class="text-muted d-block small" style="font-size: 0.75rem;">Hak Akses / Peran</span>
                                <strong class="text-dark"><?= esc($roleName) ?></strong>
                            </div>
                        </div>

                        <?php if ($user['role'] === 'mahasiswa'): ?>
                            <!-- Universitas Mitra Mahasiswa -->
                            <div class="meta-list-item">
                                <div class="meta-icon bg-primary-subtle text-primary">
                                    <i class="bi bi-buildings"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-muted d-block small" style="font-size: 0.75rem;">Asal Kampus Mitra</span>
                                    <strong class="text-dark"><?= esc($user['universitas'] ?? 'Belum Ditentukan') ?></strong>
                                </div>
                            </div>

                            <!-- Program Studi Mahasiswa -->
                            <div class="meta-list-item">
                                <div class="meta-icon bg-info-subtle text-info">
                                    <i class="bi bi-mortarboard"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-muted d-block small" style="font-size: 0.75rem;">Jurusan / Program Studi</span>
                                    <strong class="text-dark"><?= esc($user['jurusan'] ?? 'Belum Ditentukan') ?></strong>
                                </div>
                            </div>

                            <!-- Periode PPL Mahasiswa -->
                            <div class="meta-list-item">
                                <div class="meta-icon bg-warning-subtle text-warning">
                                    <i class="bi bi-calendar-range"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-muted d-block small" style="font-size: 0.75rem;">Periode Gelombang PPL</span>
                                    <strong class="text-dark">
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
                                    <span class="text-muted d-block small mb-1" style="font-size: 0.75rem;">Jurusan Binaan &amp; Kampus Mitra</span>
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
                                    <span class="text-muted d-block small" style="font-size: 0.75rem;">Mahasiswa Terbimbing</span>
                                    <strong class="text-dark"><?= (int) ($total_mahasiswa ?? 0) ?> Praktikan Aktif</strong>
                                </div>
                            </div>

                        <?php else: /* Administrator */ ?>
                            <div class="meta-list-item">
                                <div class="meta-icon bg-danger-subtle text-danger">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-muted d-block small" style="font-size: 0.75rem;">Tingkat Otoritas</span>
                                    <strong class="text-dark">Super Administrator</strong>
                                </div>
                            </div>
                            <div class="meta-list-item">
                                <div class="meta-icon bg-success-subtle text-success">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-muted d-block small" style="font-size: 0.75rem;">Satuan Pendidikan</span>
                                    <strong class="text-dark">SMK Negeri 3 Yogyakarta</strong>
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
            <div class="profile-card p-4 p-md-4">
                <form action="<?= base_url('profil/simpan') ?>" method="POST" id="formProfilTerpadu">
                    <?= csrf_field() ?>

                    <!-- SEKSI 1: DATA IDENTITAS AKUN -->
                    <div class="form-section-title">
                        <i class="bi bi-person-lines-fill text-success"></i>
                        <span>Informasi Identitas Diri</span>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label for="inputNamaProfil" class="form-label fw-semibold small text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="inputNamaProfil" class="form-control" value="<?= esc($user['nama']) ?>" required autocomplete="name">
                            <small class="text-muted" style="font-size: 0.72rem;">Nama resmi beserta gelar akademik jika ada.</small>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="inputUsernameProfil" class="form-label fw-semibold small text-dark">
                                Username / Akun
                                <?php if ($role !== 'admin'): ?>
                                    <span class="badge bg-light text-muted border ms-1" style="font-size: 0.68rem;">Terkunci</span>
                                <?php endif; ?>
                            </label>
                            <?php if ($role === 'admin'): ?>
                                <input type="text" name="username" id="inputUsernameProfil" class="form-control" value="<?= esc($user['username']) ?>" required autocomplete="username">
                                <small class="text-muted" style="font-size: 0.72rem;">Administrator dapat memperbarui username akun.</small>
                            <?php else: ?>
                                <input type="text" class="form-control bg-light text-muted" value="<?= esc($user['username']) ?>" readonly disabled>
                                <small class="text-muted" style="font-size: 0.72rem;">Username dikelola permanen oleh Administrator demi integritas data.</small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- SEKSI 2: PENGATURAN KATA SANDI -->
                    <div class="form-section-title mt-4">
                        <i class="bi bi-shield-lock-fill text-success"></i>
                        <span>Perlindungan Kata Sandi (Opsional)</span>
                    </div>

                    <div class="alert alert-light border rounded-3 p-3 mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle text-success fs-5 flex-shrink-0"></i>
                        <span class="small text-muted">
                            Biarkan seluruh kolom kata sandi di bawah ini <strong>kosong</strong> jika Anda tidak bermaksud mengganti password akun Anda saat ini.
                        </span>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <label for="inputPassLama" class="form-label fw-semibold small text-dark">Password Saat Ini</label>
                            <div class="input-group">
                                <input type="password" name="password_lama" id="inputPassLama" class="form-control border-end-0" placeholder="Masukkan password saat ini untuk verifikasi" autocomplete="current-password">
                                <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" onclick="togglePassVisibility('inputPassLama', 'iconPassLama')">
                                    <i class="bi bi-eye" id="iconPassLama"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="inputPassBaru" class="form-label fw-semibold small text-dark">Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="password_baru" id="inputPassBaru" class="form-control border-end-0" placeholder="Minimal 6 karakter" autocomplete="new-password">
                                <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" onclick="togglePassVisibility('inputPassBaru', 'iconPassBaru')">
                                    <i class="bi bi-eye" id="iconPassBaru"></i>
                                </button>
                            </div>
                            <!-- Syarat Panjang Password -->
                            <div class="mt-1 d-flex align-items-center gap-1.5 requirement-item text-muted" id="reqPanjang">
                                <i class="bi bi-circle fs-6" id="iconReqPanjang"></i>
                                <span>Minimal 6 karakter</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="inputPassKonfirmasi" class="form-label fw-semibold small text-dark">Ulangi Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="konfirmasi_password" id="inputPassKonfirmasi" class="form-control border-end-0" placeholder="Ketik ulang password baru" autocomplete="new-password">
                                <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" onclick="togglePassVisibility('inputPassKonfirmasi', 'iconPassKonfirmasi')">
                                    <i class="bi bi-eye" id="iconPassKonfirmasi"></i>
                                </button>
                            </div>
                            <!-- Feedback Kecocokan Sandi -->
                            <div class="mt-1 d-flex align-items-center gap-1.5 requirement-item text-muted" id="reqMatch">
                                <i class="bi bi-circle fs-6" id="iconReqMatch"></i>
                                <span id="textReqMatch">Ketik ulang untuk konfirmasi</span>
                            </div>
                        </div>
                    </div>

                    <!-- TOMBOL AKSI SUBMIT -->
                    <div class="d-flex justify-content-end align-items-center gap-2 pt-3 border-top mt-4">
                        <a href="<?= base_url($dashboardUrl) ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-2">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-sm btn-skagata rounded-pill px-4 py-2 d-inline-flex align-items-center gap-2 shadow-sm" id="btnSubmitProfil">
                            <i class="bi bi-check2-circle"></i>
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
                reqPanjang.className = 'mt-1 d-flex align-items-center gap-1.5 requirement-item text-muted';
                iconReqPanjang.className = 'bi bi-circle fs-6';
                reqMatch.className = 'mt-1 d-flex align-items-center gap-1.5 requirement-item text-muted';
                iconReqMatch.className = 'bi bi-circle fs-6';
                textReqMatch.textContent = 'Ketik ulang untuk konfirmasi';
                return;
            }

            // Validasi Panjang Minimal 6
            if (valBaru.length >= 6) {
                reqPanjang.className = 'mt-1 d-flex align-items-center gap-1.5 requirement-item text-success fw-medium';
                iconReqPanjang.className = 'bi bi-check-circle-fill fs-6 text-success';
            } else {
                reqPanjang.className = 'mt-1 d-flex align-items-center gap-1.5 requirement-item text-danger';
                iconReqPanjang.className = 'bi bi-x-circle-fill fs-6 text-danger';
            }

            // Validasi Kecocokan Konfirmasi
            if (!valKonfirm) {
                reqMatch.className = 'mt-1 d-flex align-items-center gap-1.5 requirement-item text-muted';
                iconReqMatch.className = 'bi bi-circle fs-6';
                textReqMatch.textContent = 'Ketik ulang untuk konfirmasi';
            } else if (valBaru === valKonfirm && valBaru.length >= 6) {
                reqMatch.className = 'mt-1 d-flex align-items-center gap-1.5 requirement-item text-success fw-medium';
                iconReqMatch.className = 'bi bi-check-circle-fill fs-6 text-success';
                textReqMatch.textContent = 'Password baru cocok';
            } else {
                reqMatch.className = 'mt-1 d-flex align-items-center gap-1.5 requirement-item text-danger';
                iconReqMatch.className = 'bi bi-x-circle-fill fs-6 text-danger';
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
