<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Presensi PPL' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="<?= base_url('favicon.png'); ?>">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --skagata-primary: #0f5132;
            --skagata-primary-hover: #0a3622;
            --skagata-mint: #10b981;
            --skagata-bg: #f8fafc;
            --skagata-text: #0f172a;
            --skagata-muted: #64748b;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--skagata-bg);
            color: var(--skagata-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Navbar (Guru & Mahasiswa) */
        .navbar-skagata {
            background-color: var(--skagata-primary);
            box-shadow: 0 4px 20px rgba(15, 81, 50, 0.15);
        }

        .navbar-skagata .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #ffffff;
        }

        .navbar-skagata .nav-link {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            font-size: 0.95rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .navbar-skagata .nav-link:hover,
        .navbar-skagata .nav-link.active {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.15);
        }

        .badge-role {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 0.35rem 0.65rem;
            border-radius: 2rem;
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .main-content {
            flex: 1 0 auto;
        }

        .footer-skagata {
            background-color: #ffffff;
            border-top: 1px solid #e2e8f0;
            color: var(--skagata-muted);
            font-size: 0.875rem;
            margin-top: auto;
        }

        .btn-skagata {
            background-color: var(--skagata-primary);
            color: #ffffff;
            border: none;
        }

        .btn-skagata:hover {
            background-color: var(--skagata-primary-hover);
            color: #ffffff;
        }

        /* ======================================= */
        /* DEDICATED ADMIN SIDEBAR LAYOUT STYLES   */
        /* ======================================= */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
            background-color: var(--skagata-bg);
        }

        .admin-sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: var(--skagata-primary);
            color: #ffffff;
            flex-shrink: 0;
            transition: margin-left 0.3s ease, left 0.3s ease;
            display: flex;
            flex-direction: column;
            z-index: 1040;
        }

        .admin-sidebar .sidebar-brand {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: #ffffff;
        }

        .admin-sidebar .sidebar-menu {
            padding: 1rem 0;
            flex: 1 1 auto;
            overflow-y: auto;
        }

        .sidebar-section-label {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.45);
            padding: 0.85rem 1.5rem 0.35rem 1.5rem;
        }

        .sidebar-nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1.5rem;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-nav-item:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.08);
        }

        .sidebar-nav-item.active {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.15);
            border-left-color: var(--skagata-mint);
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .admin-main-panel {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background-color: var(--skagata-bg);
        }

        .admin-topbar {
            height: 64px;
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }

        /* Responsive Sidebar */
        @media (min-width: 992px) {
            .admin-wrapper.collapsed .admin-sidebar {
                margin-left: -260px;
            }
        }

        @media (max-width: 991.98px) {
            .admin-sidebar {
                position: fixed;
                left: -260px;
                top: 0;
                bottom: 0;
            }

            .admin-sidebar.show {
                left: 0;
                box-shadow: 0 0 35px rgba(0, 0, 0, 0.4);
            }

            .sidebar-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1030;
                display: none;
            }

            .sidebar-backdrop.show {
                display: block;
            }
        }
    </style>

    <?= $this->renderSection('styles'); ?>

</head>

