@extends('layouts.app')
@section('content')
    <div class="nk-content">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Asignar Documento</h3>
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
                            <form action="{{ url('/documentos/'.$data->id.'/asignar-y-notificar') }}" class="form-validate" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row g-gs">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label class="form-label" for="customFileLabel">Nombre de Documento a
                                                asignar</label>
                                            <input type="text" class="form-control" name="nameArchivo"
                                                value="{{ $data->archivo }}" readonly>
                                            <input type="hidden" name="urlArchivo" value="{{ $data->archivo_firmado }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="firmado">¿Utilizar Usuarios Registrados?</label>
                                            <div class="form-control-wrap">
                                                <ul class="custom-control-group">
                                                    <li>
                                                        <div
                                                            class="custom-control custom-radio custom-control-pro no-control ">
                                                            <input type="radio" class="custom-control-input valid"
                                                                name="registrado" id="si" value="Si">
                                                            <label class="custom-control-label" for="si">Si</label>
                                                            @if ($errors->has('registrado'))
                                                                <span id="fv-full-name-error" class="invalid">
                                                                    {{ $errors->first('registrado') }}
                                                                </span>
                                                            @endif
                                                    </li>
                                                    <li>
                                                        <div
                                                            class="custom-control custom-radio custom-control-pro no-control">
                                                            <input type="radio" class="custom-control-input valid"
                                                                name="registrado" id="no" value="No">
                                                            <label class="custom-control-label" for="no">No</label>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="registrados" class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Usuarios Registrados</label>
                                            <div class="form-control-wrap">
                                                <select name="users[]" class="form-select" multiple="multiple"
                                                    data-placeholder="Seleccione multiple usuarios">
                                                    @foreach ($users as $item)
                                                        <option value="{{ $item->email }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if ($errors->has('users'))
                                                <span id="fv-full-name-error" class="invalid">
                                                    {{ $errors->first('users') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="noRegistrados" class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label class="form-label">Correo de Destinatarios</label>
                                                    <div class="form-control-wrap">
                                                        <div class="form-icon form-icon-right">
                                                            <em class="icon ni ni-mail"></em>
                                                        </div>
                                                        <input type="text" class="form-control" id="fva-email" name="destinatarios[]" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" id="aggDestinatario" class="btn btn-success mt-4"><em class="icon ni ni-plus-round-fill"></em> Agregar Destinatario</button>
                                            </div>
                                            <div class="w-100"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 ">
                                        <div class="form-group float-right">
                                            <button type="submit"
                                                class="btn btn-lg btn-primary">Guardar</button>
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
@section('scripts')
    <script>
        (function(NioApp, $){
            'use strict';

            $('#registrados').hide();
            $('#noRegistrados').hide();

            $('#si').click(function () {
                if ($(this).is(':checked')) {
                    $('#registrados').show();
                    $('#noRegistrados').hide();
                }
            });
            $('#no').click(function () {
                if ($(this).is(':checked')) {
                    $('#registrados').hide();
                    $('#noRegistrados').show();
                }
            });

            $('#aggDestinatario').click(function () {
                $("#noRegistrados .row").append('<div class="col-md-5 mt-2"><div class="form-group"><label class="form-label">Correo de Destinatarios</label><div class="form-control-wrap"><div class="form-icon form-icon-right"><em class="icon ni ni-mail"></em></div><input type="text" class="form-control" id="fva-email" name="destinatarios[]" ></div></div></div><div class="w-100"></div>');
            });


        })(NioApp, jQuery);
    </script>
@endsection
