## Quickstart

OpenBenches is written in PHP and uses the Symfony framework. The database is MySQL and the social login uses Auth0. Various other 3rd party API keys are required for mapping functions, reading text from images, posting to social media, and displaying avatars.

## Symfony

* Install the latest version of Symfony from https://symfony.com/download
* Follow the instructions on that site to set up a new project
* Run the server with `symfony server:start`

### Composer Requirements

Install the latest Composer from https://getcomposer.org/download/

Install the following packages:

```
./composer.phar require "twig/twig:^3.0"
./composer.phar require symfony/twig-bundle
./composer.phar require doctrine/dbal
./composer.phar require symfony/cache
./composer.phar require symfony/apache-pack
./composer.phar require symfony/http-client
./composer.phar require symfony/mime
```

### Auth0
If you want to use social login:

* Register an application with https://Auth0.com/
* Follow the instructions on Auth0 to connect various social networks.
* Set your callback address as `https://localhost:8000/callback` (substitute `localhost` for the name or IP address of your server).
* Install [Auth0's Symfony library](https://github.com/auth0/symfony)

```
./composer.phar require auth0/symfony
```

### Social Media
If you want to post to social media (Mastodon and Twitter):

* Register for developer accounts on Twitter and Mastodon.
* Add the keys to `.env.local`
* Install the requirements:

```
./composer.phar require eleirbag89/mastodonbotphp
./composer.phar require jublonet/codebird-php
```

You may need to update CodeBird's certificates.

* Download the file https://curl.se/ca/cacert.pem
* Find the directory `/vendor/jublonet/codebird-php/src/`
* Place cacert.pem in that directory - overwriting the old file

## Getting a copy of the code on your computer

* Create an account on https://GitHub.com/
* Follow the guide to adding a new SSH key https://docs.github.com/en/authentication/connecting-to-github-with-ssh/checking-for-existing-ssh-keys
* Fork the openbenches.org repo https://docs.github.com/en/get-started/quickstart/fork-a-repo 
* Clone your fork to your computer using the SSH method https://docs.github.com/en/repositories/creating-and-managing-repositories/cloning-a-repository

You should now have a copy of the OpenBenches code on your computer which you can use to get an instance of OpenBenches running on your computer.

## Install necessary packages

Install the following packages:

`git php-cli php-fpm php-pecl-imagick php-mbstring php-mysqlnd mariadb-server httpd mod_ssl`

N.B. if you are not using Fedora some of the required packages may be called something else.

## Database 

### Start the MariaDB server

`systemctl start mariadb.service`

N.B. Fedora does not start automatically on boot unless you specify they should. If you want that to happen run the above command again replacing `start` with `enable`.

### Set up the database

Create a user called openbenches with the password "badpassword"

`echo "create user openbenches@localhost identified by 'badpassword'" | mysql` 

Create a database called openbenc_benches (this is the name in database/openbenc_benches_database.sql)

`echo "create database openbenc_benches" | mysql` 

Give the openbenches user acecss to the openbenc_benches database

`echo "grant all privileges on openbenc_benches.* to openbenches@localhost;" | mysql`

Confirm that the openbenches user has access to the openbenc_benches database

`mysql -u openbenches -pbadpassword -D openbenc_benches

Type "exit" at the prompt to exit MariaDB.

Now set up the database using the files in the database directory.

```
cd /path/to/where/you/put/the/repo/openbenches/database
mysql -u openbenches -pbadpassword -D openbenc_benches < openbenc_benches_database.sql 
for f in openbenc_benches_table_*;do mysql -u openbenches -pbadpassword -D openbenc_benches < "${f}";done
mysql -u openbenches -pbadpassword -D openbenc_benches < openbenc_benches_extra.sql 
```

You should now be able to get some information about a bench from the database

```
echo "select * from benches where benchID=1;" |  mysql -u openbenches -pbadpassword -D openbenc_benches
benchID	latitude	longitude	address	inscription	description	present	published	added	userID
```

## Configuration

Make a copy of `.env` called `.env.local`

Open it and add the database variables:

```
DATABASE_URL="mysqli://openbenches:badpassword@127.0.0.1:3306/openbenches_db?&charset=utf8mb4"
```

Follow the instructions in that file to add all the necessary API keys and other configuration variables.

## Test the server

If you haven't already, start Symfony with `symfony server:start`

Point your web browser of choice at http://localhost. You should find that it gets redirected to https://localhost, because OpenBenches enforces https (a good thing) and your web browser should display nothing but a warning with words to the effect that the site you are visiting is not secure. This is expected behaviour in this context. Your web browser should give you the option to see more details (For Firefox click the "Advanced" button) and tell you that the web server is presenting a certificate it does not recognise as being one it should trust. Always trust your web browser in this circumstances. Except in this context, and this context only it's OK to tell your web browser to let you view the site. (For Firefox click "Accept the risk and continue"). **** Never do this in normal web browsing. This guide telling you to do this in a very specific circumstance. Never do this in normal web browsing. Seriously. Never. ****

Now that you have bypassed a security warning in your web browser in a way which you should never ever normally do, you should be looking at an instance of the OpenBenches website running on your own computer. Try clicking on one of the markers on the map, you should be able to view details of the bench (without any photos).

## Adding benches

If you want to be able to add benches to your instance of the website you may need to change some default PHP settings. Check the values of upload_max_size and upload_max_filesize

```
php -i  | grep -i upload_max_filesize
php -i  | grep -i post_max_size
```

The setting `upload_max_filesize` needs to bigger than any photo you want to add and `post_max_size` needs to be bigger than the total size of any photos you want to add for a given bench. You can increase the values like so

```
echo "upload_max_filesize = 10M" > /etc/php.d/99-mine.ini
echo "post_max_size = 40M" >> /etc/php.d/99-mine.ini
systemctl reload php-fpm.service 
```

Create the directory where photos of benches are stored and give the user that php-fpm runs as (in this case apache) write permission.

```
mkdir photos
setfacl -m u:apache:rwx photos
```

If you use SELinux it will still block the apache user writing to the directory. Change the SELinux type of the directory to something SELinux lets the apache user write to:

```
semanage fcontext -a -t httpd_sys_rw_content_t /var/www/html/photos
restorecon -v /var/www/html/photos/
```

OpenBenches uses https://images.weserv.nl/ for image caching, so after you add a bench to your own instance it will try to show you the images for it from the cloud and that won't work. In `.env.local` set `IMAGE_CACHE_PREFIX` to an empty string.

Try going to https://localhost/add and adding a bench. You will find that automatic detection of the inscription text fails. If you want that to work you need to get a Google Cloud Vision account and set `CLOUD_VISION_KEY` in `.env.local`

No address will be generated for the bench you add. If you want that to work you will need to get OpenCage and Geoapify accounts and set `OPENCAGE_API_KEY` and `GEOAPIFY_API_KEY` in `.env.local`.

## Editing a bench

Editing a bench requires that you be authenticated. Or you can remove the the authentication checks.

N.B. Do not submit pull requests containing the removed authentication!

## Submitting changes to the code

Read README.md and DEVELOPERS.md.

View your repo on github.com. Make sure you're looking at the master branch and see if you can see the message "This branch is up to date with openbenches/openbenches.org:master." If you can, good to go. If you see a message about how the branch is not up to date, click the "Sync fork" button to make it up to date.

Make sure the copy of the repo on your computer is up to ate

```
git checkout master
git pull
```

Create a branch with a name relevant to the issue you are going to submit a fix for. 

```
git branch issue999
git checkout issue999
```

Start working on your fix. If at some point you decide you've made a mess of a file and want to put it back to it's original state you can use

`git checkout -- filename`

To see which files you have modified use

`git status`

Once you have made whatever changes you want to make and confirmed they work add the relevant files to those to be committed.

`git add .`

Check the list of changes to be committed

`git status`

Commit the change. If you start your commit message with # followed by the issue number, GitHub will add a comment to the issue about the commit.

N.B. Make sure you do not include:
- Your `.env.local`
- Your photos directory

`git commit -m "#999 fix whatever"`

Then push that commit to your repo on GitHub

`git push --set-upstream origin issue999`

The output should include an URL for creating a pull request. Before using it view your repo on GitHub, select branch issue999, view the commit you just made and confirm that it looks like you expect. If it does, then give your web browser the aforementioned URL for a pull request, add a suitable comment and click the "Create pull request" button.


When you are finished working on the issue you may wish to delete the branch.

```
git checkout master
git pull
git branch -d issue999 # deletes local branch
git push -d origin issue999 # deletes branch on GitHub
```