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
                                <h3 class="nk-block-title page-title">Documentos</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                    data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt">
                                            <a href="{{ url('/documentos/nuevo-documento') }}" class="btn btn-primary">
                                                <em class="icon ni ni-plus"></em>
                                                <span>Nuevo Documento</span>
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
                                        <th>Archivo</th>
                                        <th>Tamaño</th>
                                        <th width="30px">¿Firmado?</th>
                                        <th>Usuarios Asignados</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($item->firmado == 'No')
                                                    <a href="{{ asset($item->url_archivo) }}" target="_blank">
                                                        {{ $item->archivo }}
                                                    </a>
                                                @else
                                                    <a href="{{ asset($item->archivo_firmado) }}" target="_blank">
                                                        {{ $item->archivo }}
                                                    </a>
                                                @endif

                                            </td>
                                            <td>{{ $item->tamano }} bytes</td>
                                            <td>{{ $item->firmado }}</td>
                                            <td>
                                                @foreach ($item->asigndocumentos as $dato)
                                                @if ($dato->name_user)
                                                    <span class="badge badge-secondary">{{ $dato->name_user }}</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $dato->email_destinatario }}</span>
                                                @endif

                                                @endforeach
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a href="#" class="btn btn-primary btn-sm" data-toggle="dropdown"
                                                        aria-expanded="false"><span>Acciones</span><em
                                                            class="icon ni ni-chevron-down"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-auto mt-1"
                                                        style="">
                                                        <ul class="link-list-plain">
                                                            @if ($item->firmado == 'No')
                                                                <li>
                                                                    <a
                                                                        href="{{ url('/documentos/' . $item->id . '/firmar-documento') }}">
                                                                        <em class="icon ni ni-pen2"></em>
                                                                        Firmar
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            <li><a
                                                                    href="{{ url('/documentos/' . $item->id . '/asignar-documento') }}">
                                                                    <em class="icon ni ni-user-add-fill"></em>
                                                                    Asignar
                                                                </a>
                                                            </li>
                                                            @if (Auth::user()->rol->name == 'Administrador')
                                                            <li>
                                                                <form id="formDelete" action="{{ url('/documentos/' . $item->id . '/eliminar-documento') }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="javascript:void(0);" class="delete-record">
                                                                        <em class="icon ni ni-trash-fill"></em>
                                                                        Eliminar
                                                                    </a>
                                                                </form>
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
                // let dataid = $(this).data('id');
                // let baseUrl = '{{ url("/documentos") }}/' + dataid +
                //     '/eliminar-documento';
                Swal.fire({
                    title: '¿Está Seguro de Eliminar el Documento?',
                    text: "Si tiene usuarios asignados, estos no podrán acceder a el en el futuro!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, estoy seguro!'
                }).then((result) => {
                    if (result.value) {
                        $('#formDelete').submit();
                    }
                });
            });
        })(NioApp, jQuery);
    </script>
@endsection
