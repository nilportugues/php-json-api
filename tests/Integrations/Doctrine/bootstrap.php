<?php
// bootstrap.php from http://doctrine-orm.readthedocs.io/projects/doctrine-orm/en/latest/reference/configuration.html
require_once __DIR__.'/../../../vendor/autoload.php';

use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

function GetEntityManager()
{
    $paths = [realpath(__DIR__.'/yml/')];
    $isDevMode = false;

    // the connection configuration
    $dbParams = [
            'driver' => 'pdo_sqlite',
            'user' => 'root',
            'password' => '',
            'dbname' => 'foo',
            'path' => ':memory:',
            'memory' => 'true',
    ];

    // $config instanceof Doctrine\ORM\Configuration
    $config = Setup::createYAMLMetadataConfiguration($paths, $isDevMode);
    $namespaces = [
        __DIR__.'/yml/' => 'NilPortugues\\Tests\\Api\\JsonApi\\Integrations\\Doctrine\\Entity',
    ];

    $driver = new SimplifiedYamlDriver($namespaces);
    $config->setMetadataDriverImpl($driver);//replace default driver

    return EntityManager::create($dbParams, $config);
}
