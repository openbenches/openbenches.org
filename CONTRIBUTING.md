The following was written by someone using Fedora Server 37 which has PHP 8.1 MariaDB 10.5 and ImageMagick 6.9. Not all of this will work exactly as presented on other Linux distros. This does not attempt to cover macOS or Windows.

## Getting a copy of the code on your computer

Create a GitHub account.

Add an SSH key to your account. If you do not already have a key pair, create one:
[neil@foo]$ ssh-keygen -t ed25519 
Accept the default location. Choosing to add passphrase will protect the private part of the key pair with a passphrase which you will to enter every time you use the key. This helps reduce the risk of it being used by someone who gets hold of it. The public part of the key pair will be in ~/.ssh/id_ed25519.pub copy the contents of that file to
https://github.com/settings/ssh/new


Fork the openbenches.org repo https://docs.github.com/en/get-started/quickstart/fork-a-repo 

Clone your fork to your computer using the SSH method https://docs.github.com/en/repositories/creating-and-managing-repositories/cloning-a-repository

You should now have a copy of the OpenBenches code on your computer which you can use to get an instance of OpenBenches running on your computer.


Install some needed packages
----------------------------

Install the following packages:
git php-cli php-fpm php-pecl-imagick php-mbstring php-mysqlnd mariadb-server httpd mod_ssl
N.B. if you are not using Fedora some of the required packages may be called something else.

Start the MariaDB server
[root@foo~]# systemctl start mariadb.service

N.B. Fedora does not start automatically on boot unless you specify they should. If you want that to happen run the above command again replacing start with enable.

Set up the database
-------------------

Create a user called openbenches with the password "badpassword"
[root@foo ~]# echo "create user openbenches@localhost identified by 'badpassword'" | mysql 

Create a database called openbenc_benches (this is the name in database/openbenc_benches_database.sql)
[root@foo ~]# echo "create database openbenc_benches" | mysql 

Give the openbenches user acecss to the openbenc_benches database
[root@foo ~]# echo "grant all privileges on openbenc_benches.* to openbenches@localhost;" | mysql 


Confirm that the openbenches user has access to the openbenc_benches database
[neil@foo ~]$ mysql -u openbenches -pbadpassword -D openbenc_benches
Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 19
Server version: 10.5.18-MariaDB MariaDB Server

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [openbenc_benches]> 

Type "exit" at the prompt to exit MariaDB.


Now set up the database using the files in the database directory.
[neil@foo database]$ cd /path/to/where/you/put/the/repo/openbenches/database
[neil@foo database]$ mysql -u openbenches -pbadpassword -D openbenc_benches < openbenc_benches_database.sql 
[neil@foo database]$ for f in openbenc_benches_table_*;do mysql -u openbenches -pbadpassword -D openbenc_benches < "${f}";done
[neil@foo database]$ mysql -u openbenches -pbadpassword -D openbenc_benches < openbenc_benches_extra.sql 

You should now be able to get some information about a bench from the database
[neil@foo database]$ echo "select * from benches where benchID=1;" |  mysql -u openbenches -pbadpassword -D openbenc_benches
benchID	latitude	longitude	address	inscription	description	present	published	added	userID
1	51.729153	-1.240306	Iffley Lock House, Mathematical Bridge,\n"THE OXFORD KID"ngdom	Donated 1y the G1dfrey F2017-07-11 21:12:50	8
[neil@foo database]$ 

Make config.php
---------------

In the www directory, make a copy of config.php.example called config.php and in it set the DB_ variables as follows:

//      Database
define('DB_IP',   '127.0.0.1');
define('DB_USER', 'openbenches');
define('DB_PASS', 'badpassword');
define('DB_TABLE','openbenc_benches');

(DB_TABLE is the name of the database 🤷)


Set up Apache httpd
-------------------

The default document root for Apache httpd is /var/www/html so everything that's in the www directory of the openbenches repo needs to be in there. You can copy everything to there but you may find that makes it difficult to keep track of what you have modified and you will have to copy modifications back to the repo directory before you can commit them. You can also monunt the www directory on /var/www/html using a bind mount, and from now on this guide will assume that's what you've done. 

