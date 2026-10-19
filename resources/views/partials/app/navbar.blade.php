<nav class="navbar navbar-expand-lg store-nav sticky-top" aria-label="Navegación principal">
    <div class="container">
        <a class="navbar-brand store-brand" href="{{ route('home') }}" aria-label="Tienda de mascotas, inicio">
            <span class="brand-symbol" aria-hidden="true">+</span><span>Tienda<br><strong>de mascotas</strong></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav" aria-controls="publicNav" aria-expanded="false" aria-label="Abrir menú">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="publicNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Inicio</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('catalogo*') ? 'active' : '' }}" href="{{ route('catalogo') }}" @if(request()->routeIs('catalogo*')) aria-current="page" @endif>Catálogo</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('nosotros') ? 'active' : '' }}" href="{{ route('nosotros') }}" @if(request()->routeIs('nosotros')) aria-current="page" @endif>El proyecto</a></li>
                @if (auth('users')->check())
                    @if ((int) auth('users')->user()->role_id === 1)
                        <li class="nav-item"><a class="btn btn-outline-primary" href="{{ route('admin.home') }}">Administración</a></li>
                    @else
                        <li class="nav-item"><a class="btn btn-outline-primary" href="{{ route('cliente.perfil') }}">Mi cuenta</a></li>
                    @endif
                    <li class="nav-item"><form action="{{ route('logout.auth') }}" method="post">@csrf<button class="btn btn-primary" type="submit">Salir</button></form></li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Ingresar</a></li>
                    <li class="nav-item"><a class="btn btn-primary" href="{{ route('register') }}">Crear cuenta</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>
