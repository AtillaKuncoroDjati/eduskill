<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>Verification &mdash; {{ config('app.name') }}</title>
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

                    <h4 class="fw-semibold mb-2">Verifikasi Kode OTP</h4>

                    @php
                        $methods = session('otp_delivery', []);
                    @endphp

                    <p class="text-muted mb-4">
                        Kami telah mengirimkan kode verifikasi ke
                        @if (in_array('whatsapp', $methods) && in_array('email', $methods))
                            alamat email
                            <span class="text-primary fw-medium">
                                {{ substr($user->email, 0, 3) . '****' . strstr($user->email, '@') }}
                            </span>
                            dan nomor WhatsApp
                            <span class="text-primary fw-medium">
                                {{ substr($user->phone, 0, 4) . '****' . substr($user->phone, -3) }}
                            </span>
                        @elseif (in_array('whatsapp', $methods))
                            nomor WhatsApp
                            <span class="text-primary fw-medium">
                                {{ substr($user->phone, 0, 4) . '****' . substr($user->phone, -3) }}
                            </span>
                        @elseif (in_array('email', $methods))
                            alamat email
                            <span class="text-primary fw-medium">
                                {{ substr($user->email, 0, 3) . '****' . strstr($user->email, '@') }}
                            </span>
                        @else
                            alamat email atau nomor WhatsApp Anda.
                        @endif
                    </p>

                    <form id="otp-form" action="{{ route('otp.verification') }}" method="POST"
                        class="text-start mb-3">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('otp_verification_email') }}">
                        <label class="form-label" for="code">Masukkan 6 Digit Kode</label>
                        <div class="d-flex gap-2 mt-1 mb-3" id="otp-container">
                            <input type="text" name="otp[]" maxlength="1"
                                class="form-control text-center otp-input" required autofocus data-index="0">
                            <input type="text" name="otp[]" maxlength="1"
                                class="form-control text-center otp-input" required data-index="1">
                            <input type="text" name="otp[]" maxlength="1"
                                class="form-control text-center otp-input" required data-index="2">
                            <input type="text" name="otp[]" maxlength="1"
                                class="form-control text-center otp-input" required data-index="3">
                            <input type="text" name="otp[]" maxlength="1"
                                class="form-control text-center otp-input" required data-index="4">
                            <input type="text" name="otp[]" maxlength="1"
                                class="form-control text-center otp-input" required data-index="5">
                        </div>
                        <div class="mb-3 d-grid">
                            <button class="btn btn-primary" type="submit">
                                Verifikasi
                            </button>
                        </div>

                        <p class="mb-0 text-center">
                            Belum menerima kode?
                            <a href="#" class="link-primary fw-semibold text-decoration-underline"
                                onclick="event.preventDefault(); document.getElementById('resend-otp').submit();">
                                Kirim Ulang
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('otp.resend') }}" method="POST" id="resend-otp" style="display: none;">
        @csrf
    </form>

    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <!-- Plugins js -->
    <script src="{{ asset('assets/plugins/jquery-confirm/jquery-confirm.min.js') }}"></script>
    <!-- Page js -->
    <script>
        $(document).ready(function() {
            $('.otp-input').on('input', function(e) {
                var currentIndex = $(this).data('index');
                var value = $(this).val();

                if (!/^\d$/.test(value)) {
                    $(this).val('');
                    return;
                }

                if (value.length === 1) {
                    if (currentIndex < 5) {
                        $('.otp-input[data-index="' + (currentIndex + 1) + '"]').focus();
                    }

                    checkAndSubmitOTP();
                }
            });

            $('.otp-input').on('keydown', function(e) {
                var currentIndex = $(this).data('index');

                if (e.key === 'Backspace' && $(this).val().length === 0) {
                    if (currentIndex > 0) {
                        $('.otp-input[data-index="' + (currentIndex - 1) + '"]').focus();
                    }
                }
            });

            $('#otp-container').on('paste', function(e) {
                e.preventDefault();
                var clipboardData = e.originalEvent.clipboardData || window.clipboardData;
                var pastedData = clipboardData.getData('text');
                var otpDigits = pastedData.replace(/\D/g, '').split('').slice(0, 6);

                $('.otp-input').each(function(index) {
                    if (otpDigits[index]) {
                        $(this).val(otpDigits[index]);
                    }
                });

                var lastFilledIndex = otpDigits.length - 1;
                if (lastFilledIndex < 5) {
                    $('.otp-input[data-index="' + (lastFilledIndex + 1) + '"]').focus();
                }

                if (otpDigits.length === 6) checkAndSubmitOTP();
            });

            function checkAndSubmitOTP() {
                var allFilled = true;
                $('.otp-input').each(function() {
                    if ($(this).val().length === 0) {
                        allFilled = false;
                        return false;
                    }
                });

                if (allFilled) {
                    $('#otp-form').submit()
                }
            }

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
