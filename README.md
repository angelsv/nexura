# Nexura: Prueba técnica

Realizado por:

- Ángel Silva

## Instalación

Clonar el repositorio:

```bash
# Crear un directorio para ubicar los archivos
mkdir -p /directorio/para/clonar/

# Navegar al directorio creado
cd /directorio/para/clonar/

# Clonar el repositorio
git clone https://github.com/angelsv/nexura.git

# Ingresar a la carpeta con el repo clonado
cd nexura

# Por defecto, se clona en la rama "main"
```

## Configuración inicial

Requiere [composer](https://getcomposer.org)

```bash
# Instalar dependencias
composer install
```

Instale la base de datos
Requiere [mysql](https://dev.mysql.com)

```bash
# Instalación de la base de datos
mysql -u [user] -p -h [localhost] < database/initial_script.sql
```

Cambiar los **parámetros de conexión** para su entorno:

```bash
# Instalación de la base de datos
nano .env
```

```env
# DB Connection
DB_HOST='localhost'
DB_NAME='nexura'
DB_USER='user_db'
DB_PASSWORD='pass_db'
```

Visualización:

Inicie su servidor PHP y navegue hasta:
[localhost/public](localhost/public)