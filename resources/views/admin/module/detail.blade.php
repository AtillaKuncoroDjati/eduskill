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
                            <div style="width: 100%">
                                <strong>{{ $materi->title }}</strong>

                                <div class="text-muted mt-1">
                                    <div class="mt-2">
                                        {!! nl2br(e($materi->content)) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-soft-primary" data-bs-toggle="modal"
                                    data-bs-target="#edit-material-modal" data-id="{{ $materi->id }}"
                                    data-title="{{ $materi->title }}" data-type="{{ $materi->type }}"
                                    data-content="{{ $materi->content }}">
                                    Edit
                                </button>

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
                    <input type="hidden" name="module_id" value="{{ $module->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Materi Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <label class="form-label">Judul Materi</label>
                        <input type="text" name="title" class="form-control" placeholder="Masukkan judul materi">

                        <label class="form-label mt-3">Tipe Konten</label> <select name="type" class="form-control">
                            <option value="text">Teks</option>
                            <option value="quiz">Kuis</option>
                        </select>

                        <label class="form-label mt-3">Konten</label>
                        <textarea name="content" class="form-control" rows="4" placeholder="Masukkan konten materi"></textarea>

                        <div id="quiz-builder" class="mt-3 d-none">

                            <h6>Pertanyaan Kuis</h6>
                            <div id="questions-container"></div>

                            <button type="button" class="btn btn-sm btn-soft-primary mt-2" id="add-question">
                                Tambah Pertanyaan
                            </button>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-soft-danger" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-soft-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ================= MODAL EDIT MATERI ================= --}}
    <div id="edit-material-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.content.update') }}">
                    @csrf
                    <input type="hidden" name="id" id="edit-id">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Materi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <label class="form-label">Judul Materi</label>
                        <input type="text" name="title" id="edit-title" class="form-control">

                        <label class="form-label mt-3">Tipe Konten</label>
                        <select name="type" id="edit-type" class="form-control">
                            <option value="text">Teks</option>
                            <option value="quiz">Kuis</option>
                        </select>

                        <label class="form-label mt-3">Konten</label>
                        <textarea name="content" id="edit-content" class="form-control" rows="4"></textarea>

                        <div id="edit-quiz-builder" class="mt-3 d-none">

                            <h6>Pertanyaan Kuis</h6>
                            <div id="edit-questions-container"></div>

                            <button type="button" class="btn btn-sm btn-soft-primary mt-2" id="edit-add-question">
                                Tambah Pertanyaan
                            </button>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-soft-danger" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-soft-primary">Update</button>
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

        $('select[name="type"]').on('change', function() {
            if ($(this).val() === 'quiz') {
                $('#quiz-builder').removeClass('d-none');
            } else {
                $('#quiz-builder').addClass('d-none');
            }
        });

        let qIndex = 0;

        $('#add-question').on('click', function() {
            const qHtml = `
                            <div class="card p-2 mb-2 question-block" data-index="${qIndex}">
                                <label>Pertanyaan</label>
                                <input type="text" name="questions[${qIndex}][text]" class="form-control mb-2">

                                <div class="options-container"></div>

                                <button type="button" class="btn btn-sm btn-secondary add-option" data-q="${qIndex}">
                                    Tambah Jawaban
                                </button>
                            </div>
                        `;

            $('#questions-container').append(qHtml);
            qIndex++;
        });

        $(document).on('click', '.add-option', function() {
            let q = $(this).data('q');
            let optionCount = $(`.question-block[data-index="${q}"] .option-block`).length;

            const optHtml = `
                            <div class="input-group mb-2 option-block">
                                <span class="input-group-text">
                                    <input type="checkbox" name="questions[${q}][options][${optionCount}][is_correct]">
                                </span>
                                <input type="text" class="form-control"
                                    name="questions[${q}][options][${optionCount}][text]"
                                    placeholder="Isi jawaban">
                                <button type="button" class="btn btn-danger remove-option">X</button>
                            </div>
                        `;

            $(`.question-block[data-index="${q}"] .options-container`).append(optHtml);
        });

        $(document).on('click', '.remove-option', function() {
            $(this).closest('.option-block').remove();
        });


        $('#edit-material-modal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);

            let id = button.data('id');
            let type = button.data('type');

            $('#edit-id').val(id);
            $('#edit-title').val(button.data('title'));
            $('#edit-type').val(type);
            $('#edit-content').val(button.data('content'));

            $('#edit-questions-container').html('');
            $('#edit-quiz-builder').addClass('d-none');

            if (type !== 'quiz') return;

            $('#edit-quiz-builder').removeClass('d-none');

            $.get("{{ url('admin/kursus/content') }}/" + id + "/quiz-data", function(res) {

                let qIndex = 0;

                res.questions.forEach((q, i) => {

                    let qHtml = `
                                <div class="card p-2 mb-2 edit-question-block" data-index="${i}">
                                    <label>Pertanyaan</label>
                                    <input type="text" name="questions[${i}][text]" value="${q.question}" class="form-control mb-2">

                                    <div class="edit-options-container">
                            `;

                    q.options.forEach((opt, oi) => {
                        qHtml += `
                                <div class="input-group mb-2 option-block">
                                    <span class="input-group-text">
                                        <input type="checkbox" name="questions[${i}][options][${oi}][is_correct]" ${opt.is_correct ? 'checked' : ''}>
                                    </span>
                                    <input type="text" name="questions[${i}][options][${oi}][text]"
                                        value="${opt.option_text}" class="form-control">
                                    <button type="button" class="btn btn-danger remove-option">X</button>
                                </div>
                            `;
                    });

                    qHtml += `
                            </div>

                            <button type="button" class="btn btn-sm btn-secondary edit-add-option" data-q="${i}">
                                Tambah Jawaban
                            </button>
                        </div>
                    `;

                    $('#edit-questions-container').append(qHtml);
                    qIndex = i + 1;
                });

                window.editQIndex = qIndex;
            });
        });
    </script>
@endpush
