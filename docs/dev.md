# Local Docker Development

This guide was set up using Windows 10, WSL2 and Docker Desktop.

## Container structure

### Database
This is a [mariadb](https://hub.docker.com/_/mariadb) instance, based on the default latest tag.  It exposes port 3306, allowing host tooling to communicate with the mariadb instance.  Some success has been achieved using [MySQL workbench](https://www.mysql.com/products/workbench/) - however there are warnings that are displayed when connecting 

![Warning displayed when trying to connect from MySQL WorkBench to MariaDB container](assets/257367381-e5801a05-8a3e-468f-9e7b-de663e61c7b8.png)

As part of the container start up, the Database scripts are run, seeding the database with (at the time of writing) 1664 benches.

### Apache
An Apache proxy has been set up to serve static contant from the site.  It listens to port 80 and 443, and serves static content from the public folder - typically CSS, Fonts, images etc

Requests for PHP execution are passed through to the PHP FPM container.

Apache builds a Self Signed SSL certificate - which has an expiration date of a year.  In the event that your SSL certificate expires, then a new SSL certificate can be created using 
```docker exec -it apache sh generate_ssl.sh``` and restarting the container - containers can be restarted through docker-desktop UI - or by executing the command ```docker container restart apache```

### PHP FPM Container
A [PHP FPM](https://hub.docker.com/_/php/) container is built, based on the [php:8.2-fpm-bookworm](https://hub.docker.com/layers/library/php/8.2.10-fpm-bookworm/images/sha256-47b377aa55e11f9b6aa3d1e457857cf7c5e3a480760afaba8ff1bc129cc2e15f?context=explore) container image - this script installs `libmagickwand`, `zip`, and sets up some PECL extensions.

It installs [Composer](https://getcomposer.org/), and copies the www project code to `/var/www/symfony_docker`

## Volume Mapping
When instantiating the containers, custom volume mapping is used to mount parts of the project source to the containers.  Ths has the advantage that code can be modified within the project, and those changes are shared onto the executing containers, allowing developers the ability to modify PHP code or configuration, and (in the case of PHP code) see those changes reflected straight away.  For configuration changes, the container would need to be rebuilt.

### Apache
- ```www``` folder is mapped to ```/var/www/symfony_docker```
- ```apache/httpd.conf``` - an Apache configuration file is mounted into the Apache container at ```/usr/local/apache2/conf/httpd.conf```
- ```apache/openbenches.conf``` - an Apache virtual host configuation file is mounted onto the Apache container at ```/usr/local/apache2/conf/extra/openbenches.conf```, setting up root directories, access and error log locations, and passing PHP requests through to the php container, on port 9000.

### PHP
- ```www``` folder is mapped to ```/var/www/symfony_docker```
- ```docker_config/www/.env.docker``` is mapped to /var/www/.env.docker
- ```docker_config/www/config/packages/cache.yaml``` is mapped to ```/var/www/symfony_docker/config/packages/cache.yaml``` as read only
  - Configures the caching to use ```/tmp``` to store cache data.


## Setting up the Docker Environment
### Building the containers

```console
docker-compose up -d
```
will buid the containers, and automatically seed the database structure and some limited test data.  Apache will be running on port 80 (and 443), so navigating to http://localhost will send a request to Apache, and from there to PHP - however none of the dependancies are installed yet.  Loading the page in this state would throw the following error
![Error message shown when dependencies are not loaded.](assets/257641883-71c2690e-682f-4d05-832e-4f69525c29f2.png)
```
Warning: include(/var/www/symfony_docker/vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/CompatibilityAnnotationDriver.php): Failed to open stream: No such file or directory in /var/www/symfony_docker/vendor/composer/ClassLoader.php on line 576

Warning: include(): Failed opening '/var/www/symfony_docker/vendor/composer/../doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/CompatibilityAnnotationDriver.php' for inclusion (include_path='.:/usr/local/lib/php') in /var/www/symfony_docker/vendor/composer/ClassLoader.php on line 576

Fatal error: Uncaught Error: Class "Symfony\Component\ErrorHandler\ThrowableUtils" not found in /var/www/symfony_docker/vendor/symfony/error-handler/ErrorHandler.php:494 Stack trace: #0 [internal function]: Symfony\Component\ErrorHandler\ErrorHandler->handleException(Object(Error)) #1 {main} thrown in /var/www/symfony_docker/vendor/symfony/error-handler/ErrorHandler.php on line 494

Fatal error: Uncaught Error: Class "Symfony\Component\ErrorHandler\Error\FatalError" not found in /var/www/symfony_docker/vendor/symfony/error-handler/ErrorHandler.php:619 Stack trace: #0 [internal function]: Symfony\Component\ErrorHandler\ErrorHandler::handleFatalError() #1 {main} thrown in /var/www/symfony_docker/vendor/symfony/error-handler/ErrorHandler.php on line 619
```
### Installing Dependencies
To install the PHP dependencies, `composer install` needs to be run

```console
docker exec -it php composer install
```

will install the dependencies.

# Setting up the .env file.
To connect to the database, a connection string will be required. Containers are accessible via their container name - so the following connection configuration can be used.

```
DATABASE_URL="mysqli://openbenches:badpassword@database:3306/openbenc_benches?&charset=utf8mb4"
```
# Debugging using Xdebug
Xdebug is automatically installed - however for typical operation it is switched off - there is a speed cost associated with running the Xdebug debugging system.

To activate Xdebug - update the [xdebug.ini](..\docker_config\www\php\xdebug\conf.d\xdebug.ini), removing the leading `;` from the line - 

```
;zend_extension=xdebug
```

Restart the container by issueing ```docker container restart```.

## Attaching to the debugger in VS Code.
A example ```launch.json``` is provided in the project, that will allow a developer to debug php code executing on the PHP FPM container, and to use breakpoints to monitor execution.  

To use xdebug within Visual Studio, enable Xdebug on the server, and run the debug profile __Listen for Xdebug__.  Accessing the site using a browser will cause any breakpoints to be activated within VS Code.

