@extends('layouts.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/editors/summernote.css?ver=2.9.0') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
    <style>
        .firma{
            font-family: 'Dancing Script', cursive !important;
            font-size: 20px;
            color:black;
        }
    </style>
@endsection
@section('content')
    <div class="nk-content">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Firmar Documento</h3>
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
                            <form id="formFirma" action="{{ url('/documentos/' . $data->id . '/completar-firma-documento') }}"
                                class="form-validate" method="POST">
                                @csrf
                                <div class="row g-gs">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="customFileLabel">Nombre de Documento a
                                                firmar</label>
                                            <input type="text" class="form-control" name="nameArchivo"
                                                value="{{ $data->archivo }}" readonly>
                                            <input type="hidden" name="urlArchivo" value="{{ $data->url_archivo }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label" for="customFileLabel">Visualice el documento antes de
                                            firmar</label>
                                        <iframe src="{{ asset($data->url_archivo) }}" style="width:100%; height:400px;"
                                            frameborder="0"></iframe>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="posicion">Indique la posición de la firma en el
                                                documento</label>
                                            <div class="form-control-wrap ">
                                                <div class="form-control-select">
                                                    <select class="form-control" name="posicion" id="posicion">
                                                        <option >Seleccione</option>
                                                        <option value="ESI">Esquina Superior Izquierda</option>
                                                        <option value="CS">Centro Superior</option>
                                                        <option value="ESD">Esquina Superior Derecha</option>
                                                        <option value="CIM">Centro Izquierdo (Mitad del documento)
                                                        </option>
                                                        <option value="C">Centro (Mitad del documento)</option>
                                                        <option value="CD">Centro Derecho</option>
                                                        <option value="EII">Esquina Inferior Izquierda</option>
                                                        <option value="CI">Centro Inferior</option>
                                                        <option value="EID">Esquina Inferior Derecha</option>
                                                    </select>
                                                    @if ($errors->has('posicion'))
                                                        <span id="fv-full-name-error" class="invalid text-danger">
                                                            {{ $errors->first('posicion') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <p><strong>Nota: Las indicaciones de la firma son necesarias para evitar confusiones
                                                en donde corresponda la firma de cualquiera de los usuarios, sea el autor, o
                                                los firmantes restantes.</strong></p>
                                    </div>
                                    <div class="col-md-12">
                                        <textarea id="firmadoc" class="summernote-basic" name="firma"></textarea>
                                        @if ($errors->has('firma'))
                                            <span id="fv-full-name-error" class="invalid text-danger">
                                                {{ $errors->first('firma') }}
                                            </span>
                                        @endif
                                    </div>
                                    <input type="hidden" id="capturaFirma" name="firmaimg" value="">
                                    <div class="col-md-12 ">
                                        <div class="form-group float-right">
                                            <button id="btnEnviar" type="button"
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
    <script src="{{ asset('assets/js/libs/editors/summernote.js?ver=2.9.0') }}"></script>
    <script src="{{ asset('assets/js/editors.js?ver=2.9.0') }}"></script>
    <script src="{{ asset('js/html2canvas.js') }}"></script>
    <script>
        (function(NioApp, $) {
            'use strict';

            $('#btnEnviar').click(function() {
                const containerFirma = document.createElement("div");
                containerFirma.setAttribute('id', 'contenfirma');
                containerFirma.setAttribute('class', 'col-md-1 p-0 text-center firma');
                containerFirma.insertAdjacentHTML('beforeend', $("#firmadoc").val(), );

                document.body.appendChild(containerFirma);
                html2canvas(containerFirma).then((canvas) => {
                    const base64image = canvas.toDataURL("image/png");
                    // console.log(base64image);
                    $('#capturaFirma').val(base64image);

                    $('#formFirma').submit();
                });
            });
        })(NioApp, jQuery);
    </script>
@endsection
