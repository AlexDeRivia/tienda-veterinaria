# Auditoría de seguridad previa al despliegue — 19 de octubre de 2026

## Alcance y método

Revisión de la aplicación Laravel 13/PHP 8.3/MySQL, sus rutas web, controladores, vistas, migraciones, dependencias bloqueadas, imagen Docker y Blueprint de Render. Base de partida: `cb207d5` más los cambios locales de recuperación y despliegue. Se examinó código estático, configuración y el historial Git disponible; no se efectuó un ataque activo contra servicios externos.

Referencias de control: [OWASP Top 10 2025](https://owasp.org/projects/top-ten/), [OWASP API Security Top 10 2023](https://owasp.org/www-project-api-security/) y [OWASP ASVS 5.0.0](https://owasp.org/www-project-application-security-verification-standard/). Solo se revisaron los controles pertinentes a esta aplicación; esto no representa una certificación ASVS.

Herramientas y comprobaciones ejecutadas:

- Búsqueda de secretos y artefactos sensibles con `rg` y `git grep` en árbol actual e historial disponible. No se encontró un secreto real versionado. Se retiró del árbol actual un SQL legado de otro proyecto que no contenía `INSERT`, contraseñas ni claves; permanece en el commit inicial histórico.
- `composer audit --locked --no-dev --format=summary`: ningún aviso de seguridad en dependencias PHP de producción.
- `npm audit --audit-level=moderate`: cero avisos después de actualizar el conjunto de herramientas de desarrollo. `npm run build`: exitoso con Vite 8; persisten avisos de deprecación Sass/Bootstrap, sin fallo de compilación.
- `php artisan migrate --force` sobre MySQL local 8.0 y `php artisan test`: 25 pruebas, 207 aserciones, todas correctas.
- Revisión visual de Render y Aiven: Render Hobby sin tarjeta, servicio web previsto como Free; Aiven MySQL aparece en Free (1 GB) y facturación actual de $0. La API de correo Brevo y el sitio público aún no se verificaron al redactar este informe.

Gitleaks, Semgrep, ZAP y un análisis dinámico de producción no se ejecutaron: las herramientas no estaban disponibles o aún no había despliegue. La ausencia de resultados de estas herramientas no implica ausencia de vulnerabilidades.

## Arquitectura y límites de confianza

La parte pública expone catálogo, registro, autenticación y recuperación de contraseña. Un cliente autenticado usa carrito y compras simuladas. El administrador puede editar catálogo y proveedores. Laravel consulta MySQL de Aiven por TLS; el servicio web gratuito de Render es público. La recuperación envía mensajes por la API HTTPS de Brevo. Los activos sensibles son credenciales de usuarios y administrador, sesiones, tokens de recuperación, secretos de Aiven/Brevo y datos de compras.

Las entradas externas pasan por validación Laravel, middleware de rol y consultas parametrizadas. Se verificó que una compra y una factura pertenecen al cliente de la sesión. Los formularios web usan protección CSRF; las vistas inspeccionadas no imprimen HTML sin escapar. Las imágenes nuevas solo pueden cargarse desde administración, con MIME de imagen y límite de 512 KB; se almacenan en MySQL. El `Dockerfile` sirve únicamente `public/` y `.dockerignore` excluye `.env`, SQL, `vendor`, `node_modules` y datos de ejecución.

## Resumen de hallazgos

| ID | Hallazgo | Severidad | Estado |
| --- | --- | --- | --- |
| S-01 | Registro y compras simuladas podían hacer crecer MySQL | Alta | Corregido y probado localmente |
| S-02 | Recuperación podía generar correo y coste/abuso | Alta | Corregido y probado localmente; entrega real pendiente |
| S-03 | Dependencias npm con avisos de seguridad | Media | Corregido; auditoría y build correctos |
| S-04 | Cabeceras y cifrado de sesión de producción incompletos | Media | Corregido y probado localmente |
| S-05 | Aiven permite conexiones desde cualquier IP | Media, potencial | Pendiente por IP de salida variable en Render Free |
| S-06 | La protección ante tráfico masivo depende de límites Free externos | Media, potencial | Riesgo residual |

No se confirmó una vulnerabilidad de autorización, inyección SQL, XSS, CSRF o exposición de credenciales en el código revisado. Esto es una conclusión acotada al alcance y pruebas anteriores.

## Detalle y evidencia

### S-01 — Altas y compras simuladas

**Observación:** las rutas de registro y compra inicialmente podían escribirse repetidamente. Un atacante podía llenar MySQL Free o agotar cupos de demostración. **Cambio:** `routes/web.php` y `app/Providers/RouteServiceProvider.php` aplican límites globales/de usuario; `app/Http/Controllers/HomeController.php` serializa registros con bloqueo de la fila de rol y restringe a 10 cuentas por día y 50 en total; `app/Http/Controllers/Cliente/PurchaseController.php` limita a 10 compras por cliente/día, 100 por día y 500 en total. La base de datos es el control final aunque se evada la caché temporal de Render. **Prueba:** `StoreWorkflowTest` verifica ambos topes de cuentas y los flujos de compra. **Riesgo residual:** un atacante todavía puede ocupar los cupos permitidos y denegar el registro legítimo; revisar periódicamente cuentas y uso. Referencias: OWASP API4:2023 y ASVS de límites de recursos.

### S-02 — Solicitudes de correo

**Observación:** cada cuenta conocida podía solicitar varios correos de recuperación. **Cambio:** `app/Services/PasswordResetEmailQuota.php` y la migración de cuotas conservan en MySQL un máximo de 3 intentos por cuenta/día y 30 por aplicación/día, con bloqueo para concurrencia; `PasswordResetController` responde de forma genérica para cuentas inexistentes o cupos agotados. El broker además limita la generación de tokens a una vez por minuto. **Prueba:** prueba de cuota MySQL y envío HTTP simulado a Brevo. **Riesgo residual:** todavía falta probar el remitente verificado, la clave y la entrega real de Brevo Free. Referencias: OWASP API4:2023 y ASVS de recuperación de credenciales.

### S-03 — Dependencias de desarrollo

**Observación:** la primera auditoría npm detectó 12 avisos (2 moderados, 9 altos, 1 crítico) en la cadena de compilación, que no se incluye en la imagen de producción. **Cambio:** `package.json` y `package-lock.json` actualizados; Vite 8, `laravel-vite-plugin` 3 y Sass actual. **Prueba:** `npm audit --audit-level=moderate` informó cero vulnerabilidades y `npm run build` terminó. `composer audit --locked --no-dev` no informó avisos. **Riesgo residual:** nuevas alertas futuras requieren revisión y actualización.

### S-04 — Endurecimiento HTTP y sesiones

**Observación:** faltaban cabeceras de defensa adicionales y cifrado explícito de sesiones al usar MySQL. **Cambio:** `app/Http/Middleware/SecurityHeaders.php` añade `nosniff`, rechazo de marcos, política de referencia/permisos, restricciones CSP de objetos/base/marcos y HSTS por HTTPS. `render.yaml` fija `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`, `SESSION_ENCRYPT=true`; las cookies son HttpOnly/SameSite Lax. **Prueba:** aserción de cabeceras y pruebas funcionales. **Riesgo residual:** la CSP es parcial y todavía permite scripts/estilos existentes; una CSP completa necesitaría adaptar plantillas y probarlas en navegador.

### S-05 — Acceso a MySQL desde Internet

**Observación:** Aiven muestra `IP address allowlist: Open to all`; exige TLS y la aplicación verifica el certificado CA. Con las credenciales de base de datos, un tercero podría conectarse desde otra red. **Estado:** potencial, no se demostró acceso no autorizado. **Acción recomendada:** usar usuario de aplicación con privilegios mínimos si el plan lo permite, rotar credenciales tras cualquier exposición y restringir IP cuando se disponga de salida estática compatible. Render Free no ofrece una IP de salida fija para una lista estable. Nunca publicar host+usuario+contraseña juntos.

### S-06 — Tráfico masivo hacia el servicio gratuito

**Observación:** ningún límite dentro de PHP puede impedir que un volumen grande de solicitudes consuma horas, ancho de banda o conexiones antes de llegar a la aplicación. Los límites de escritura anteriores evitan gran parte del crecimiento de registros, compras y correos; el caché `file` se pierde al reiniciar Render. **Estado:** potencial. **Acción recomendada:** mantener cuentas sin tarjeta, vigilar uso y suspender el servicio si se detecta abuso. El servicio Free puede dejar de estar disponible al agotar su cuota. La protección de acceso no debe interpretarse como garantía de disponibilidad.

## Comprobaciones parciales y plan

1. **Antes de compartir la URL:** comprobar en navegador HTTPS, cookies, rutas, autorización y recuperación de contraseña con una cuenta propia; verificar que Render, Aiven y Brevo indiquen Free y carezcan de método de pago.
2. **Después del despliegue:** revisar logs de Render sin imprimir secretos, comprobar migraciones en MySQL 8.4 y observar consumo de Aiven. La prueba local fue MySQL 8.0.
3. **Mantenimiento:** ejecutar periódicamente `composer audit --locked --no-dev` y `npm audit`, y revisar actualizaciones de imagen PHP. Limpiar cuentas y sesiones de demostración si se acerca el límite de 1 GB.
4. **Mejora posterior:** una auditoría dinámica autorizada con ZAP en un entorno aislado y una CSP más estricta; considerar protección de borde contra bots si el tráfico público lo exige y existe una opción gratuita con límites claros.

## Lista previa a publicación

| Control | Estado |
| --- | --- |
| Pruebas MySQL locales y compilación | Completado |
| Auditorías de Composer y npm | Completado al 19-10-2026 |
| No subir `.env`, claves ni datos generados a Git | Comprobado en los archivos revisados; repetir al confirmar el commit |
| Render Web Service Free, sin tarjeta | Verificado en panel; confirmar al crear el servicio |
| Aiven MySQL Free y coste actual $0, sin tarjeta | Verificado en panel; confirmar después del despliegue |
| Brevo Free, remitente verificado y clave restringida a email transaccional | Completado; entrega real pendiente |
| HTTPS, inicio de sesión y compra simulada pública | Pendiente |
| Quitar `BOOTSTRAP_ADMIN_PASSWORD` tras el primer inicio | Pendiente |