<body>

    <?php if (session()->get('isLoggedIn') || session()->get('logged_in')): ?>
        <?php
        $role = session()->get('role');
        $namaUser = session()->get('nama') ?? 'Pengguna';
        $currentUri = service('uri')->getPath();

        $roleLabels = [
            'admin'       => 'Administrator',
            'guru'        => 'Guru Pamong',
            'guru_pamong' => 'Guru Pamong',
            'gtt'         => 'Guru Tidak Tetap',
            'mahasiswa'   => 'Mahasiswa Praktikan'
        ];
        $roleLabel = $roleLabels[$role] ?? ucfirst($role ?? '');
        ?>

        <?php if ($role === 'admin'): ?>
            <!-- ============================================== -->
            <!-- 1. DEDICATED ADMIN WORKSPACE (SIDEBAR LAYOUT)  -->
            <!-- ============================================== -->
            <div class="admin-wrapper" id="adminWrapper">
                <!-- Mobile Backdrop -->
                <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleAdminSidebar()"></div>

                <!-- Admin Sidebar -->
                <aside class="admin-sidebar" id="adminSidebar">
                    <a href="<?= base_url('admin') ?>" class="sidebar-brand">
                        <img src="<?= base_url('logo-skagata.png') ?>" alt="Logo Skagata" style="width: 34px; height: 34px; object-fit: contain;">
                        <div class="lh-sm">
                            <span class="d-block fw-bold tracking-wide" style="font-size: 0.95rem;">SIPENSI SKAGATA</span>
                            <small class="d-block text-white-50" style="font-size: 0.68rem;">SMK Negeri 3 Yogyakarta</small>
                        </div>
                    </a>

                    <div class="sidebar-menu">
                        <div class="sidebar-section-label">Menu Utama</div>
                        <a href="<?= base_url('admin') ?>" class="sidebar-nav-item <?= (strpos($currentUri, 'admin/pengaturan') === false && strpos($currentUri, 'admin/pengguna') === false && strpos($currentUri, 'admin') !== false) ? 'active' : '' ?>">
                            <i class="bi bi-grid-1x2-fill"></i>
                            <span>Beranda Admin</span>
                        </a>

                        <div class="sidebar-section-label">Manajemen Pengguna</div>
                        <a href="<?= base_url('admin/pengguna') ?>" class="sidebar-nav-item <?= (strpos($currentUri, 'admin/pengguna') !== false) ? 'active' : '' ?>">
                            <i class="bi bi-people-fill"></i>
                            <span>Data Pengguna</span>
                        </a>

                        <div class="sidebar-section-label">Master Data</div>
                        <a href="<?= base_url('admin') ?>" class="sidebar-nav-item d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-journal-bookmark-fill"></i>
                                <span>Data Jurusan</span>
                            </div>
                            <span class="badge bg-white bg-opacity-25 rounded-pill" style="font-size: 0.65rem;">Segera</span>
                        </a>
                        <a href="<?= base_url('admin') ?>" class="sidebar-nav-item d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-buildings-fill"></i>
                                <span>Asal Universitas</span>
                            </div>
                            <span class="badge bg-white bg-opacity-25 rounded-pill" style="font-size: 0.65rem;">Segera</span>
                        </a>

                        <div class="sidebar-section-label">Sistem &amp; Pengaturan</div>
                        <a href="<?= base_url('admin/pengaturan') ?>" class="sidebar-nav-item <?= (strpos($currentUri, 'admin/pengaturan') !== false) ? 'active' : '' ?>">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>Pengaturan Presensi</span>
                        </a>
                    </div>

                    <div class="sidebar-footer">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-white-50">SIPENSI v2.0</span>
                            <span class="badge bg-white bg-opacity-25 rounded-pill">Skagata</span>
                        </div>
                    </div>
                </aside>

                <!-- Admin Main Panel -->
                <div class="admin-main-panel">
                    <!-- Admin Topbar -->
                    <header class="admin-topbar">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-light border text-success rounded-circle p-2 d-flex align-items-center justify-content-center" onclick="toggleAdminSidebar()" title="Toggle Sidebar" style="width: 36px; height: 36px;">
                                <i class="bi bi-list fs-5"></i>
                            </button>
                            <span class="fw-semibold text-dark ms-2 d-none d-sm-inline" style="font-size: 0.95rem;">
                                Panel Administrator SIPENSI
                            </span>
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <span class="badge badge-role d-none d-md-inline-block bg-success text-white border-0">
                                Administrator
                            </span>

                            <!-- Dropdown Akun Admin -->
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 dropdown-toggle d-flex align-items-center gap-2" type="button" id="adminUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle text-success fs-6"></i>
                                    <span class="fw-semibold text-dark text-truncate" style="max-width: 140px;"><?= esc($namaUser) ?></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="adminUserDropdown">
                                    <li class="px-3 py-2 text-muted small border-bottom">
                                        Login sebagai: <strong class="text-dark"><?= esc($namaUser) ?></strong>
                                        <br><span class="badge bg-success-subtle text-success border border-success-subtle mt-1">Superadmin</span>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item py-2 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalProfilAdmin">
                                            <i class="bi bi-person-gear text-success"></i> Profil Saya
                                        </button>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="<?= base_url('ubah_password') ?>">
                                            <i class="bi bi-key text-muted"></i> Ubah Password
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <a class="dropdown-item py-2 text-danger d-flex align-items-center gap-2" href="<?= base_url('auth/logout') ?>">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </header>

                    <!-- Main Content Admin -->
                    <main class="main-content">
                        <?= $this->renderSection('content'); ?>
                    </main>

                    <!-- Modal Profil Administrator Global -->
                    <div class="modal fade" id="modalProfilAdmin" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                <div class="modal-header bg-success text-white py-3">
                                    <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                                        <i class="bi bi-person-gear"></i>
                                        <span>Kelola Profil Administrator</span>
                                    </h6>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="<?= base_url('admin/update-profil') ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <div class="modal-body text-start p-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small text-dark">Nama Administrator</label>
                                            <input type="text" name="nama" class="form-control" value="<?= esc(session()->get('nama') ?? '') ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small text-dark">Username Administrator</label>
                                            <input type="text" name="username" class="form-control" value="<?= esc(session()->get('username') ?? '') ?>" required>
                                        </div>

                                        <hr class="my-3 text-muted">
                                        <div class="small fw-bold text-dark mb-2 d-flex align-items-center gap-1">
                                            <i class="bi bi-shield-lock text-success"></i>
                                            <span>Ubah Kata Sandi (Opsional)</span>
                                        </div>
                                        <p class="text-muted small mb-3" style="font-size: 0.78rem;">
                                            Biarkan kolom kata sandi di bawah kosong jika Anda tidak ingin mengganti password akun admin.
                                        </p>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small text-dark">Password Saat Ini</label>
                                            <div class="input-group">
                                                <input type="password" name="password_lama" id="inputAdminPasswordLamaGlobal" class="form-control border-end-0" placeholder="Masukkan jika ingin mengganti password">
                                                <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" onclick="togglePasswordVisibilityGlobal('inputAdminPasswordLamaGlobal', 'iconToggleAdminLamaGlobal')">
                                                    <i class="bi bi-eye" id="iconToggleAdminLamaGlobal"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small text-dark">Password Baru</label>
                                            <div class="input-group">
                                                <input type="password" name="password_baru" id="inputAdminPasswordBaruGlobal" class="form-control border-end-0" placeholder="Minimal 6 karakter">
                                                <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" onclick="togglePasswordVisibilityGlobal('inputAdminPasswordBaruGlobal', 'iconToggleAdminBaruGlobal')">
                                                    <i class="bi bi-eye" id="iconToggleAdminBaruGlobal"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 bg-light py-2 px-4">
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-sm btn-skagata rounded-pill px-4">
                                            <i class="bi bi-check2-circle me-1"></i> Simpan Profil
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Admin -->
                    <footer class="footer-skagata py-3 text-center">
                        <div class="container-fluid px-4">
                            <div class="small text-muted">
                                Crafted with <span class="text-danger">❤️</span> by <a href="https://awanbeo.my.id" target="_blank" class="fw-semibold text-success text-decoration-none">awanbeo.my.id</a>
                            </div>
                            <div class="small text-muted mt-1 fw-medium" style="font-size: 0.8rem;">
                                SMK Negeri 3 Yogyakarta
                            </div>
                        </div>
                    </footer>
                </div>
            </div>

        <?php else: ?>
            <!-- ============================================== -->
            <!-- 2. GURU & MAHASISWA (MOBILE-FIRST NAVBAR)      -->
            <!-- ============================================== -->
            <nav class="navbar navbar-expand-lg navbar-dark navbar-skagata py-2">
                <div class="container">
                    <a class="navbar-brand d-flex align-items-center gap-2" href="<?= base_url($role === 'guru' ? 'guru' : 'mahasiswa') ?>">
                        <span class="fs-4"><i class="bi bi-geo-alt-fill text-warning"></i></span>
                        <div class="lh-sm">
                            <span class="d-block fw-bold tracking-wide">SIPENSI SKAGATA</span>
                            <small class="d-block fw-normal text-white-50" style="font-size: 0.7rem;">SMK Negeri 3 Yogyakarta</small>
                        </div>
                    </a>

                    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                            <?php if ($role === 'guru' || $role === 'guru_pamong'): ?>
                                <li class="nav-item">
                                    <a class="nav-link <?= ($currentUri === 'guru' || $currentUri === 'guru/') ? 'active' : '' ?>" href="<?= base_url('guru') ?>">
                                        <i class="bi bi-calendar2-check me-1"></i> Presensi Harian
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= strpos($currentUri, 'guru/laporan_piket') !== false ? 'active' : '' ?>" href="<?= base_url('guru/laporan_piket') ?>">
                                        <i class="bi bi-camera me-1"></i> Laporan Piket
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= strpos($currentUri, 'guru/laporan') !== false && strpos($currentUri, 'guru/laporan_piket') === false ? 'active' : '' ?>" href="<?= base_url('guru/laporan') ?>">
                                        <i class="bi bi-file-earmark-spreadsheet me-1"></i> Laporan Bulanan
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="nav-item">
                                    <a class="nav-link <?= ($currentUri === 'mahasiswa' || $currentUri === 'mahasiswa/') ? 'active' : '' ?>" href="<?= base_url('mahasiswa') ?>">
                                        <i class="bi bi-house me-1"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= strpos($currentUri, 'mahasiswa/piket') !== false ? 'active' : '' ?>" href="<?= base_url('mahasiswa/piket') ?>">
                                        <i class="bi bi-camera me-1"></i> Piket KBM
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= strpos($currentUri, 'mahasiswa/riwayat') !== false ? 'active' : '' ?>" href="<?= base_url('mahasiswa/riwayat') ?>">
                                        <i class="bi bi-clock-history me-1"></i> Riwayat Mandiri
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>

                        <div class="d-flex align-items-center gap-3">
                            <span class="badge badge-role d-none d-md-inline-block">
                                <?= esc($roleLabel) ?>
                            </span>

                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-light rounded-pill px-3 dropdown-toggle d-flex align-items-center gap-2" type="button" id="userMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle"></i>
                                    <span class="fw-semibold text-truncate" style="max-width: 140px;"><?= esc($namaUser) ?></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="userMenuDropdown">
                                    <li class="px-3 py-2 text-muted small border-bottom">
                                        Login sebagai: <strong><?= esc($namaUser) ?></strong>
                                        <br><span class="badge bg-light text-dark border mt-1"><?= esc($roleLabel) ?></span>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="<?= base_url('ubah_password') ?>">
                                            <i class="bi bi-key text-muted me-2"></i> Ubah Password
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <a class="dropdown-item py-2 text-danger" href="<?= base_url('auth/logout') ?>">
                                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="main-content">
                <?= $this->renderSection('content'); ?>
            </main>

            <footer class="footer-skagata py-3 text-center">
                <div class="container">
                    <div class="small text-muted">
                        Crafted with <span class="text-danger">❤️</span> by <a href="https://awanbeo.my.id" target="_blank" class="fw-semibold text-success text-decoration-none">awanbeo.my.id</a>
                    </div>
                    <div class="small text-muted mt-1 fw-medium" style="font-size: 0.8rem;">
                        SMK Negeri 3 Yogyakarta
                    </div>
                </div>
            </footer>
        <?php endif; ?>

    <?php else: ?>
        <!-- ============================================== -->
        <!-- 3. GUEST / BELUM LOGIN (LOGIN SCREEN)          -->
        <!-- ============================================== -->
        <main class="main-content">
            <?= $this->renderSection('content'); ?>
        </main>

        <footer class="footer-skagata py-3 text-center">
            <div class="container">
                <div class="small text-muted">
                    Crafted with <span class="text-danger">❤️</span> by <a href="https://awanbeo.my.id" target="_blank" class="fw-semibold text-success text-decoration-none">awanbeo.my.id</a>
                </div>
                <div class="small text-muted mt-1 fw-medium" style="font-size: 0.8rem;">
                    SMK Negeri 3 Yogyakarta
                </div>
            </div>
        </footer>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: <?= json_encode((string) session()->getFlashdata('error')) ?>,
                    confirmButtonColor: '#0f5132',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('pesan')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: <?= json_encode((string) session()->getFlashdata('pesan')) ?>,
                    confirmButtonColor: '#0f5132',
                    timer: 2500,
                    showConfirmButton: false
                });
            });
        </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const wrapper = document.getElementById('adminWrapper');
            if (window.innerWidth < 992) {
                if (sidebar) sidebar.classList.toggle('show');
                if (backdrop) backdrop.classList.toggle('show');
            } else {
                if (wrapper) wrapper.classList.toggle('collapsed');
            }
        }

        function togglePasswordVisibilityGlobal(inputId, iconId) {
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
    </script>

    <?= $this->renderSection('scripts'); ?>

</body>

</html>