<footer class="store-footer">
    <div class="container">
        <div class="store-footer-main">
            <div><h2>Tienda de mascotas<span>.</span></h2><p>Un proyecto de comercio electrónico para explorar productos y probar una compra de principio a fin.</p></div>
            <nav aria-label="Enlaces del pie de página"><a href="{{ route('home') }}">Inicio</a><a href="{{ route('catalogo') }}">Catálogo</a><a href="{{ route('nosotros') }}">El proyecto</a><a href="{{ route('login') }}">Ingresar</a></nav>
        </div>
        <div class="store-footer-bottom">
            <small>© {{ now()->year }} Alex Alcantara · Tienda Veterinaria</small>
            <div class="store-footer-meta">
                <span>Sin cobros ni pedidos reales</span>
                <a class="store-footer-source" href="https://github.com/AlexDeRivia/tienda-veterinaria" target="_blank" rel="noopener noreferrer" aria-label="Ver el código completo en GitHub (abre en otra pestaña)">
                    <i class="fab fa-github" aria-hidden="true"></i>
                    <span>Ver código en GitHub</span>
                </a>
            </div>
        </div>
    </div>
</footer>
