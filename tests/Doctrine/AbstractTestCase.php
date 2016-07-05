<?php
/**
 * Based on https://www.theodo.fr/blog/2011/09/symfony2-unit-database-tests/
 * from Benjamin Grandfond
 */

namespace NilPortugues\Tests\Api\JsonApi\Doctrine;

use Doctrine\ORM\Tools\SchemaTool;

require_once 'bootstrap.php';

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $entityManager;

	public function setUp()
	{
		global $entityManager;
		$this->entityManager = $entityManager;//global value from bootstrap.php
		// Build the schema for sqlite
		$this->generateSchema();

		parent::setUp();
	}

	public function tearDown()
	{
		parent::tearDown();
	}

	protected function generateSchema()
	{
		// Get the metadata of the application to create the schema.
		$metadata = $this->getMetadata();

		if ( ! empty($metadata)) {
			// Create SchemaTool
			$tool = new SchemaTool($this->entityManager);
			$tool->createSchema($metadata);
		} else {
			throw new Doctrine\DBAL\Schema\SchemaException('No Metadata Classes to process.');
		}
	}

	/**
	 * Overwrite this method to get specific metadata.
	 *
	 * @return Array
	 */
	protected function getMetadata()
	{
		return $this->entityManager->getMetadataFactory()->getAllMetadata();
	}
}