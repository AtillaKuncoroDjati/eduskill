@extends('template', ['title' => 'Dashboard'])

@section('content')
    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
        <div class="flex-grow-1">
            <h4 class="fs-18 text-uppercase fw-bold mb-0">Dashboard</h4>
        </div>
    </div>

    @if (auth()->user()->permission === 'admin')
        {{-- ==================== ADMIN DASHBOARD ==================== --}}
        <div class="row g-3">
            <div class="col-12">
                <div class="card shadow-sm bg-primary text-white">
                    <div class="card-body py-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="ti ti-shield-check" style="font-size: 48px;"></i>
                            </div>
                            <div>
                                <h3 class="mb-1 text-white">Selamat datang, {{ auth()->user()->name }}!</h3>
                                <p class="mb-0 opacity-75">Administrator Panel - Kelola platform pembelajaran Anda</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div
                                    class="avatar-sm rounded-circle bg-soft-primary d-flex align-items-center justify-content-center">
                                    <i class="ti ti-book-2 fs-2 text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Total Kursus</p>
                                <h3 class="mb-0 fw-bold">{{ $stats['total_kursus'] }}</h3>
                                <small class="text-success">
                                    <i class="ti ti-check"></i> {{ $stats['kursus_aktif'] }} Aktif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div
                                    class="avatar-sm rounded-circle bg-soft-info d-flex align-items-center justify-content-center">
                                    <i class="ti ti-users fs-2 text-info"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Total User</p>
                                <h3 class="mb-0 fw-bold">{{ $stats['total_user'] }}</h3>
                                <small class="text-muted">Pengguna terdaftar</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div
                                    class="avatar-sm rounded-circle bg-soft-success d-flex align-items-center justify-content-center">
                                    <i class="ti ti-user-check fs-2 text-success"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Total Enrollment</p>
                                <h3 class="mb-0 fw-bold">{{ $stats['total_enrollment'] }}</h3>
                                <small class="text-muted">Pendaftaran kursus</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div
                                    class="avatar-sm rounded-circle bg-soft-warning d-flex align-items-center justify-content-center">
                                    <i class="ti ti-chart-line fs-2 text-warning"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Avg. Completion</p>
                                <h3 class="mb-0 fw-bold">{{ $stats['completion_rate'] }}%</h3>
                                <small class="text-muted">Tingkat penyelesaian</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-trending-up me-1"></i> Enrollment Trend (6 Bulan Terakhir)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height: 300px;">
                            <canvas id="enrollmentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-percentage me-1"></i> Completion Rate
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach ($completionData as $data)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="fw-bold">{{ $data['title'] }}</small>
                                    <small class="text-muted">{{ $data['rate'] }}%</small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar {{ $data['rate'] >= 70 ? 'bg-success' : ($data['rate'] >= 40 ? 'bg-warning' : 'bg-danger') }}"
                                        style="width: {{ $data['rate'] }}%"></div>
                                </div>
                                <small class="text-muted">{{ $data['completed'] }}/{{ $data['total'] }} selesai</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-flame me-1"></i> Kursus Populer
                        </h5>
                        <a href="{{ route('admin.kursus.index') }}" class="btn btn-sm btn-soft-primary">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kursus</th>
                                        <th class="text-center">Peserta</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($kursusPopuler as $index => $kursus)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('uploads/kursus/' . $kursus->thumbnail) }}"
                                                        class="rounded me-2"
                                                        style="width: 40px; height: 40px; object-fit: cover;">
                                                    <div>
                                                        <div class="fw-bold small">{{ Str::limit($kursus->title, 30) }}
                                                        </div>
                                                        <small class="text-muted">{{ ucfirst($kursus->category) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ $kursus->user_courses_count }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if ($kursus->status === 'aktif')
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($kursus->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">Belum ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-activity me-1"></i> Aktivitas Terbaru
                        </h5>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        @forelse ($recentActivities as $activity)
                            <div class="d-flex mb-3 pb-3 border-bottom">
                                <div class="flex-shrink-0 me-3">
                                    <div
                                        class="avatar-sm rounded-circle bg-soft-{{ $activity['color'] }} d-flex align-items-center justify-content-center">
                                        <i class="ti {{ $activity['icon'] }} text-{{ $activity['color'] }}"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 small">
                                        <strong>{{ $activity['user'] }}</strong>
                                        @if ($activity['type'] === 'enrollment')
                                            mendaftar kursus
                                        @else
                                            menyelesaikan kursus
                                        @endif
                                        <strong>{{ Str::limit($activity['kursus'], 30) }}</strong>
                                    </p>
                                    <small class="text-muted">
                                        <i class="ti ti-clock"></i>
                                        {{ $activity['date']->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-muted py-3">Belum ada aktivitas</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- ==================== USER DASHBOARD ==================== --}}

        <div class="row g-3 mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body py-4">
                        <h4 class="mb-1">Selamat datang, {{ auth()->user()->name }}! 👋</h4>
                        <p class="text-muted mb-0">Semoga harimu menyenangkan dan penuh semangat untuk belajar!</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="card border-start border-primary border-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="ti ti-book-2 fs-1 text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-0 fw-bold">{{ $stats['total_kursus'] }}</h3>
                                <p class="text-muted mb-0">Total Kursus</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-start border-warning border-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="ti ti-clock fs-1 text-warning"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-0 fw-bold">{{ $stats['kursus_berlangsung'] }}</h3>
                                <p class="text-muted mb-0">Sedang Belajar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-start border-success border-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="ti ti-circle-check fs-1 text-success"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-0 fw-bold">{{ $stats['kursus_selesai'] }}</h3>
                                <p class="text-muted mb-0">Kursus Selesai</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-start border-info border-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="ti ti-file-check fs-1 text-info"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-0 fw-bold">{{ $stats['total_materi_selesai'] }}</h3>
                                <p class="text-muted mb-0">Materi Selesai</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Aktivitas Belajar --}}
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-notebook me-1"></i>Aktivitas Belajar
                            </h5>
                            <a href="{{ route('user.kursus.index') }}" class="btn btn-sm btn-primary">
                                <i class="ti ti-plus me-1"></i>Jelajahi Kursus Lainnya
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($semuaKursus->isEmpty())
                            <div class="text-center py-5">
                                <i class="ti ti-book-off text-muted" style="font-size: 64px;"></i>
                                <h5 class="mt-3 mb-2">Belum Ada Kursus</h5>
                                <p class="text-muted mb-3">Anda belum mengikuti kursus apapun</p>
                                <a href="{{ route('user.kursus.index') }}" class="btn btn-primary">
                                    <i class="ti ti-search me-1"></i>Jelajahi Kursus
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th width="60">#</th>
                                            <th>Kursus</th>
                                            <th width="200" class="text-center">Progress</th>
                                            <th width="150" class="text-center">Status</th>
                                            <th width="200" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($semuaKursus as $index => $userCourse)
                                            <tr>
                                                <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('uploads/kursus/' . $userCourse->kursus->thumbnail) }}"
                                                            alt="Thumbnail" class="rounded me-3"
                                                            style="width: 60px; height: 60px; object-fit: cover;">
                                                        <div>
                                                            <h6 class="mb-1">{{ $userCourse->kursus->title }}</h6>
                                                            <small class="text-muted">
                                                                <i class="ti ti-calendar me-1"></i>
                                                                Terdaftar: {{ $userCourse->enrolled_at->format('d M Y') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($userCourse->status === 'completed')
                                                        <div class="text-center">
                                                            <i class="ti ti-trophy text-success fs-2"></i>
                                                            <p class="mb-0 small text-success fw-bold">100% Selesai</p>
                                                        </div>
                                                    @else
                                                        <div>
                                                            <div class="d-flex justify-content-between mb-1">
                                                                <small class="text-muted">Progress</small>
                                                                <small
                                                                    class="fw-bold text-primary">{{ $userCourse->progress_percentage }}%</small>
                                                            </div>
                                                            <div class="progress" style="height: 8px;">
                                                                <div class="progress-bar bg-primary" role="progressbar"
                                                                    style="width: {{ $userCourse->progress_percentage }}%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($userCourse->status === 'enrolled')
                                                        <span class="badge bg-info">Terdaftar</span>
                                                    @elseif($userCourse->status === 'in_progress')
                                                        <span class="badge bg-warning">Sedang Belajar</span>
                                                    @elseif($userCourse->status === 'completed')
                                                        <span class="badge bg-success">Selesai</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($userCourse->status === 'completed')
                                                        @if ($userCourse->kursus->certificate)
                                                            <div class="d-grid gap-1">
                                                                <a href="{{ route('user.certificate.preview', $userCourse->id) }}"
                                                                    class="btn btn-sm btn-success w-100">
                                                                    <i class="ti ti-certificate me-1"></i>Sertifikat
                                                                </a>
                                                                <a href="{{ route('user.certificate.download', $userCourse->id) }}"
                                                                    class="btn btn-sm btn-primary w-100">
                                                                    <i class="ti ti-download me-1"></i>Download
                                                                </a>
                                                            </div>
                                                        @else
                                                            <span class="text-muted small">Tanpa Sertifikat</span>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('user.kursus.learn', $userCourse->kursus->id) }}"
                                                            class="btn btn-sm btn-primary w-100">
                                                            <i class="ti ti-player-play me-1"></i>Lanjutkan Belajar
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-chart-line me-1"></i>Statistik 7 Hari Terakhir
                        </h5>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height: 300px;">
                            <canvas id="progressChart"></canvas>
                        </div>
                        <div class="mt-3 text-center">
                            <p class="text-muted mb-0 small">
                                Total materi yang diselesaikan dalam 7 hari terakhir:
                                <span class="fw-bold text-primary">
                                    {{ collect($progressData)->sum('count') }} materi
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('styles')
    <style>
        .avatar-sm {
            width: 48px;
            height: 48px;
        }

        .bg-soft-primary {
            background-color: rgba(13, 110, 253, 0.1);
        }

        .bg-soft-info {
            background-color: rgba(13, 202, 240, 0.1);
        }

        .bg-soft-success {
            background-color: rgba(25, 135, 84, 0.1);
        }

        .bg-soft-warning {
            background-color: rgba(255, 193, 7, 0.1);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        @if (auth()->user()->permission === 'admin')
            // Chart untuk Enrollment Trend (Admin)
            const enrollmentCtx = document.getElementById('enrollmentChart');
            const enrollmentData = @json($enrollmentData);

            new Chart(enrollmentCtx, {
                type: 'line',
                data: {
                    labels: enrollmentData.map(item => item.month),
                    datasets: [{
                        label: 'Enrollment',
                        data: enrollmentData.map(item => item.count),
                        borderColor: 'rgb(13, 110, 253)',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        @else
            // Chart untuk User Progress
            const ctx = document.getElementById('progressChart');
            const progressData = @json($progressData);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: progressData.map(item => item.date),
                    datasets: [{
                        label: 'Materi Diselesaikan',
                        data: progressData.map(item => item.count),
                        backgroundColor: 'rgba(13, 110, 253, 0.8)',
                        borderColor: 'rgb(13, 110, 253)',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        @endif
    </script>
@endpush
