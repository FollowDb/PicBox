PicBox
======
PicBox is a Yii based web application aimed to store user images.
Main features:
* Ajax based images multiloader
* Restricted access to images. Only the user who uploaded that image can see it, NO direct linkings.
  This feature uses Nginx X-accel technology (is commonly known as X-Sendfile).
* Quick registration
  
Setup instructions:
===
* Import database dump (/protected/data/picbox.mysql.sql)
* Edit nginx config to use X-accel as follows: 
    location ~/imgs/ {
      	internal;
    }
* Configure database settings (/protected/config/main.php)

That's it!


General Description:
===
* The format of uploaded images : JPG, PNG, GIF
* Images are stored on the server
* The user can view only their images

Register / Log in user :
* Registration takes place during the process of loading images - a new account is created and authenticated with credentials provided.

User Actions:
* View the list of downloaded images
** Images are displayed in a table. Can be sorted by fields : Image Name, Image Type, Date Created. If the list is large - pagination appears
* View a particular image on a unique link ( not a direct link to the image) - regulating access to content
* Deleting Images
* Upload images - via AJAX uploader. Able to load multiple images at once

Technical requirements:
* OS: Debian 7
* PHP: 5.4
* DB: MySQL 5.5
* Enviroment: nginx + php-fpm
