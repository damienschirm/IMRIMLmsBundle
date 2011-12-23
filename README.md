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

### b) Database schema creation

At the top of symfony2 directory run the following command: 
    app/console doctrine:schema:create

### c) Doctrine Data Fixtures for dev

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

Finally, at the top of symfony2 directory run the following command: 
    app/console doctrine:fixtures:load


To load the design of the site, you have to copy the content of src/IMRIM/Bundle/LmsBundle/Ressource/public
in web/bundles/imrim
