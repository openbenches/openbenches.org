# Local Docker Development

This guide was set up using Windows 10, WSL2 and Docker Desktop.

## Container structure
Issuing the command :

```console
$ docker-compose up -d
```
will buid the following containers

### Database
This is a [mariadb](https://hub.docker.com/_/mariadb) instance, based on the default latest tag.  It exposes port 3306, allowing host tooling to communicate with the mariadb instance.  Some success has been achieved using [MySQL workbench](https://www.mysql.com/products/workbench/) - however there are warnings that are displayed when connecting 

![Warning displayed when trying to connect from MySQL WorkBench to MariaDB container](https://github.com/computamike/openbenches.org/assets/464876/e5801a05-8a3e-468f-9e7b-de663e61c7b8)

As part of the container start up, the Database scripts are run, seeding the database with (at the time of writing) 1664 benches.

### Nginx
An Nginx proxy has been set up to serve static contant from the site.  It listens to port 80, and serves static content from the public folder - typically CSS, Fonts, images etc.

Requests for PHP exection are passed through to the PHP FPM container

### PHP FPM Container
A [PHP FPM](https://hub.docker.com/_/php/) container is built, based on the php:8.2-fpm-bookworm container image - this script installs libmagickwand, zip, and sets up some PECL extensions.

It installs Symfony and Composer, and copies the www project code to /var/www/symfony_docker

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




# Setting up the development containers.
```console
foo@bar:~$ whoami
foo
```



```console
$ docker-compose up -d
```


```console
$ docker exec -it openbenchesorg-php-1 composer install
```


set up your env.local



docker-compose up -d
docker exec -it openbenchesorg-php-1 composer install
