<div class="nk-sidebar nk-sidebar-fixed is-light " data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand">
            <a href="javascript:void();" class="logo-link nk-sidebar-logo">
                <img class="logo-light logo-img logo-img-lg" src="{{ asset('images/logo alpasahd.jpg') }}" width="180%" alt="logo">
                <img class="logo-dark logo-img logo-img-lg" src="{{ asset('images/logo alpasahd.jpg') }}" width="180%" alt="logo-dark">
            </a>
        </div>
        <div class="nk-menu-trigger mr-n2">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em
                    class="icon ni ni-arrow-left"></em></a>
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex"
                data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
        </div>
    </div><!-- .nk-sidebar-element -->
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">Menu</h6>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item">
                        <a href="{{ url('/dashboard') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-bag"></em></span>
                            <span class="nk-menu-text">Dashboard</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    @if (Auth::user()->rol->name == 'Administrador')
                    <li class="nk-menu-item">
                        <a href="{{ url('/documentos') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-book-read"></em></span>
                            <span class="nk-menu-text">Documentos</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    @endif

                    @if (Auth::user()->rol->name == 'Operador')
                    <li class="nk-menu-item">
                        <a href="{{ url('/mis-documentos') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-book-read"></em></span>
                            <span class="nk-menu-text">Mis Documentos</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    @endif
                </ul><!-- .nk-menu -->
            </div><!-- .nk-sidebar-menu -->
        </div><!-- .nk-sidebar-content -->
    </div><!-- .nk-sidebar-element -->
</div>
