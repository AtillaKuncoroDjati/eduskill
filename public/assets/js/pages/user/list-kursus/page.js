jQuery.index = {
    data: {
        searchInput: null,
        searchButton: null,
        categoryFilter: 'all',
        currentPage: 1,
        perPage: 8,
        totalData: 0,
        kursusContainer: null,
        paginationContainer: null,
    },
    init: function () {
        var self = this;

        self.data.searchInput = $('#search-kursus');
        self.data.searchButton = $('.btn-search');
        self.data.kursusContainer = $('#kursus-list');
        self.data.paginationContainer = $('.pagination');

        self.setEvents();
        self.loadData();
    },
    loadData: function (page = 1) {
        var self = this;

        self.data.currentPage = page;

        $.ajax({
            url: '/user/daftar-kursus/request',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                search: {
                    value: self.data.searchInput.val()
                },
                category: self.data.categoryFilter,
                start: (page - 1) * self.data.perPage,
                length: self.data.perPage,
                draw: 1
            },
            beforeSend: function () {
                self.data.kursusContainer.html(`
                    <div class="col-12 text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Memuat data...</p>
                    </div>
                `);
            },
            success: function (response) {
                self.data.totalData = response.recordsTotal;
                self.renderKursus(response.data);
                self.renderPagination();
            },
            error: function () {
                self.data.kursusContainer.html(`
                    <div class="col-12 text-center py-5">
                        <i class="ti ti-alert-circle fs-1 text-danger"></i>
                        <p class="mt-3 text-danger">Gagal memuat data</p>
                    </div>
                `);
            }
        });
    },
    renderKursus: function (data) {
        var self = this;
        var html = '';

        if (data.length === 0) {
            html = `
                <div class="col-12 text-center py-5">
                    <i class="ti ti-folder-off fs-1 text-muted"></i>
                    <p class="mt-3 text-muted">Tidak ada kursus yang ditemukan</p>
                </div>
            `;
        } else {
            data.forEach(function (k) {
                var difficultyBadge = '';
                var difficultyClass = '';

                if (k.difficulty === 'pemula') {
                    difficultyBadge = '<i class="ti ti-star"></i> Pemula';
                    difficultyClass = 'bg-info';
                } else if (k.difficulty === 'menengah') {
                    difficultyBadge = '<i class="ti ti-star"></i> Menengah';
                    difficultyClass = 'bg-warning';
                } else {
                    difficultyBadge = '<i class="ti ti-star"></i> Lanjutan';
                    difficultyClass = 'bg-danger';
                }

                var certificateBadge = '';
                if (k.certificate == 1) {
                    certificateBadge = `
                        <span class="position-absolute top-0 end-0 m-2 badge bg-primary">
                            <i class="ti ti-certificate"></i> Dengan Sertifikat
                        </span>
                    `;
                } else {
                    certificateBadge = `
                        <span class="position-absolute top-0 end-0 m-2 badge bg-danger">
                            <i class="ti ti-certificate-off"></i> Tanpa Sertifikat
                        </span>
                    `;
                }

                var categoryBadge = '';
                var categoryClass = '';
                var categoryIcon = '';

                if (k.category === 'programming') {
                    categoryBadge = 'Programming';
                    categoryClass = 'bg-primary';
                    categoryIcon = 'ti-code';
                } else if (k.category === 'design') {
                    categoryBadge = 'Design';
                    categoryClass = 'bg-soft-success text-success';
                    categoryIcon = 'ti-palette';
                } else if (k.category === 'marketing') {
                    categoryBadge = 'Marketing';
                    categoryClass = 'bg-soft-info text-info';
                    categoryIcon = 'ti-chart-bar';
                } else if (k.category === 'business') {
                    categoryBadge = 'Bisnis';
                    categoryClass = 'bg-soft-warning text-warning';
                    categoryIcon = 'ti-briefcase';
                } else if (k.category === 'cybersecurity') {
                    categoryBadge = 'Cybersecurity';
                    categoryClass = 'bg-soft-danger text-danger';
                    categoryIcon = 'ti-shield-lock';
                }

                var shortDesc = k.short_description && k.short_description.length > 60
                    ? k.short_description.substring(0, 60) + '...'
                    : k.short_description;

                html += `
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="card shadow-sm h-100 border" data-kategori="${k.category}">
                            <div class="position-relative overflow-hidden" style="height: 180px;">
                                <img src="/uploads/kursus/${k.thumbnail}" class="w-100 h-100"
                                    style="object-fit: cover;" alt="${k.title}">

                                <span class="position-absolute top-0 start-0 m-2 badge ${difficultyClass}">
                                    ${difficultyBadge}
                                </span>

                                ${certificateBadge}
                            </div>

                            <div class="card-body d-flex flex-column">
                                <div class="mb-2">
                                    <span class="badge ${categoryClass}">
                                        <i class="ti ${categoryIcon}"></i> ${categoryBadge}
                                    </span>
                                </div>

                                <h5 class="card-title mb-2">
                                    ${k.title}
                                </h5>

                                <p class="card-text text-muted small">
                                    ${shortDesc}
                                </p>

                                <div class="mt-auto">
                                    <a href="/user/daftar-kursus/${k.id}"
                                        class="btn btn-sm btn-primary w-100">
                                        Detail Kursus
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        }

        self.data.kursusContainer.html(html);
    },
    renderPagination: function () {
        var self = this;
        var totalPages = Math.ceil(self.data.totalData / self.data.perPage);
        var html = '';

        // Previous button
        if (self.data.currentPage > 1) {
            html += `
                <li class="page-item">
                    <a class="page-link page-prev" href="#" data-page="${self.data.currentPage - 1}">
                        <i class="ti ti-chevron-left"></i>
                    </a>
                </li>
            `;
        } else {
            html += `
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">
                        <i class="ti ti-chevron-left"></i>
                    </a>
                </li>
            `;
        }

        // Page numbers
        var startPage = Math.max(1, self.data.currentPage - 2);
        var endPage = Math.min(totalPages, self.data.currentPage + 2);

        if (startPage > 1) {
            html += `
                <li class="page-item">
                    <a class="page-link page-number" href="#" data-page="1">1</a>
                </li>
            `;
            if (startPage > 2) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }

        for (var i = startPage; i <= endPage; i++) {
            if (i === self.data.currentPage) {
                html += `
                    <li class="page-item active">
                        <a class="page-link" href="#">${i}</a>
                    </li>
                `;
            } else {
                html += `
                    <li class="page-item">
                        <a class="page-link page-number" href="#" data-page="${i}">${i}</a>
                    </li>
                `;
            }
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
            html += `
                <li class="page-item">
                    <a class="page-link page-number" href="#" data-page="${totalPages}">${totalPages}</a>
                </li>
            `;
        }

        // Next button
        if (self.data.currentPage < totalPages) {
            html += `
                <li class="page-item">
                    <a class="page-link page-next" href="#" data-page="${self.data.currentPage + 1}">
                        <i class="ti ti-chevron-right"></i>
                    </a>
                </li>
            `;
        } else {
            html += `
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">
                        <i class="ti ti-chevron-right"></i>
                    </a>
                </li>
            `;
        }

        self.data.paginationContainer.html(html);
    },
    setEvents: function () {
        var self = this;

        // Search button click
        self.data.searchButton.on('click', function () {
            var button = $(this);
            button.prop('disabled', true);

            self.loadData(1);

            setTimeout(function () {
                button.prop('disabled', false);
            }, 500);
        });

        // Search on enter
        self.data.searchInput.keyup(function (e) {
            if (e.keyCode === 13) {
                self.data.searchButton.click();
            }
        });

        // Category filter
        $('.filter-kategori').on('click', function (e) {
            e.preventDefault();
            self.data.categoryFilter = $(this).data('kategori');

            // Update button text
            var kategoriText = $(this).text().trim();
            $('#filterKategori').html(`
                <i class="ti ti-filter"></i>
                <span class="d-none d-md-inline ms-1">${kategoriText}</span>
            `);

            self.loadData(1);
        });

        // Pagination click
        $(document).on('click', '.page-number, .page-prev, .page-next', function (e) {
            e.preventDefault();
            var page = $(this).data('page');
            self.loadData(page);

            // Scroll to top
            $('html, body').animate({
                scrollTop: $('#kursus-list').offset().top - 100
            }, 300);
        });
    }
};

$(document).ready(function () {
    jQuery.index.init();
});
