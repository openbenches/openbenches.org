# Local Docker Development

This guide was set up using Windows 10, WSL2 and Docker Desktop.

## Container structure
Issuing the command :

```console
$ docker-compose up -d
```
will buid the following containers

### database
This is a [mariadb](https://hub.docker.com/_/mariadb) instance, based on the default latest tag.  It exposes port 3306, allowing host tooling to communicate with the mariadb instance.  Some success has been achieved using (MySQL workbench)[https://www.mysql.com/products/workbench/] - however there are warnings that are displayed when connecting 

![Warning displayed when trying to connect from MySQL WorkBench to MariaDB container](https://github.com/computamike/openbenches.org/assets/464876/e5801a05-8a3e-468f-9e7b-de663e61c7b8)

As part of the container start up, the Database scripts are run, seeding the database with (at the time of writing 1700 benches)

### NGINX
An Nginx proxy has been set up to serve static contant from the site.  It listens to port 80, and serves static content from the public folder - typically CSS, Fonts, images etc.

Requests for PHP exection are passed through to the PHP FPM container





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
