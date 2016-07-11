<?php
// bootstrap.php from http://doctrine-orm.readthedocs.io/projects/doctrine-orm/en/latest/reference/configuration.html
require_once 'vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

function GetEntityManager()
{
    $paths = array(__DIR__.'/yml/');
    $isDevMode = false;

    // the connection configuration
    $dbParams = array(
            'driver' => 'pdo_sqlite',
            'user' => 'root',
            'password' => '',
            'dbname' => 'foo',
            'path' => ':memory:',
            'memory' => 'true',
    );

    // $config instanceof Doctrine\ORM\Configuration
    $config = Setup::createYAMLMetadataConfiguration($paths, $isDevMode);
    $namespaces = array(
            __DIR__.'/yml/' => 'Doctrine\\Entity',
    );
    $driver = new \Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver($namespaces);
    $config->setMetadataDriverImpl($driver);//replace default driver

    return EntityManager::create($dbParams, $config);
}
