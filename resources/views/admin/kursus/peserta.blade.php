@extends('template', ['title' => 'Peserta Kursus - ' . $kursus->title])

@section('content')
    <div class="page-title-head d-flex justify-content-between align-items-center w-100 flex-wrap gap-2">
        <div>
            <h4 class="fs-18 text-uppercase fw-bold mb-0">Peserta Kursus</h4>
            <p class="text-muted mb-0">{{ $kursus->title }}</p>
        </div>
        <a href="{{ route('admin.kursus.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card border-start border-primary border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-users fs-1 text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0 fw-bold">{{ $kursus->total_peserta }}</h3>
                            <p class="text-muted mb-0">Total Peserta</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-start border-warning border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-book-2 fs-1 text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0 fw-bold">{{ $kursus->peserta_aktif }}</h3>
                            <p class="text-muted mb-0">Sedang Belajar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-start border-success border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-certificate fs-1 text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0 fw-bold">{{ $kursus->peserta_selesai }}</h3>
                            <p class="text-muted mb-0">Sudah Selesai</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <div class="row g-2 mb-3">
                    <div class="col-12 col-md-auto d-flex flex-grow-1 align-items-center">
                        <input type="text" id="search-peserta" class="form-control bg-light border-0 me-2"
                            placeholder="Cari nama atau email peserta" />

                        <button class="btn btn-soft-dark btn-search me-2">
                            <i class="ti ti-search"></i>
                            <span class="d-none d-md-inline ms-1">Cari</span>
                        </button>

                        <div class="dropdown">
                            <button class="btn btn-soft-secondary dropdown-toggle" type="button" id="filterStatus"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-filter"></i>
                                <span class="d-none d-md-inline ms-1">Filter</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="filterStatus">
                                <li><a class="dropdown-item filter-status" href="#" data-status="all">Semua</a></li>
                                <li><a class="dropdown-item filter-status" href="#"
                                        data-status="enrolled">Terdaftar</a></li>
                                <li><a class="dropdown-item filter-status" href="#" data-status="in_progress">Sedang
                                        Belajar</a></li>
                                <li><a class="dropdown-item filter-status" href="#"
                                        data-status="completed">Selesai</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="peserta-table" class="table table-bordered table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center text-uppercase">Nama</th>
                                <th class="text-center text-uppercase">Email</th>
                                <th class="text-center text-uppercase">Progress</th>
                                <th class="text-center text-uppercase">Status</th>
                                <th class="text-center text-uppercase">Tanggal Daftar</th>
                                <th class="text-center text-uppercase">Tanggal Selesai</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
    <script src="{{ asset('assets/js/pages/admin/kursus/peserta.js') }}"></script>
@endpush
