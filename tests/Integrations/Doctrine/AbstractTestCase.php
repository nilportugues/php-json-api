<?php
/**
 * Based on https://www.theodo.fr/blog/2011/09/symfony2-unit-database-tests/
 * from Benjamin Grandfond.
 */

namespace NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\SchemaTool;
use NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Customer;
use NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Mappings\CustomerMapping;
use NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Mappings\PostMapping;
use NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Mappings\CommentMapping;
use NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Post;
use NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Comment;

require_once 'bootstrap.php';

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected static $entityManager;
    protected static $classConfig;

    public static function setUpBeforeClass()
    {
        self::$entityManager = GetEntityManager();
        // Build the schema for sqlite
        self::generateSchema();

        $newCustomer = new Customer();
        $newCustomer->setActive(true);
        $newCustomer->setPersonName('Name 1');
        self::$entityManager->persist($newCustomer);

        $newPost = new Post();
        $newPost->setCustomer($newCustomer);
        $newPost->setDate(new \DateTime('2016-07-12 16:30:12.000000'));
        $newPost->setDescription('Description test');
        self::$entityManager->persist($newPost);

        $newComment = new Comment();
        $newComment->setPost($newPost);
        $newComment->setComment('Comment 1');
        self::$entityManager->persist($newComment);

        $newComment2 = new Comment();
        $newComment2->setPost($newPost);
        $newComment2->setComment('Comment 2');
        $newComment2->setParentComment($newComment);
        self::$entityManager->persist($newComment2);

        self::$entityManager->flush();

        self::$classConfig = [
                CustomerMapping::class,
                PostMapping::class,
                CommentMapping::class,
        ];

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
