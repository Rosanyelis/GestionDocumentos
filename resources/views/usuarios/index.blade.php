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
                                <h3 class="nk-block-title page-title">Usuarios</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                    data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt">
                                            <a href="{{ url('/usuarios/nuevo-usuario') }}" class="btn btn-primary">
                                                <em class="icon ni ni-plus"></em>
                                                <span>Nuevo Usuario</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->

                    <div class="card card-preview">
                        <div class="card-inner">

                            <table class="datatable-init table">
                                <thead>
                                    <tr>
                                        <th width="30px">#</th>
                                        <th>Usuario</th>
                                        <th>Correo</th>
                                        <th>Rol</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }} </td>
                                            <td>{{ $item->email }} </td>
                                            <td>{{ $item->rol->name }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <a href="#" class="btn btn-primary btn-sm" data-toggle="dropdown"
                                                        aria-expanded="false"><span>Acciones</span><em
                                                            class="icon ni ni-chevron-down"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-auto mt-1"
                                                        style="">
                                                        <ul class="link-list-plain">
                                                            <li>
                                                                <a href="{{ url('/usuarios/' . $item->id . '/editar-usuario') }}">
                                                                    <em class="icon ni ni-pen2"></em>
                                                                    Editar
                                                                </a>
                                                            </li>
                                                            @if (Auth::user()->rol->name == 'Administrador')
                                                            <li>
                                                                <a href="javascript:void(0);" class="delete-record" data-id="{{ $item->id }}">
                                                                    <em class="icon ni ni-trash-fill"></em>
                                                                    Eliminar
                                                                </a>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div><!-- .card-preview -->
                </div> <!-- nk-block -->
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        (function(NioApp, $) {
            'use strict';

            @include('layouts.alerts')

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.datatable-init tbody').on('click', '.delete-record', function() {
                let dataid = $(this).data('id');
                let baseUrl = '{{ url("/usuarios") }}/' + dataid +
                    '/eliminar-usuario';
                Swal.fire({
                    title: '??Est?? Seguro de Eliminar al Usuario?',
                    text: "Los usuarios eliminados no podran acceder al sistema en el futuro!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, estoy seguro!'
                }).then((result) => {
                    $.ajax({
                        type: 'POST',
                        url: baseUrl,
                        dataType: 'json',
                        success: function(response) {
                            console.log(response);
                            Swal.fire({
                                position: 'top-center',
                                icon: 'success',
                                title: 'Usuario Eliminado Exit??samente!',
                                showConfirmButton: false,
                                timer: 2500
                            });
                            location.reload();
                        }
                    });
                });
            });
        })(NioApp, jQuery);
    </script>
@endsection
