@extends('template', ['title' => 'Tambah Kursus'])

@section('content')
    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
        <div class="flex-grow-1">
            <h4 class="fs-18 text-uppercase fw-bold mb-0">Tambah Kursus</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ route('admin.kursus.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-info-circle me-2"></i>Informasi Dasar
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="thumbnail" class="form-label">
                                    Thumbnail<span class="text-danger ms-1">*</span>
                                </label>
                                <input type="file" id="thumbnail" name="thumbnail"
                                    class="form-control @error('thumbnail') is-invalid @enderror">

                                @error('thumbnail')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="title" class="form-label">
                                    Judul<span class="text-danger ms-1">*</span>
                                </label>
                                <input type="text" id="title" name="title"
                                    class="form-control @error('title') is-invalid @enderror" autocomplete="off">

                                @error('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="short_description" class="form-label">
                                    Deskripsi Singkat<span class="text-danger ms-1">*</span>
                                </label>
                                <textarea id="short_description" name="short_description"
                                    class="form-control @error('short_description') is-invalid @enderror" rows="2" autocomplete="off" required></textarea>

                                @error('short_description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">
                                    Deskripsi Lengkap<span class="text-danger ms-1">*</span>
                                </label>
                                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror"
                                    rows="5" autocomplete="off" required></textarea>

                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="category" class="form-label">
                                    Kategori<span class="text-danger ms-1">*</span>
                                </label>
                                <select class="form-control category @error('category') is-invalid @enderror" id="category"
                                    name="category">
                                    <option></option>
                                    <option value="programming">Programming</option>
                                    <option value="design">Design</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="business">Business</option>
                                    <option value="cybersecurity">Cybersecurity</option>
                                </select>

                                @error('category')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="difficulty" class="form-label">
                                    Tingkat Kesulitan<span class="text-danger ms-1">*</span>
                                </label>
                                <select class="form-control difficulty @error('difficulty') is-invalid @enderror"
                                    id="difficulty" name="difficulty">
                                    <option></option>
                                    <option value="pemula">Pemula</option>
                                    <option value="menengah">Menengah</option>
                                    <option value="lanjutan">Lanjutan</option>
                                </select>

                                @error('difficulty')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="certificate" class="form-label">
                                    Sertifikat<span class="text-danger ms-1">*</span>
                                </label>
                                <select class="form-control certificate @error('certificate') is-invalid @enderror"
                                    id="certificate" name="certificate">
                                    <option></option>
                                    <option value="1">Ya</option>
                                    <option value="0">Tidak</option>
                                </select>

                                @error('certificate')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="status" class="form-label">
                                    Status
                                </label>
                                <select class="form-control status @error('status') is-invalid @enderror" id="status"
                                    name="status">
                                    <option></option>
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                    <option value="arsip">Arsip</option>
                                </select>

                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <button type="reset" class="btn btn-soft-danger me-2">Reset</button>
                        <button type="submit" class="btn btn-soft-primary">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.category').select2({
                placeholder: '- Pilih Kategori -',
                minimumResultsForSearch: Infinity
            });
        });

        $(document).ready(function() {
            $('.difficulty').select2({
                placeholder: '- Pilih Tingkat Kesulitan -',
                minimumResultsForSearch: Infinity
            });
        });

        $(document).ready(function() {
            $('.certificate').select2({
                placeholder: 'Dengan Sertifikat?',
                minimumResultsForSearch: Infinity
            });
        });

        $(document).ready(function() {
            $('.status').select2({
                placeholder: '- Pilih Status -',
                minimumResultsForSearch: Infinity
            });
        });
    </script>
@endpush
