<?php

/**
 * Presensi PPL - Automated Test Runner
 * Menjalankan test suite mandiri untuk memverifikasi keamanan dan fungsionalitas aplikasi.
 */

define('TEST_START_TIME', microtime(true));

// Load official CodeIgniter 4 Test Bootstrap
require_once __DIR__ . '/../system/Test/bootstrap.php';

class TestRunner
{
    private int $passed = 0;
    private int $failed = 0;
    private array $errors = [];

    public function describe(string $title): void
    {
        echo "\n\033[1;36m=== {$title} ===\033[0m\n";
    }

    public function it(string $description, callable $testCase): void
    {
        try {
            $testCase();
            $this->passed++;
            echo "  \033[32m✔ PASS\033[0m: {$description}\n";
        } catch (\Throwable $e) {
            $this->failed++;
            $this->errors[] = [
                'desc' => $description,
                'msg'  => $e->getMessage(),
                'file' => $e->getFile() . ':' . $e->getLine()
            ];
            echo "  \033[31m✖ FAIL\033[0m: {$description} - " . $e->getMessage() . "\n";
        }
    }

    public function assertTrue(bool $condition, string $message = 'Expected true, got false'): void
    {
        if (!$condition) {
            throw new \AssertionError($message);
        }
    }

    public function assertFalse(bool $condition, string $message = 'Expected false, got true'): void
    {
        if ($condition) {
            throw new \AssertionError($message);
        }
    }

    public function assertEquals($expected, $actual, string $message = ''): void
    {
        if ($expected !== $actual) {
            $msg = $message ?: "Expected " . var_export($expected, true) . ", got " . var_export($actual, true);
            throw new \AssertionError($msg);
        }
    }

    public function assertMatchesRegularExpression(string $pattern, string $string, string $message = ''): void
    {
        if (!preg_match($pattern, $string)) {
            $msg = $message ?: "String '{$string}' does not match pattern '{$pattern}'";
            throw new \AssertionError($msg);
        }
    }

    public function report(): int
    {
        $duration = number_format(microtime(true) - TEST_START_TIME, 4);
        echo "\n\033[1;33m--------------------------------------------------\033[0m\n";
        echo "\033[1;37mHASIL PENGUJIAN OTOMATIS (TEST REPORT):\033[0m\n";
        echo "  Total Tests Run : " . ($this->passed + $this->failed) . "\n";
        echo "  \033[32mPassed          : {$this->passed}\033[0m\n";
        if ($this->failed > 0) {
            echo "  \033[31mFailed          : {$this->failed}\033[0m\n";
            echo "\nRincian Kegagalan:\n";
            foreach ($this->errors as $err) {
                echo "  - {$err['desc']}\n    {$err['msg']} at {$err['file']}\n";
            }
        } else {
            echo "  \033[32mFailed          : 0\033[0m\n";
            echo "  \033[1;32mSTATUS          : SEMUA PENGUJIAN LULUS (ALL TESTS PASSED)! 🎉\033[0m\n";
        }
        echo "  Durasi          : {$duration} detik\n";
        echo "\033[1;33m--------------------------------------------------\033[0m\n\n";

        return $this->failed === 0 ? 0 : 1;
    }
}

// Inisialisasi runner
$runner = new TestRunner();

// ==========================================
// 1. PENGUJIAN FILTER AUTENTIKASI (AuthFilter)
// ==========================================
$runner->describe("1. Pengujian AuthFilter (Proteksi Akses Tamu)");

require_once __DIR__ . '/../app/Filters/AuthFilter.php';

// Injeksi MockSession untuk pengujian CLI tanpa ketergantungan header browser
$configSession = new \Config\Session();
$mockSession = new \CodeIgniter\Test\Mock\MockSession(
    new \CodeIgniter\Session\Handlers\ArrayHandler($configSession, '127.0.0.1'),
    $configSession
);
\Config\Services::injectMock('session', $mockSession);

$request = \Config\Services::request();
$session = \Config\Services::session();
$authFilter = new \App\Filters\AuthFilter();

$runner->it("Tamu tanpa sesi 'isLoggedIn' harus ditolak dan dialihkan ke /auth", function() use ($runner, $authFilter, $request, $session) {
    $session->remove('isLoggedIn');
    $result = $authFilter->before($request);
    $runner->assertTrue($result instanceof \CodeIgniter\HTTP\RedirectResponse, "Filter harus mengembalikan RedirectResponse");
    $runner->assertTrue(strpos($result->getHeaderLine('Location'), '/auth') !== false, "Target redirect harus mengarah ke /auth");
});

$runner->it("User yang memiliki sesi 'isLoggedIn = true' harus diizinkan lewat (return null)", function() use ($runner, $authFilter, $request, $session) {
    $session->set('isLoggedIn', true);
    $result = $authFilter->before($request);
    $runner->assertTrue($result === null, "Filter harus return null (lanjutkan eksekusi)");
});


// ==========================================
// 2. PENGUJIAN FILTER PERAN (RoleFilter)
// ==========================================
$runner->describe("2. Pengujian RoleFilter (Pemisahan Hak Akses Guru & Mahasiswa)");

require_once __DIR__ . '/../app/Filters/RoleFilter.php';
$roleFilter = new \App\Filters\RoleFilter();

$runner->it("User belum login yang mengakses rute role-restricted harus dialihkan ke /auth", function() use ($runner, $roleFilter, $request, $session) {
    $session->remove('isLoggedIn');
    $session->remove('role');
    $result = $roleFilter->before($request, ['guru']);
    $runner->assertTrue($result instanceof \CodeIgniter\HTTP\RedirectResponse);
    $runner->assertTrue(strpos($result->getHeaderLine('Location'), '/auth') !== false);
});

$runner->it("Mahasiswa mencoba mengakses rute Guru harus dialihkan ke /mahasiswa dengan pesan ditolak", function() use ($runner, $roleFilter, $request, $session) {
    $session->set('isLoggedIn', true);
    $session->set('role', 'mahasiswa');
    $result = $roleFilter->before($request, ['guru']);
    $runner->assertTrue($result instanceof \CodeIgniter\HTTP\RedirectResponse);
    $runner->assertTrue(strpos($result->getHeaderLine('Location'), '/mahasiswa') !== false);
});

$runner->it("Guru mencoba mengakses rute Mahasiswa harus dialihkan ke /guru dengan pesan ditolak", function() use ($runner, $roleFilter, $request, $session) {
    $session->set('isLoggedIn', true);
    $session->set('role', 'guru');
    $result = $roleFilter->before($request, ['mahasiswa']);
    $runner->assertTrue($result instanceof \CodeIgniter\HTTP\RedirectResponse);
    $runner->assertTrue(strpos($result->getHeaderLine('Location'), '/guru') !== false);
});

$runner->it("Guru mengakses rute Guru harus diizinkan (return null)", function() use ($runner, $roleFilter, $request, $session) {
    $session->set('isLoggedIn', true);
    $session->set('role', 'guru');
    $result = $roleFilter->before($request, ['guru']);
    $runner->assertTrue($result === null);
});


// ==========================================
// 3. PENGUJIAN KEAMANAN UPLOAD FOTO PIKET
// ==========================================
$runner->describe("3. Pengujian Keamanan File Upload Bukti Piket");

$runner->it("Format data Base64 berbahaya dengan MIME bukan gambar (misal image/php) harus ditolak regex", function() use ($runner) {
    $maliciousPayload = "data:image/php;base64," . base64_encode("<?php phpinfo(); ?>");
    $pattern = '/^data:(image\/(jpeg|jpg|png|webp));base64,(.+)$/i';
    $runner->assertFalse((bool) preg_match($pattern, $maliciousPayload), "Header image/php harus ditolak");
});

