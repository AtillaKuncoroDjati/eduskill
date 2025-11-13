@extends('template', ['title' => auth()->user()->name])

@section('content')
    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
        <div class="flex-grow-1">
            <h4 class="fs-18 text-uppercase fw-bold mb-0">Profil Saya</h4>
        </div>
    </div>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block mb-3">
                        <img src="{{ asset('uploads/avatar/' . $user->avatar) }}" alt="User Avatar {{ $user->name }}"
                            class="rounded-circle border border-4 border-white shadow-sm" width="150" height="150"
                            style="object-fit: cover;">
                        <button type="button"
                            class="btn btn-primary btn-sm rounded-pill position-absolute bottom-0 end-0 shadow-sm"
                            style="width: 36px; height: 36px;" data-bs-toggle="modal" data-bs-target="#modal-avatar">
                            <i class="ti ti-camera-filled fs-5"></i>
                        </button>
                    </div>

                    <h4 class="mb-1 fw-bold">{{ $user->name }}</h4>
                    <span class="badge bg-primary text-capitalize mb-3 rounded-pill">
                        <span class="fw-semibold">
                            Bergabung sejak
                            {{ \Carbon\Carbon::parse($user->created_at)->locale('id')->translatedFormat('d F Y') }}
                        </span>
                    </span>
                </div>
            </div>

            <!-- Additional Info Card -->
            <div class="card mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-info-circle me-2"></i>Informasi Tambahan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-device-laptop text-muted me-2"></i>
                        <div>
                            <small class="text-muted d-block">Perangkat Aktif</small>
                            <span class="fw-semibold">{{ $user->active_device }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Profile Info -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-user-circle me-2"></i>Informasi Pribadi
                        </h5>
                        <div class="d-flex gap-2">
                            <button id="btn-cancel" class="btn btn-sm btn-soft-danger rounded-pill" style="display: none;">
                                Batal
                            </button>
                            <button id="btn-save-profile" class="btn btn-sm btn-soft-primary rounded-pill"
                                style="display: none;">
                                Simpan
                            </button>
                            <button id="btn-edit" class="btn btn-sm btn-soft-primary rounded-pill">
                                Ubah
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="profile-form" action="{{ route('user.profile.update_profile', $user) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-muted small mb-1">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control bg-light border-0 py-2 fw-semibold"
                                    value="{{ $user->name }}" disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small mb-1">Username</label>
                                <input type="text" name="username"
                                    class="form-control bg-light border-0 py-2 fw-semibold" value="{{ $user->username }}"
                                    disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small mb-1">Nomor Telepon</label>
                                <input type="text" name="phone" class="form-control bg-light border-0 py-2 fw-semibold"
                                    value="{{ $user->phone }}" disabled>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-muted small mb-1">Email</label>
                                <input type="email" name="email" class="form-control bg-light border-0 py-2 fw-semibold"
                                    value="{{ $user->email }}" disabled>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Additional Sections -->
            <div class="card mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-lock me-2"></i>Keamanan Akun
                        </h5>
                    </div>
                </div>
                <div class="card-body" style="padding: 26px">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-0 fw-semibold">Kata Sandi</h6>
                            <small class="text-muted" id="last-changed-text">
                                Terakhir diubah
                                <span
                                    id="last-changed-time">{{ \Carbon\Carbon::parse($user->password_changed_at)->locale('id')->diffForHumans() }}</span>
                            </small>
                        </div>
                        <button type="button" class="btn btn-sm btn-soft-info rounded-pill" data-bs-toggle="modal"
                            data-bs-target="#modal-password">
                            Ubah
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Section - Pengaturan Akun -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-settings me-2"></i>Pengaturan Akun
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Notifikasi -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-0 fw-semibold">Preferensi Akun</h6>
                                    <small class="text-muted">Kelola preferensi akun Anda</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-soft-primary rounded-pill"
                                    data-bs-toggle="offcanvas" data-bs-target="#account-settings-offcanvas">
                                    Kelola
                                </button>
                            </div>
                        </div>

                        <!-- Tema -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-0 fw-semibold">Preferensi Tema</h6>
                                    <small class="text-muted">Sesuaikan tema antarmuka pengguna Anda</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-soft-primary rounded-pill"
                                    data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas">
                                    Kelola
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Begin:: Account Settings -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="account-settings-offcanvas">
        <div class="d-flex align-items-center gap-2 px-3 py-3 offcanvas-header border-bottom border-dashed">
            <h5 class="flex-grow-1 mb-0">Preferensi Akun</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-0 h-100" data-simplebar>
            <div class="p-3">
                <h5 class="mb-3 fs-16 fw-bold">
                    Pengaturan Keamanan
                </h5>

                <!-- OTP Settings Form -->
                <form id="form-preferensi-akun" action="{{ route('user.profile.update_otp_setting', $user) }}"
                    method="POST">
                    @csrf
                    <div class="card mb-3 border">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Gunakan Kode OTP</h6>
                                    <small class="text-muted">Gunakan kode OTP untuk masuk</small>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="otp-switch"
                                            name="is_otp" {{ $user->is_otp ? 'checked' : '' }} data-switch="bool">
                                        <label class="form-check-label" for="otp-switch" data-on-label="On"
                                            data-off-label="Off"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <hr />

                <h5 class="mb-3 fs-16 fw-bold">
                    Pengaturan Notifikasi
                </h5>

                <!-- Email Notification Form -->
                <form id="form-email-notif" action="{{ route('user.profile.update_email_setting', $user) }}"
                    method="POST">
                    @csrf
                    <div class="card border">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Notifikasi Email</h6>
                                    <small class="text-muted">Terima pemberitahuan penting via email</small>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="email-notif-switch" name="is_email_notification_enabled"
                                            {{ $user->is_email_notification_enabled ? 'checked' : '' }}
                                            data-switch="bool">
                                        <label class="form-check-label" for="email-notif-switch" data-on-label="On"
                                            data-off-label="Off"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- WhatsApp Notification Form -->
                <form id="form-whatsapp-notif" action="{{ route('user.profile.update_whatsapp_setting', $user) }}"
                    method="POST">
                    @csrf
                    <div class="card border">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Notifikasi WhatsApp</h6>
                                    <small class="text-muted">Terima pemberitahuan penting via whatsapp</small>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="whatsapp-notif-switch" name="is_whatsapp_notification_enabled"
                                            {{ $user->is_whatsapp_notification_enabled ? 'checked' : '' }}
                                            data-switch="bool">
                                        <label class="form-check-label" for="whatsapp-notif-switch" data-on-label="On"
                                            data-off-label="Off"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <!-- End:: Account Settings -->

    <!-- Begin:: Improved Avatar Modal -->
    <div id="modal-avatar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="avatar-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-semibold" id="avatar-modalLabel">
                        Perbarui Foto Profil
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('user.profile.update_avatar', auth()->user()) }}" method="POST"
                    enctype="multipart/form-data" id="avatar-form">
                    @csrf
                    <div class="modal-body">
                        <div class="d-flex flex-column align-items-center">
                            <!-- Preview with better styling -->
                            <div class="position-relative mb-4" id="avatar-preview-container" style="display: none;">
                                <img id="avatar-preview" src="#" alt="Preview"
                                    class="img-thumbnail rounded-circle border-primary"
                                    style="width: 180px; height: 180px; object-fit: cover; border-width: 3px !important;">
                                <button type="button" id="btn-remove"
                                    class="btn btn-soft-danger btn-icon btn-sm position-absolute rounded-circle p-0"
                                    style="width: 30px; height: 30px; top: -10px; right: -10px; display: none;">
                                    <i class="ti ti-refresh fs-5"></i>
                                </button>
                            </div>

                            <!-- Upload area with enhanced dropzone styling -->
                            <div class="w-100 text-center">
                                <label for="avatar-fileinput" class="d-block cursor-pointer">
                                    <div class="border-2 border-dashed rounded-3 p-4 mb-3 transition-all"
                                        style="border-color: #dee2e6; transition: all 0.3s; background-color: rgba(108,117,125,0.1);"
                                        id="dropzone">
                                        <div class="avatar-upload-icon mb-3">
                                            <div class="icon-wrapper bg-soft-primary rounded-circle p-3 d-inline-flex">
                                                <i class="ti ti-cloud-upload fs-3 text-primary"></i>
                                            </div>
                                        </div>
                                        <h6 class="mb-1 fw-semibold">Tarik file ke sini atau klik untuk mengunggah</h6>
                                        <p class="text-muted small mb-2">Gambar beresolusi tinggi memberikan hasil terbaik
                                        </p>
                                        <div class="d-flex justify-content-center gap-2">
                                            <span class="badge bg-soft-primary text-primary">JPG</span>
                                            <span class="badge bg-soft-primary text-primary">PNG</span>
                                            <span class="badge bg-soft-primary text-primary">GIF</span>
                                        </div>
                                        <p class="text-muted small mt-2 mb-0">Ukuran maksimal file: 5MB</p>
                                    </div>
                                    <input type="file" id="avatar-fileinput" name="avatar" class="d-none"
                                        accept="image/jpeg,image/png,image/jpg,image/gif">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-dark rounded-pill px-4"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-outline-primary rounded-pill px-4" id="btn-save" disabled>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End:: Improved Avatar Modal -->

    <!-- Begin:: Modal Change Password -->
    <div id="modal-password" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="password-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-semibold" id="password-modalLabel">
                        Perbarui Kata Sandi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="passwordForm" method="POST" action="{{ route('user.profile.update_password', $user) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="current_password" class="form-label text-muted mb-1">
                                        Kata Sandi Saat Ini
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="current_password" id="current_password"
                                            class="form-control bg-light border-0" placeholder="Kata Sandi Saat Ini"
                                            required>
                                        <span class="input-group-text bg-light border-0 toggle-password"
                                            data-target="current_password">
                                            <i class="ti ti-eye-off fs-5 text-muted" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="new_password" class="form-label text-muted mb-1">
                                        Kata Sandi Baru
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="new_password" id="new_password"
                                            class="form-control bg-light border-0" placeholder="Kata Sandi Baru" required>
                                        <span class="input-group-text bg-light border-0 toggle-password"
                                            data-target="new_password">
                                            <i class="ti ti-eye-off fs-5 text-muted" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="confirm_password" class="form-label text-muted mb-1">
                                        Konfirmasi Kata Sandi Baru
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="new_password_confirmation" id="confirm_password"
                                            class="form-control bg-light border-0" placeholder="Konfirmasi Kata Sandi"
                                            required>
                                        <span class="input-group-text bg-light border-0 toggle-password"
                                            data-target="confirm_password">
                                            <i class="ti ti-eye-off fs-5 text-muted" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-dark rounded-pill px-4"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-outline-primary rounded-pill px-4" id="btn-save">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End:: Modal Change Password -->
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/pages/user/profile-page.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lastChangedTimeElement = document.getElementById('last-changed-time');
            const originalTime = "{{ $user->password_changed_at }}";

            function updateTime() {
                const now = new Date();
                const changedTime = new Date(originalTime);
                const diffInSeconds = Math.floor((now - changedTime) / 1000);

                let timeText;

                if (diffInSeconds < 60) {
                    timeText = `${diffInSeconds} detik yang lalu`;
                } else if (diffInSeconds < 3600) {
                    const minutes = Math.floor(diffInSeconds / 60);
                    timeText = `${minutes} menit yang lalu`;
                } else if (diffInSeconds < 86400) {
                    const hours = Math.floor(diffInSeconds / 3600);
                    timeText = `${hours} jam yang lalu`;
                } else {
                    const days = Math.floor(diffInSeconds / 86400);
                    timeText = `${days} hari yang lalu`;
                }

                lastChangedTimeElement.textContent = timeText;
            }

            updateTime();
            setInterval(updateTime, 1000);
        });
    </script>
@endpush
