<?php

namespace NilPortugues\Tests\Api\JsonApi\Doctrine;

require_once 'AbstractTestCase.php';

// use NilPortugues\Tests\JsonApi\Doctrine\AbstractTestCase;
class DoctrineTest extends AbstractTestCase {

	public function testPersit() {
		$newCustomer = new \Doctrine\Entity\Customer();
		$newCustomer->setActive(true);
		$newCustomer->setName('Name 1');
		
		$this->entityManager->persist($newCustomer);
		$this->entityManager->flush();
		$repoCustomer = $this->entityManager->getRepository('Doctrine\\Entity\\Customer');
		$savedCustomer = $repoCustomer->findAll();
		
		$this->assertEquals($newCustomer->getId(), $savedCustomer[0]->getId());
		$this->assertEquals($newCustomer->getName(), $savedCustomer[0]->getName());
	}
}