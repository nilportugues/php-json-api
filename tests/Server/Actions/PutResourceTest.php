<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/15/15
 * Time: 4:31 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Server\Actions;

use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Api\JsonApi\Server\Actions\PutResource;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use NilPortugues\Api\Mapping\Mapper;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\Post;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\User;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\ValueObject\PostId;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\ValueObject\UserId;
use NilPortugues\Tests\Api\JsonApi\HelperMapping;
use Symfony\Component\HttpFoundation\Response;

class PutResourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JsonApiSerializer
     */
    private $serializer;

    /**
     * @var PutResource
     */
    private $resource;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var callable
     */
    private $updateCallable;

    /**
     * @var callable
     */
    private $findOneCallable;

    /**
     *
     */
    public function setUp()
    {
        $this->serializer = new JsonApiSerializer(new JsonApiTransformer(new Mapper(HelperMapping::complex())));
        $this->resource = new PutResource($this->serializer);

        $this->data = [
            'type' => 'post',
            'id' => 10,
            'attributes' => [
                'title' => 'My first blog post',
                'content' => 'Ever heard of Lorem Ipsum?',
                'author' => 1,
                'comments' => [],
            ],
        ];

        $this->findOneCallable = function () {
            $user = new User(new UserId(1), 'Post Author');

            return new Post(new PostId(10), 'Old title', 'Old Content', $user, []);
        };

        $this->updateCallable = function (Post $post, array $data, array $values, ErrorBag $errorBag) {
            $post->setTitle($values['title']);
            $post->setContent($values['content']);

            return $post;
        };
    }

    public function testItCanGet()
    {
        $response = $this->resource->get(10, $this->data, Post::class, $this->findOneCallable, $this->updateCallable);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Ever heard of Lorem Ipsum?', $response->getContent());
        $this->assertContains('My first blog post', $response->getContent());
    }

    public function testItCanGetWillReturnUnprocessableEntityErrorResponse()
    {
        $data = [
            'type' => 'post',
            'id' => 10,
            'attributes' => [
                'title' => 'My first blog post',

            ],
        ];

        $response = $this->resource->get(10, $data, Post::class, $this->findOneCallable, $this->updateCallable);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(422, $response->getStatusCode());
    }

    public function testItCanGetWillReturnResourceNotFoundErrorWhenFindOneCallableFailsToReturnModel()
    {
        $findOneCallable = function () {
            return;
        };

        $response = $this->resource->get(10, $this->data, Post::class, $findOneCallable, $this->updateCallable);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testItCanGetWillReturnErrorResponse()
    {
        $callable = function () {
            throw new \Exception();
        };

        $response = $this->resource->get(10, $this->data, Post::class, $callable, $this->updateCallable);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
    }
}
