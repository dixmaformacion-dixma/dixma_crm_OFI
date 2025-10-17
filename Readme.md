markdown
# Proyecto PHP Sencillo

Este es un proyecto PHP sencillo sin frameworks. Este archivo explica cómo instalarlo y configurarlo en **Windows** (con XAMPP) y **Linux** (con Nginx).

## Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:
- PHP (versión 7.4 o superior)
- Un servidor web:
  - **Windows**: XAMPP (contiene Apache y MySQL)
  - **Linux**: Nginx
- MySQL o MariaDB
- Un navegador para probar el proyecto

---

## Instalación en Windows (con XAMPP)

### Paso 1: Descargar e Instalar XAMPP
1. Ve al sitio oficial de [XAMPP](https://www.apachefriends.org/).
2. Descarga XAMPP para Windows y sigue el instalador.
3. Asegúrate de iniciar los servicios **Apache** y **MySQL** desde el Panel de Control de XAMPP.

### Paso 2: Clonar o Descargar el Proyecto
1. Descarga este repositorio clónalo usando Git:
   bash
   git clone https://github.com/isoftgest/dixma.git
   
2. Extrae o coloca los archivos del proyecto en la carpeta `htdocs` dentro del directorio de instalación de XAMPP. Por ejemplo:
   
   C:\xampp\htdocs\dixma
   

*Paso 3: Configurar la Base de Datos*
1. Abre *phpMyAdmin* desde [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
2. Crea una nueva base de datos, por ejemplo, `dixma`.
3. Importa el archivo `database.sql` incluido en el proyecto para configurar las tablas.
4. Copia el fichero C:\xampp\htdocs\dixma\funciones\conexionDB-sample.php como conexionDB.php
4. Coloca las credenciales de base de datos en C:\xampp\htdocs\dixma\funciones\conexionDB.php

*Paso 4: Probar el Proyecto*
1. Abre tu navegador y ve a:
   
   http://localhost/dixma
   

---

*Instalación en Linux (con Nginx)*

*Paso 1: Instalar Nginx, PHP y MySQL*
Ejecuta los siguientes comandos para instalar los paquetes necesarios:
bash
sudo apt update
sudo apt install nginx php-fpm php-mysql mysql-server


*Paso 2: Configurar Nginx*
1. Crea un bloque de servidor para el proyecto. Abre un archivo de configuración nuevo:
   bash
   sudo nano /etc/nginx/sites-available/dixma
   
2. Añade lo siguiente al archivo:
   
   server {
       listen 80;
       server_name localhost;

       root /var/www/html/dixma;
       index index.php index.html;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location ~ \.php$ {
           include snippets/fastcgi-php.conf;
           fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
           include fastcgi_params;
       }
   }
   
3. Guarda y cierra el archivo.
4. Habilita la configuración del sitio:
   bash
   sudo ln -s /etc/nginx/sites-available/dixma /etc/nginx/sites-enabled/
   
5. Reinicia Nginx:
   bash
   sudo systemctl restart nginx
   

*Paso 3: Clonar o Descargar el Proyecto*
1. Descarga este repositorio o clónalo:
   bash
   git clone https://github.com/isoftgest/dixma.git
   
2. Copia los archivos del proyecto al directorio `/var/www/html/dixma`:
   bash
   sudo cp -r dixma-sencillo /var/www/html/dixma
   
3. Asegúrate de que los permisos sean correctos:
   bash
   sudo chown -R www-data:www-data /var/www/html/dixma
   

*Paso 4: Configurar la Base de Datos*
1. Accede al cliente MySQL:
   bash
   sudo mysql -u root -p
   
2. Crea una base de datos, por ejemplo, `dixma`:
   sql
   CREATE DATABASE dixma;
   
3. Sal del cliente MySQL y usa la herramienta `mysql` para importar el archivo `database.sql` incluido:
   bash
   mysql -u root -p dixma < /var/www/html/dixma/database.sql
   

*Paso 5: Probar el Proyecto*
1. Abre tu navegador y ve a:
   
   http://localhost
   

---

*Instalacion de CRON*
crea una tarea programada o CronTab para ejecutar la migracion de creditos el 1 de enero de cada año.

bash
   crontab -e
   0 0 31 12 * /usr/bin/php moverCreditos.php
