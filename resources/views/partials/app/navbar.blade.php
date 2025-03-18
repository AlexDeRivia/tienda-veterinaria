<nav class="navbar navbar-expand-lg bg-dark sticky-top shadow-sm" data-bs-theme="dark">

    <div class="container">

        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('img/logo/white.png') }}" height="40">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarSupportedContent" data-bs-theme="light">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" aria-current="page" href="{{ route('home') }}">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('nosotros') ? 'active' : '' }}" aria-current="page" href="{{ route('nosotros') }}">Nosotros</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-light me-3 ms-0 mx-lg-3 mb-2 mb-lg-0" href="{{ route('login') }}">Ingresar</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary mb-2 mb-lg-0" href="{{ route('register') }}">Registrarse</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
