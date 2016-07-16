<?php

namespace NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine;

use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Api\Mapping\Mapper;
use NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Customer;
use NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Mappings\CustomerMapping;
use NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Mappings\PostMapping;
use NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Mappings\CommentMapping;
use NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Post;
use NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Comment;

class DoctrineTest extends AbstractTestCase
{
    public function testPersistAndSerializeSimpleEntity()
    {
        $newCustomer = new Customer();
        $newCustomer->setActive(true);
        $newCustomer->setName('Name 1');

        self::$entityManager->persist($newCustomer);
        self::$entityManager->flush();
        $repoCustomer = self::$entityManager->getRepository(Customer::class);
        $savedCustomer = $repoCustomer->findAll();

        $classConfig = [
            CustomerMapping::class,
        ];

        $expected = <<<JSON
			{
				"data":
					[{
						"type":"customer",
						"id":"1",
						"attributes":
							{
								"active":true,
								"id":1,
								"name":"Name 1"
							},
						"links":
							{
								"self":{"href":"http://example.com/customer/1"}
							}
					}],
				"jsonapi":
					{
						"version":"1.0"
					}
			}
JSON;
        $mapper = new Mapper($classConfig);
        $transformer = new JsonApiTransformer($mapper);
        $serializer = new JsonApiSerializer($transformer);
        $customerSerialize = $serializer->serialize($savedCustomer);

        $this->assertEquals($newCustomer->getId(), $savedCustomer[0]->getId());
        $this->assertEquals($newCustomer->getName(), $savedCustomer[0]->getName());
        $this->assertEquals(json_decode($expected, true), json_decode($customerSerialize, true));
    }

    public function testPersistAndSerializeComplexEntity()
    {
        $newCustomer = new Customer();
        $newCustomer->setActive(true);
        $newCustomer->setName('Name 1');

        self::$entityManager->persist($newCustomer);
        self::$entityManager->flush();

        $newPost = new Post();
        $newPost->setCustomer($newCustomer);
        $newPost->setDate(new \DateTime('2016-07-12 16:30:12.000000'));
        $newPost->setDescription('Description test');
        self::$entityManager->persist($newPost);
        self::$entityManager->flush();

        $newComment = new Comment();
        $newComment->setPost($newPost);
        $newComment->setComment('Comment 1');
        self::$entityManager->persist($newComment);
        self::$entityManager->flush();

        $repoCustomer = self::$entityManager->getRepository(Comment::class);
        $savedComment = $repoCustomer->findAll();

        $classConfig = [
                CustomerMapping::class,
                PostMapping::class,
                CommentMapping::class,
        ];

        $expected = <<<JSON
        {
        		"data":
		[{
			"type":"comment",
			"id":"1",
			"attributes":
				{
					"comment":"Comment 1",
					"id":1,
					"parent_comment":null,
					"parent_id":null
				},
			"links":
				{"self":{"href":"http://example.com/comment/1"}},
			"relationships":
				{
					"post":
						{
							"data":
								{
									"type":"post",
									"id":"1"
								}
						}
				}
		}
	],
			"included":
				[
					{
						"type":"customer",
						"id":"2",
						"attributes":
							{
								"name":"Name 1",
								"active":true
							},
						"links":{"self":{"href":"http://example.com/customer/2"}}
					},
					{	"type":"post",
						"id":"1",
						"attributes":
							{
								"date":{"date":"2016-07-12 16:30:12.000000","timezone_type":3,"timezone":"Europe/Madrid"},
								"description":"Description test"
							},
						"relationships":
							{
								"customer":
									{
										"data":
											{
												"type":"customer",
												"id":"2"
											}
									}
							},
						"links":{"self":{"href":"http://example.com/post/1"}}
					}
				],
			"jsonapi":{"version":"1.0"}
}
JSON;
        $mapper = new Mapper($classConfig);
        $transformer = new JsonApiTransformer($mapper);
        $serializer = new JsonApiSerializer($transformer);
        $customerSerialize = $serializer->serialize($savedComment);

        $this->assertEquals(json_decode($expected, true), json_decode($customerSerialize, true));
    }
}
