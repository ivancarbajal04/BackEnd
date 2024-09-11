1. Clonar
- Se debera clonar la rama master con el comando 'git clone -b master https://github.com/tu-usuario/tu-repositorio-backend.git'

2. Instalar Dependencias
- Antes de ejecutar el proyecto, se debe tener instalado php, composer y mysql.
- Una vez dentro del proyecto clonado, ejecutar 'composer install' en la terminal

3. Entorno
- Asegurarse de que el archivo env tenga la siguiente informacion:

APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:t4+p7Wes92EMDXJooeAROXlMkX+9J8hDjGDZv4gTnm8=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
<!-- APP_MAINTENANCE_STORE=database -->

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=challenge
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

4. Generar claves
- Ejecutar en la consola del proyecto 'artisan key:generate', esto va a servir para generar los token, autenticacion y sesiones.

5. Migrar las bases de datos
- Para poder tener la base de datos utilizada en el proyecto, en la consola debera ejecutar 'php artisan migrate', y para obtener los datos de prueba 'php artisan db:seed'
- Recorda que para este paso debes tener instalado y ejecutado xampp (apache y mysql)

6. Verificacion
- Ya deberia estar listo el servidor para ejecutarse con 'php artisan serve'

7. Contacto
- Para cualquier pregunta o comentario, escribir a ivanncarbajal@hotmail.com

