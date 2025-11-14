@extends('template', ['title' => 'Dashboard'])

@section('content')
    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
        <div class="flex-grow-1">
            <h4 class="fs-18 text-uppercase fw-bold mb-0">
                Daftar Materi Kursus: {{ $kursus->title }}
            </h4>
        </div>
        <div class="mt-3 mt-sm-0">
            <div class="row g-2 mb-0 align-items-center">
                <div class="col-auto">
                    <a href="#" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Tambah Materi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <div class="row" id="materi-list" data-plugin="dragula">
                    <div class="col-12">
                        <div class="card mb-0 mt-3 text-white bg-primary">
                            <div class="card-body">
                                <blockquote class="card-bodyquote mb-0">
                                    <p>
                                        Modul 1: Pengantar ke Kursus
                                    </p>
                                    <footer>Someone famous in <cite title="Source Title">Source Title</cite>
                                    </footer>
                                </blockquote>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->

                    <div class="col-12">
                        <div class="card mb-0 mt-3 text-white bg-primary">
                            <div class="card-body">
                                <blockquote class="card-bodyquote mb-0">
                                    <p>
                                        Modul 2: Dasar-dasar Materi
                                    </p>
                                    <footer>Someone famous in <cite title="Source Title">Source Title</cite>
                                    </footer>
                                </blockquote>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->

                    <div class="col-12">
                        <div class="card mb-0 mt-3 text-white bg-primary">
                            <div class="card-body">
                                <blockquote class="card-bodyquote mb-0">
                                    <p>
                                        Modul 3: Materi Lanjutan
                                    </p>
                                    <footer>Someone famous in <cite title="Source Title">Source Title</cite>
                                    </footer>
                                </blockquote>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->

                    <div class="col-12">
                        <div class="card mb-0 mt-3 text-white bg-primary text-xs-center">
                            <div class="card-body">
                                <blockquote class="card-bodyquote mb-0">
                                    <p>
                                        Modul 4: Ujian Tengah Kursus
                                    </p>
                                    <footer>Someone famous in <cite title="Source Title">Source Title</cite>
                                    </footer>
                                </blockquote>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->

                    <div class="col-12">
                        <div class="card mb-0 mt-3 text-white bg-primary text-xs-center">
                            <div class="card-body">
                                <blockquote class="card-bodyquote mb-0">
                                    <p>
                                        Modul 5: Materi Tambahan
                                    </p>
                                    <footer>Someone famous in <cite title="Source Title">Source Title</cite>
                                    </footer>
                                </blockquote>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->

                    <div class="col-12">
                        <div class="card mb-0 mt-3 text-white bg-primary text-xs-center">
                            <div class="card-body">
                                <blockquote class="card-bodyquote mb-0">
                                    <p>
                                        Modul 6: Ujian Akhir Kursus
                                    </p>
                                    <footer>Someone famous in <cite title="Source Title">Source Title</cite>
                                    </footer>
                                </blockquote>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
    <script src="{{ asset('assets/vendor/dragula/dragula.min.js') }}"></script>

    <script>
        class Dragula {
            initDragula() {
                document.querySelectorAll('[data-plugin="dragula"]').forEach(function(t) {
                    var a = t.getAttribute("data-containers"),
                        n = [],
                        e = (a ? (a = JSON.parse(a)).forEach(function(t) {
                            n.push(document.getElementById(t))
                        }) : n = [t], t.getAttribute("data-handleclass"));
                    e ? dragula(n, {
                        moves: function(t, a, n) {
                            return n.classList.contains(e)
                        }
                    }) : dragula(n)
                })
            }
            init() {
                this.initDragula()
            }
        }
        document.addEventListener("DOMContentLoaded", function(t) {
            (new Dragula).init()
        });
    </script>
@endpush
