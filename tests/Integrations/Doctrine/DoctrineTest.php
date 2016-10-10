<?php

namespace NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine;

use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Api\Mapping\Mapper;
use NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Customer;
use NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Post;
use NilPortugues\Tests\Api\JsonApi\Integrations\Doctrine\Entity\Comment;

class DoctrineTest extends AbstractTestCase
{
    public function testSerializeSimpleEntity()
    {
        $repoCustomer = self::$entityManager->getRepository(Customer::class);
        $savedCustomer = $repoCustomer->find(1);

        $expected = <<<JSON
		{  
		   "data":{  
		      "type":"customer",
		      "id":"1",
		      "attributes":{  
		         "active":true,
		         "id":1,
		         "name":"Name 1"
		      },
		      "links":{  
		         "self":{  
		            "href":"http://example.com/customer/1"
		         }
		      }
		   },
		   "links":{  
		      "self":{  
		         "href":"http://example.com/customer/1"
		      }
		   },
		   "jsonapi":{  
		      "version":"1.0"
		   }
		}
JSON;
        $mapper = new Mapper(self::$classConfig);
        $transformer = new JsonApiTransformer($mapper);
        $serializer = new JsonApiSerializer($transformer);
        $customerSerialize = $serializer->serialize($savedCustomer);

        $this->assertEquals(json_decode($expected, true), json_decode($customerSerialize, true));
    }

    public function testSerializeComplexEntity()
    {
        $repoCustomer = self::$entityManager->getRepository(Comment::class);
        $savedComment = $repoCustomer->find(1);

        $expected = <<<JSON
        {  
		   "data":{  
		      "type":"comment",
		      "id":"1",
		      "attributes":{  
		         "comment":"Comment 1",
		         "id":1,
		         "parent_comment":null,
		         "parent_id":null
		      },
		      "links":{  
		         "self":{  
		            "href":"http://example.com/comment/1"
		         }
		      },
		      "relationships":{  
		         "post":{  
		            "data":{  
		               "type":"post",
		               "id":"1"
		            }
		         }
		      }
		   },
		   "included":[  
		      {  
		         "type":"customer",
		         "id":"1",
		         "attributes":{  
		            "name":"Name 1",
		            "active":true
		         },
		         "links":{  
		            "self":{  
		               "href":"http://example.com/customer/1"
		            }
		         }
		      },
		      {  
		         "type":"post",
		         "id":"1",
		         "attributes":{  
		            "date":{  
		               "date":"2016-07-12 16:30:12.000000",
		               "timezone_type":3,
		               "timezone":"Europe/Madrid"
		            },
		            "description":"Description test"
		         },
		         "relationships":{  
		            "customer":{  
		               "data":{  
		                  "type":"customer",
		                  "id":"1"
		               }
		            }
		         },
		         "links":{  
		            "self":{  
		               "href":"http://example.com/post/1"
		            }
		         }
		      }
		   ],
		   "links":{  
		      "self":{  
		         "href":"http://example.com/comment/1"
		      }
		   },
		   "jsonapi":{  
		      "version":"1.0"
		   }
		}
JSON;
        $mapper = new Mapper(self::$classConfig);
        $transformer = new JsonApiTransformer($mapper);
        $serializer = new JsonApiSerializer($transformer);
        $commentSerialize = $serializer->serialize($savedComment);

        $this->assertEquals(json_decode($expected, true), json_decode($commentSerialize, true));
    }

    public function testSecondLevelNestingEntitySerialize()
    {
        $repoCustomer = self::$entityManager->getRepository(Comment::class);
        $savedComment = $repoCustomer->find(2);

        $expected = <<<JSON
        {  
		   "data":{  
		      "type":"comment",
		      "id":"2",
		      "attributes":{  
		         "comment":"Comment 2",
		         "id":2,
		         "parent_id":null
		      },
		      "links":{  
		         "self":{  
		            "href":"http://example.com/comment/2"
		         }
		      },
		      "relationships":{  
		         "parentComment":{  
		            "data":{  
		               "type":"comment",
		               "id":"1"
		            }
		         },
		         "post":{  
		            "data":{  
		               "type":"post",
		               "id":"1"
		            }
		         }
		      }
		   },
		   "included":[  
		      {  
		         "type":"customer",
		         "id":"1",
		         "attributes":{  
		            "name":"Name 1",
		            "active":true
		         },
		         "links":{  
		            "self":{  
		               "href":"http://example.com/customer/1"
		            }
		         }
		      },
		      {  
		         "type":"post",
		         "id":"1",
		         "attributes":{  
		            "description":"Description test",
		            "date":{  
		               "date":"2016-07-12 16:30:12.000000",
		               "timezone_type":3,
		               "timezone":"Europe/Madrid"
		            }
		         },
		         "relationships":{  
		            "customer":{  
		               "data":{  
		                  "type":"customer",
		                  "id":"1"
		               }
		            }
		         },
		         "links":{  
		            "self":{  
		               "href":"http://example.com/post/1"
		            }
		         }
		      },
		      {  
		         "type":"comment",
		         "id":"1",
		         "attributes":{  
		            "comment":"Comment 1",
		            "parent_id":null,
		            "parentComment":null
		         },
		         "relationships":{  
		            "post":{  
		               "data":{  
		                  "type":"post",
		                  "id":"1"
		               }
		            }
		         },
		         "links":{  
		            "self":{  
		               "href":"http://example.com/comment/1"
		            }
		         }
		      }
		   ],
		   "links":{  
		      "self":{  
		         "href":"http://example.com/comment/2"
		      }
		   },
		   "jsonapi":{  
		      "version":"1.0"
		   }
		}
JSON;
        $mapper = new Mapper(self::$classConfig);
        $transformer = new JsonApiTransformer($mapper);
        $serializer = new JsonApiSerializer($transformer);
        $customerSerialize = $serializer->serialize($savedComment);

        $this->assertEquals(json_decode($expected, true), json_decode($customerSerialize, true));
    }
}
