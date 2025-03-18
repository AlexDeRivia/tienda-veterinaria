@php
    $active = "active bg-darg bg-gradient";
@endphp

<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Principal</div>
                <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.home') ? $active : '' }}" href="{{ route('admin.home') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-house"></i></div>
                    Inicio
                </a>
                <div class="sb-sidenav-menu-heading">Personal</div>
                <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.perfil') ? $active : '' }}" href="{{ route('admin.perfil') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-user"></i></div>
                    Mi cuenta
                </a>
                <div class="sb-sidenav-menu-heading">Opciones</div>
                <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.clientes') ? $active : '' }}" href="{{ route('admin.clientes') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-user-group"></i></div>
                    Clientes
                </a>
                <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.productos') ? $active : '' }}" href="{{ route('admin.productos') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-bone"></i></div>
                    Productos
                </a>
                <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.ventas') || Str::startsWith(Route::currentRouteName(), 'admin.factura') ? $active : '' }}" href="{{ route('admin.ventas') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                    Ventas
                </a>
                <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.proveedores') ? $active : '' }}" href="{{ route('admin.proveedores') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-truck"></i></div>
                    Proveedores
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logueado como:</div>{{ $email }}
        </div>
    </nav>
</div>
