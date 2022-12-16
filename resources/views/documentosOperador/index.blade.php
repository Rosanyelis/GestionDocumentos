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
                                <h3 class="nk-block-title page-title">Mis Documentos</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                    data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt">
                                            <!-- <a href="{{ url('/documentos/nuevo-documento') }}" class="btn btn-primary">
                                                <em class="icon ni ni-plus"></em>
                                                <span>Nuevo Documento</span>
                                            </a> -->
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
                                        <th width="30px">¿Firma.Autor?</th>
                                        {{-- <th>Usuarios Asignados</th> --}}
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($item->documento->firmado == 'No')
                                                    <a href="{{ asset($item->documento->url_archivo) }}" target="_blank">
                                                        {{ $item->documento->archivo }}
                                                    </a>
                                                @else
                                                    <a href="{{ asset($item->documento->archivo_firmado) }}" target="_blank">
                                                        {{ $item->documento->archivo }}
                                                    </a>
                                                @endif

                                            </td>
                                            <td>{{ $item->documento->tamano }} bytes</td>
                                            <td>{{ $item->documento->firmado }}</td>
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
                                                                        href="{{ url('/mis-documentos/' . $item->documento_id . '/firmar') }}">
                                                                        <em class="icon ni ni-pen2"></em>
                                                                        Firmar
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
                let baseUrl = '{{ url('') }}/' + dataid +
                    '/eliminar-categoria';
                Swal.fire({
                    title: '¿Está Seguro de Desactivar el Registro?',
                    text: "Si tiene datos dependientes, no podrá desactivarlo!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, estoy seguro!'
                }).then((result) => {
                    if (result.value) {
                        // $.ajax({
                        //     type: 'POST',
                        //     url: baseUrl,
                        //     dataType: 'json',
                        //     success: function(response) {
                        //        console.log(response);
                        //         localStorage.setItem("success", 1);
                        //         location.reload();
                        //     }
                        // });
                    }
                });
            });
        })(NioApp, jQuery);
    </script>
@endsection
