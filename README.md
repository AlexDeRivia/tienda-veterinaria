# Tienda virtual de productos para mascotas

Aplicación Laravel con catálogo público, registro e inicio de sesión, carrito, compras de demostración, historial, recuperación de contraseña, edición de perfil y administración de productos y proveedores.

**La compra es simulada:** se registra el método elegido, pero no se cobra dinero ni existe una pasarela de pago.

## Requisitos

- PHP 8.3 o superior compatible con Laravel 13.
- MySQL 8 y la extensión pdo_mysql de PHP. Las migraciones crean disparadores, procedimientos y una vista; SQLite no sirve para este proyecto.
- Composer 2.
- Node.js y npm solo para compilar los recursos de Vite. Las vistas actuales cargan los recursos versionados de public/dist.

## Inicio local

Usa una **base de datos nueva y vacía**. No apuntes estos comandos a una base con datos que necesites conservar.

1. Instala las dependencias PHP:

       composer install

2. Copia la configuración y genera la clave:

       Copy-Item .env.example .env
       php artisan key:generate

3. Configura en .env APP_ENV=local, APP_URL y las variables DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME y DB_PASSWORD para esa base MySQL. Deja APP_DEBUG=true solo en local.
4. Crea las tablas y los datos de demostración, enlaza el almacenamiento público de imágenes e inicia el servidor:

       php artisan migrate --seed
       php artisan storage:link
       php artisan serve

Las cuentas locales de demostración son admin@example.test y cliente1@example.test; ambas usan la contraseña DemoPass123!. Se crean solo cuando APP_ENV es local o testing. Cambia las credenciales y usa datos propios antes de publicar una instancia.

El catálogo y el detalle de cada producto se pueden explorar sin cuenta. Para usar el carrito y registrar una compra de demostración hay que iniciar sesión. Un producto oculto por el administrador deja de estar disponible para compras nuevas, pero sigue en el historial de las anteriores.

Las imágenes nuevas subidas por el administrador se guardan en MySQL (máximo 512 KB por imagen), por lo que sobreviven los reinicios de Render Free. Las imágenes antiguas en `public/productos` y las rutas antiguas en `storage/app/public/products` siguen funcionando localmente. Para estas últimas sigue siendo útil `php artisan storage:link`.

La recuperación de contraseña usa el correo configurado en Laravel en local. Con `MAIL_MAILER=log`, el enlace aparece en `storage/logs/laravel.log`. En el despliegue se usa la API HTTPS de Brevo mediante `BREVO_API_KEY` y `BREVO_FROM_EMAIL`, porque Render Free bloquea los puertos SMTP habituales. `APP_URL` debe coincidir con la dirección pública HTTPS.

Para reiniciar **únicamente una base descartable**, se puede usar php artisan migrate:fresh --seed. Ese comando elimina todas sus tablas.

## Pruebas

Las pruebas de los flujos usan MySQL real. Crea una segunda base de datos local cuyo nombre termine en _test y configúrala explícitamente en el proceso de prueba. Ejemplo en PowerShell:

    $env:APP_ENV = 'testing'
    $env:DB_CONNECTION = 'mysql'
    $env:DB_HOST = '127.0.0.1'
    $env:DB_PORT = '3306'
    $env:DB_DATABASE = 'tienda_mascotas_test'
    $env:DB_USERNAME = 'root'
    $env:DB_PASSWORD = ''
    php artisan migrate:fresh --seed --force
    php artisan test

Las pruebas de base de datos se omiten si DB_DATABASE no se define explícitamente con el sufijo _test. Ejecuta migrate:fresh solo después de comprobar que la conexión apunta a la base descartable.

Para verificar la compilación de Vite:

    npm ci
    npm run build

## Despliegue gratuito de la demostración

**Demostración pública:** [tienda-mascotas-demo-mmoh.onrender.com](https://tienda-mascotas-demo-mmoh.onrender.com). El servicio usa Render Free, Aiven MySQL Free y Brevo Free. Al estar inactivo, Render puede tardar en responder a la primera visita.

Consulta [docs/deploy-render-aiven.md](docs/deploy-render-aiven.md). El archivo `render.yaml` declara un único servicio web con `plan: free`, sin vistas previas automáticas ni despliegues en cada commit. Para evitar cambios automáticos de costes, se recomienda crear el Web Service manualmente, seleccionar Free de forma explícita y desactivar Auto-Deploy. La base de datos se crea por separado en Aiven MySQL Free.

El registro público solicita solo correo, contraseña y nombre o alias. La compra sigue siendo **simulada**: ningún método de pago cobra dinero. En producción, el administrador inicial se crea una sola vez con credenciales privadas; no se insertan las cuentas de ejemplo.

Para proteger una demostración con 1 GB de MySQL, el registro admite como máximo 10 cuentas de cliente por día y 50 en total. Las compras simuladas se limitan a 10 por cliente y día, 100 por día y 500 en total. La recuperación de contraseña admite 3 correos por cuenta y día y 30 por día en toda la aplicación. Estos topes pueden dejar la demostración sin cupos; se documentan en [la auditoría de seguridad](docs/security-audit-2026-10-19.md).

El propietario confirmó que tiene permiso para publicar las imágenes actuales; comprueba la licencia de cualquier recurso que agregues después. Las cuentas de Render y Aiven no tienen tarjeta registrada al 19-10-2026. Sigue comprobando los planes y el uso gratuito; un plan Free con tarjeta agregada en Render puede producir cargos por exceso de uso.
