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
                                <th class="text-center text-uppercase">Thumbnail</th>
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
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/jquery-confirm/jquery-confirm.min.js') }}"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
    <script src="{{ asset('assets/js/pages/admin/kursus/page.js') }}"></script>
@endpush
