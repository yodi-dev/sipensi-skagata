<?= $this->extend('layout/template') ?>

<?= $this->section('styles') ?>
<style>
    .password-card {
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        border: none;
        overflow: hidden;
    }

    .password-header {
        background: linear-gradient(135deg, #0f5132, #0a3622);
        color: white;
        padding: 2.5rem 1.5rem;
        text-align: center;
    }

    .password-body {
        padding: 2.5rem;
        background-color: #ffffff;
    }

    .form-control {
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        background-color: #f8fafc;
        border: 1px solid #dee2e6;
    }

    .form-control:focus {
        background-color: #fff;
        border-color: #10b981;
        box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.2);
    }

    .input-group-text {
        background-color: #f8fafc;
        border: 1px solid #dee2e6;
        color: #6c757d;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .btn-primary-custom {
        background-color: #0f5132;
        border-color: #0f5132;
        border-radius: 2rem;
        padding: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary-custom:hover {
        background-color: #0a3622;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(15, 81, 50, 0.25);
    }

    .btn-outline-custom {
        border-radius: 0.5rem;
        padding: 0.75rem;
        font-weight: 600;
    }

    .form-label-custom {
        color: #334155;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.4rem;
    }

    .requirement-item {
        font-size: 0.78rem;
        transition: all 0.2s ease;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-11 col-sm-10 col-md-8 col-lg-6 col-xl-5">

            <div class="card password-card">
                <div class="password-header">
                    <i class="bi bi-shield-lock-fill fs-1 d-block mb-2"></i>
                    <h4 class="fw-bold mb-0">Ubah Password</h4>
                    <p class="text-white-50 small mb-0 mt-1">Pastikan akun kamu selalu aman</p>
                </div>

                <div class="password-body">

                    <form action="<?= base_url('auth/proses_ubah_password') ?>" method="POST" id="formUbahPassword">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label for="passLama" class="form-label form-label-custom">Password Lama</label>
                            <div class="input-group">
                                <span class="input-group-text border-end-0"><i class="bi bi-key"></i></span>
                                <input type="password" name="password_lama" id="passLama" class="form-control border-start-0 border-end-0" placeholder="Masukkan password saat ini" autocomplete="current-password" required>
                                <span class="input-group-text border-start-0 cursor-pointer toggle-password" data-target="passLama" title="Tampilkan / sembunyikan password">
                                    <i class="bi bi-eye-slash"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="passBaru" class="form-label form-label-custom">Password Baru</label>
                            <div class="input-group mb-1">
                                <span class="input-group-text border-end-0"><i class="bi bi-shield-plus"></i></span>
                                <input type="password" name="password_baru" id="passBaru" class="form-control border-start-0 border-end-0" placeholder="Minimal 6 karakter" autocomplete="new-password" required minlength="6">
                                <span class="input-group-text border-start-0 cursor-pointer toggle-password" data-target="passBaru" title="Tampilkan / sembunyikan password">
                                    <i class="bi bi-eye-slash"></i>
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-1 requirement-item text-muted" id="ruleMinLength">
                                <i class="bi bi-circle text-muted" id="iconRuleMinLength"></i>
                                <span id="textRuleMinLength">Minimal 6 karakter</span>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label for="passKonfirm" class="form-label form-label-custom">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text border-end-0"><i class="bi bi-shield-check"></i></span>
                                <input type="password" name="konfirmasi_password" id="passKonfirm" class="form-control border-start-0 border-end-0" placeholder="Ulangi password baru" autocomplete="new-password" required minlength="6">
                                <span class="input-group-text border-start-0 cursor-pointer toggle-password" data-target="passKonfirm" title="Tampilkan / sembunyikan password">
                                    <i class="bi bi-eye-slash"></i>
                                </span>
                            </div>
                            <div id="matchFeedback" class="small mt-1 d-none" style="font-size: 0.8rem;"></div>
                        </div>

                        <div class="d-grid gap-3">
                            <button type="submit" id="btnSubmitUbahPass" class="btn btn-primary-custom text-white d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-save"></i>
                                <span>Simpan Password</span>
                            </button>
                            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-outline-custom text-center">
                                <i class="bi bi-arrow-left me-2"></i> Batal / Kembali
                            </a>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.toggle-password');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const inputElement = document.getElementById(targetId);
                const iconElement = this.querySelector('i');

                if (inputElement.type === "password") {
                    inputElement.type = "text";
                    iconElement.classList.remove('bi-eye-slash');
                    iconElement.classList.add('bi-eye');
                    iconElement.classList.add('text-success');
                } else {
                    inputElement.type = "password";
                    iconElement.classList.remove('bi-eye');
                    iconElement.classList.add('bi-eye-slash');
                    iconElement.classList.remove('text-success');
                }
            });
        });

        // Real-time Validation & Feedback
        const passBaru = document.getElementById('passBaru');
        const passKonfirm = document.getElementById('passKonfirm');
        const ruleMinLength = document.getElementById('ruleMinLength');
        const iconRuleMinLength = document.getElementById('iconRuleMinLength');
        const textRuleMinLength = document.getElementById('textRuleMinLength');
        const matchFeedback = document.getElementById('matchFeedback');
        const formUbahPass = document.getElementById('formUbahPassword');
        const btnSubmitUbahPass = document.getElementById('btnSubmitUbahPass');

        function validatePasswordRules() {
            const valBaru = passBaru.value;
            const valKonfirm = passKonfirm.value;

            // Check length >= 6
            if (valBaru.length >= 6) {
                ruleMinLength.classList.remove('text-muted');
                ruleMinLength.classList.add('text-success');
                iconRuleMinLength.className = 'bi bi-check-circle-fill text-success';
                textRuleMinLength.classList.add('fw-semibold');
            } else {
                ruleMinLength.classList.remove('text-success');
                ruleMinLength.classList.add('text-muted');
                iconRuleMinLength.className = 'bi bi-circle text-muted';
                textRuleMinLength.classList.remove('fw-semibold');
            }

            // Check match
            if (valKonfirm.length > 0) {
                matchFeedback.classList.remove('d-none');
                if (valBaru === valKonfirm) {
                    matchFeedback.innerHTML = '<span class="text-success fw-medium"><i class="bi bi-check-circle-fill me-1"></i>Password konfirmasi cocok</span>';
                } else {
                    matchFeedback.innerHTML = '<span class="text-danger fw-medium"><i class="bi bi-x-circle-fill me-1"></i>Password konfirmasi belum cocok</span>';
                }
            } else {
                matchFeedback.classList.add('d-none');
            }
        }

        if (passBaru) passBaru.addEventListener('input', validatePasswordRules);
        if (passKonfirm) passKonfirm.addEventListener('input', validatePasswordRules);

        if (formUbahPass) {
            formUbahPass.addEventListener('submit', function(e) {
                if (passBaru.value.length < 6) {
                    e.preventDefault();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Password Terlalu Pendek',
                            text: 'Password baru minimal harus terdiri dari 6 karakter.',
                            confirmButtonColor: '#0f5132'
                        });
                    } else {
                        alert('Password baru minimal harus terdiri dari 6 karakter.');
                    }
                    passBaru.focus();
                    return false;
                }

                if (passBaru.value !== passKonfirm.value) {
                    e.preventDefault();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Password Tidak Cocok',
                            text: 'Konfirmasi password baru tidak cocok dengan password baru.',
                            confirmButtonColor: '#0f5132'
                        });
                    } else {
                        alert('Konfirmasi password baru tidak cocok dengan password baru.');
                    }
                    passKonfirm.focus();
                    return false;
                }

                if (btnSubmitUbahPass) {
                    btnSubmitUbahPass.disabled = true;
                    btnSubmitUbahPass.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span><span>Menyimpan Password...</span>';
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>