<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Dev Rosanyelis">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema de Gestión de Documentos.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.jpeg') }}">
    <!-- Page Title  -->
    <title>ALPASA | Gestión de Documentos y Firmas</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css?ver=2.9.0') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('assets/css/theme.css?ver=2.9.0') }}">
    @yield('styles')
</head>

<body class="nk-body bg-lighter npc-default has-sidebar ">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- sidebar @s -->
            @include('layouts.navigation')
            <!-- sidebar @e -->
            <!-- wrap @s -->
            <div class="nk-wrap ">
                <!-- main header @s -->
                @include('layouts.navigationheader')
                <!-- main header @e -->
                <!-- content @s -->
                @yield('content')
                <!-- content @e -->
                <!-- footer @s -->
                <div class="nk-footer">
                    <div class="container-fluid">
                        <div class="nk-footer-wrap">
                            <div class="nk-footer-copyright"> &copy; 2022 Gestión de Documentos. Todos los derechos
                                reservados.
                            </div>
                        </div>
                    </div>
                </div>
                <!-- footer @e -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
    <!-- JavaScript -->
    <script src="{{ asset('assets/js/bundle.js?ver=2.9.0') }}"></script>
    <script src="{{ asset('assets/js/scripts.js?ver=2.9.0') }}"></script>
    @yield('scripts')
    <script>
        $(function() {
            'use strict';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $.ajax({
                type: 'GET',
                url: '{{ url('/notificaciones-json') }}',
                dataType: 'json',
                data: {},
                success: function(response) {
                    $.each(response.data, function(idx, el) {
                        let time = new Date(el.created_at).toLocaleDateString();
                        $('.nk-quick-nav .notification-dropdown .dropdown-menu .dropdown-body .nk-notification')
                        .append('<div class="nk-notification-item dropdown-inner"><div class="nk-notification-icon"><em class="icon icon-circle bg-success-dim ni ni-curve-down-right"></em></div><div class="nk-notification-content"><div class="nk-notification-text">'+el.descripcion+'</div><div class="nk-notification-time"><strong>'+time+'</strong></div></div></div>');
                    });

                },
                error: function(response) {
                    // console.log(response);
                }
            });
        });
    </script>
</body>

</html>
