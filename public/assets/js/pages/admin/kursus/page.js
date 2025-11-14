jQuery.index = {
    data: {
        table: null,
        searchInput: null,
        searchButton: null,
        statusFilter: 'all',
    },
    init: function () {
        var self = this;

        self.data.searchInput = $('#search-kursus');
        self.data.searchButton = $('.btn-search');

        self.initTable();
        self.setEvents();
    },
    initTable: function () {
        var self = this;

        self.data.table = $('#ohmytable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/admin/kursus/request/data',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (d) {
                    d.search = {
                        value: self.data.searchInput.val(),
                        regex: false
                    };
                    d.status_filter = self.data.statusFilter;
                    return d;
                }
            },
            columns: [
                {
                    data: null,
                    className: 'text-center fw-bold align-middle',
                    width: '5%',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'thumbnail',
                    className: 'text-center align-middle',
                    width: '15%',
                    render: function (thumbnail) {
                        return `
                                <img src="/uploads/kursus/${thumbnail}"
                                    alt="Thumbnail"
                                    class="img-fluid rounded"
                                    style="width:120px; height:120px; object-fit:cover;"/>
                            `;
                    }
                },
                {
                    data: null,
                    className: 'text-start align-middle',
                    width: '25%',
                    render: function (data) {
                        return `
                                <div>
                                    <strong>${data.title}</strong>
                                    <hr class="my-1"/>
                                    <small class="text-muted">${data.short_description}</small>
                                </div>
                            `;
                    }
                },
                {
                    data: null,
                    className: 'text-start align-middle',
                    width: '25%',
                    render: function (data) {
                        return `
                                <div>
                                    <b>Kategori:</b> ${data.category}<br>
                                    <b>Tingkat Kesulitan:</b> ${data.difficulty}<br>
                                    <b>Sertifikat:</b> ${data.certificate ? 'Ya' : 'Tidak'}
                                </div>
                            `;
                    }
                },
                {
                    data: 'status',
                    className: 'text-center align-middle',
                    width: '10%',
                    render: function (status) {
                        switch (status) {
                            case 'aktif':
                                return `<span class="badge bg-success">Aktif</span>`;
                            case 'nonaktif':
                                return `<span class="badge bg-warning text-dark">Nonaktif</span>`;
                            case 'arsip':
                                return `<span class="badge bg-secondary">Arsip</span>`;
                            default:
                                return `<span class="badge bg-dark">Tidak Diketahui</span>`;
                        }
                    }
                },
                {
                    data: null,
                    className: 'text-center align-middle',
                    width: '20%',
                    render: function (data) {
                        return `
                                <div class="d-grid gap-2">
                                    <button class="btn btn-sm btn-soft-warning btn-edit w-100"
                                        data-id="${data.id}" data-name="${data.title}">
                                        <i class="ti ti-pencil align-middle me-1 fs-18"></i> Ubah
                                    </button>
                                    <button class="btn btn-sm btn-soft-danger btn-hapus w-100"
                                        data-id="${data.id}" data-name="${data.title}">
                                        <i class="ti ti-trash align-middle me-1 fs-18"></i> Hapus
                                    </button>
                                    <button class="btn btn-sm btn-soft-primary btn-module w-100"
                                        data-id="${data.id}" data-name="${data.title}">
                                        <i class="ti ti-book-2 align-middle me-1 fs-18"></i> Akses Materi
                                    </button>
                                </div>
                            `;
                    }
                }
            ],
            scrollY: '500px',
            scrollCollapse: true,
            paging: true,
            pageLength: 10,
            lengthChange: false,
            searching: false,
            ordering: false,
            autoWidth: false,
            language: {
                emptyTable: "Tidak ditemukan data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                search: "Cari:",
                paginate: {
                    next: "Next",
                    previous: "Prev"
                }
            }
        });
    },
    setEvents: function () {
        var self = this;

        self.data.searchButton.on('click', function () {
            var button = $(this);
            button.attr('data-kt-indicator', 'on');
            button.prop('disabled', true);

            self.data.table.ajax.reload(function () {
                button.removeAttr('data-kt-indicator');
                button.prop('disabled', false);
            });
        });

        self.data.searchInput.keyup(function (e) {
            if (e.keyCode === 13) {
                self.data.searchButton.click();
            }
        });

        $('.filter-status').on('click', function (e) {
            e.preventDefault();
            self.data.statusFilter = $(this).data('status');
            self.data.table.ajax.reload();
        });

        $(document).on('click', '.btn-edit', function () {
            var id = $(this).data('id');
            window.location.href = '/admin/kursus/' + id + '/edit';
        });

        $("#ohmytable").on('click', 'button.btn-hapus', function () {
            var id = $(this).data("id");
            var name = $(this).data("name");

            $.confirm({
                title: 'Konfirmasi Hapus',
                type: 'orange',
                columnClass: 'small',
                content: 'Apakah Anda yakin ingin menghapus kursus <strong>' + name + '</strong>? Data yang telah dihapus tidak dapat dikembalikan lagi.',
                buttons: {
                    cancel: {
                        text: 'Tidak',
                        btnClass: 'btn-red',
                        keys: ['esc']
                    },
                    confirm: {
                        text: 'Ya, Saya Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $("#id-delete").val(id)
                            $("#form-delete").submit();
                        }
                    }
                }
            });
        });
    }
};

$(document).ready(function () {
    jQuery.index.init();
});
