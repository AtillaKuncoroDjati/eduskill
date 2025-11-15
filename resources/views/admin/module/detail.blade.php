@extends('template', ['title' => 'Detail Modul'])

@section('content')
    <div class="page-title-head d-flex align-items-center justify-content-between mb-3">
        <h4 class="fs-18 text-uppercase fw-bold mb-0">{{ $module->title }}</h4>

        <a href="{{ route('admin.kursus.edit', $module->kursus_id) }}#pane-modul" class="btn btn-soft-danger">
            Kembali ke Kursus
        </a>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Materi</h5>

            <button class="btn btn-soft-primary" data-bs-toggle="modal" data-bs-target="#create-material-modal">
                Tambah Materi
            </button>
        </div>

        <div class="card-body">
            <div id="materi-list" data-plugin="dragula">
                @forelse ($module->contents as $materi)
                    <div class="card p-3 mb-2" data-id="{{ $materi->id }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>{{ $materi->title }}</strong>

                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.content.edit', $materi->id) }}"
                                    class="btn btn-sm btn-primary">Edit</a>

                                <form method="POST" action="{{ route('admin.content.delete') }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $materi->id }}">
                                    <button type="submit" class="btn btn-sm btn-soft-danger"
                                        onclick="return confirm('Hapus materi ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center my-3">Belum ada materi.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ================= MODAL TAMBAH MATERI ================= --}}
    <div id="create-material-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.content.store', $module->id) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Materi Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <label class="form-label">Judul Materi</label>
                        <input type="text" name="title" class="form-control" placeholder="Masukkan judul materi">

                        <label class="form-label mt-3">Tipe Konten</label>
                        <select name="type" class="form-control">
                            <option value="video">Video</option>
                            <option value="text">Teks</option>
                            <option value="file">File / Dokumen</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-soft-danger" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-soft-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/vendor/dragula/dragula.min.js') }}"></script>

    <script>
        // Drag & Drop Materi
        dragula([document.getElementById("materi-list")]).on('drop', function() {
            let orders = {};
            $("#materi-list .card").each(function(index) {
                orders[index] = $(this).data("id");
            });

            $.post("{{ route('admin.content.updateOrder') }}", {
                _token: "{{ csrf_token() }}",
                orders: orders
            });
        });
    </script>
@endpush
