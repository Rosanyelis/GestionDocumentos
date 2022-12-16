@extends('layouts.app')
@section('content')
    <div class="nk-content">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Nuevo Documento</h3>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                    data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt">
                                            <a href="{{ url('/documentos') }}" class="btn btn-secondary">
                                                <em class="icon ni ni-arrow-left"></em>
                                                <span>Regresar</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div>

                <div class="nk-block nk-block-lg">
                    <div class="card card-bordered">
                        <div class="card-inner">
                            <form action="{{ url('/documentos/guardar-documento') }}" class="form-validate" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row g-gs">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="customFileLabel">Cargar Documento</label>
                                            <div class="form-control-wrap">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="customFile"
                                                        name="archivo">
                                                    <label class="custom-file-label" for="customFile">Cargar
                                                        Documento...</label>
                                                    @if ($errors->has('archivo'))
                                                        <span id="fv-full-archivo-error" class="invalid">
                                                            {{ $errors->first('archivo') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="firmado">¿Documento Firmado?</label>
                                            <div class="form-control-wrap">
                                                <ul class="custom-control-group">
                                                    <li>
                                                        <div
                                                            class="custom-control custom-radio custom-control-pro no-control checked">
                                                            <input type="radio" class="custom-control-input valid"
                                                                name="firmado" id="si" value="Si">
                                                            <label class="custom-control-label" for="si">Si</label>
                                                            @if ($errors->has('firmado'))
                                                                <span id="fv-full-name-error" class="invalid">
                                                                    {{ $errors->first('firmado') }}
                                                                </span>
                                                            @endif
                                                    </li>
                                                    <li>
                                                        <div
                                                            class="custom-control custom-radio custom-control-pro no-control">
                                                            <input type="radio" class="custom-control-input valid"
                                                                name="firmado" id="no" value="No">
                                                            <label class="custom-control-label" for="no">No</label>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <span class="form-note">Es necesario confirmar si el documento está firmado por
                                                el autor o no.</span>

                                        </div>
                                    </div>

                                    <div class="col-md-12 ">
                                        <div class="form-group float-right">
                                            <button type="submit" class="btn btn-lg btn-primary">Guardar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
