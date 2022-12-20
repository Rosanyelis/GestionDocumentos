<div class="nk-header nk-header-fixed is-light">
    <div class="container-fluid">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger d-xl-none ml-n1">
                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em
                        class="icon ni ni-menu"></em></a>
            </div>
            <div class="nk-header-brand d-xl-none">
                <a href="html/index.html" class="logo-link">
                    <img class="logo-light logo-img" src="{{ asset('images/logo alpasahd.jpg') }}" alt="logo">
                    <img class="logo-dark logo-img" src="{{ asset('images/logo alpasahd.jpg') }}" alt="logo-dark">
                </a>
            </div><!-- .nk-header-brand -->
            <!-- <div class="nk-header-search ml-3 ml-xl-0">
                <em class="icon ni ni-search"></em>
                <input type="text" class="form-control border-transparent form-focus-none"
                    placeholder="Search anything">
            </div>.nk-header-news -->
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                    <li class="dropdown notification-dropdown">
                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-toggle="dropdown">
                            <div class="icon-status icon-status-info"><em class="icon ni ni-bell"></em></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right">
                            <div class="dropdown-head">
                                <span class="sub-title nk-dropdown-title">Notificaciones</span>
                            </div>
                            <div class="dropdown-body">
                                <div class="nk-notification">
                                </div><!-- .nk-notification -->
                            </div><!-- .nk-dropdown-body -->
                            <div class="dropdown-foot center">
                                <a href="{{ url('/todas-las-notificaciones') }}">Ver todas</a>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle mr-n1" data-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-avatar sm">
                                    <em class="icon ni ni-user-alt"></em>
                                </div>
                                <div class="user-info d-none d-xl-block">
                                    <div class="user-status user-status-unverified">
                                        {{ Auth::user()->rol->name }}</div>
                                    <div class="user-name dropdown-indicator">{{ Auth::user()->name }}
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    <div class="user-avatar">
                                        <span>AB</span>
                                    </div>
                                    <div class="user-info">
                                        <span class="lead-text">{{ Auth::user()->name }}</span>
                                        <span class="sub-text">{{ Auth::user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="dropdown-inner">
                                <ul class="link-list">

                                </ul>
                            </div> -->
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li>
                                        <form method="POST" action="{{ url('logout') }}">
                                            @csrf
                                            <a href="#" onclick="event.preventDefault();
                                            this.closest('form').submit();">
                                                <em class="icon ni ni-signout"></em>
                                                <span>Cerrar Sesión</span>
                                            </a>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div><!-- .nk-header-wrap -->
    </div><!-- .container-fliud -->
</div>
