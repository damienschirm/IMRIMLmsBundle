README FILE
===========

1) Introduction
---------------
This file will help you to install and configure the project.

2) Installation
---------------

### a) Doctrine Data Fixtures for dev

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

Finally, register the Bundle DoctrineFixturesBundle in app/AppKernel.php
    public function registerBundle()
    {
        $bundle = array(
            // ...
            new Symfony\Bundle\DoctrineFixturesBundle\DoctrineFixturesBundle(),
            // ...
        );
        // ...
    }