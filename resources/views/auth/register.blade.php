<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../../../">
    <meta charset="utf-8">
    <meta name="author" content="Dev Rosanyelis">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema de Gestión de Documentos.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.jpeg') }}">
    <!-- Page Title  -->
    <title>Registrarme | ALPASA | Gestión de Documentos y Firmas</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css?ver=2.9.0') }}">
    <link id="skin-default" rel="stylesheet"
        href="{{ asset('assets/css/theme.css?ver=2.9.0') }}">
</head>

<body class="nk-body bg-white npc-default pg-auth">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="nk-block nk-block-middle nk-auth-body  wide-xs">
                        <div class="brand-logo pb-4 text-center">
                        <a href="javascript:void();" class="logo-link">
                                <img class="logo-light logo-img logo-img-lg" src="{{ asset('images/logo alpasahd.jpg') }}" width="180%" alt="logo">
                                <img class="logo-dark logo-img logo-img-lg" src="{{ asset('images/logo alpasahd.jpg') }}" width="180%" alt="logo-dark">
                            </a>
                        </div>
                        <div class="card">
                            <div class="card-inner card-inner-lg">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h4 class="nk-block-title">Crear cuenta</h4>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-label" for="name">Nombre</label>
                                        <div class="form-control-wrap">
                                            <input type="text" name="name"
                                                class="form-control form-control-lg @error('name') invalid @enderror"
                                                id="name" value="{{ old('name') }}" required
                                                autofocus placeholder="Ingrese nombre de usuario">
                                            @if($errors->has('name'))
                                                <span id="fv-name-error" class="invalid">
                                                    {{ $errors->first('name') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="email">Correo Electrónico</label>
                                        <div class="form-control-wrap">
                                            <input type="text" name="email" class="form-control form-control-lg" id="email"
                                                value="{{ old('email') }}" placeholder="Ingrese su correo">
                                                @if($errors->has('email'))
                                                <span id="fv-email-error" class="invalid">
                                                    {{ $errors->first('email') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="password">Password</label>
                                        <div class="form-control-wrap">
                                            <a href="#" class="form-icon form-icon-right passcode-switch lg"
                                                data-target="password">
                                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                            </a>
                                            <input type="password" name="password" required autocomplete="new-password"
                                                class="form-control form-control-lg" id="password"
                                                placeholder="Ingresa tu password">
                                            @if($errors->has('password'))
                                            <span id="fv-name-error" class="invalid">
                                                {{ $errors->first('password') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-lg btn-primary btn-block">Registrarme</button>
                                    </div>
                                </form>
                                <div class="form-note-s2 text-center pt-4"> Posees una cuenta? <a
                                        href="{{ url('/') }}"><strong>Iniciar Sesion</strong></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nk-footer nk-auth-footer-full">
                        <div class="container wide-lg">
                            <div class="row g-3">
                                <div class="col-lg-6 order-lg-last">
                                    <!-- <ul class="nav nav-sm justify-content-center justify-content-lg-end">
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Terms & Condition</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Privacy Policy</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Help</a>
                                        </li>
                                    </ul> -->
                                </div>
                                <div class="col-lg-6">
                                    <div class="nk-block-content text-center text-lg-left">
                                        <p class="text-soft">&copy; 2022 Gestión de Documentos. Todos los derechos
                                            reservados.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- wrap @e -->
            </div>
            <!-- content @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
    <!-- JavaScript -->
    <script src="{{ asset('assets/js/bundle.js?ver=2.9.0') }}"></script>
    <script src="{{ asset('assets/js/scripts.js?ver=2.9.0') }}"></script>

</html>
