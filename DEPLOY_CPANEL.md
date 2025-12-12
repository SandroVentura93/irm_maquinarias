# Despliegue en cPanel — Guía práctica

Este documento reúne los pasos para preparar y desplegar la aplicación Laravel en un hosting con cPanel.
Está pensado para minimizar cambios en servidor y dejar la aplicación lista para producción.

**Resumen rápido**
- Preparar entorno local: composer (sin dev), compilar assets, generar `APP_KEY`.
- Subir archivos al hosting (ZIP o Git), apuntar el documento raíz a `public/`.
- Configurar `.env` en cPanel (o gestor de aplicaciones), permisos, y ejecutar migraciones.

---

**1) Comprobaciones locales (antes de subir)**
- No subir `.env` con credenciales. Mantén `.env.example` actualizado.
- Revisa `composer.json` para remover paquetes que no vayas a usar en producción (ej.: API externas). Si quieres eliminar paquete:

```powershell
# Desde PowerShell en la carpeta del proyecto
composer remove vendor/package-name --no-interaction
```

- Genera `APP_KEY` si no está:

```powershell
php artisan key:generate --show
# Copia el valor y ponlo en el .env de producción (o en cPanel variables de entorno)
```

**2) Preparar dependencias y assets (local recomendado)**
- Instala dependencias de PHP sin dev y optimiza autoload:

```powershell
composer install --no-dev --optimize-autoloader --no-interaction
```

- Compila assets front-end (si usas Laravel Mix/Vite):

```powershell
# si usas npm
npm ci
npm run prod
# o si usas yarn
# yarn install --frozen-lockfile
# yarn build
```

- Verifica que `public/js`, `public/css` y `mix-manifest.json` están actualizados.

**3) Limpieza y cachés**
Ejecuta localmente (o en servidor si tienes acceso SSH):

```powershell
php artisan view:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**4) Preparar `vendor/` y paquete para subir**
- Si tu hosting NO tiene Composer o no puedes ejecutar `composer install` en servidor, sube la carpeta `vendor/` ya generada.
- Empaqueta el proyecto en un ZIP listo para subir. Excluye `.git`, `.env`, `node_modules` (si ya subiste assets compilados) y archivos locales.

Ejemplo con PowerShell (desde la raíz del proyecto):

```powershell
# Crear ZIP excluyendo cosas comunes
Compress-Archive -Path * -DestinationPath deploy_package.zip -CompressionLevel Optimal
# Nota: Ajusta para excluir .git y otros si es necesario; en Windows puede que necesites un script más avanzado.
```

**5) Subir y configurar en cPanel**
- En cPanel -> File Manager, sube `deploy_package.zip` a la carpeta del dominio/subdominio.
- Extrae los archivos.
- IMPORTANTE: Cambia el Document Root (Dominios -> Editor de zonas o al crear subdominio) para que apunte a la carpeta `public/` de la aplicación. Por ejemplo: `/home/usuario/project/public`.
- Si no puedes cambiar Document Root, mueve el contenido de `public/` al `public_html/` y ajusta `index.php` paths (no recomendado, mejor cambiar doc root).

**6) `.env` en servidor**
- Crea un archivo `.env` en la raíz del proyecto (no en `public/`) con las variables de producción: `APP_ENV=production`, `APP_DEBUG=false`, `APP_KEY`, DB credentials, MAIL, etc.
- En cPanel puedes usar la sección "Setup Node.js App" (si disponible) o "Variables de entorno" según versión; si no, crea `.env` manualmente.

**7) Permisos**
Establece permisos en `storage/` y `bootstrap/cache`:

```bash
# vía SSH (si disponible)
cd /home/usuario/project
chown -R usuario:usuario storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

Si no hay SSH, usa File Manager para ajustar permisos (775 o 755 según soporte).

**8) Enlaces simbólicos de `storage`**
- `php artisan storage:link` crea `public/storage` apuntando a `storage/app/public`.
- Si cPanel no permite symlinks, copia `storage/app/public/*` a `public/storage/` manualmente.

**9) Migraciones y seeders**
Desde SSH (si disponible):

```bash
php artisan migrate --force
php artisan db:seed --force
```

Si no hay SSH, puedes ejecutar migraciones desde un endpoint temporal (no recomendado) o pedir al hosting que ejecute los comandos.

**10) Tareas programadas y colas**
- Scheduler: añade cron en cPanel (Cron Jobs) que ejecute cada minuto:

```
* * * * * php /home/usuario/project/artisan schedule:run >> /dev/null 2>&1
```

- Colas: en cPanel no hay supervisor; recomendamos usar `queue:work` con cron o servicios externos (Laravel Horizon requiere supervisor).

**11) PDF y dependencias nativas**
- DomPDF y otras bibliotecas pueden requerir extensiones PHP (gd, mbstring, zip). En cPanel asegúrate de que la versión de PHP y extensiones coincidan.
- Desde cPanel -> Select PHP Version, marca `gd`, `mbstring`, `zip`, `intl` si son necesarias.

**12) Ajustes finales y pruebas**
- Prueba la web en modo producción: rutas, login, subida de archivos, generación de PDF.
- Revisa `storage/logs/laravel.log` para errores.

---

**Consejos específicos para este proyecto**
- Si eliminaste una integración externa (Sunat API), asegúrate de:
  - Remover del `composer.json` y ejecutar `composer update`.
  - Borrar o comentar controladores y servicios que lo referencien.
  - Verificar `config/` y `services.php` por claves relacionadas.

- Si no quieres ejecutar `composer` en el hosting, sube `vendor/` pero **asegúrate** de no incluir archivos de desarrollo sensibles.

---

**Comandos locales (PowerShell) — resumen**
```powershell
# Preparar
composer install --no-dev --optimize-autoloader
npm ci
npm run prod
php artisan key:generate --show  # copiar APP_KEY a .env del servidor
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link  # si vas a ejecutar en servidor o copiar manualmente
```

---

Si quieres, puedo:
- Generar automáticamente un ZIP listo para subir (excluyendo `.env` y `.git`).
- Auditar y eliminar referencias a la API de Sunat (hacer los `composer remove` y parchar el código).
- Ejecutar localmente `composer install --no-dev` y compilar assets si me autorizas a correr esos comandos en tu entorno.

Dime qué prefieres que haga ahora: crear el ZIP, auditar el código para remover la API de Sunat, o ejecutar los comandos de preparación localmente.