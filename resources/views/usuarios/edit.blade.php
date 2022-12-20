@extends('layouts.app')
@section('content')
    <div class="nk-content">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Editar Usuario</h3>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                    data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt">
                                            <a href="{{ url('/usuarios') }}" class="btn btn-secondary">
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
                            <form action="{{ url('/usuarios/'.$data->id.'/actualizar-usuario') }}" class="form-validate" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row g-gs">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="name">Nombre de Usuario</label>
                                            <div class="form-control-wrap">
                                                <div class="custom-file">
                                                <input type="text" name="name" class="form-control" id="name" value="{{ $data->name }}" placeholder="Ejem: Carlos Mendoza">

                                                    @if ($errors->has('name'))
                                                        <span id="fv-full-name-error" class="invalid">
                                                            {{ $errors->first('name') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="email">Correo Electrónico</label>
                                            <div class="form-control-wrap">
                                                <div class="custom-file">
                                                <input type="email" name="email" class="form-control" id="email" value="{{ $data->email }}" placeholder="Ejem: carlosmendoza@example.com">
                                                    @if ($errors->has('email'))
                                                        <span id="fv-full-email-error" class="invalid">
                                                            {{ $errors->first('email') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="password">Contraseña</label>
                                            <div class="form-control-wrap">
                                                <div class="custom-file">
                                                <input type="password" name="password" class="form-control" id="password" placeholder="*********">
                                                    @if ($errors->has('password'))
                                                        <span id="fv-full-password-error" class="invalid">
                                                            {{ $errors->first('password') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
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
