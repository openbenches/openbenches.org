# Local Docker Development

This guide was set up using Windows 10, WSL2 and Docker Desktop.

## Container structure

### Database
This is a [mariadb](https://hub.docker.com/_/mariadb) instance, based on the default latest tag.  It exposes port 3306, allowing host tooling to communicate with the mariadb instance.  Some success has been achieved using [MySQL workbench](https://www.mysql.com/products/workbench/) - however there are warnings that are displayed when connecting 

![Warning displayed when trying to connect from MySQL WorkBench to MariaDB container](https://github.com/computamike/openbenches.org/assets/464876/e5801a05-8a3e-468f-9e7b-de663e61c7b8)

As part of the container start up, the Database scripts are run, seeding the database with (at the time of writing) 1664 benches.

### Nginx
An Nginx proxy has been set up to serve static contant from the site.  It listens to port 80, and serves static content from the public folder - typically CSS, Fonts, images etc.

Requests for PHP exection are passed through to the PHP FPM container

### PHP FPM Container
A [PHP FPM](https://hub.docker.com/_/php/) container is built, based on the [php:8.2-fpm-bookworm](https://hub.docker.com/layers/library/php/8.2.10-fpm-bookworm/images/sha256-47b377aa55e11f9b6aa3d1e457857cf7c5e3a480760afaba8ff1bc129cc2e15f?context=explore) container image - this script installs libmagickwand, zip, and sets up some PECL extensions.

It installs (Composer)[https://getcomposer.org/], and copies the www project code to /var/www/symfony_docker

## Volume Mapping
When instantiating the containers, custom volume mapping is used to mount parts of the project source to the containers.

### Nginx
- ```www``` folder is mapped to ```/var/www/symfony_docker```
- ```nginx/default.conf``` - an Nginx configuation file is mounted onto the Nginx container at ```/etc/nginx/conf.d/default.conf```, setting up root directories, access and error log locations, and passing PHP requests through to the php container, on port 9000

### PHP
- ```www``` folder is mapped to ```/var/www/symfony_docker```
- ```docker_config/www/.env.docker``` is mapped to /var/www/.env.docker
- ```docker_config/www/config/packages/cache.yaml``` is mapped to ```/var/www/symfony_docker/config/packages/cache.yaml``` as read only
  - Configures the caching to use ```/tmp``` to store cache data.


## Setting up the Docker Environment
### Building the containers

# Setting up the development containers.
Firstly instantiate the development containers with the command : 
```console
$ docker-compose up -d
```
Now to install the PHP dependencies, `composer install` needs to be run

```console
$ docker exec -it php composer install
```

This will install the dependencies.

# Setting up the .env file.
To connecto the database, a connection string will be required.

```
DATABASE_URL="mysqli://openbenches:badpassword@database:3306/openbenc_benches?&charset=utf8mb4"
```
# Debugging using Xdebug
Xdebug is automatically installed - however for typical operation it is switched off - there is a speed cost associated with running the Xdebug debugging system.

To activate Xdebug - update the xdebug.ini, removing the leading ; from the line : 

```
;zend_extension=xdebug
```

Restart the container by issueing 