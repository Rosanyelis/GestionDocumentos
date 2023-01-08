@extends('layouts.app')
@section('styles')
@endsection
@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Almacén CEBÚ</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                    data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt">
                                            {{-- <a href="{{ url('/usuarios/nuevo-usuario') }}" class="btn btn-primary">
                                                <em class="icon ni ni-plus"></em>
                                                <span>Nuevo Usuario</span>
                                            </a> --}}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->

                    <div class="card card-preview">
                        <div class="card-inner">
                            <div class="row g-gs">
                                <div class="col-md-12 text-center">
                                    <img src="{{ asset('images/imagen_almacén.jpg') }}" width="50%" alt="">
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-preview">
                                        <div class="card-inner">
                                            <div class="card-head text-center">
                                                <h6 class="title">Almacén de 10.500  m2 - Capacidad Física 10.000 m2</h6>
                                            </div>
                                            <div class="nk-ck-sm">
                                                <canvas class="pie-chart" id="pieChartData"></canvas>
                                            </div>
                                        </div>
                                    </div><!-- .card-preview -->
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-preview">
                                        <div class="card-inner">
                                            <div class="card-head text-center">
                                                <h6 class="title">Almacén - Bahías</h6>
                                            </div>
                                            <div class="nk-ck-sm">
                                                <canvas class="pie-chart" id="pieChartDataBahia"></canvas>
                                            </div>
                                        </div>
                                    </div><!-- .card-preview -->
                                </div>
                                <div class="col-md-12">
                                    <div class="card card-preview">
                                        <div class="card-inner">
                                            <div class="card-head text-center">
                                                <h6 class="title">Bahía A</h6>
                                            </div>
                                            <div class="nk-ck-sm">
                                                <canvas class="bar-chart" id="barChartMultiple"></canvas>
                                            </div>
                                        </div>
                                    </div><!-- .card-preview -->
                                </div>
                                <div class="col-md-12">
                                    <div class="card card-preview">
                                        <div class="card-inner">
                                            <div class="card-head text-center">
                                                <h6 class="title">Bahía B</h6>
                                            </div>
                                            <div class="nk-ck-sm">
                                                <canvas class="bar-chart" id="barChartMultiple2"></canvas>
                                            </div>
                                        </div>
                                    </div><!-- .card-preview -->
                                </div>
                                <div class="col-md-12">
                                    <div class="card card-preview">
                                        <div class="card-inner">
                                            <div class="card-head text-center">
                                                <h6 class="title">Bahía C</h6>
                                            </div>
                                            <div class="nk-ck-sm">
                                                <canvas class="bar-chart" id="barChartMultiple3"></canvas>
                                            </div>
                                        </div>
                                    </div><!-- .card-preview -->
                                </div>
                            </div>
                        </div>
                    </div><!-- .card-preview -->
                </div> <!-- nk-block -->
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/example-chart.js?ver=2.9.0') }}"></script>
@endsection
