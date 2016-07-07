<?php

namespace NilPortugues\Tests\Api\JsonApi\Doctrine;

class DoctrineTest extends AbstractTestCase {

	public function testPersit() {
		$newCustomer = new \Doctrine\Entity\Customer();
		$newCustomer->setActive(true);
		$newCustomer->setName('Name 1');
		
		self::$entityManager->persist($newCustomer);
		self::$entityManager->flush();
		$repoCustomer = self::$entityManager->getRepository('Doctrine\\Entity\\Customer');
		$savedCustomer = $repoCustomer->findAll();
		
		$this->assertEquals($newCustomer->getId(), $savedCustomer[0]->getId());
		$this->assertEquals($newCustomer->getName(), $savedCustomer[0]->getName());
	}
}