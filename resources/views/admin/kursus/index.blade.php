@extends('template', ['title' => 'Daftar Kursus'])

@section('content')
    <div class="page-title-head d-flex justify-content-between align-items-center w-100 flex-wrap gap-2">
        <h4 class="fs-18 text-uppercase fw-bold mb-0">Daftar Kursus</h4>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <div class="row g-2">
                    <div class="col-12 col-md-auto d-flex flex-grow-1 align-items-center">
                        <!-- Input Pencarian -->
                        <input type="text" id="search-kursus" class="form-control bg-light border-0 me-2"
                            placeholder="Tulis kata kunci pencarian" />

                        <!-- Tombol Cari (Text disembunyikan di mobile) -->
                        <button class="btn btn-soft-dark btn-search me-2">
                            <i class="ti ti-search"></i>
                            <span class="d-none d-md-inline ms-1">Cari</span>
                        </button>

                        <div class="dropdown">
                            <button class="btn btn-soft-secondary dropdown-toggle" type="button" id="filterJenisIzin"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-filter"></i>
                                <span class="d-none d-md-inline ms-1">Filter</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="filterJenisIzin">
                                <li>
                                    <a class="dropdown-item filter-status" href="#" data-status="all">
                                        Semua
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item filter-status" href="#" data-status="aktif">
                                        Aktif
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item filter-status" href="#" data-status="nonaktif">
                                        Nonaktif
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item filter-status" href="#" data-status="arsip">
                                        Arsip
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <button type="buton" class="btn btn-soft-success w-100"
                            onclick="window.location.href='{{ route('admin.kursus.create') }}'">
                            <i class="ti ti-plus me-1"></i>Tambah Kursus
                        </button>
                    </div>
                </div>

                <div class="table-responsive mt-3">
                    <table id="ohmytable" class="table table-bordered table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center text-uppercase">Gambar</th>
                                <th class="text-center text-uppercase">Judul</th>
                                <th class="text-center text-uppercase">Informasi</th>
                                <th class="text-center text-uppercase">Status</th>
                                <th class="text-center">
                                    <i class="ti ti-category-2 fs-18"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <form id="form-delete" style="display: none;" action="{{ route('admin.kursus.delete') }}" method="POST">
        @csrf
        <input id="id-delete" name="id" type="hidden">
    </form>

    {{-- <div class="row">
        <div class="col-md-4">
            <div class="card d-block">
                <div class="dropdown position-absolute top-0 end-0 m-2">
                    <button type="button" class="btn btn-icon btn-sm btn-dark" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="ti ti-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="ti ti-pencil me-2"></i> Ubah
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="#">
                                <i class="ti ti-trash me-2"></i> Hapus
                            </a>
                        </li>
                    </ul>
                </div>
                <img class="card-img-top" src="https://placehold.jp/1280x720.png" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">
                        Belajar Laravel untuk Pemula
                    </h5>
                    <p class="card-text text-truncate" style="max-width: 450px;">
                        Kursus ini akan mengajarkan dasar-dasar framework Laravel, mulai dari instalasi hingga pembuatan
                        aplikasi sederhana.
                    </p>

                    <div class="text-start">
                        <span class="badge bg-dark fs-10 fs-lg-15">
                            <a href="#" class="text-light"># Pemrograman</a>
                        </span>
                        <span class="badge bg-warning fs-10 fs-lg-15">
                            Level: Pemula
                        </span>
                        <span class="badge bg-info fs-10 fs-lg-15">
                            Sertifikat: Tersedia
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card d-block">
                <div class="dropdown position-absolute top-0 end-0 m-2">
                    <button type="button" class="btn btn-icon btn-sm btn-dark" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="ti ti-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="ti ti-pencil me-2"></i> Ubah
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="#">
                                <i class="ti ti-trash me-2"></i> Hapus
                            </a>
                        </li>
                    </ul>
                </div>
                <img class="card-img-top" src="https://placehold.jp/1280x720.png" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">
                        Belajar Python untuk Pemula
                    </h5>
                    <p class="card-text text-truncate" style="max-width: 450px;">
                        Kursus ini akan mengajarkan dasar-dasar bahasa pemrograman Python, mulai dari sintaks hingga
                        pembuatan
                        program sederhana.
                    </p>

                    <div class="text-start">
                        <span class="badge bg-dark fs-10 fs-lg-15">
                            <a href="#" class="text-light"># Pemrograman</a>
                        </span>
                        <span class="badge bg-warning fs-10 fs-lg-15">
                            Level: Pemula
                        </span>
                        <span class="badge bg-info fs-10 fs-lg-15">
                            Sertifikat: Tersedia
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card d-block">
                <div class="dropdown position-absolute top-0 end-0 m-2">
                    <button type="button" class="btn btn-icon btn-sm btn-dark" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="ti ti-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="ti ti-pencil me-2"></i> Ubah
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="#">
                                <i class="ti ti-trash me-2"></i> Hapus
                            </a>
                        </li>
                    </ul>
                </div>
                <img class="card-img-top" src="https://placehold.jp/1280x720.png" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">
                        Belajar Mencintai Dia
                    </h5>
                    <p class="card-text text-truncate" style="max-width: 450px;">
                        Kursus ini akan mengajarkan dasar-dasar cinta, mulai dari memahami diri sendiri hingga
                        membangun hubungan yang sehat.
                    </p>

                    <div class="text-start">
                        <span class="badge bg-dark fs-10 fs-lg-15">
                            <a href="#" class="text-light"># Cinta</a>
                        </span>
                        <span class="badge bg-warning fs-10 fs-lg-15">
                            Level: Lanjutan
                        </span>
                        <span class="badge bg-info fs-10 fs-lg-15">
                            Sertifikat: Tersedia
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mt-2 mb-4">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item">
                        <a class="page-link" href="javascript: void(0);" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item"><a class="page-link" href="javascript: void(0);">1</a></li>
                    <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a></li>
                    <li class="page-item"><a class="page-link" href="javascript: void(0);">3</a></li>
                    <li class="page-item"><a class="page-link" href="javascript: void(0);">4</a></li>
                    <li class="page-item"><a class="page-link" href="javascript: void(0);">5</a></li>
                    <li class="page-item">
                        <a class="page-link" href="javascript: void(0);" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

    </div> --}}
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
    {{-- <style>
        .fs-10 {
            font-size: 10px !important;
        }

        /* Untuk desktop ke atas (≥992px) */
        @media (min-width: 992px) {
            .fs-lg-15 {
                font-size: 15px !important;
            }
        }
    </style> --}}
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/jquery-confirm/jquery-confirm.min.js') }}"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
    <script src="{{ asset('assets/js/pages/admin/kursus/page.js') }}"></script>
@endpush