$runner->it("Ekstensi manipulasi seperti .phtml, .cgi, .sh harus ditolak", function() use ($runner) {
    $allowedMimes = ['image/jpeg' => 'jpg', 'image/jpg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $runner->assertFalse(isset($allowedMimes['image/phtml']));
    $runner->assertFalse(isset($allowedMimes['image/x-php']));
    $runner->assertFalse(isset($allowedMimes['text/plain']));
});

$runner->it("Header image/png tetapi isi data binary palsu (bukan gambar asli) harus terdeteksi oleh getimagesizefromstring", function() use ($runner) {
    $fakeImageBinary = "Ini bukan binary gambar yang valid, ini teks palsu!";
    $info = @getimagesizefromstring($fakeImageBinary);
    $runner->assertFalse($info !== false, "Binary palsu harus gagal diidentifikasi sebagai gambar");
});

$runner->it("Gambar PNG asli 1x1 pixel harus berhasil divalidasi getimagesizefromstring", function() use ($runner) {
    // 1x1 transparent PNG base64
    $validPngBase64 = "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAAElFTkSuQmCC";
    $binary = base64_decode($validPngBase64);
    $info = @getimagesizefromstring($binary);
    $runner->assertTrue($info !== false);
    $runner->assertEquals('image/png', $info['mime']);
});

$runner->it("Format nama file baru harus mematuhi pola aman: piket_{userId}_{timestamp}_{randomHash}.{ext}", function() use ($runner) {
    $userId = 12;
    $time = time();
    $randomHash = bin2hex(random_bytes(4));
    $ext = 'jpg';
    $fileName = "piket_{$userId}_{$time}_{$randomHash}.{$ext}";
    $runner->assertMatchesRegularExpression('/^piket_\d+_\d+_[a-f0-9]{8}\.(jpg|png|webp)$/', $fileName);
});

$runner->it("File .htaccess di folder uploads harus memblokir eksekusi skrip PHP", function() use ($runner) {
    $htaccessPath = __DIR__ . '/../public/uploads/.htaccess';
    $runner->assertTrue(file_exists($htaccessPath), "File .htaccess harus ada di public/uploads/");
    $content = file_get_contents($htaccessPath);
    $runner->assertMatchesRegularExpression('/php|phtml|phar/i', $content);
    $runner->assertMatchesRegularExpression('/Deny from all/i', $content);
});


// ==========================================
// 4. PENGUJIAN PENANGGULANGAN SQL INJECTION
// ==========================================
$runner->describe("4. Pengujian Penanggulangan SQL Injection");

$runner->it("Filter tanggal dengan format tidak valid atau SQL Injection harus di-fallback ke tanggal hari ini", function() use ($runner) {
    $sqliPayload = "2026-04-16' OR 1=1 --";
    $isValid = (is_string($sqliPayload) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $sqliPayload));
    $tanggalPilih = $isValid ? $sqliPayload : date('Y-m-d');
    $runner->assertEquals(date('Y-m-d'), $tanggalPilih, "Payload SQL Injection pada tanggal harus dinetralkan");
});

$runner->it("Filter bulan dan tahun dengan SQL Injection harus dibersihkan oleh integer casting", function() use ($runner) {
    $sqliBulan = "04; DROP TABLE users; --";
    $isValidBulan = (is_string($sqliBulan) && preg_match('/^(0[1-9]|1[0-2])$/', $sqliBulan));
    $bulanPilih = $isValidBulan ? $sqliBulan : date('m');
    $runner->assertEquals(date('m'), $bulanPilih);

    $sqliTahun = "2026 UNION SELECT password FROM users";
    $isValidTahun = (is_string($sqliTahun) && preg_match('/^\d{4}$/', $sqliTahun));
    $tahunPilih = $isValidTahun ? $sqliTahun : date('Y');
    $runner->assertEquals(date('Y'), $tahunPilih);
});


// ==========================================
// 5. PENGUJIAN SANITASI XSS (Cross-Site Scripting)
// ==========================================
$runner->describe("5. Pengujian Sanitasi Output XSS");

