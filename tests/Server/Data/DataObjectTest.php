<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/15/15
 * Time: 4:32 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Server\Data;

use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Api\JsonApi\Server\Data\DataException;
use NilPortugues\Api\JsonApi\Server\Data\AttributeNameResolverService;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use NilPortugues\Api\Mapping\Mapper;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\Post;
use NilPortugues\Tests\Api\JsonApi\HelperMapping;

class DataObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ErrorBag
     */
    private $errorBag;

    /**
     * @var JsonApiSerializer
     */
    private $serializer;

    public function setUp()
    {
        $this->errorBag = new ErrorBag([]);
        $this->serializer = new JsonApiSerializer(new JsonApiTransformer(new Mapper(HelperMapping::complex())));
    }

    public function testAssertPostWillReturnErrors()
    {
        $errorBag = new ErrorBag();
        $data = [
            'type' => 'post',
            'attributes' => [
                'content' => 'Ever heard of Lorem Ipsum?',
                'author' => 1,
                'comments' => [],
            ],
        ];

        $hasErrors = false;
        try {
            AttributeNameResolverService::assertPost($data, Post::class, $errorBag);
        } catch (DataException $e) {
            $hasErrors = true;
        }
        $this->assertTrue($hasErrors);
    }

    public function testAssertPostWillReturnErrorsForRelationshipMissingDataError()
    {
        $errorBag = new ErrorBag();
        $data = [
            'type' => 'post',
            'attributes' => [
                'content' => 'Ever heard of Lorem Ipsum?',
                'author' => 1,
                'comments' => [],
            ],
            'relationships' => [
                [

                ],
            ],
        ];

        $hasErrors = false;
        try {
            AttributeNameResolverService::assertPost($data, Post::class, $errorBag);
        } catch (DataException $e) {
            $hasErrors = true;
        }
        $this->assertTrue($hasErrors);
    }

    public function testAssertPostWillReturnErrorsForRelationshipMissingTypeError()
    {
        $errorBag = new ErrorBag();
        $data = [
            'type' => 'post',
            'attributes' => [
                'content' => 'Ever heard of Lorem Ipsum?',
                'author' => 1,
                'comments' => [],
            ],
            'relationships' => [
                [
                    'data' => [
                        'attributes' => [
                            'content' => 'Ever heard of Lorem Ipsum?',
                            'author' => 1,
                            'comments' => [],
                            'this_does_not_exist' => null,
                        ],
                    ],
                ],
            ],
        ];

        $hasErrors = false;
        try {
            AttributeNameResolverService::assertPost($data, Post::class, $errorBag);
        } catch (DataException $e) {
            $hasErrors = true;
        }
        $this->assertTrue($hasErrors);
    }

    public function testAssertPostWillReturnErrorsForRelationshipInvalidAttributeError()
    {
        $errorBag = new ErrorBag();
        $data = [
            'type' => 'post',
            'attributes' => [
                'content' => 'Ever heard of Lorem Ipsum?',
                'author' => 1,
                'comments' => [],
            ],
            'relationships' => [
                [
                    'data' => [
                        'type' => 'post',
                        'attributes' => [
                            'content' => 'Ever heard of Lorem Ipsum?',
                            'author' => 1,
                            'comments' => [],
                            'this_does_not_exist' => null,
                        ],
                    ],
                ],
            ],
        ];

        $hasErrors = false;
        try {
            AttributeNameResolverService::assertPost($data, Post::class, $errorBag);
        } catch (DataException $e) {
            $hasErrors = true;
        }
        $this->assertTrue($hasErrors);
    }

    public function testAssertPostWillReturnErrorsForRelationshipInvalidTypeError()
    {
        $errorBag = new ErrorBag();
        $data = [
            'type' => 'post',
            'attributes' => [
                'content' => 'Ever heard of Lorem Ipsum?',
                'author' => 1,
                'comments' => [],
            ],
            'relationships' => [
                [
                    'data' => [
                        'type' => 'post000',
                        'attributes' => [
                            'content' => 'Ever heard of Lorem Ipsum?',
                            'author' => 1,
                            'comments' => [],
                        ],
                    ],
                ],
            ],
        ];

        $hasErrors = false;
        try {
            AttributeNameResolverService::assertPost($data, Post::class, $errorBag);
        } catch (DataException $e) {
            $hasErrors = true;
        }
        $this->assertTrue($hasErrors);
    }

    public function testAssertPostWillReturnErrorsForRelationshipElementWithArray()
    {
        $errorBag = new ErrorBag();
        $data = [
            'type' => 'post',
            'attributes' => [
                'content' => 'Ever heard of Lorem Ipsum?',
                'author' => 1,
                'comments' => [],
            ],
            'relationships' => [
                [
                    'data' => [
                        [
                            'type' => 'post',
                            'attributes' => [
                                'content' => 'Ever heard of Lorem Ipsum?',
                                'author' => 1,
                                'comments' => [],
                                'this_does_not_exist' => null,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $hasErrors = false;
        try {
            AttributeNameResolverService::assertPost($data, Post::class, $errorBag);
        } catch (DataException $e) {
            $hasErrors = true;
        }
        $this->assertTrue($hasErrors);
    }

    public function testAssertPatchWillReturnErrors()
    {
        $errorBag = new ErrorBag();
        $data = [
            'type' => 'post0',
            'attributes' => [
                'content' => 'Ever heard of Lorem Ipsum?',
                'author' => 1,
            ],
        ];
        $hasErrors = false;
        try {
            $this->patchSpecification->assert($data, Post::class, $errorBag);
        } catch (DataException $e) {
            $hasErrors = true;
        }
        $this->assertTrue($hasErrors);
    }

    public function testAssertPutWillReturnErrors()
    {
        $errorBag = new ErrorBag();
        $data = [
            'type' => 'post',
            'attributes' => [
                'content' => 'Ever heard of Lorem Ipsum?',
                'author' => 1,
                'comments' => [],
            ],
        ];
        $hasErrors = false;
        try {
            AttributeNameResolverService::assertPut($data, Post::class, $errorBag);
        } catch (DataException $e) {
            $hasErrors = true;
        }
        $this->assertTrue($hasErrors);
    }

    public function testGetAttributes()
    {
        $data = [
            'type' => 'post',
            'attributes' => [
                'title' => 'My first blog post',
                'content' => 'Ever heard of Lorem Ipsum?',
                'author' => 1,
                'comments' => [],
            ],
        ];
        $attributes = AttributeNameResolverService::resolve($data);

        $this->assertNotEmpty($attributes);
    }
}
