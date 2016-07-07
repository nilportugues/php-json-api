<?php
/**
 * based on cli-config.php from
 * http://doctrine-orm.readthedocs.io/projects/doctrine-orm/en/latest/reference/configuration.html
 */
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once 'Tests/Doctrine/bootstrap.php';

//global $entityManager from boostrap.php
return ConsoleRunner::createHelperSet(GetEntityManager());
