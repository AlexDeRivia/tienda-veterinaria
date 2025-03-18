<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark shadow-sm">
    <a class="navbar-brand ps-3" href="{{ route('admin.home') }}">
        <img src="{{ asset('img/logo/white.png') }}" alt="" height="40">
    </a>
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    <ul class="navbar-nav ms-auto me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ $email }}<i class="ms-2 fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="{{ route('admin.home') }}">Inicio</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.perfil') }}">Mi cuenta</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li>
                    <form action="{{ route('logout.auth') }}" method="post">
                        @csrf
                        <input type="submit" value="Cerrar sesión" role="link" class="dropdown-item">
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
