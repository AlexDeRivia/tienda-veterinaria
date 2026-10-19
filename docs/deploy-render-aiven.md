# Demostración en Render Free + Aiven MySQL Free

La aplicación registra compras simuladas. No procesa pagos. Esta guía prepara una demostración pública pequeña, sin promesa de disponibilidad continua.

**Instancia publicada:** [tienda-mascotas-demo-mmoh.onrender.com](https://tienda-mascotas-demo-mmoh.onrender.com). El 19-10-2026 se verificaron la portada, el catálogo, la compra simulada, la edición de perfil y la entrega de un correo de recuperación. Render quedó Free/$0 sin tarjeta; Aiven, Free con coste neto actual de $0 sin tarjeta; Brevo, Free.

## Límite de gasto: cero

1. Usa cuentas **sin método de pago** en Render, Aiven y Brevo. Si cualquiera exige agregar una tarjeta para continuar, detén el despliegue. No aceptes una prueba de pago ni un cambio de plan.
2. En Aiven, selecciona **MySQL Free**, no Developer ni una prueba de un plan superior. El servicio Free ofrece 1 GB de disco y puede apagarse por inactividad.
3. En Render, crea manualmente **un solo Web Service Docker, plan Free** desde el repositorio privado. La pantalla de creación propone un plan de pago por defecto: selecciona expresamente **$0/mes**. Deja Auto-Deploy en **Off**. No agregues Postgres, Key Value, discos persistentes, workers ni dominios de pago. `render.yaml` queda como configuración reproducible de referencia; un Blueprint sincroniza cambios futuros y puede cambiar costes, por lo que el despliegue manual ofrece una revisión explícita de cada cambio.
4. En Brevo, selecciona **Free** y no actives complementos, créditos de pago ni cambios de plan. El correo sale por su API HTTPS.
5. Comprueba después de crear cada recurso que su panel indica Free/$0 y que no existe un método de pago. Revisa el uso incluido de Render con frecuencia. Si alcanzas un límite sin tarjeta, el servicio puede suspenderse hasta el siguiente período; no aumentes el plan para reactivarlo.

El código y el Blueprint no pueden imponer un límite financiero a una cuenta externa. La ausencia de método de pago es el control decisivo: [Render explica que incluso los servicios Free pueden facturar exceso de ancho de banda o minutos de compilación si hay una tarjeta](https://render.com/docs/faq). [Aiven Free no requiere tarjeta](https://aiven.io/docs/products/mysql/concepts/mysql-free-tier). [Brevo Free tiene límite diario](https://help.brevo.com/hc/en-us/articles/208589409-About-Brevo-s-pricing-plans).

## Preparación de cuentas

1. Crea una base **Aiven for MySQL Free** vacía. Copia host, puerto, nombre de base, usuario y contraseña desde Aiven. Descarga el certificado CA PEM del servicio. No los guardes en Git.
2. Crea una cuenta **Brevo Free**, registra y verifica un remitente y genera una clave de API para correo transaccional. Puedes usar un remitente de correo gratuito; Brevo puede sustituir su dirección al entregar mensajes. Haz una prueba de entrega a tu propia bandeja antes de abrir el registro público.
3. Genera la clave de Laravel localmente con `php artisan key:generate --show`. Conserva el valor `base64:...` para `APP_KEY` en Render. No lo copies en archivos versionados.
4. Elige un correo administrador real y una contraseña única de al menos 12 caracteres. No uses `admin@example.test` ni `DemoPass123!`.

## Render

Publica primero una versión del repositorio que contenga `Dockerfile`, `render.yaml` y el `composer.lock` actualizado. En Render crea un Web Service manual desde `main`, elige Docker y plan Free, configura `/health` como Health Check Path y desactiva Auto-Deploy. Revisa que el total indique **$0/mes** antes de confirmar.

Completa estas variables privadas en Render:

| Variable | Valor |
| --- | --- |
| `APP_URL` | URL HTTPS exacta del servicio `*.onrender.com` |
| `APP_KEY` | Resultado de `php artisan key:generate --show` |
| `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Datos de conexión de Aiven |
| `AIVEN_CA_BASE64` | Certificado CA PEM de Aiven codificado en base64, en una sola línea; sigue siendo un secreto de configuración |
| `BREVO_API_KEY` | Clave privada de API de Brevo |
| `BREVO_FROM_EMAIL` | Remitente registrado y verificado en Brevo |
| `BOOTSTRAP_ADMIN_EMAIL`, `BOOTSTRAP_ADMIN_PASSWORD` | Cuenta inicial privada del administrador |

El arranque exige `APP_URL` HTTPS, el certificado CA y la configuración de correo. Ejecuta `migrate --force` (nunca `migrate:fresh`), inserta el catálogo de ejemplo de forma idempotente y crea el administrador solo si aún no existe. Cuando el primer despliegue funcione, **elimina `BOOTSTRAP_ADMIN_PASSWORD` de Render**; los reinicios posteriores preservan la cuenta y no cambian su contraseña.

`AIVEN_CA_BASE64` se obtiene codificando el archivo PEM descargado desde Aiven; en PowerShell: `[Convert]::ToBase64String([IO.File]::ReadAllBytes('ca.pem'))`. Esta codificación solo permite poner el certificado en una línea; el valor debe seguir guardándose como variable privada. La clave de Brevo debe tener acceso restringido a **Emails transaccionales**. Brevo permite elegir la categoría, pero la opción `Todos` de esa categoría también concede gestión de plantillas y registros; revisa y rota la clave si deja de ser necesaria.

`SESSION_DRIVER=database` preserva el inicio de sesión entre suspensiones de Render. Las imágenes nuevas están en MySQL y tienen un límite de 512 KB cada una; vigila el disco de 1 GB de Aiven. Los archivos locales nuevos se perderían al suspenderse Render, por lo que no se usan para las imágenes nuevas.

El registro público tiene límites persistentes de 10 clientes por día y 50 en total; las compras simuladas y los correos de recuperación también tienen cuotas en MySQL. Consulta [la auditoría de seguridad](security-audit-2026-10-19.md) para las cifras, evidencia y riesgos residuales. Los límites de Render Free no garantizan disponibilidad ante tráfico masivo; con la cuenta sin tarjeta, el servicio puede suspenderse al agotar sus cupos.

## Comprobación pública

1. Abre `/health`, `/`, `/catalogo` y un producto. La primera visita después de inactividad puede tardar cerca de un minuto.
2. Entra con la cuenta administradora y confirma el catálogo. Sube una imagen pequeña a un producto, espera a que el servicio se suspenda o redespliega manualmente y comprueba que la imagen sigue visible.
3. Registra una cuenta de prueba con alias y correo propio. Añade un producto al carrito y termina una **compra simulada**. Confirma historial y factura.
4. Solicita recuperación de contraseña a tu correo y comprueba que llega el enlace HTTPS. Si Brevo no entrega, resuelve el remitente y la clave antes de compartir la URL.
5. Comprueba de nuevo los tres paneles: plan Free, uso incluido y ausencia de método de pago.

Render Free suspende la aplicación después de 15 minutos sin tráfico y puede reiniciarla. También limita el tráfico iniciado por el servicio hacia Aiven y Brevo. Aiven Free puede apagarse por inactividad. Estas limitaciones son propias de los [planes gratuitos de Render](https://render.com/docs/free) y [Aiven](https://aiven.io/docs/products/mysql/concepts/mysql-free-tier).