Mount the www directory on /var/www/html
[root@foo ~]# mount --bind /path/to/where/you/put/the/repo/openbenches/www/ /var/www/html/

If you now list the contents of /var/www/html/ you should see the contents of the www directory in the repo. E.g.
[root@foo ~]# ls -l /var/www/html/front.php
-rw-r--r--. 1 neil neil 1444 Feb 25 16:44 /var/www/html/front.php
[root@foo ~]# 

Fedora uses SELinux (a layer of mandatory access control imposed the kernel which superceeds filesystem permissios) and that will prevent httpd from serving the files due to them having type user_home_t 
[root@foo ~]# ls -lZ /var/www/html/front.php
-rw-r--r--. 1 neil neil unconfined_u:object_r:user_home_t:s0 1444 Feb 25 16:44 /var/www/html/front.php
[root@foo ~]# 

You can tell SELinux to allow httpd to read files of type user_home_t with

[root@neil ~]# setsebool -P httpd_read_user_content 1

N.B. This is a permanent modification which persists over reboots. (This guide also assumes you're not doing any of this on any sort of production or importnat environment. ;) )


SELinux will also prevent php-fpm accessing the MariaDB service by default, so allow that with
[root@neil ~]# setsebool -P httpd_can_network_connect_db 1

N.B. Again, permanent.

OpenBenches uses a .htaccess file which httpd (as shipped by Fedora at least) will ignore by default so you need to create some configuration to tell httpd to use the .htaccess file. Create this file:
[root@foo ~]# cat /etc/httpd/conf.d/mine.conf 
<Directory "/var/www/html">
    AllowOverride All 
</Directory>
[root@foo ~]# 

Check this configuration you've created by telling httpd to test it's configuration
[root@foo ~]# apachectl configtest 

If you see "Syntax OK" then on to the next step. If you don't see that, work out what is wrong and fix it.

Start the php-fpm and httpd services.
[root@foo ~]# systemctl start php-fpm
[root@foo ~]# systemctl start httpd


Point your web browser of choice at http://localhost. You should find that it gets redirected to https://localhost, because OpenBenches enforces https (a good thing) and your web browser should display nothing but a warning with words to the effect that the site you are visiting is not secure. This is expected behaviour in this context. Your web browser should give you the option to see more details (For Firefox click the "Advanced" button) and tell you that the web server is presenting a certificate it does not recognise as being one it should trust. Always trust your web browser in this circumstances. Except in this context, and this context only it's OK to tell your web browser to let you view the site. (For Firefox click "Accept the risk and continue"). **** Never do this in normal web browsing. This guide telling you to do this in a very specific circumstance. Never do this in normal web browsing. Seriously. Never. ****

Now that you have bypassed a security warning in your web browser in a way which you should never ever normally do, you should be looking at an instance of the OpenBenches website running on your own computer. Try clicking on one of the markers on the map, you should be able to view details of the bench (without any photos).

Adding benches
--------------

If you want to be able to add benches to your instance of the website you may need to change some default PHP settings. Check the values of upload_max_size and upload_max_filesize

[root@foo ~]# php -i  | grep -i upload_max_filesize
upload_max_filesize => 2M => 2M
[root@foo ~]# php -i  | grep -i  post_max_size
post_max_size => 8M => 8M
[root@foo ~]# 

upload_max_filesize needs to bigger than any photo you want to add and post_max_size needs to be bigger than the total size of any photos you want to add for a given bench. You can increase the values like so

[root@foo ~]# echo "upload_max_filesize = 10M" > /etc/php.d/99-mine.ini
[root@foo ~]# echo "post_max_size = 40M" >> /etc/php.d/99-mine.ini
[root@foo ~]# systemctl reload php-fpm.service 

Create the directory where photos of benches are stored and give the user that php-fpm runs as (in this case apache) write permission.

[neil@foo www]$ mkdir photos
[neil@foo www]$ setfacl -m u:apache:rwx photos

By default, SELinux will still block the apache user writing to the directory so change the SELinux type of the directory to something SELinux lets the apache user write to
[root@foo ~]# semanage fcontext -a -t httpd_sys_rw_content_t /var/www/html/photos
[root@foo ~]# restorecon -v /var/www/html/photos/

OpenBenches uses cloudimg.io for image caching, so by default after you add a bench to your own instance it will try to show you the images for it from cloudimg.io and that won't work. In config.php set IMAGE_CACHE_PREFIX to a zero length string.


Try going to https://192.168.124.22/add and adding a bench. You will find that automatic detection of the inscription text fails. If you want that to work you need to get a Google Cloud Vision account and set CLOUD_VISION_KEY in config.php.

No address will be generated for the bench you add. If you want that to work you will need to get OpenCage and Geoapify accounts and set OPENCAGE_API_KEY and GEOAPIFY_API_KEY in config.php.

Editing a bench
---------------

You need to set a value for EDIT_SALT in config.php. You can look up how the PHP crypt function works, or, check if the PHP constant CRYPT_MD5 is set
[neil@foo www]$ echo "print (CRYPT_MD5);" | php -a
Interactive shell

1
[neil@foo www]$ 

1 in the output means it is set. So you can create a suitable EDIT_SALT value using
[neil@foo www]$ echo "\$1\$$(tr -dc A-Za-z0-9 </dev/urandom | head -c 8)\$"

Editing a bench requires that you be authenticated. You can work out how to get one or more of the authentication methods OpenBenches supports working (if you do why not update this guide with the details), or you can hotwire the authentication. In functions.php rename get_user_details() to not_get_user_details() and and add this 

function get_user_details(){
  return array("twitter", 88888888, "mrjerzeibalowski");
}

You should now be able to edit a bench as the fictional Twitter user mrjerzeibalowski. (Note that "mrjerzeibalowski" is too long to be a valid Twitter username.)

N.B. Do not submit pull requests containing the modified get_user_details() function!

Submitting changes to the code
------------------------------

Read README.md and DEVELOPERS.md.

View your repo on github.com. Make sure you're looking at the master branch and see if you can see the message "This branch is up to date with openbenches/openbenches.org:master." If you can, good to go. If you see a message about how the branch is not up to date, click the "Sync fork" button to make it up to date.

Make sure the copy of the repo on your computer is up to ate

[neil@foo openbenches]$ git checkout master
[neil@foo openbenches]$ git pull

Check that you have user.name and user.email set in your git config
[neil@foo openbenches]$ git config user.email
[neil@foo openbenches]$ git config user.name

if you don't see values set them with

[neil@foo openbenches]$ git config user.name yourname
[neil@foo openbenches]$ git config user.email youremail

Create a branch with a name relevant to the issue you are going to submit a fix for. 
[neil@foo openbenches]$ git branch issue999
[neil@foo openbenches]$ git checkout issue999

Start working on your fix. If at some point you decide you've made a mess of a file and want to put it back to it's original state you can use

[neil@foo openbenches]$ git checkout -- filename
 
To see which files you have modified use
[neil@foo openbenches]$ git status

Once you have made whatever changes you want to make and confirmed they work add the relevant files to those to be committed.

[neil@foo openbenches]$ git add www/index.php

Check the list of changes to be committed
[neil@foo openbenches]$ git status

Commit the change. If you start your commit message with # followed by the issue number, GitHub will add a comment to the issue about the commit.
N.B. Make sure you do not include:
- Your config.php
- functions.php with the modified get_twitter_details function in it
- Your photos directory
[neil@foo openbenches]$ git commit -m "#999 fix whatever"

Then push that commit to your repo on GitHub

[neil@foo openbenches]$ git push --set-upstream origin issue999

The output should include an URL for creating a pull request. Before using it view your repo on GitHub, select branch issue999, view the commit you just made and confirm that it looks like you expect. If it does, then give your web browseer the aforementioned URL for a pull request, add a suitable comment and click the "Create pull request" button.


When you are finished working on the issue you may wish to delete the branch.
[neil@foo openbenches]$ git checkout master
[neil@foo openbenches]$ git pull
[neil@foo openbenches]$ git branch -d issue999 # deletes local branch
[neil@foo openbenches]$ git push -d origin issue999 # deletes branch on GitHub




