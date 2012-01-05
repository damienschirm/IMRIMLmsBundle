README FILE
===========

1) Introduction
---------------
This file will help you to install and configure the project.

2) Installation
---------------

### a) Parameters Symfony for authentication security

Please edit app/config/security.yml file to update symfony security policy

    security:
        encoders:
            IMRIM\Bundle\LmsBundle\Entity\User:
                algorithm:   sha1
                iterations: 1
                encode_as_base64: false

        providers:
            internal:
                entity:
                    class: IMRIM\Bundle\LmsBundle\Entity\User
                    property: login

        firewalls:
            dev:
                pattern:  ^/(_(profiler|wdt)|css|images|js)/
                security: false

            login_firewall:
                pattern:    ^/login$
                anonymous:  ~

            secured_area:
                pattern:    ^/
                form_login: ~
                logout: ~

And update the routing file of symfony2 : app/config/routing.yml

    IMRIMLmsBundle:
        resource: "@IMRIMLmsBundle/Controller/"
        type:     annotation
        prefix:   /

    login:
        pattern:   /login
        defaults:  { _controller: IMRIMLmsBundle:Authentication:login }
    login_check:
        pattern:   /login_check
    logout:
        pattern:   /logout

### b) Database schema creation

Please edit the app/config/parameters.ini (check if "locale" parameter value is "fr") : 

    [parameters]
        database_driver   = "pdo_mysql"
        database_host     = <host>
        database_port     =
        database_name     = <name of the database>
        database_user     = <name of the database user>
        database_password = <password of the database user>

        mailer_transport  = 
        mailer_host       = 
        mailer_user       = 
        mailer_password   = 

        locale            = "fr"

        secret            = <to change>

At the top of symfony2 directory run the following command: 
    
    app/console doctrine:schema:create

### c) Configure TinymceBundle dependency for WYSIWYG editions. 

Please install the bundle by adding the following lines at the end of deps file : 

    [TinymceBundle]
        git=git://github.com/stfalcon/TinymceBundle.git
        target=/bundles/Stfalcon/Bundle/TinymceBundle

Modify the app/autoload.php file by registering the following namespace :

    $loader->registerNamespaces(array(
        // ...
        'Stfalcon'                       => __DIR__.'/../vendor/bundles',
    ));

Instantiate Bundle in your app/AppKernel.hpp file

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
        );
    }

Configure the application by adding the following lines in app/config/config.yml

    # TinyMCE Configuration
    stfalcon_tinymce:
        include_jquery: true
        textarea_class: "tinymce"
        theme:
            simple:
                mode: "textareas"
                theme: "advanced"
                theme_advanced_buttons1: "mylistbox,mysplitbutton,bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,undo,redo,link,unlink"
                theme_advanced_buttons2: ""
                theme_advanced_buttons3: ""
                theme_advanced_toolbar_location: "top"
                theme_advanced_toolbar_align: "left"
                theme_advanced_statusbar_location: "bottom"
                plugins: "fullscreen"
                theme_advanced_buttons1_add: "fullscreen"
            advanced:
                theme: "advanced"
                plugins: "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template"
                theme_advanced_buttons1: "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect"
                theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,|,insertdate,inserttime,preview,|,forecolor,backcolor"
                theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,advhr,|,print,|,ltr,rtl,|,fullscreen"
                theme_advanced_buttons4: "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak"
                theme_advanced_toolbar_location: "top"
                theme_advanced_toolbar_align: "left"
                theme_advanced_statusbar_location: "bottom"
                theme_advanced_resizing: true
                language: fr

Then, run this command : 

    bin/vendors install --reinstall



### d) Environment installation

To load the design of the site, please run the following command :

    app/console assets:install web

To allow upload of documents, please run enter the following lines : 

    mkdir -p web/uploads/documents
    chmod 777 web/uploads/documents # do not use in production environment

### e) Doctrine Data Fixtures for dev

To use data fixtures, please add the following lines at the end of deps file : 
    
    [doctrine-fixtures]
    git=http://github.com/doctrine/data-fixtures.git

    [DoctrineFixturesBundle]
    git=http://github.com/symfony/DoctrineFixturesBundle.git
    target=/bundles/Symfony/Bundle/DoctrineFixturesBundle

and modify the following lines on the same file :

    [SensioFrameworkExtraBundle]
    git=http://github.com/sensio/SensioFrameworkExtraBundle.git
    target=/bundles/Sensio/Bundle/FrameworkExtraBundle
    version=origin/2.0

Then, run the following command : 
    
    php bin/vendors update

Register the Doctrine\Common\DataFixtures namespace in app/autoload.php
    
    $loader->registerNamespaces(array(
        // ...
        'Doctrine\\Common\DataFixtures' => __DIR__.'/../vendor/doctrine-fixtures/lib',
        'Doctrine\\Common' => __DIR__.'/../vendor/doctrine-common/lib',
        // ...
    ));

Warning !!! Be sure to register the new namespace before Doctrine\Common !

Register the Bundle DoctrineFixturesBundle in app/AppKernel.php

    public function registerBundle()
    {
        $bundle = array(
            // ...
            new Symfony\Bundle\DoctrineFixturesBundle\DoctrineFixturesBundle(),
            // ...
        );
        // ...
    }

Finally, at the top of symfony2 directory run the following command :
 
    app/console doctrine:fixtures:load
