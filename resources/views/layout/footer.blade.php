<footer class="footer">
    <div class="page-container">
        <div class="row">
            <div class="col-md-12 text-center text-md-start">
                Copyright &copy; {{ date('Y') }}
                <span class="fw-bold text-decoration-underline text-uppercase text-reset fs-12" style="cursor: pointer;"
                    data-bs-toggle="modal" data-bs-target="#anggotaModal">
                    {{ config('app.name') }}
                </span>.
            </div>
        </div>
    </div>
</footer>

<div class="modal fade" id="anggotaModal" tabindex="-1" aria-labelledby="anggotaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="anggotaModalLabel">
                    Daftar Anggota Tim Pengembang {{ config('app.name') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    <li class="list-group-item"><b>41522010153</b> - ATILLA KUNCORO DJATI (Frontend)</li>
                    <li class="list-group-item"><b>41522010150</b> - NAUFAL ATHILLAH (Backend)</li>
                    <li class="list-group-item"><b>41522010061</b> - RAFLY PRIATMOJO (UI/UX)</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-soft-dark shadow-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
