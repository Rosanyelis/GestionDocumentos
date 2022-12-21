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
                            <h3 class="nk-block-title page-title">Inicio</h3>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="card card-preview">
                    <div class="card-inner text-center py-5">
                        <div class="row py-5">
                            <div class="col-md-12">
                                <img src="{{ asset('images/logo alpasahd.jpg') }}" alt="logo ALPASA">
                            </div>
                            <div class="col-md-12">
                                <h2 class="mt-5">Gestión de Documentos y Firmas</h2>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

@endsection
