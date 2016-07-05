<?php
// bootstrap.php from http://doctrine-orm.readthedocs.io/projects/doctrine-orm/en/latest/reference/configuration.html
require_once "vendor/autoload.php";

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = array("Entity");
$isDevMode = false;

// the connection configuration
$dbParams = array(
    'driver'   => 'pdo_sqlite',
    'user'     => 'root',
    'password' => '',
    'dbname'   => 'foo',
	'path'     => ':memory:',
    'memory'   => 'true'
);

$config = Setup::createYAMLMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);