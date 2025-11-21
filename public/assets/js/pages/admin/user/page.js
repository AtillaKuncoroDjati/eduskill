jQuery.index = {
    data: {
        table: null,
        searchInput: null,
        searchButton: null,
        statusFilter: 'all',
        permissionFilter: 'all',
    },
    init: function () {
        var self = this;

        self.data.searchInput = $('#search-user');
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
                url: '/admin/pengguna/request',
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
                    d.permission_filter = self.data.permissionFilter;
                    return d;
                }
            },
            columns: [
                {
                    data: null,
                    className: 'text-center fw-bold align-middle',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: 'avatar',
                    className: 'text-center align-middle',
                    render: function (avatar) {
                        const avatarUrl = avatar && avatar !== 'default-avatar.png'
                            ? '/uploads/avatar/' + avatar
                            : '/uploads/avatar/default-avatar.png';
                        return `<img src="${avatarUrl}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">`;
                    }
                },
                {
                    data: 'name',
                    className: 'align-middle',
                    render: function (name, type, row) {
                        return '<strong>' + name + '</strong>';
                    }
                },
                {
                    data: null,
                    className: 'align-middle',
                    render: function (data) {
                        let html = '<div class="small">';
                        html += '<div class="mb-1"><i class="ti ti-at"></i> <strong>Email:</strong> ' + (data.email || '-') + '</div>';
                        html += '<div class="mb-1"><i class="ti ti-user-circle"></i> <strong>Username:</strong> ' + (data.username || '-') + '</div>';
                        html += '<div><i class="ti ti-phone"></i> <strong>Telepon:</strong> ' + (data.phone || '-') + '</div>';
                        html += '</div>';
                        return html;
                    }
                },
                {
                    data: 'permission',
                    className: 'text-center align-middle',
                    render: function (permission) {
                        return permission === 'admin'
                            ? '<span class="badge bg-danger"><i class="ti ti-shield-check"></i> Admin</span>'
                            : '<span class="badge bg-primary"><i class="ti ti-user"></i> User</span>';
                    }
                },
                {
                    data: 'status',
                    className: 'text-center align-middle',
                    render: function (status) {
                        const badges = {
                            'aktif': '<span class="badge bg-success">Aktif</span>',
                            'nonaktif': '<span class="badge bg-warning">Nonaktif</span>',
                            'banned': '<span class="badge bg-danger">Banned</span>'
                        };
                        return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
                    }
                },
                {
                    data: 'created_at',
                    className: 'text-center align-middle',
                    render: function (date) {
                        const d = new Date(date);
                        return d.toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });
                    }
                },
                {
                    data: null,
                    className: 'text-center align-middle',
                    render: function (data) {
                        return `
                            <div class='d-grid gap-1'>
                                <button class='btn btn-sm btn-soft-warning btn-edit w-100'
                                    data-id='${data.id}'>
                                    <i class='ti ti-pencil me-1'></i>Edit
                                </button>
                                <button class='btn btn-sm btn-soft-danger btn-hapus w-100'
                                    data-id='${data.id}'
                                    data-name='${data.name}'>
                                    <i class='ti ti-trash me-1'></i>Hapus
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
                emptyTable: "Tidak ada data pengguna",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ pengguna",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 pengguna",
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
            self.data.table.ajax.reload();
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

        $('.filter-permission').on('click', function (e) {
            e.preventDefault();
            self.data.permissionFilter = $(this).data('permission');
            self.data.table.ajax.reload();
        });

        $(document).on('click', '.btn-edit', function () {
            var id = $(this).data('id');

            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.get('/admin/pengguna/' + id + '/detail', function (user) {
                Swal.close();

                $('#edit-id').val(user.id);
                $('#edit-name').val(user.name);
                $('#edit-username').val(user.username || '');
                $('#edit-email').val(user.email);
                $('#edit-phone').val(user.phone || '');
                $('#edit-permission').val(user.permission);
                $('#edit-status').val(user.status);

                const avatarUrl = user.avatar && user.avatar !== 'default-avatar.png'
                    ? '/uploads/avatar/' + user.avatar
                    : '/uploads/avatar/default-avatar.png';
                $('#edit-avatar-preview').attr('src', avatarUrl);

                $('#modalEditUser').modal('show');
            }).fail(function () {
                Swal.fire('Error', 'Gagal memuat data user', 'error');
            });
        });

        $(document).on('click', '.btn-hapus', function () {
            var id = $(this).data('id');
            var name = $(this).data('name');

            $.confirm({
                title: 'Konfirmasi Hapus',
                type: 'red',
                columnClass: 'medium',
                content: 'Apakah Anda yakin ingin menghapus pengguna <strong>' + name + '</strong>? <br/><br/>' +
                    '<span class="text-danger">Data yang telah dihapus tidak dapat dikembalikan!</span>',
                buttons: {
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-secondary',
                        keys: ['esc']
                    },
                    confirm: {
                        text: 'Ya, Hapus!',
                        btnClass: 'btn-danger',
                        keys: ['enter'],
                        action: function () {
                            $('#id-delete').val(id);
                            $('#form-delete').submit();
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
