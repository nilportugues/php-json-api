<?php
/**
 * Based on https://www.theodo.fr/blog/2011/09/symfony2-unit-database-tests/
 * from Benjamin Grandfond.
 */

namespace NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\SchemaTool;

require_once 'bootstrap.php';

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected static $entityManager;

    public static function setUpBeforeClass()
    {
        self::$entityManager = GetEntityManager();
        // Build the schema for sqlite
        self::generateSchema();

        parent::setUpBeforeClass();
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
    }

    protected static function generateSchema()
    {
        // Get the metadata of the application to create the schema.
        $metadata = self::getMetadata();

        if (!empty($metadata)) {
            // Create SchemaTool
            $tool = new SchemaTool(self::$entityManager);
            $tool->createSchema($metadata);
        } else {
            throw new SchemaException('No Metadata Classes to process.');
        }
    }

    /**
     * Overwrite this method to get specific metadata.
     *
     * @return array
     */
    protected static function getMetadata()
    {
        return self::$entityManager->getMetadataFactory()->getAllMetadata();
    }
}
