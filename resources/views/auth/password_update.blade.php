<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>Reset Password &mdash; {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <meta name="color-scheme" content="light">

    <link rel="shortcut icon" href="{{ asset('assets/media/favicon/favicon.ico') }}" type="image/x-icon" />

    <!-- Primary Meta Tags -->
    <meta name="keywords"
        content="KembangIn Digital Nusantara, Software House, IT Consultant, Konsultan IT, Pengembangan Software, Jasa Pembuatan Aplikasi, Pembuatan Website, Sistem Informasi, Solusi IT, Digital Transformation, Web Development, Internet of Things, IoT, IT Support, Teknologi Informasi, Perusahaan IT Terbaik, IT Solution Provider, Aplikasi Custom, Software Development Company, Konsultan Digital, Transformasi Digital, IT Services, Software Engineering, Enterprise Solution, Aplikasi Bisnis, Startup Development, IT Infrastructure, Konsultan IT Lumajang, Konsultan IT Jember, Software House Indonesia" />
    <meta name="author" content="Laravel Auth" />
    <meta name="title" content="Laravel Auth — Authentication base system" />
    <meta name="description" content="Laravel Auth is an authentication base system built with Laravel." />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://laravel-auth.example.com" />
    <meta property="og:author" content="Laravel Auth" />
    <meta property="og:title" content="Laravel Auth — Authentication base system" />
    <meta property="og:description" content="Laravel Auth is an authentication base system built with Laravel." />
    <meta property="og:image" content="{{ asset('assets/media/meta-thumb.png') }}" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="https://laravel-auth.example.com" />
    <meta property="twitter:title" content="Laravel Auth — Authentication base system" />
    <meta property="twitter:description" content="Laravel Auth is an authentication base system built with Laravel." />
    <meta property="twitter:image" content="{{ asset('assets/media/meta-thumb.png') }}" />

    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <!-- Vendor css -->
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <!-- Icons css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Plugins css -->
    <link href="{{ asset('assets/plugins/jquery-confirm/jquery-confirm.min.css') }}" rel="stylesheet" />
</head>

<body>
    <div class="position-absolute top-0 end-0 m-3 d-flex gap-2 align-items-center">
        <!-- Tombol Light/Dark Mode -->
        <button id="light-dark-mode"
            class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center" type="button"
            title="Ganti Mode">
            <i class="ti ti-moon fs-20" id="theme-icon"></i>
        </button>
    </div>

    <div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center">
        <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xl-4 col-lg-5 col-md-6">
                <div class="card overflow-hidden text-center h-100 p-xxl-4 p-3 mb-0">
                    <a href="{{ route('home.index') }}" class="auth-brand mb-3">
                        <img src="{{ asset('assets/media/logo/logo-dark.png') }}" alt="dark logo" height="60"
                            class="logo-dark">
                        <img src="{{ asset('assets/media/logo/logo.png') }}" alt="logo light" height="60"
                            class="logo-light">
                    </a>

                    <h4 class="fw-semibold mb-2">
                        Buat Ulang Kata Sandi
                    </h4>

                    <p class="text-muted mb-4">
                        Silakan buat ulang kata sandi, gunakan kata sandi yang kuat.
                    </p>

                    <form method="POST" action="{{ route('password.update', ['token' => $token]) }}"
                        class="text-start mb-3">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="email">
                                Alamat Email
                            </label>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Masukkan alamat email Anda" value="{{ $email }}" readonly
                                style="cursor: not-allowed;">

                            @error('email')
                                <div class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3 position-relative">
                            <label class="form-label" for="password">
                                Kata Sandi Baru <small class="text-primary ms-1">Minimal 6 karakter</small>
                            </label>
                            <div class="input-group">
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Masukkan kata sandi baru">
                                <span class="input-group-text bg-transparent" id="togglePassword"
                                    style="cursor: pointer;">
                                    <i class="ti ti-eye" id="passwordIcon"></i>
                                </span>
                            </div>

                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3 position-relative">
                            <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi</label>
                            <div class="input-group">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Konfirmasi kata sandi baru">
                                <span class="input-group-text bg-transparent" id="toggleConfirmPassword"
                                    style="cursor: pointer;">
                                    <i class="ti ti-eye" id="confirmIcon"></i>
                                </span>
                            </div>

                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-primary" type="submit">Reset Kata Sandi</button>
                        </div>
                    </form>

                    <p class="text-danger fs-14 mb-4">
                        Kembali ke <a href="{{ route('auth.view') }}" class="fw-semibold text-dark ms-1">Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <!-- Plugins js -->
    <script src="{{ asset('assets/plugins/jquery-confirm/jquery-confirm.min.js') }}"></script>
    <!-- Page js -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const password = document.getElementById('password');
            const passwordConfirmation = document.getElementById('password_confirmation');
            const passwordIcon = document.getElementById('passwordIcon');
            const confirmIcon = document.getElementById('confirmIcon');

            if (togglePassword) {
                togglePassword.addEventListener('click', function() {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    passwordIcon.classList.toggle('ti-eye');
                    passwordIcon.classList.toggle('ti-eye-off');
                });
            }

            if (toggleConfirmPassword) {
                toggleConfirmPassword.addEventListener('click', function() {
                    const type = passwordConfirmation.getAttribute('type') === 'password' ? 'text' :
                        'password';
                    passwordConfirmation.setAttribute('type', type);
                    confirmIcon.classList.toggle('ti-eye');
                    confirmIcon.classList.toggle('ti-eye-off');
                });
            }

            // Real-time validation
            if (password) {
                password.addEventListener('blur', function() {
                    validatePassword(this.value);
                });

                password.addEventListener('input', function() {
                    // Clear validation when user starts typing
                    if (this.value.length > 0) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else {
                        this.classList.remove('is-valid', 'is-invalid');
                    }
                });
            }

            function validatePassword(value) {
                const commonPasswords = ['123456', '654321', 'password', '12345678', '111111', '000000'];

                if (value.length === 0) {
                    showPasswordError('Kata sandi wajib diisi.');
                    return false;
                }

                if (value.length < 6) {
                    showPasswordError('Kata sandi minimal 6 karakter.');
                    return false;
                }

                if (commonPasswords.includes(value)) {
                    showPasswordError('Kata sandi terlalu umum dan mudah ditebak.');
                    return false;
                }

                if (/(.)\1{5,}/.test(value)) {
                    showPasswordError('Kata sandi tidak boleh berisi karakter yang berulang.');
                    return false;
                }

                if (/123456|654321|12345678|123456789/.test(value)) {
                    showPasswordError('Kata sandi tidak boleh berisi angka yang berurutan.');
                    return false;
                }

                // Jika valid, hapus error
                password.classList.remove('is-invalid');
                password.classList.add('is-valid');
                const errorElement = password.parentElement.nextElementSibling;
                if (errorElement && errorElement.classList.contains('invalid-feedback')) {
                    errorElement.remove();
                }

                return true;
            }

            function showPasswordError(message) {
                password.classList.remove('is-valid');
                password.classList.add('is-invalid');

                // Hapus error message sebelumnya
                let existingError = password.parentElement.nextElementSibling;
                if (existingError && existingError.classList.contains('invalid-feedback')) {
                    existingError.remove();
                }

                // Tambahkan error message baru
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback d-block';
                errorDiv.textContent = message;
                password.parentElement.after(errorDiv);
            }

            // Validasi konfirmasi password
            if (passwordConfirmation) {
                passwordConfirmation.addEventListener('blur', function() {
                    validatePasswordConfirmation();
                });

                passwordConfirmation.addEventListener('input', function() {
                    if (this.value.length > 0 && this.value === password.value) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else {
                        this.classList.remove('is-valid', 'is-invalid');
                    }
                });
            }

            function validatePasswordConfirmation() {
                if (passwordConfirmation.value.length === 0) {
                    showConfirmPasswordError('Konfirmasi kata sandi wajib diisi.');
                    return false;
                }

                if (passwordConfirmation.value !== password.value) {
                    showConfirmPasswordError('Konfirmasi kata sandi tidak cocok.');
                    return false;
                }

                passwordConfirmation.classList.remove('is-invalid');
                passwordConfirmation.classList.add('is-valid');
                const errorElement = passwordConfirmation.parentElement.nextElementSibling;
                if (errorElement && errorElement.classList.contains('invalid-feedback')) {
                    errorElement.remove();
                }

                return true;
            }

            function showConfirmPasswordError(message) {
                passwordConfirmation.classList.remove('is-valid');
                passwordConfirmation.classList.add('is-invalid');

                let existingError = passwordConfirmation.parentElement.nextElementSibling;
                if (existingError && existingError.classList.contains('invalid-feedback')) {
                    existingError.remove();
                }

                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback d-block';
                errorDiv.textContent = message;
                passwordConfirmation.parentElement.after(errorDiv);
            }

            // Form submission validation
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(event) {
                    const isPasswordValid = validatePassword(password.value);
                    const isConfirmValid = validatePasswordConfirmation();

                    if (!isPasswordValid || !isConfirmValid) {
                        event.preventDefault();
                        event.stopPropagation();

                        // Scroll to first error
                        const firstError = document.querySelector('.is-invalid');
                        if (firstError) {
                            firstError.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                            firstError.focus();
                        }
                    }
                });
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            @if (\Illuminate\Support\Facades\Session::get('failed_message'))
                $.alert({
                    title: 'Peringatan',
                    content: '{{ \Illuminate\Support\Facades\Session::get('failed_message') }}',
                    type: 'red',
                    theme: 'material',
                    backgroundDismissAnimation: 'shake',
                    onOpenBefore: function() {
                        this.$title.css("color", "black");
                        this.$content.css("color", "black");
                    }
                });
            @endif

            @if (\Illuminate\Support\Facades\Session::get('success_message'))
                $.alert({
                    title: 'Informasi',
                    content: '{{ \Illuminate\Support\Facades\Session::get('success_message') }}',
                    type: 'green',
                    theme: 'material',
                    backgroundDismissAnimation: 'shake',
                    onOpenBefore: function() {
                        this.$title.css("color", "black");
                        this.$content.css("color", "black");
                    }
                });
            @endif
        });
    </script>
</body>

</html>
