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
                        Reset Kata Sandi
                    </h4>

                    <p class="text-muted mb-4">Masukkan alamat email Anda untuk meminta reset kata sandi.</p>

                    <form method="POST" action="{{ route('password.reset') }}" class="text-start mb-3">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="email">
                                Alamat Email
                            </label>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Masukkan alamat email Anda" autocomplete="off"
                                value="{{ old('email') }}">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
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
    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- Page js -->
    <script>
        function onSubmit(token) {
            document.querySelector("form").submit();
        }
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
