@extends('template', ['title' => 'Backup Database'])

@section('content')
    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
        <div class="flex-grow-1">
            <h4 class="fs-18 text-uppercase fw-bold mb-1">Backup Database</h4>
            <span class="text-muted">
                Lakukan backup database secara berkala untuk melindungi data Anda dari kehilangan yang tidak terduga.
            </span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h5 class="fw-bold mb-2">Backup manual</h5>

                    <p class="text-muted">
                        Simpan file SQL <mark>db_backup-tanggal_waktu.sql</mark> yang berisi seluruh isi tabel. Anda dapat
                        menggunakannya untuk memulihkan konfigurasi jika terjadi perubahan yang tidak diinginkan
                        atau migrasi server.
                    </p>

                    <hr class="border-dark opacity-25">

                    <ul class="text-muted mb-3">
                        <li>Lakukan backup sebelum mengubah banyak konten sekaligus.</li>
                        <li>Simpan file di lokasi aman (misal storage terenkripsi atau repository internal).</li>
                        <li>Gunakan perintah SQL standar untuk mengembalikan data apabila diperlukan.</li>
                    </ul>

                    <a href="{{ route('system.backup.download') }}" class="btn btn-dark rounded-pill">
                        Unduh backup SQL
                    </a>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
