<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/15/15
 * Time: 4:30 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Server\Actions;

use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Api\JsonApi\Server\Actions\CreateCommandHandler;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use NilPortugues\Api\Mapping\Mapper;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\Post;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\User;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\ValueObject\PostId;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\ValueObject\UserId;
use NilPortugues\Tests\Api\JsonApi\HelperMapping;
use Symfony\Component\HttpFoundation\Response;

class CreateResourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JsonApiSerializer
     */
    private $serializer;

    /**
     * @var CreateCommandHandler
     */
    private $resource;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var callable
     */
    private $callable;

    /**
     *
     */
    public function setUp()
    {
        $this->serializer = new JsonApiSerializer(new JsonApiTransformer(new Mapper(HelperMapping::complex())));
        $this->resource = new CreateCommandHandler($this->serializer);

        $this->data = [
            'type' => 'post',
            'attributes' => [
                'title' => 'My first blog post',
                'content' => 'Ever heard of Lorem Ipsum?',
                'author' => 1,
                'comments' => [],
            ],
        ];

        $this->callable = function (array $data, array $values, ErrorBag $errorBag) {
            $user = new User(new UserId(1), 'Post Author');
            $id = (!empty($data['id'])) ? $data['id'] : 1;

            return new Post(new PostId($id), $values['title'], $values['content'], $user, []);
        };
    }

    public function testItCanGet()
    {
        $response = $this->resource->get($this->data, Post::class, $this->callable);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testItCanGetWillReturnUnprocessableEntityErrorResponse()
    {
        $data = [
            'type' => 'post',
            'attributes' => [
                'title' => 'My first blog post',

            ],
        ];

        $response = $this->resource->get($data, Post::class, $this->callable);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(422, $response->getStatusCode());
    }

    public function testItCanGetWillReturnErrorResponse()
    {
        $callable = function () {
           throw new \Exception();
        };

        $response = $this->resource->get($this->data, Post::class, $callable);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
    }
}