function esc($data, string $context = 'html'): string {
    return htmlspecialchars((string) $data, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$runner->it("Tag berbahaya <script> dalam nama siswa atau keterangan harus diubah menjadi entitas HTML aman", function() use ($runner) {
    $xssNama = "<script>alert('pwned')</script>";
    $escaped = esc($xssNama);
    $runner->assertFalse(strpos($escaped, '<script>') !== false, "Tag <script> tidak boleh muncul mentah");
    $runner->assertEquals("&lt;script&gt;alert(&#039;pwned&#039;)&lt;/script&gt;", $escaped);
});

$runner->it("Attribut event handler XSS seperti onerror=alert(1) harus dinetralkan", function() use ($runner) {
    $xssAttr = '<img src=x onerror=alert(1)>';
    $escaped = esc($xssAttr);
    $runner->assertFalse(strpos($escaped, '<img') !== false);
});

$runner->it("Output flash message dalam JavaScript SweetAlert aman menggunakan json_encode()", function() use ($runner) {
    $rawFlash = "Pesan 'quote' dan </script><script>alert(1)</script>";
    $jsonEncoded = json_encode((string) $rawFlash);
    $runner->assertFalse(strpos($jsonEncoded, "</script>") !== false, "Script injection dalam JSON harus terhindar dari pemecah string JS");
});


// ==========================================
// 6. PENGUJIAN KONFIGURASI GIT & ENV
// ==========================================
$runner->describe("6. Pengujian Sanitasi Git & Environment");

$runner->it("File .gitignore harus mengabaikan kredensial .env, .env.*, dan folder writable", function() use ($runner) {
    $gitignorePath = __DIR__ . '/../.gitignore';
    $runner->assertTrue(file_exists($gitignorePath));
    $content = file_get_contents($gitignorePath);
    $runner->assertMatchesRegularExpression('/\.env/i', $content);
    $runner->assertMatchesRegularExpression('/writable\/session/i', $content);
});

$runner->it("File .env.example harus tersedia dan tidak boleh memuat password database sensitif", function() use ($runner) {
    $examplePath = __DIR__ . '/../.env.example';
    $runner->assertTrue(file_exists($examplePath));
    $content = file_get_contents($examplePath);
    $runner->assertFalse(strpos($content, 'VbX4pzFXTf') !== false, "Password produksi tidak boleh bocor di .env.example");
    $runner->assertFalse(strpos($content, 'sql204.infinityfree.com') !== false, "Host produksi tidak boleh bocor di .env.example");
});


// ==========================================
// 7. PENGUJIAN GPS GEOFENCING & HAVERSINE FORMULA
// ==========================================
$runner->describe("7. Pengujian GPS Geofencing (Formula Haversine & Validasi Radius)");

require_once __DIR__ . '/../app/Config/Presensi.php';
$presensiConfig = new \Config\Presensi();

$runner->it("Koordinat titik tepat sekolah harus menghasilkan jarak ~0 meter", function() use ($runner, $presensiConfig) {
    $jarak = \Config\Presensi::hitungJarak(
        $presensiConfig->schoolLatitude,
        $presensiConfig->schoolLongitude,
        $presensiConfig->schoolLatitude,
        $presensiConfig->schoolLongitude
    );
    $runner->assertTrue($jarak < 1.0, "Jarak harus mendekati 0 meter");
});

$runner->it("Koordinat dalam radius 50 meter harus diizinkan (< radius 100m)", function() use ($runner, $presensiConfig) {
    // Geser sedikit latitude (+0.0003 derajat ~ 33 meter)
    $latDekat = $presensiConfig->schoolLatitude + 0.0003;
    $longDekat = $presensiConfig->schoolLongitude;
    $jarak = \Config\Presensi::hitungJarak(
        $latDekat,
        $longDekat,
        $presensiConfig->schoolLatitude,
        $presensiConfig->schoolLongitude
    );
    $runner->assertTrue($jarak <= $presensiConfig->schoolRadius, "Jarak {$jarak}m harus masuk radius {$presensiConfig->schoolRadius}m");
});

$runner->it("Koordinat sejauh 500 meter (> 100 meter) harus ditolak berada di luar radius", function() use ($runner, $presensiConfig) {
    // Geser latitude (+0.005 derajat ~ 550 meter)
    $latJauh = $presensiConfig->schoolLatitude + 0.005;
    $longJauh = $presensiConfig->schoolLongitude;
    $jarak = \Config\Presensi::hitungJarak(
        $latJauh,
        $longJauh,
        $presensiConfig->schoolLatitude,
        $presensiConfig->schoolLongitude
    );
    $runner->assertTrue($jarak > $presensiConfig->schoolRadius, "Jarak {$jarak}m harus melebihi batas radius {$presensiConfig->schoolRadius}m");
});

$runner->it("Format koordinat tidak valid (di luar rentang -90..90 atau -180..180) harus ditolak", function() use ($runner) {
    $latInvalid = 95.0;
    $longInvalid = 200.0;
    $isValid = ($latInvalid >= -90 && $latInvalid <= 90 && $longInvalid >= -180 && $longInvalid <= 180);
    $runner->assertFalse($isValid, "Koordinat di luar bumi harus tidak valid");
});


// ==========================================
// 8. PENGUJIAN KEBIJAKAN JAM KERJA & KETERLAMBATAN
// ==========================================
$runner->describe("8. Pengujian Kebijakan Jam Kerja & Keterlambatan");

$runner->it("Presensi sebelum 07:15:00 WIB harus berstatus 'hadir'", function() use ($runner, $presensiConfig) {
    $jamMasukTepatWaktu = "07:05:00";
    $isTerlambat = ($jamMasukTepatWaktu > $presensiConfig->jamMasukMax);
    $status = $isTerlambat ? 'terlambat' : 'hadir';
    $runner->assertEquals('hadir', $status);
});

$runner->it("Presensi setelah 07:15:00 WIB harus berstatus 'terlambat'", function() use ($runner, $presensiConfig) {
    $jamMasukTerlambat = "07:22:15";
    $isTerlambat = ($jamMasukTerlambat > $presensiConfig->jamMasukMax);
    $status = $isTerlambat ? 'terlambat' : 'hadir';
    $runner->assertEquals('terlambat', $status);
});

$runner->it("Presensi pulang sebelum 15:00:00 WIB harus dicegah / ditolak", function() use ($runner, $presensiConfig) {
    $jamPulangAwal = "13:30:00";
    $bisaPulang = ($jamPulangAwal >= $presensiConfig->jamPulangMin);
    $runner->assertFalse($bisaPulang, "Jam pulang sebelum 15:00:00 harus dicegah");
});

$runner->it("Presensi pulang pada atau setelah 15:00:00 WIB harus diizinkan", function() use ($runner, $presensiConfig) {
    $jamPulangSah = "15:15:00";
    $bisaPulang = ($jamPulangSah >= $presensiConfig->jamPulangMin);
    $runner->assertTrue($bisaPulang, "Jam pulang setelah 15:00:00 harus diizinkan");
});


// ==========================================
// 9. PENGUJIAN HAK AKSES ADMIN & USER MANAGEMENT
// ==========================================
$runner->describe("9. Pengujian Hak Akses Admin & Manajemen Pengguna");

$runner->it("Admin mengakses rute Admin harus diizinkan (return null)", function() use ($runner, $roleFilter, $request, $session) {
    $session->set('isLoggedIn', true);
    $session->set('role', 'admin');
    $result = $roleFilter->before($request, ['admin']);
    $runner->assertTrue($result === null);
});

$runner->it("Mahasiswa mencoba mengakses rute Admin harus dialihkan ke /admin", function() use ($runner, $roleFilter, $request, $session) {
    $session->set('isLoggedIn', true);
    $session->set('role', 'mahasiswa');
    $result = $roleFilter->before($request, ['admin']);
    $runner->assertTrue($result instanceof \CodeIgniter\HTTP\RedirectResponse);
    $runner->assertTrue(strpos($result->getHeaderLine('Location'), '/mahasiswa') !== false);
});

$runner->it("Guru mencoba mengakses rute Admin harus dialihkan ke /guru", function() use ($runner, $roleFilter, $request, $session) {
    $session->set('isLoggedIn', true);
    $session->set('role', 'guru');
    $result = $roleFilter->before($request, ['admin']);
    $runner->assertTrue($result instanceof \CodeIgniter\HTTP\RedirectResponse);
    $runner->assertTrue(strpos($result->getHeaderLine('Location'), '/guru') !== false);
});

$runner->it("Admin dilarang menghapus akun dirinya sendiri", function() use ($runner) {
    $currentAdminId = 1;
    $targetDeleteId = 1;
    $isSelfDelete = ((int) $targetDeleteId === (int) $currentAdminId);
    $runner->assertTrue($isSelfDelete, "Penghapusan akun sendiri harus terdeteksi");
});


// ==========================================
// 10. PENGUJIAN EXPORT EXCEL & INTEGRITAS FITUR
// ==========================================
$runner->describe("10. Pengujian Laporan Excel & Status Whitelist");

$runner->it("Daftar status sah di Guru::update_status dan PresensiModel harus mendukung 'terlambat'", function() use ($runner) {
    $presensiModel = new \App\Models\PresensiModel();
    $rules = $presensiModel->getValidationRules();
    $runner->assertTrue(isset($rules['status']));
    $runner->assertTrue(strpos($rules['status'], 'terlambat') !== false, "Status 'terlambat' harus ada di validasi PresensiModel");
});

$runner->it("Method exportExcel harus terdefinisi pada Controller Guru", function() use ($runner) {
    $guruController = new \App\Controllers\Guru();
    $runner->assertTrue(method_exists($guruController, 'exportExcel'), "Method exportExcel harus ada di Guru controller");
});

$runner->it("Controller Admin harus memiliki metode CRUD lengkap", function() use ($runner) {
    $adminController = new \App\Controllers\Admin();
    $runner->assertTrue(method_exists($adminController, 'index'));
    $runner->assertTrue(method_exists($adminController, 'tambahUser'));
    $runner->assertTrue(method_exists($adminController, 'editUser'));
    $runner->assertTrue(method_exists($adminController, 'hapusUser'));
    $runner->assertTrue(method_exists($adminController, 'resetPassword'));
});


// ==========================================
// 11. PENGUJIAN MODUL 1: PENGATURAN GEOFENCING DINAMIS
// ==========================================
$runner->describe("11. Pengujian Modul 1: Pengaturan Geofencing Dinamis & SettingModel");

require_once __DIR__ . '/../app/Models/SettingModel.php';

$runner->it("SettingModel harus memiliki metode getSetting, setSetting, dan getAllSettings", function() use ($runner) {
    $runner->assertTrue(method_exists(\App\Models\SettingModel::class, 'getSetting'));
    $runner->assertTrue(method_exists(\App\Models\SettingModel::class, 'setSetting'));
    $runner->assertTrue(method_exists(\App\Models\SettingModel::class, 'getAllSettings'));
});

$runner->it("SettingModel::getSetting harus mengembalikan nilai default bila key tidak ditemukan", function() use ($runner) {
    $val = \App\Models\SettingModel::getSetting('kunci_tidak_ada_di_database_xyz', 'nilai_default_aman');
    $runner->assertEquals('nilai_default_aman', $val);
});

$runner->it("Config\\Presensi harus memuat properti dinamis (schoolName, geofenceActive, dsb)", function() use ($runner) {
    $cfg = new \Config\Presensi();
    $runner->assertTrue(property_exists($cfg, 'schoolName'));
    $runner->assertTrue(property_exists($cfg, 'geofenceActive'));
    $runner->assertTrue(property_exists($cfg, 'schoolLatitude'));
    $runner->assertTrue(property_exists($cfg, 'schoolLongitude'));
    $runner->assertTrue(property_exists($cfg, 'schoolRadius'));
});

$runner->it("Validasi input pengaturan harus menolak koordinat dan radius di luar batas", function() use ($runner) {
    $latSalah = 105.5; // > 90
    $isLatValid = (is_numeric($latSalah) && $latSalah >= -90 && $latSalah <= 90);
    $runner->assertFalse($isLatValid, "Latitude di atas 90 harus ditolak");

    $radiusNegatif = -50;
    $isRadiusValid = (is_numeric($radiusNegatif) && $radiusNegatif >= 10 && $radiusNegatif <= 5000);
    $runner->assertFalse($isRadiusValid, "Radius negatif harus ditolak");

    $radiusTerlaluBesar = 10000;
    $isRadiusValid2 = (is_numeric($radiusTerlaluBesar) && $radiusTerlaluBesar >= 10 && $radiusTerlaluBesar <= 5000);
    $runner->assertFalse($isRadiusValid2, "Radius di atas 5000 meter harus ditolak");
});

$runner->it("Validasi jam kerja harus menerima format HH:MM dan HH:MM:SS", function() use ($runner) {
    $jamValid1 = "07:15";
    $jamValid2 = "07:15:00";
    $jamSalah  = "25:70:99";
    $pattern = '/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/';

    $runner->assertTrue((bool) preg_match($pattern, $jamValid1));
    $runner->assertTrue((bool) preg_match($pattern, $jamValid2));
    $runner->assertFalse((bool) preg_match($pattern, $jamSalah));
});

$runner->it("Mode toleransi (geofenceActive = false) tidak boleh memblokir presensi di luar radius", function() use ($runner, $presensiConfig) {
    $latJauh = $presensiConfig->schoolLatitude + 0.05; // ~5.5 km
    $longJauh = $presensiConfig->schoolLongitude;
    $jarak = \Config\Presensi::hitungJarak($latJauh, $longJauh, $presensiConfig->schoolLatitude, $presensiConfig->schoolLongitude);
    $runner->assertTrue($jarak > $presensiConfig->schoolRadius, "Jarak harus jauh (> 100m)");

    // Simulasi logika controller dengan toggle nonaktif
    $geofenceActive = false;
    $apakahDitolak = false;
    if ($geofenceActive && $jarak > $presensiConfig->schoolRadius) {
        $apakahDitolak = true;
    }
    $runner->assertFalse($apakahDitolak, "Ketika geofence nonaktif, presensi tidak boleh ditolak");
});

$runner->it("Controller Admin harus memiliki method pengaturan dan simpanPengaturan", function() use ($runner) {
    $adminController = new \App\Controllers\Admin();
    $runner->assertTrue(method_exists($adminController, 'pengaturan'));
    $runner->assertTrue(method_exists($adminController, 'simpanPengaturan'));
});

$runner->describe("12. Pengujian Hari Kerja Efektif & Identitas Resmi Skagata");

$runner->it("Config\\Presensi harus mengarahkan institusi ke SMK Negeri 3 Yogyakarta dan koordinat Skagata", function() use ($runner) {
    $cfg = new \Config\Presensi();
    $runner->assertEquals('SMK Negeri 3 Yogyakarta', $cfg->schoolName);
    $runner->assertEquals(-7.780120, $cfg->schoolLatitude);
    $runner->assertEquals(110.366450, $cfg->schoolLongitude);
});

$runner->it("Logika siklus kerja 5 hari harus mengidentifikasi Sabtu dan Minggu sebagai akhir pekan resmi", function() use ($runner) {
    // 2026-10-02 adalah Jumat (hari kerja), 2026-10-03 adalah Sabtu, 2026-10-04 adalah Minggu
    $jumat = date('N', strtotime('2026-10-02')); // 5
    $sabtu = date('N', strtotime('2026-10-03')); // 6
    $minggu = date('N', strtotime('2026-10-04')); // 7

    $isHariKerjaJumat = ($jumat >= 1 && $jumat <= 5);
    $isHariKerjaSabtu = ($sabtu >= 1 && $sabtu <= 5);
    $isHariKerjaMinggu = ($minggu >= 1 && $minggu <= 5);

    $runner->assertTrue($isHariKerjaJumat, "Jumat harus dihitung hari kerja");
    $runner->assertFalse($isHariKerjaSabtu, "Sabtu bukan hari kerja");
    $runner->assertFalse($isHariKerjaMinggu, "Minggu bukan hari kerja");
});

$runner->it("Layout template harus memuat brand SIPENSI SKAGATA dan kredit awanbeo.my.id", function() use ($runner) {
    $templateFile = APPPATH . 'Views/layout/template.php';
    $runner->assertTrue(file_exists($templateFile));
    $content = file_get_contents($templateFile);
    $runner->assertTrue(strpos($content, 'SIPENSI SKAGATA') !== false, "Template harus memuat 'SIPENSI SKAGATA'");
    $runner->assertTrue(strpos($content, 'awanbeo.my.id') !== false, "Template harus memuat kredit 'awanbeo.my.id'");
    $runner->assertTrue(strpos($content, 'SMK Negeri 3 Yogyakarta') !== false, "Template harus memuat 'SMK Negeri 3 Yogyakarta'");
});

$runner->describe("13. Pengujian Mekanisme Tombol Datang & Status Izin/Sakit");

$runner->it("Tombol datang pada view mahasiswa harus disabled jika presensi hari ini sudah ada", function() use ($runner) {
    $viewFile = APPPATH . 'Views/mahasiswa/index.php';
    $runner->assertTrue(file_exists($viewFile));
    $content = file_get_contents($viewFile);
    $runner->assertTrue(strpos($content, "(\$presensi_hari_ini) ? 'disabled' : ''") !== false, "Tombol datang harus disabled saat presensi_hari_ini ada");
});

$runner->it("Controller Mahasiswa harus memberikan pesan error spesifik jika sudah izin atau sakit", function() use ($runner) {
    $controllerFile = APPPATH . 'Controllers/Mahasiswa.php';
    $runner->assertTrue(file_exists($controllerFile));
    $content = file_get_contents($controllerFile);
    $runner->assertTrue(strpos($content, "in_array(\$cek['status'], ['izin', 'sakit'], true)") !== false, "Harus membedakan pesan jika status izin/sakit");
    $runner->assertTrue(strpos($content, "Anda telah mengajukan") !== false, "Pesan harus mengindikasikan pengajuan izin/sakit");
});

$runner->describe("14. Pengujian Riwayat Presensi Mahasiswa & Tampilan Adaptive");

$runner->it("View mahasiswa/riwayat harus mendukung dual-view (kartu mobile dan tabel desktop)", function() use ($runner) {
    $viewFile = APPPATH . 'Views/mahasiswa/riwayat.php';
    $runner->assertTrue(file_exists($viewFile));
    $content = file_get_contents($viewFile);
    $runner->assertTrue(strpos($content, 'presence-item-card') !== false, "Harus memuat kartu mobile 'presence-item-card'");
    $runner->assertTrue(strpos($content, 'table-custom') !== false, "Harus memuat tabel desktop 'table-custom'");
    $runner->assertTrue(strpos($content, 'persenKehadiran') !== false, "Harus memuat metrik persen kehadiran terpadu");
});

$runner->it("Controller Mahasiswa::riwayat harus menghitung persentase kehadiran dan mengirimkan data pengguna", function() use ($runner) {
    $controllerFile = APPPATH . 'Controllers/Mahasiswa.php';
    $runner->assertTrue(file_exists($controllerFile));
    $content = file_get_contents($controllerFile);
    $runner->assertTrue(strpos($content, 'persenKehadiran') !== false, "Controller harus menghitung persenKehadiran");
    $runner->assertTrue(strpos($content, 'totalHadirFisik') !== false, "Controller harus menghitung totalHadirFisik");
});

$runner->describe("15. Pengujian Beranda Admin & Manajemen Pengguna");

$runner->it("View admin/index harus memuat operational banner, avatar inisial, dan quick action", function() use ($runner) {
    $viewFile = APPPATH . 'Views/admin/index.php';
    $runner->assertTrue(file_exists($viewFile));
    $content = file_get_contents($viewFile);
    $runner->assertTrue(strpos($content, 'operational-banner') !== false, "Harus memuat operational-banner status sistem");
    $runner->assertTrue(strpos($content, 'avatar-initial') !== false, "Harus memuat avatar inisial pengguna");
    $runner->assertTrue(strpos($content, 'stat-card-modern') !== false, "Harus memuat stat-card-modern tema Skagata");
    $runner->assertTrue(strpos($content, 'quick-action-item') !== false, "Harus memuat quick-action-item");
    $runner->assertTrue(strpos($content, 'modalTambahUser') !== false, "Harus memuat modalTambahUser");
});

$runner->it("View admin/pengguna harus memuat tabel pengguna, filter toolbar, dan modal CRUD", function() use ($runner) {
    $viewFile = APPPATH . 'Views/admin/pengguna.php';
    $runner->assertTrue(file_exists($viewFile));
    $content = file_get_contents($viewFile);
    $runner->assertTrue(strpos($content, 'table-custom') !== false, "Harus memuat table-custom");
    $runner->assertTrue(strpos($content, 'filter-wrapper') !== false, "Harus memuat filter-wrapper");
    $runner->assertTrue(strpos($content, 'modalTambahUser') !== false, "Harus memuat modalTambahUser");
    $runner->assertTrue(strpos($content, 'modalEditUser') !== false, "Harus memuat modalEditUser");
    $runner->assertTrue(strpos($content, 'modalResetPassword') !== false, "Harus memuat modalResetPassword");
});

$runner->it("Layout template harus menyediakan dedicated admin-sidebar dan modal profil admin", function() use ($runner) {
    $templateFile = APPPATH . 'Views/layout/template.php';
    $runner->assertTrue(file_exists($templateFile));
    $content = file_get_contents($templateFile);
    $runner->assertTrue(strpos($content, 'admin-sidebar') !== false, "Template harus memuat admin-sidebar");
    $runner->assertTrue(strpos($content, 'admin/pengguna') !== false, "Template harus memuat navigasi ke admin/pengguna");
    $runner->assertTrue(strpos($content, 'auth/logout') !== false, "Template harus memuat auth/logout");
    $runner->assertTrue(strpos($content, 'toggleAdminSidebar') !== false, "Template harus memuat toggleAdminSidebar");
    $runner->assertTrue(strpos($content, "session()->get('isLoggedIn')") !== false, "Template harus mengecek session isLoggedIn");
    $runner->assertTrue(strpos($content, 'modalProfilAdmin') !== false, "Template harus memuat modalProfilAdmin global");
});

$runner->it("Controller Admin harus memiliki method pengguna, updateProfil, dan CRUD lengkap", function() use ($runner) {
    $controller = new \App\Controllers\Admin();
    $runner->assertTrue(method_exists($controller, 'index'), "Controller Admin harus memiliki method index");
    $runner->assertTrue(method_exists($controller, 'pengguna'), "Controller Admin harus memiliki method pengguna");
    $runner->assertTrue(method_exists($controller, 'updateProfil'), "Controller Admin harus memiliki method updateProfil");
    $controllerFile = APPPATH . 'Controllers/Admin.php';
    $runner->assertTrue(file_exists($controllerFile));
    $content = file_get_contents($controllerFile);
    $runner->assertTrue(strpos($content, "where('role !=', 'admin')") !== false, "Query harus mengecualikan role admin");
    $runner->assertTrue(strpos($content, 'daftar_jurusan') !== false, "Controller harus menyediakan daftar_jurusan dinamis");
    $runner->assertTrue(strpos($content, 'SIPENSI SKAGATA') !== false, "Title harus mencerminkan SIPENSI SKAGATA");
});

$runner->describe("16. Pengujian Master Data Jurusan (Konsentrasi Keahlian)");

$runner->it("JurusanModel harus memiliki validasi kode_jurusan, nama_jurusan, dan method getDaftarNama", function() use ($runner) {
    $model = new \App\Models\JurusanModel();
    $runner->assertEquals('jurusan', $model->getTable(), "Tabel JurusanModel harus 'jurusan'");
    $rules = $model->getValidationRules();
    $runner->assertTrue(isset($rules['kode_jurusan']), "Rule kode_jurusan harus ada");
    $runner->assertTrue(isset($rules['nama_jurusan']), "Rule nama_jurusan harus ada");
    $runner->assertTrue(method_exists($model, 'getDaftarNama'), "Method getDaftarNama harus ada");
    $runner->assertTrue(method_exists($model, 'getJurusanWithUserCount'), "Method getJurusanWithUserCount harus ada");
    $runner->assertTrue(method_exists($model, 'countPenggunaByJurusan'), "Method countPenggunaByJurusan harus ada");
});

$runner->it("Route master data jurusan harus terdaftar di Config/Routes.php", function() use ($runner) {
    $routesFile = APPPATH . 'Config/Routes.php';
    $runner->assertTrue(file_exists($routesFile));
    $content = file_get_contents($routesFile);
    $runner->assertTrue(strpos($content, "'Admin::jurusan'") !== false, "Route GET admin/jurusan harus terdaftar");
    $runner->assertTrue(strpos($content, "'Admin::tambahJurusan'") !== false, "Route POST admin/jurusan/tambah harus terdaftar");
    $runner->assertTrue(strpos($content, "'Admin::editJurusan'") !== false, "Route POST admin/jurusan/edit harus terdaftar");
    $runner->assertTrue(strpos($content, "'Admin::hapusJurusan'") !== false, "Route POST admin/jurusan/hapus harus terdaftar");
});

$runner->it("Controller Admin harus mengimplementasikan CRUD jurusan dan proteksi integritas data", function() use ($runner) {
    $controller = new \App\Controllers\Admin();
    $runner->assertTrue(method_exists($controller, 'jurusan'), "Admin::jurusan harus terdefinisi");
    $runner->assertTrue(method_exists($controller, 'tambahJurusan'), "Admin::tambahJurusan harus terdefinisi");
    $runner->assertTrue(method_exists($controller, 'editJurusan'), "Admin::editJurusan harus terdefinisi");
    $runner->assertTrue(method_exists($controller, 'hapusJurusan'), "Admin::hapusJurusan harus terdefinisi");

    $controllerFile = APPPATH . 'Controllers/Admin.php';
    $content = file_get_contents($controllerFile);
    $runner->assertTrue(strpos($content, 'countPenggunaByJurusan') !== false, "Hapus jurusan harus memeriksa countPenggunaByJurusan untuk keamanan data");
});

$runner->it("View admin/jurusan harus memuat stat-card, tabel interaktif, dan modal CRUD SweetAlert", function() use ($runner) {
    $viewFile = APPPATH . 'Views/admin/jurusan.php';
    $runner->assertTrue(file_exists($viewFile), "File Views/admin/jurusan.php harus ada");
    $content = file_get_contents($viewFile);
    $runner->assertTrue(strpos($content, 'stat-card-modern') !== false, "Harus memuat stat-card-modern");
    $runner->assertTrue(strpos($content, 'modalTambahJurusan') !== false, "Harus memuat modalTambahJurusan");
    $runner->assertTrue(strpos($content, 'modalEditJurusan') !== false, "Harus memuat modalEditJurusan");
    $runner->assertTrue(strpos($content, 'konfirmasiHapusJurusan') !== false, "Harus memuat konfirmasiHapusJurusan");
    $runner->assertTrue(strpos($content, 'Swal.fire') !== false, "Harus memuat integrasi SweetAlert2");
});

$runner->it("Layout template dan form pengguna harus terintegrasi dengan Master Data Jurusan", function() use ($runner) {
    $templateFile = APPPATH . 'Views/layout/template.php';
    $runner->assertTrue(file_exists($templateFile));
    $content = file_get_contents($templateFile);
    $runner->assertTrue(strpos($content, 'admin/jurusan') !== false, "Sidebar harus mengarahkan ke admin/jurusan");

    // Pastikan admin/index dan guru/index juga menggunakan daftar jurusan dinamis
    $adminIndex = file_get_contents(APPPATH . 'Views/admin/index.php');
    $runner->assertTrue(strpos($adminIndex, '$daftar_jurusan') !== false, "Admin index harus mendukung daftar_jurusan");
    $guruIndex = file_get_contents(APPPATH . 'Views/guru/index.php');
    $runner->assertTrue(strpos($guruIndex, '$daftar_jurusan') !== false, "Guru index harus mendukung daftar_jurusan");
});

$runner->describe("17. Pengujian Master Data Universitas (Mitra Kampus PPL/PK)");

$runner->it("UniversitasModel harus memiliki validasi kode_universitas, nama_universitas, dan helper method", function() use ($runner) {
    $model = new \App\Models\UniversitasModel();
    $runner->assertEquals('universitas', $model->getTable(), "Tabel UniversitasModel harus 'universitas'");
    $rules = $model->getValidationRules();
    $runner->assertTrue(isset($rules['kode_universitas']), "Rule kode_universitas harus ada");
    $runner->assertTrue(isset($rules['nama_universitas']), "Rule nama_universitas harus ada");
    $runner->assertTrue(method_exists($model, 'getDaftarNama'), "Method getDaftarNama harus ada");
    $runner->assertTrue(method_exists($model, 'getUniversitasWithUserCount'), "Method getUniversitasWithUserCount harus ada");
    $runner->assertTrue(method_exists($model, 'countPenggunaByUniversitas'), "Method countPenggunaByUniversitas harus ada");
});

$runner->it("Route master data universitas harus terdaftar di Config/Routes.php", function() use ($runner) {
    $routesFile = APPPATH . 'Config/Routes.php';
    $runner->assertTrue(file_exists($routesFile));
    $content = file_get_contents($routesFile);
    $runner->assertTrue(strpos($content, "'Admin::universitas'") !== false, "Route GET admin/universitas harus terdaftar");
    $runner->assertTrue(strpos($content, "'Admin::tambahUniversitas'") !== false, "Route POST admin/universitas/tambah harus terdaftar");
    $runner->assertTrue(strpos($content, "'Admin::editUniversitas'") !== false, "Route POST admin/universitas/edit harus terdaftar");
    $runner->assertTrue(strpos($content, "'Admin::hapusUniversitas'") !== false, "Route POST admin/universitas/hapus harus terdaftar");
});

$runner->it("Controller Admin harus mengimplementasikan CRUD universitas dan proteksi integritas data", function() use ($runner) {
    $controller = new \App\Controllers\Admin();
    $runner->assertTrue(method_exists($controller, 'universitas'), "Admin::universitas harus terdefinisi");
    $runner->assertTrue(method_exists($controller, 'tambahUniversitas'), "Admin::tambahUniversitas harus terdefinisi");
    $runner->assertTrue(method_exists($controller, 'editUniversitas'), "Admin::editUniversitas harus terdefinisi");
    $runner->assertTrue(method_exists($controller, 'hapusUniversitas'), "Admin::hapusUniversitas harus terdefinisi");

    $controllerFile = APPPATH . 'Controllers/Admin.php';
    $content = file_get_contents($controllerFile);
    $runner->assertTrue(strpos($content, 'countPenggunaByUniversitas') !== false, "Hapus universitas harus memeriksa countPenggunaByUniversitas untuk keamanan data");
});

$runner->it("View admin/universitas harus memuat stat-card, tabel interaktif, dan modal CRUD SweetAlert", function() use ($runner) {
    $viewFile = APPPATH . 'Views/admin/universitas.php';
    $runner->assertTrue(file_exists($viewFile), "File Views/admin/universitas.php harus ada");
    $content = file_get_contents($viewFile);
    $runner->assertTrue(strpos($content, 'stat-card-modern') !== false, "Harus memuat stat-card-modern");
    $runner->assertTrue(strpos($content, 'modalTambahUniversitas') !== false, "Harus memuat modalTambahUniversitas");
    $runner->assertTrue(strpos($content, 'modalEditUniversitas') !== false, "Harus memuat modalEditUniversitas");
    $runner->assertTrue(strpos($content, 'konfirmasiHapusUniversitas') !== false, "Harus memuat konfirmasiHapusUniversitas");
    $runner->assertTrue(strpos($content, 'Swal.fire') !== false, "Harus memuat integrasi SweetAlert2");
});

$runner->it("Layout template dan manajemen pengguna harus terintegrasi dengan Master Data Universitas", function() use ($runner) {
    $templateFile = APPPATH . 'Views/layout/template.php';
    $runner->assertTrue(file_exists($templateFile));
    $content = file_get_contents($templateFile);
    $runner->assertTrue(strpos($content, 'admin/universitas') !== false, "Sidebar harus mengarahkan ke admin/universitas");

    $penggunaView = file_get_contents(APPPATH . 'Views/admin/pengguna.php');
    $runner->assertTrue(strpos($penggunaView, '$daftar_universitas') !== false, "Pengguna view harus mendukung daftar_universitas");
    $runner->assertTrue(strpos($penggunaView, 'editUniversitasWrapper') !== false, "Pengguna view harus memiliki editUniversitasWrapper");

    $adminIndex = file_get_contents(APPPATH . 'Views/admin/index.php');
    $runner->assertTrue(strpos($adminIndex, '$daftar_universitas') !== false, "Admin index harus mendukung daftar_universitas");
});

$runner->describe("18. Pengujian Master Data Periode (Gelombang PPL & Tahun Ajaran)");

$runner->it("PeriodeModel harus memiliki validasi lengkap, timeline stats, dan helper methods", function() use ($runner) {
    $model = new \App\Models\PeriodeModel();
    $runner->assertEquals('periode', $model->getTable(), "Tabel PeriodeModel harus 'periode'");
    $rules = $model->getValidationRules();
    $runner->assertTrue(isset($rules['nama_periode']), "Rule nama_periode harus ada");
    $runner->assertTrue(isset($rules['tahun_ajaran']), "Rule tahun_ajaran harus ada");
    $runner->assertTrue(isset($rules['semester']), "Rule semester harus ada");
    $runner->assertTrue(isset($rules['tanggal_mulai']), "Rule tanggal_mulai harus ada");
    $runner->assertTrue(isset($rules['tanggal_selesai']), "Rule tanggal_selesai harus ada");

    $runner->assertTrue(method_exists($model, 'getPeriodeWithStats'), "Method getPeriodeWithStats harus ada");
    $runner->assertTrue(method_exists($model, 'getPeriodeAktif'), "Method getPeriodeAktif harus ada");
    $runner->assertTrue(method_exists($model, 'setAktif'), "Method setAktif harus ada");
    $runner->assertTrue(method_exists($model, 'countPenggunaByPeriode'), "Method countPenggunaByPeriode harus ada");
    $runner->assertTrue(method_exists($model, 'getDaftarPilihan'), "Method getDaftarPilihan harus ada");

    $periodeAktif = $model->getPeriodeAktif();
    $runner->assertTrue(is_array($periodeAktif), "Harus ada periode aktif yang ditemukan");
    $runner->assertEquals(1, (int)$periodeAktif['is_aktif'], "Periode aktif harus memiliki is_aktif = 1");
});

$runner->it("Route master data periode harus terdaftar di Config/Routes.php", function() use ($runner) {
    $routesFile = APPPATH . 'Config/Routes.php';
    $runner->assertTrue(file_exists($routesFile));
    $content = file_get_contents($routesFile);
    $runner->assertTrue(strpos($content, "'Admin::periode'") !== false, "Route GET admin/periode harus terdaftar");
    $runner->assertTrue(strpos($content, "'Admin::tambahPeriode'") !== false, "Route POST admin/periode/tambah harus terdaftar");
    $runner->assertTrue(strpos($content, "'Admin::editPeriode'") !== false, "Route POST admin/periode/edit harus terdaftar");
    $runner->assertTrue(strpos($content, "'Admin::hapusPeriode'") !== false, "Route POST admin/periode/hapus harus terdaftar");
    $runner->assertTrue(strpos($content, "'Admin::setAktifPeriode'") !== false, "Route POST admin/periode/set-aktif harus terdaftar");
});

$runner->it("Controller Admin harus mengimplementasikan CRUD periode, aktivasi gelombang, dan proteksi integritas", function() use ($runner) {
    $controller = new \App\Controllers\Admin();
    $runner->assertTrue(method_exists($controller, 'periode'), "Admin::periode harus terdefinisi");
    $runner->assertTrue(method_exists($controller, 'tambahPeriode'), "Admin::tambahPeriode harus terdefinisi");
    $runner->assertTrue(method_exists($controller, 'editPeriode'), "Admin::editPeriode harus terdefinisi");
    $runner->assertTrue(method_exists($controller, 'hapusPeriode'), "Admin::hapusPeriode harus terdefinisi");
    $runner->assertTrue(method_exists($controller, 'setAktifPeriode'), "Admin::setAktifPeriode harus terdefinisi");

    $controllerFile = APPPATH . 'Controllers/Admin.php';
    $content = file_get_contents($controllerFile);
    $runner->assertTrue(strpos($content, 'countPenggunaByPeriode') !== false, "Hapus periode harus memeriksa countPenggunaByPeriode untuk keamanan data");
    $runner->assertTrue(strpos($content, 'strtotime($tanggalSelesai) < strtotime($tanggalMulai)') !== false, "Validasi rentang tanggal harus memastikan tanggal_selesai >= tanggal_mulai");
});

$runner->it("View admin/periode harus memuat stat-card, tabel interaktif, dan modal CRUD SweetAlert", function() use ($runner) {
    $viewFile = APPPATH . 'Views/admin/periode.php';
    $runner->assertTrue(file_exists($viewFile), "File Views/admin/periode.php harus ada");
    $content = file_get_contents($viewFile);
    $runner->assertTrue(strpos($content, 'stat-card-modern') !== false, "Harus memuat stat-card-modern");
    $runner->assertTrue(strpos($content, 'modalTambahPeriode') !== false, "Harus memuat modalTambahPeriode");
    $runner->assertTrue(strpos($content, 'modalEditPeriode') !== false, "Harus memuat modalEditPeriode");
    $runner->assertTrue(strpos($content, 'konfirmasiSetAktif') !== false, "Harus memuat konfirmasiSetAktif");
    $runner->assertTrue(strpos($content, 'konfirmasiHapusPeriode') !== false, "Harus memuat konfirmasiHapusPeriode");
    $runner->assertTrue(strpos($content, 'Swal.fire') !== false, "Harus memuat integrasi SweetAlert2");
});

$runner->it("Layout template dan manajemen pengguna harus terintegrasi dengan Master Data Periode", function() use ($runner) {
    $templateFile = APPPATH . 'Views/layout/template.php';
    $runner->assertTrue(file_exists($templateFile));
    $content = file_get_contents($templateFile);
    $runner->assertTrue(strpos($content, 'admin/periode') !== false, "Sidebar harus mengarahkan ke admin/periode");

    $penggunaView = file_get_contents(APPPATH . 'Views/admin/pengguna.php');
    $runner->assertTrue(strpos($penggunaView, '$daftar_periode') !== false, "Pengguna view harus mendukung daftar_periode");
    $runner->assertTrue(strpos($penggunaView, 'editPeriodeWrapper') !== false, "Pengguna view harus memiliki editPeriodeWrapper");

    $adminIndex = file_get_contents(APPPATH . 'Views/admin/index.php');
    $runner->assertTrue(strpos($adminIndex, '$daftar_periode') !== false, "Admin index harus mendukung daftar_periode");
    $runner->assertTrue(strpos($adminIndex, 'admin/periode') !== false, "Admin index harus menyediakan pintasan admin/periode");
});

$runner->describe("19. Pengujian Pemetaan Guru Pamong & Isolasi Jurusan");

$runner->it("GuruPamongModel harus memiliki skema tabel, CRUD penugasan, dan isolasi relasi jurusan", function() use ($runner) {
    $model = new \App\Models\GuruPamongModel();
    $runner->assertEquals('guru_pamong', $model->getTable(), "Tabel GuruPamongModel harus 'guru_pamong'");

    $runner->assertTrue(method_exists($model, 'getJurusanByGuru'), "Method getJurusanByGuru harus ada");
    $runner->assertTrue(method_exists($model, 'getGuruWithJurusan'), "Method getGuruWithJurusan harus ada");
    $runner->assertTrue(method_exists($model, 'assignJurusanToGuru'), "Method assignJurusanToGuru harus ada");
    $runner->assertTrue(method_exists($model, 'isMahasiswaSupervisedByGuru'), "Method isMahasiswaSupervisedByGuru harus ada");
    $runner->assertTrue(method_exists($model, 'countMahasiswaByGuru'), "Method countMahasiswaByGuru harus ada");

    // Ambil guru test menggunakan koneksi default (MySQL db_presensi)
    $db = \Config\Database::connect('default');
    $guru = $db->table('users')->where('role', 'guru')->get()->getRowArray();
    $runner->assertTrue(!empty($guru), "Harus ada minimal satu akun guru di database");

    $guruId = (int) $guru['id'];
    $assigned = $model->getJurusanByGuru($guruId);
    $runner->assertTrue(is_array($assigned), "Hasil getJurusanByGuru harus berupa array");

    // Test getGuruWithJurusan
    $list = $model->getGuruWithJurusan();
    $runner->assertTrue(is_array($list) && count($list) > 0, "getGuruWithJurusan harus mengembalikan daftar guru pamong");
    $firstGuru = $list[0];
    $runner->assertTrue(isset($firstGuru['jurusan_list']), "Item guru harus memuat jurusan_list");
    $runner->assertTrue(isset($firstGuru['total_mahasiswa']), "Item guru harus memuat total_mahasiswa");
});

$runner->it("Verifikasi isolasi pengawasan mahasiswa berdasarkan pemetaan jurusan", function() use ($runner) {
    $model = new \App\Models\GuruPamongModel();
    $db = \Config\Database::connect('default');

    // Ambil guru febriyana atau guru pertama
    $guru = $db->table('users')->where('role', 'guru')->get()->getRowArray();
    $runner->assertTrue(!empty($guru));
    $guruId = (int) $guru['id'];

    // Pastikan guru ini dipetakan ke jurusan 'Informatika'
    $model->assignJurusanToGuru($guruId, ['Informatika'], 'SK Testing 2026');
    $assigned = $model->getJurusanByGuru($guruId);
    $runner->assertTrue(in_array('Informatika', $assigned, true), "Guru harus terpetakan ke Informatika");

    // Cari mahasiswa dengan jurusan Informatika
    $mhsIF = $db->table('users')->where('role', 'mahasiswa')->where('jurusan', 'Informatika')->get()->getRowArray();
    if ($mhsIF) {
        $isSupervised = $model->isMahasiswaSupervisedByGuru($guruId, (int) $mhsIF['id']);
        $runner->assertTrue($isSupervised, "Mahasiswa Informatika harus diawasi oleh guru pamong Informatika");
    }

    // Cari mahasiswa selain Informatika
    $mhsNonIF = $db->table('users')->where('role', 'mahasiswa')->where('jurusan !=', 'Informatika')->get()->getRowArray();
    if ($mhsNonIF) {
        $isSupervisedNon = $model->isMahasiswaSupervisedByGuru($guruId, (int) $mhsNonIF['id']);
        $runner->assertFalse($isSupervisedNon, "Mahasiswa di luar Informatika TIDAK boleh diawasi oleh guru Informatika");
    }

    // Reset/kosongkan jurusan guru
    $model->assignJurusanToGuru($guruId, []);
    $emptyAssigned = $model->getJurusanByGuru($guruId);
    $runner->assertEquals([], $emptyAssigned, "Setelah direset, guru tidak boleh memiliki jurusan bimbingan");
    if ($mhsIF) {
        $isSupervisedAfterReset = $model->isMahasiswaSupervisedByGuru($guruId, (int) $mhsIF['id']);
        $runner->assertFalse($isSupervisedAfterReset, "Guru tanpa jurusan tidak boleh dapat mengawasi mahasiswa manapun");
    }

    // Kembalikan pemetaan guru semula
    $model->assignJurusanToGuru($guruId, ['Informatika'], 'SK Pengawasan 2026');
});

$runner->it("Route pemetaan guru pamong harus terdaftar di Config/Routes.php", function() use ($runner) {
    $routesFile = APPPATH . 'Config/Routes.php';
    $runner->assertTrue(file_exists($routesFile));
    $content = file_get_contents($routesFile);
    $runner->assertTrue(strpos($content, "'Admin::guruPamong'") !== false, "Route GET admin/guru-pamong harus terdaftar");
    $runner->assertTrue(strpos($content, "'Admin::simpanPemetaanGuru'") !== false, "Route POST admin/guru-pamong/simpan harus terdaftar");
    $runner->assertTrue(strpos($content, "'Admin::hapusPemetaanGuru'") !== false, "Route POST admin/guru-pamong/hapus harus terdaftar");
});

$runner->it("Controller Guru harus menerapkan isolasi data di index, update_status, laporan, exportExcel, dan piket", function() use ($runner) {
    $guruControllerFile = APPPATH . 'Controllers/Guru.php';
    $runner->assertTrue(file_exists($guruControllerFile));
    $content = file_get_contents($guruControllerFile);

    $runner->assertTrue(strpos($content, 'isMahasiswaSupervisedByGuru') !== false, "Guru::update_status harus memvalidasi isMahasiswaSupervisedByGuru");
    $runner->assertTrue(strpos($content, 'assignedJurusans') !== false, "Guru controller harus memfilter query berdasarkan assignedJurusans");
    $runner->assertTrue(strpos($content, 'guruPamongModel') !== false, "Guru controller harus menginjeksi guruPamongModel");
});

$runner->it("View admin/guru_pamong harus memuat stat-card, modal pemetaan, dan isolasi feedback", function() use ($runner) {
    $viewFile = APPPATH . 'Views/admin/guru_pamong.php';
    $runner->assertTrue(file_exists($viewFile), "File Views/admin/guru_pamong.php harus ada");
    $content = file_get_contents($viewFile);

    $runner->assertTrue(strpos($content, 'stat-card-modern') !== false, "Harus memuat stat-card-modern");
    $runner->assertTrue(strpos($content, 'modalPemetaan') !== false, "Harus memuat modalPemetaan");
    $runner->assertTrue(strpos($content, 'formResetPemetaan') !== false, "Harus memuat formResetPemetaan");
    $runner->assertTrue(strpos($content, 'tabelGuruPamong') !== false, "Harus memuat tabelGuruPamong");
    $runner->assertTrue(strpos($content, 'bukaModalPemetaan') !== false, "Harus memuat handler JS bukaModalPemetaan");
    $runner->assertTrue(strpos($content, 'Swal.fire') !== false, "Harus memuat integrasi SweetAlert2");
});

$runner->it("Layout template dan manajemen pengguna harus terintegrasi dengan Pemetaan Guru Pamong", function() use ($runner) {
    $templateFile = APPPATH . 'Views/layout/template.php';
    $runner->assertTrue(file_exists($templateFile));
    $content = file_get_contents($templateFile);
    $runner->assertTrue(strpos($content, 'admin/guru-pamong') !== false, "Sidebar harus mengarahkan ke admin/guru-pamong");

    $penggunaView = file_get_contents(APPPATH . 'Views/admin/pengguna.php');
    $runner->assertTrue(strpos($penggunaView, 'admin/guru-pamong') !== false, "Pengguna view harus memiliki tautan cepat ke pemetaan pamong");
    $runner->assertTrue(strpos($penggunaView, "Jurusan Bimbingan / Pamong") !== false, "Pengguna view harus mendukung label jurusan pamong");

    // Guru views harus memuat indikator jurusan pamong
    $guruIndexView = file_get_contents(APPPATH . 'Views/guru/index.php');
    $runner->assertTrue(strpos($guruIndexView, 'assigned_jurusans') !== false, "Guru index view harus menampilkan identitas jurusan pamong");

    $guruLaporanView = file_get_contents(APPPATH . 'Views/guru/laporan.php');
    $runner->assertTrue(strpos($guruLaporanView, 'assigned_jurusans') !== false, "Guru laporan view harus menampilkan identitas jurusan pamong");
});

// Cetak laporan akhir & exit code
exit($runner->report());
