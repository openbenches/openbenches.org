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

![image](https://github.com/computamike/openbenches.org/assets/464876/e5801a05-8a3e-468f-9e7b-de663e61c7b8)


# Setting up the development containers.
```console
foo@bar:~$ whoami
foo
```



```console
$ docker-compose up -d
```




set up your env.local



docker-compose up -d
docker exec -it openbenchesorg-php-1 composer install
