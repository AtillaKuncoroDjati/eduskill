@extends('template', ['title' => 'Preview Sertifikat'])

@section('content')
    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-18 text-uppercase fw-bold mb-0">Preview Sertifikat</h4>
        </div>
        <div>
            <a href="{{ route('user.kursus.learn', $userCourse->kursus_id) }}" class="btn btn-soft-secondary me-2">
                <i class="ti ti-arrow-left me-1"></i>Kembali ke Kursus
            </a>
            <a href="{{ route('user.certificate.download', $userCourse->id) }}" class="btn btn-soft-dark">
                <i class="ti ti-download me-1"></i>Download Sertifikat
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-body p-4">
                    {{-- Certificate Preview Image --}}
                    <div class="text-center mb-4">
                        <img src="{{ route('user.certificate.show', $userCourse->id) }}"
                            alt="Sertifikat {{ $userCourse->kursus->title }}" class="img-fluid rounded shadow"
                            style="max-width: 100%; height: auto;">
                    </div>

                    {{-- Certificate Info --}}
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="border rounded p-3 text-center bg-light">
                                <small class="text-muted d-block mb-1">Penerima</small>
                                <h6 class="mb-0 fw-bold">{{ auth()->user()->name }}</h6>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 text-center bg-light">
                                <small class="text-muted d-block mb-1">Kursus</small>
                                <h6 class="mb-0 fw-bold">{{ $userCourse->kursus->title }}</h6>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 text-center bg-light">
                                <small class="text-muted d-block mb-1">Tanggal Selesai</small>
                                <h6 class="mb-0 fw-bold">
                                    {{ $userCourse->completed_at->locale('id')->translatedFormat('d F Y') }}</h6>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="alert alert-info mb-0">
                        <div class="d-flex align-items-start">
                            <i class="ti ti-info-circle fs-4 me-3"></i>
                            <div>
                                <h6 class="alert-heading mb-2">Informasi Sertifikat</h6>
                                <ul class="mb-0 ps-3">
                                    <li>Sertifikat ID: <strong>{{ $certificateId }}</strong></li>
                                    <li>Klik tombol "Download Sertifikat" untuk menyimpan file PNG</li>
                                    <li>Sertifikat dapat digunakan sebagai bukti penyelesaian kursus</li>
                                    <li>Simpan sertifikat dengan baik untuk keperluan verifikasi</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
