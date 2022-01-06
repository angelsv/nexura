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

Requiere:

- [composer](https://getcomposer.org)
- [mysql](https://dev.mysql.com)
- [apache](https://apache.org/)

Procesos previos al arranque:

```bash
# Instalar dependencias
composer install
```

Instale la base de datos

```bash
# Instalación de la base de datos
mysql -u [user] -p -h [localhost] < database/initial_script.sql
```

Cambiar los **parámetros de conexión** para su entorno:

```bash
# Edición del archivo con las variables
nano .env
```

```env
# DB Connection
DB_HOST='localhost'
DB_NAME='nexura'
DB_USER='user_db'
DB_PASSWORD='pass_db'
```

## Ejecución

Inicie su servidor PHP y navegue hasta:

[localhost/public](localhost/public)

## Documentación relacionada

[Prueba técnica](https://github.com/angelsv/nexura/blob/main/docs/pdf/Prueba%20tecnica%20-%20Dev%20PHP.pdf)