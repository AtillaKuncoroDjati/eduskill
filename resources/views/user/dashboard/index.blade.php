{{-- resources/views/user/dashboard/index.blade.php --}}
@extends('template', ['title' => 'Dashboard'])

@section('content')
    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
        <div class="flex-grow-1">
            <h4 class="fs-18 text-uppercase fw-bold mb-0">Dashboard</h4>
        </div>
    </div>

    @if (auth()->user()->permission === 'user')
        <div class="row g-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-body py-4">
                        <h4 class="mb-1">Selamat datang, {{ auth()->user()->name }}! 👋</h4>
                        <p class="text-muted mb-0">Semoga harimu menyenangkan dan penuh semangat untuk belajar!</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-2">
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

        <div class="row g-2">
            <div class="col-12">
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
                                                            <a href="#" class="btn btn-sm btn-success w-100 mb-1">
                                                                <i class="ti ti-certificate me-1"></i>Sertifikat
                                                            </a>
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

            <div class="col-12">
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('progressChart');

        const progressData = @json($progressData);
        const labels = progressData.map(item => item.date);
        const data = progressData.map(item => item.count);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Materi Diselesaikan',
                    data: data,
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
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        callbacks: {
                            label: function(context) {
                                return 'Materi: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
@endpush
