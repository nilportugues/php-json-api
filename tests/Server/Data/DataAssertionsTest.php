<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/15/15
 * Time: 4:31 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Server\Data;

use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Api\JsonApi\Server\Data\DataAssertions;
use NilPortugues\Api\JsonApi\Server\Data\DataException;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Server\Errors\InvalidAttributeError;
use NilPortugues\Api\JsonApi\Server\Errors\InvalidTypeError;
use NilPortugues\Api\JsonApi\Server\Errors\MissingAttributesError;
use NilPortugues\Api\JsonApi\Server\Errors\MissingDataError;
use NilPortugues\Api\JsonApi\Server\Errors\MissingTypeError;
use NilPortugues\Api\Mapping\Mapper;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\Post;
use NilPortugues\Tests\Api\JsonApi\HelperMapping;

class DataAssertionsTest extends \PHPUnit_Framework_TestCase
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

    public function testItWillAddToErrorBagMissingDataError()
    {
        $data = [];

        try {
            DataAssertions::assert($data, $this->serializer, Post::class, $this->errorBag);
        } catch (DataException $e) {
        }

        $this->assertArrayContainsExpectedErrorType(MissingDataError::class);
    }

    public function testItWillAddToErrorBagMissingTypeError()
    {
        $data = [

            'attributes' => [
                'title' => 'My first blog post',
                'content' => 'Ever heard of Lorem Ipsum?',
                'author' => 1,
                'comments' => [],
            ],

        ];

        try {
            DataAssertions::assert($data, $this->serializer, Post::class, $this->errorBag);
        } catch (DataException $e) {
        }
        $this->assertArrayContainsExpectedErrorType(MissingTypeError::class);
    }

    public function testItWillAddToErrorBagInvalidTypeError()
    {
        $data = [

            'type' => 'post0000',
            'attributes' => [
                'title' => 'My first blog post',
                'content' => 'Ever heard of Lorem Ipsum?',
                'author' => 1,
                'comments' => [],
            ],

        ];

        try {
            DataAssertions::assert($data, $this->serializer, Post::class, $this->errorBag);
        } catch (DataException $e) {
        }
        $this->assertArrayContainsExpectedErrorType(InvalidTypeError::class);
    }

    public function testItWillAddToErrorBagMissingAttributesError()
    {
        $data = [
            'type' => 'post',
        ];

        try {
            DataAssertions::assert($data, $this->serializer, Post::class, $this->errorBag);
        } catch (DataException $e) {
        }
        $this->assertArrayContainsExpectedErrorType(MissingAttributesError::class);
    }

    public function testItWillAddToErrorBagInvalidAttributeError()
    {
        $data = [

            'type' => 'post',
            'attributes' => [
                'title000000' => 'My first blog post',
                'content' => 'Ever heard of Lorem Ipsum?',
                'author' => 1,
                'comments' => [],
            ],

        ];

        try {
            DataAssertions::assert($data, $this->serializer, Post::class, $this->errorBag);
        } catch (DataException $e) {
        }
        $this->assertArrayContainsExpectedErrorType(InvalidAttributeError::class);
    }

    /**
     * @param string $class
     */
    private function assertArrayContainsExpectedErrorType($class)
    {
        $hasErrorType = false;
        foreach ($this->errorBag as $error) {
            if (get_class($error) === $class) {
                $hasErrorType = true;
                break;
            }
        }
        $this->assertTrue($hasErrorType);
    }
}
