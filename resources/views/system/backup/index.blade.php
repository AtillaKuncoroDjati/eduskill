{{-- resources/views/system/backup/index.blade.php --}}
@extends('template', ['title' => 'Backup Database'])

@section('content')
    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-18 text-uppercase fw-bold mb-0">Backup Database</h4>
        </div>
    </div>

    <div class="row g-3">
        {{-- Create Backup --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-primary">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="ti ti-database-export text-primary" style="font-size: 64px;"></i>
                    </div>
                    <h5 class="mb-3">Buat Backup Baru</h5>
                    <p class="text-muted mb-4">
                        Backup database akan mengunduh file SQL yang berisi semua data dari database saat ini.
                    </p>
                    <a href="{{ route('system.backup.download') }}" class="btn btn-primary btn-lg w-100"
                        id="btnDownloadBackup">
                        <i class="ti ti-download me-2"></i>Download Backup Sekarang
                    </a>
                    <small class="text-muted d-block mt-3">
                        <i class="ti ti-info-circle"></i> File akan disimpan & diunduh
                    </small>
                </div>
            </div>
        </div>

        {{-- Info & Statistics --}}
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="ti ti-info-circle me-2"></i>Informasi Backup
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="border-start border-primary border-3 ps-3">
                                <h6 class="text-muted mb-2 small">Database</h6>
                                <h5 class="mb-0 fw-bold">{{ env('DB_DATABASE') }}</h5>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-start border-success border-3 ps-3">
                                <h6 class="text-muted mb-2 small">Total Tabel</h6>
                                <h5 class="mb-0 fw-bold" id="totalTables">
                                    <i class="ti ti-loader fa-spin"></i>
                                </h5>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-start border-info border-3 ps-3">
                                <h6 class="text-muted mb-2 small">Backup Tersimpan</h6>
                                <h5 class="mb-0 fw-bold" id="totalBackups">
                                    <i class="ti ti-loader fa-spin"></i>
                                </h5>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-start border-warning border-3 ps-3">
                                <h6 class="text-muted mb-2 small">Total Ukuran</h6>
                                <h5 class="mb-0 fw-bold" id="totalSize">
                                    <i class="ti ti-loader fa-spin"></i>
                                </h5>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="alert alert-info mb-0">
                        <h6 class="alert-heading">
                            <i class="ti ti-bulb me-2"></i>Tips Backup
                        </h6>
                        <ul class="mb-0 ps-3 small">
                            <li>Lakukan backup secara rutin, minimal 1x seminggu</li>
                            <li>Simpan file backup di tempat yang aman (external storage)</li>
                            <li>File backup tersimpan di: <code>storage/app/backups</code></li>
                            <li>Hapus backup lama yang sudah tidak diperlukan untuk menghemat space</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Backup History --}}
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ti ti-history me-2"></i>Riwayat Backup
                        </h5>
                        <button class="btn btn-sm btn-soft-primary" onclick="loadBackups()">
                            <i class="ti ti-refresh"></i> Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="backupTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Nama File</th>
                                    <th width="12%" class="text-center">Ukuran</th>
                                    <th width="18%" class="text-center">Tanggal</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="ti ti-loader fa-spin fs-2"></i>
                                        <p class="text-muted mt-2">Loading...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            loadBackups();
            loadStatistics();
        });

        // Add loading state to download button
        $('#btnDownloadBackup').on('click', function(e) {
            const btn = $(this);
            const originalHtml = btn.html();

            btn.prop('disabled', true);
            btn.html('<i class="ti ti-loader fa-spin me-2"></i>Membuat Backup...');

            // Reset after download starts (3 seconds)
            setTimeout(function() {
                btn.prop('disabled', false);
                btn.html(originalHtml);

                // Reload backup list after creating new backup
                setTimeout(function() {
                    loadBackups();
                }, 1000);
            }, 3000);
        });

        function loadBackups() {
            $.get('{{ route('system.backup.list') }}', function(response) {
                const tbody = $('#backupTable tbody');
                tbody.empty();

                $('#totalBackups').text(response.total);
                $('#totalSize').text(response.total_size);

                if (response.data.length === 0) {
                    tbody.append(`
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="ti ti-database-off fs-1 text-muted"></i>
                                <p class="text-muted mt-3 mb-0">Belum ada riwayat backup</p>
                                <small class="text-muted">Klik tombol "Download Backup Sekarang" untuk membuat backup pertama</small>
                            </td>
                        </tr>
                    `);
                    return;
                }

                response.data.forEach((backup, index) => {
                    tbody.append(`
                        <tr>
                            <td class="text-center fw-bold">${index + 1}</td>
                            <td>
                                <i class="ti ti-file-database me-2 text-primary"></i>
                                <span class="fw-bold">${backup.name}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">${backup.size}</span>
                            </td>
                            <td class="text-center">
                                <small>${backup.date}</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="/system/backup/download/${backup.name}"
                                       class="btn btn-sm btn-soft-success"
                                       title="Download backup">
                                        <i class="ti ti-download"></i>
                                    </a>
                                    <button class="btn btn-sm btn-soft-danger"
                                            onclick="deleteBackup('${backup.name}')"
                                            title="Hapus backup">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `);
                });
            });
        }

        function loadStatistics() {
            $.get('/api/database-stats', function(data) {
                $('#totalTables').text(data.total_tables || '0');
            }).fail(function() {
                $('#totalTables').html('<small class="text-muted">N/A</small>');
            });
        }

        function deleteBackup(filename) {
            if (!confirm('Apakah Anda yakin ingin menghapus backup ini?\n\nFile: ' + filename)) {
                return;
            }

            $.ajax({
                url: `/system/backup/${filename}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    showNotification('Backup berhasil dihapus', 'success');
                    loadBackups();
                },
                error: function(xhr) {
                    showNotification('Gagal menghapus backup', 'error');
                }
            });
        }

        function showNotification(message, type) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const icon = type === 'success' ? 'ti-check' : 'ti-x';

            const notification = $(`
                <div class="alert ${alertClass} alert-dismissible fade show position-fixed"
                     role="alert"
                     style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                    <i class="ti ${icon} me-2"></i>${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);

            $('body').append(notification);

            setTimeout(() => {
                notification.fadeOut(() => notification.remove());
            }, 3000);
        }
    </script>
@endpush

@push('styles')
    <style>
        .fa-spin {
            animation: fa-spin 1s infinite linear;
        }

        @keyframes fa-spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush
