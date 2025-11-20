jQuery.peserta = {
    data: {
        table: null,
        searchInput: null,
        searchButton: null,
        statusFilter: 'all',
        kursusId: null,
    },
    init: function (kursusId) {
        var self = this;

        self.data.kursusId = kursusId;
        self.data.searchInput = $('#search-peserta');
        self.data.searchButton = $('.btn-search');

        self.initTable();
        self.setEvents();
    },
    initTable: function () {
        var self = this;

        self.data.table = $('#peserta-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/admin/kursus/' + self.data.kursusId + '/peserta/request',
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
                    data: 'user',
                    className: 'text-start align-middle',
                    render: function (user) {
                        return user ? user.name : '-';
                    }
                },
                {
                    data: 'user',
                    className: 'text-start align-middle',
                    render: function (user) {
                        return user ? user.email : '-';
                    }
                },
                {
                    data: 'progress_percentage',
                    className: 'text-center align-middle',
                    render: function (progress) {
                        return `
                            <div>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                         style="width: ${progress}%">
                                        ${progress}%
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                },
                {
                    data: 'status',
                    className: 'text-center align-middle',
                    render: function (status) {
                        switch (status) {
                            case 'enrolled':
                                return '<span class="badge bg-info">Terdaftar</span>';
                            case 'in_progress':
                                return '<span class="badge bg-warning">Sedang Belajar</span>';
                            case 'completed':
                                return '<span class="badge bg-success">Selesai</span>';
                            default:
                                return '<span class="badge bg-secondary">-</span>';
                        }
                    }
                },
                {
                    data: 'enrolled_at',
                    className: 'text-center align-middle',
                    render: function (date) {
                        if (!date) return '-';
                        const d = new Date(date);
                        return d.toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });
                    }
                },
                {
                    data: 'completed_at',
                    className: 'text-center align-middle',
                    render: function (date) {
                        if (!date) return '-';
                        const d = new Date(date);
                        return d.toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });
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
                emptyTable: "Belum ada peserta yang mendaftar",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ peserta",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 peserta",
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
            button.prop('disabled', true);

            self.data.table.ajax.reload(function () {
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
    }
};

$(document).ready(function () {
    const pathArray = window.location.pathname.split('/');
    const kursusId = pathArray[pathArray.indexOf('kursus') + 1];

    jQuery.peserta.init(kursusId);
});
