@extends('template', ['title' => 'Daftar Kursus'])

@section('content')
    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
        <div class="flex-grow-1">
            <h4 class="fs-18 text-uppercase fw-bold mb-0">Daftar Kursus</h4>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-md-4 col-lg-2">
                            <h3 class="card-title mb-0">
                                <i class="ti ti-book"></i> Kursus Tersedia
                            </h3>
                        </div>
                        <div class="col-12 col-md-8 col-lg-10">
                            <div class="d-flex gap-2">
                                <!-- Input Pencarian -->
                                <input type="text" id="search-kursus" class="form-control bg-light border-0 flex-grow-1"
                                    placeholder="Tulis kata kunci" />

                                <!-- Tombol Cari -->
                                <button class="btn btn-soft-dark btn-search">
                                    <i class="ti ti-search"></i>
                                    <span class="d-none d-md-inline ms-1">Cari</span>
                                </button>

                                <!-- Dropdown Filter -->
                                <div class="dropdown">
                                    <button class="btn btn-soft-secondary dropdown-toggle" type="button"
                                        id="filterKategori" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-filter"></i>
                                        <span class="d-none d-md-inline ms-1">Kategori</span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="filterKategori">
                                        <li>
                                            <a class="dropdown-item filter-kategori" href="#" data-kategori="all">
                                                <i class="ti ti-list"></i> Semua Kategori
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item filter-kategori" href="#"
                                                data-kategori="programming">
                                                <i class="ti ti-code"></i> Programming
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item filter-kategori" href="#" data-kategori="design">
                                                <i class="ti ti-palette"></i> Design
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item filter-kategori" href="#"
                                                data-kategori="marketing">
                                                <i class="ti ti-chart-bar"></i> Marketing
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item filter-kategori" href="#"
                                                data-kategori="business">
                                                <i class="ti ti-briefcase"></i> Bisnis
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item filter-kategori" href="#"
                                                data-kategori="cybersecurity">
                                                <i class="ti ti-shield-lock"></i> Cybersecurity
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3" id="kursus-list">
                        <div class="col-12 text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3 text-muted">Memuat data kursus...</p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center justify-content-md-end mt-4">
                        <nav aria-label="Page navigation">
                            <ul class="pagination mb-0">
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/pages/user/list-kursus/page.js') }}"></script>
@endpush
