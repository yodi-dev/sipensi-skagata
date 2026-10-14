<?= $this->extend('layout/template') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    .settings-card {
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(15, 81, 50, 0.05);
        background: #ffffff;
        overflow: hidden;
    }

    .card-header-custom {
        background-color: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.25rem 1.5rem;
    }

    #map {
        height: 400px;
        width: 100%;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        z-index: 1;
    }

    @media (max-width: 576px) {
        #map {
            height: 290px;
        }

        .card-header-custom {
            padding: 1rem;
        }
    }

    .form-section-title {
        font-size: 0.82rem;
        font-weight: 700;
        color: #0f5132;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 0.45rem;
        margin-bottom: 1.25rem;
    }

    .form-range::-webkit-slider-thumb {
        background-color: #0f5132;
    }

    .form-range::-moz-range-thumb {
        background-color: #0f5132;
    }

    .form-range:focus::-webkit-slider-thumb {
        box-shadow: 0 0 0 1px #fff, 0 0 0 0.25rem rgba(15, 81, 50, 0.25);
    }

    .form-check-input:checked {
        background-color: #0f5132;
        border-color: #0f5132;
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
    }

    .shadow-xs {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 py-4">

    <!-- Top Header -->
    <div class="mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-sliders text-success me-2"></i>
                    <span>Pengaturan Lokasi &amp; Jam Presensi</span>
                </h4>
                <p class="text-muted small mt-1 mb-0">Kalibrasi perimeter GPS radius sekolah dan kebijakan waktu presensi SMK Negeri 3 Yogyakarta</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-muted border rounded-pill px-3 py-2 d-inline-flex align-items-center shadow-xs" style="font-size: 0.82rem;">
                    <i class="bi bi-clock-history text-success me-2 fs-6"></i>
                    <span>Zona Waktu: <strong>WIB (UTC+7)</strong></span>
                </span>
            </div>
        </div>
    </div>

    <!-- Ringkasan Parameter Berjalan -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="p-3 bg-white border rounded-4 shadow-xs d-flex align-items-center gap-3 h-100">
                <div class="p-2 rounded-3 bg-success-subtle text-success flex-shrink-0" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <div class="lh-sm">
                    <small class="text-muted d-block" style="font-size: 0.75rem;">Status Geofencing</small>
                    <span class="fw-bold text-dark" style="font-size: 0.9rem;" id="summaryGeofenceStatus">
                        <?= $geofence_active ? 'Radius Ketat Aktif' : 'Mode Toleransi Bebas' ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="p-3 bg-white border rounded-4 shadow-xs d-flex align-items-center gap-3 h-100">
                <div class="p-2 rounded-3 bg-info-subtle text-info-emphasis flex-shrink-0" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div class="lh-sm">
                    <small class="text-muted d-block" style="font-size: 0.75rem;">Perimeter Radius Absensi</small>
                    <span class="fw-bold text-dark" style="font-size: 0.9rem;" id="summaryRadiusStatus">
                        <?= esc($school_radius) ?> Meter dari Titik Pusat
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="p-3 bg-white border rounded-4 shadow-xs d-flex align-items-center gap-3 h-100">
                <div class="p-2 rounded-3 bg-warning-subtle text-warning-emphasis flex-shrink-0" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="lh-sm">
                    <small class="text-muted d-block" style="font-size: 0.75rem;">Jam Masuk &amp; Pulang</small>
                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                        Maks <?= esc(substr($jam_masuk_max, 0, 5)) ?> — Min <?= esc(substr($jam_pulang_min, 0, 5)) ?> WIB
                    </span>
                </div>
            </div>
        </div>
    </div>

    <form action="<?= base_url('admin/pengaturan/simpan') ?>" method="POST" id="formPengaturan">
        <?= csrf_field() ?>

        <div class="row g-4">

            <!-- Kolom Kiri: Peta Interaktif Leaflet -->
            <div class="col-12 col-lg-7">
                <div class="card settings-card h-100 shadow-xs">
                    <div class="card-header-custom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="bi bi-geo-alt-fill text-success me-2"></i>
                                <span>Peta Perimeter Skagata</span>
                            </h6>
                            <small class="text-muted">Geser marker atau klik peta untuk menentukan titik koordinat</small>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-shrink-0 flex-nowrap">
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 shadow-xs text-nowrap d-inline-flex align-items-center gap-2" onclick="resetKeSkagata()" id="btnResetSkagata" title="Kembalikan koordinat ke titik resmi SMKN 3 Yogyakarta">
                                <i class="bi bi-arrow-counterclockwise text-secondary me-2"></i>
                                <span>Reset Skagata</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3 shadow-xs text-nowrap d-inline-flex align-items-center gap-2" onclick="deteksiLokasiSaya()" id="btnLokasiSaya" title="Deteksi posisi GPS perangkat saya saat ini">
                                <i class="bi bi-crosshair me-2"></i>
                                <span>Lokasi Saya</span>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3 d-flex flex-column">
                        <div id="map" class="flex-grow-1"></div>
                        <div class="mt-3 p-3 bg-light rounded-3 text-muted small border">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-info-circle-fill text-success fs-6 mt-0.5 me-2"></i>
                                <div class="lh-sm">
                                    Lingkaran hijau menggambarkan <strong>radius perimeter absensi resmi</strong>. Presensi mahasiswa maupun guru hanya dapat dilakukan jika posisi perangkat berada di dalam lingkaran ini (kecuali jika mode toleransi darurat diaktifkan).
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Form Konfigurasi -->
            <div class="col-12 col-lg-5">
                <div class="card settings-card shadow-xs">
                    <div class="card-header-custom">
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-gear-fill text-success me-2"></i>
                            <span>Parameter Konfigurasi Operasional</span>
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        <!-- Section Institusi -->
                        <div class="form-section-title d-flex align-items-center gap-2">
                            <i class="bi bi-building me-2"></i>
                            <span>Informasi Institusi</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Nama Sekolah / Institusi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-building text-success"></i></span>
                                <input type="text" name="school_name" class="form-control border-start-0" value="<?= esc($school_name) ?>" required placeholder="Masukkan nama sekolah">
                            </div>
                        </div>

                        <!-- Section Koordinat & Radius -->
                        <div class="form-section-title mt-4 d-flex align-items-center gap-2">
                            <i class="bi bi-geo-alt me-2"></i>
                            <span>Geofencing &amp; Titik Koordinat</span>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold small text-muted">Latitude</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-geo-alt text-success"></i></span>
                                    <input type="text" name="school_latitude" id="school_latitude" class="form-control border-start-0 font-monospace" value="<?= esc($school_latitude) ?>" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold small text-muted">Longitude</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-geo-alt text-success"></i></span>
                                    <input type="text" name="school_longitude" id="school_longitude" class="form-control border-start-0 font-monospace" value="<?= esc($school_longitude) ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted d-flex justify-content-between align-items-center">
                                <span>Radius Presensi (Meter)</span>
                                <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold rounded-pill px-2.5 py-1" id="radiusLabel"><?= esc($school_radius) ?> meter</span>
                            </label>
                            <input type="range" name="school_radius" id="school_radius" class="form-range" min="10" max="1000" step="5" value="<?= esc($school_radius) ?>" oninput="updateRadius(this.value)">
                            <div class="d-flex justify-content-between text-muted small mt-1" style="font-size: 0.72rem;">
                                <span>10m</span>
                                <span>100m (Default)</span>
                                <span>500m</span>
                                <span>1000m</span>
                            </div>
                        </div>

                        <div class="mb-3 p-3 bg-light rounded-3 border">
                            <div class="form-check form-switch m-0 d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" name="geofence_active" id="geofence_active" value="1" <?= $geofence_active ? 'checked' : '' ?> onchange="updateSwitchSummary(this.checked)">
                                <label class="form-check-label fw-semibold text-dark ms-2 small" for="geofence_active">
                                    Aktifkan Penegakan Radius Ketat
                                </label>
                            </div>
                            <small class="text-muted d-block mt-1.5 lh-sm" style="font-size: 0.75rem;">
                                Jika dinonaktifkan, presensi dapat dilakukan tanpa batasan jarak (mode toleransi sinyal GPS darurat).
                            </small>
                        </div>

                        <!-- Section Jam Presensi -->
                        <div class="form-section-title mt-4 d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history me-2"></i>
                            <span>Ketentuan Jam Kerja Operasional</span>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <label class="form-label fw-semibold small text-muted">Batas Datang Tepat Waktu</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-box-arrow-in-right text-success"></i></span>
                                    <input type="time" name="jam_masuk_max" class="form-control border-start-0" value="<?= esc(substr($jam_masuk_max, 0, 5)) ?>" required>
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">Lewat waktu ini = Terlambat</small>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold small text-muted">Batas Pulang Minimal</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-box-arrow-right text-warning"></i></span>
                                    <input type="time" name="jam_pulang_min" class="form-control border-start-0" value="<?= esc(substr($jam_pulang_min, 0, 5)) ?>" required>
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">Sebelum jam ini = Cegah checkout</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-skagata w-100 py-2.5 fw-semibold rounded-pill shadow-sm d-inline-flex align-items-center justify-content-center gap-2" id="btnSubmitPengaturan">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <span>Simpan Konfigurasi Sistem</span>
                        </button>

                    </div>
                </div>
            </div>

        </div>
    </form>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let currentLat = <?= json_encode((float) $school_latitude) ?>;
    let currentLng = <?= json_encode((float) $school_longitude) ?>;
    let currentRadius = <?= json_encode((int) $school_radius) ?>;

    // Inisialisasi Peta Leaflet
    const map = L.map('map').setView([currentLat, currentLng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Marker Titik Pusat
    const marker = L.marker([currentLat, currentLng], {
        draggable: true,
        title: 'Titik Pusat SMKN 3 Yogyakarta'
    }).addTo(map);

    // Circle Perimeter Radius Skagata Emerald
    const circle = L.circle([currentLat, currentLng], {
        radius: currentRadius,
        color: '#0f5132',
        fillColor: '#10b981',
        fillOpacity: 0.22,
        weight: 2
    }).addTo(map);

    function setCoordinates(lat, lng) {
        document.getElementById('school_latitude').value = Number(lat).toFixed(6);
        document.getElementById('school_longitude').value = Number(lng).toFixed(6);
        marker.setLatLng([lat, lng]);
        circle.setLatLng([lat, lng]);
    }

    // Event Marker Dragged
    marker.on('dragend', function(e) {
        const pos = e.target.getLatLng();
        setCoordinates(pos.lat, pos.lng);
    });

    // Event Map Clicked
    map.on('click', function(e) {
        setCoordinates(e.latlng.lat, e.latlng.lng);
    });

    // Event Input Latitude / Longitude Manual
    document.getElementById('school_latitude').addEventListener('change', function() {
        const lat = parseFloat(this.value);
        const lng = parseFloat(document.getElementById('school_longitude').value);
        if (!isNaN(lat) && !isNaN(lng)) {
            setCoordinates(lat, lng);
            map.panTo([lat, lng]);
        }
    });

    document.getElementById('school_longitude').addEventListener('change', function() {
        const lat = parseFloat(document.getElementById('school_latitude').value);
        const lng = parseFloat(this.value);
        if (!isNaN(lat) && !isNaN(lng)) {
            setCoordinates(lat, lng);
            map.panTo([lat, lng]);
        }
    });

    // Slider Radius Interaktif
    function updateRadius(val) {
        const r = parseInt(val, 10);
        document.getElementById('radiusLabel').innerText = r + ' meter';
        const summaryRadius = document.getElementById('summaryRadiusStatus');
        if (summaryRadius) {
            summaryRadius.innerText = r + ' Meter dari Titik Pusat';
        }
        circle.setRadius(r);
    }

    // Update Status Geofencing Dinamis
    function updateSwitchSummary(checked) {
        const summaryStatus = document.getElementById('summaryGeofenceStatus');
        if (summaryStatus) {
            summaryStatus.innerText = checked ? 'Radius Ketat Aktif' : 'Mode Toleransi Bebas';
        }
    }

    // Reset Koordinat ke Titik Resmi Skagata
    function resetKeSkagata() {
        const defLat = -7.780120;
        const defLng = 110.366450;
        setCoordinates(defLat, defLng);
        map.setView([defLat, defLng], 17);
        map.invalidateSize();
        Swal.fire({
            icon: 'success',
            title: 'Reset ke SMKN 3 Yogyakarta',
            text: 'Titik pusat koordinat berhasil dikembalikan ke lokasi resmi sekolah.',
            confirmButtonColor: '#0f5132',
            timer: 1800,
            showConfirmButton: false
        });
    }

    // Deteksi Lokasi Saya Saat Ini
    function deteksiLokasiSaya() {
        if (!navigator.geolocation) {
            Swal.fire({
                icon: 'error',
                title: 'Tidak Didukung',
                text: 'Browser Anda tidak mendukung Geolocation API.',
                confirmButtonColor: '#0f5132'
            });
            return;
        }

        const btn = document.getElementById('btnLokasiSaya');
        const originalContent = btn ? btn.innerHTML : '<i class="bi bi-crosshair me-2"></i><span>Lokasi Saya</span>';
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span><span>Mencari...</span>';
        }

        navigator.geolocation.getCurrentPosition(
            function(pos) {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                }
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                setCoordinates(lat, lng);
                map.setView([lat, lng], 17);
                map.invalidateSize();
                Swal.fire({
                    icon: 'success',
                    title: 'Lokasi Terdeteksi',
                    text: 'Koordinat berhasil disesuaikan ke posisi GPS Anda saat ini.',
                    confirmButtonColor: '#0f5132',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            function(err) {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                }
                let errorMsg = 'Pastikan izin akses lokasi telah diberikan pada browser Anda.';
                if (err.code === 1) {
                    errorMsg = 'Akses lokasi ditolak. Harap izinkan browser mengakses lokasi Anda.';
                } else if (err.code === 2) {
                    errorMsg = 'Posisi lokasi tidak tersedia. Pastikan perangkat Anda terhubung ke internet/GPS.';
                } else if (err.code === 3) {
                    errorMsg = 'Waktu permintaan lokasi habis (timeout). Silakan coba kembali.';
                }
                Swal.fire({
                    icon: 'warning',
                    title: 'Gagal Mendeteksi Lokasi',
                    text: errorMsg,
                    confirmButtonColor: '#0f5132'
                });
            }, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }

    // Invalidate map size to prevent gray tiles on mobile and window resize
    setTimeout(function() {
        map.invalidateSize();
    }, 250);

    window.addEventListener('resize', function() {
        map.invalidateSize();
    });

    // Submit spinner handling
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formPengaturan');
        if (form) {
            form.addEventListener('submit', function() {
                const btn = document.getElementById('btnSubmitPengaturan');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...';
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>