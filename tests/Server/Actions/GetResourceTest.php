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

use NilPortugues\Api\JsonApi\Http\Request\Parameters\Fields;
use NilPortugues\Api\JsonApi\Http\Request\Parameters\Included;
use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Api\JsonApi\Server\Actions\GetResource;
use NilPortugues\Api\Mapping\Mapper;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\Post;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\User;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\ValueObject\PostId;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\ValueObject\UserId;
use NilPortugues\Tests\Api\JsonApi\HelperMapping;
use Symfony\Component\HttpFoundation\Response;

class GetResourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JsonApiSerializer
     */
    private $serializer;

    /**
     * @var GetResource
     */
    private $resource;

    /**
     * @var callable
     */
    private $findOneCallable;

    /**
     * @var Fields
     */
    private $fields;

    /**
     * @var Included
     */
    private $included;

    /**
     *
     */
    public function setUp()
    {
        $this->serializer = new JsonApiSerializer(new JsonApiTransformer(new Mapper(HelperMapping::complex())));
        $this->fields = new Fields();
        $this->included = new Included();

        $this->resource = new GetResource($this->serializer, $this->fields, $this->included);

        $this->findOneCallable = function () {
            $user = new User(new UserId(1), 'Post Author');

            return new Post(new PostId(10), 'Old title', 'Old content', $user, []);
        };
    }

    public function testItCanGet()
    {
        $response = $this->resource->get(10, Post::class, $this->findOneCallable);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testItCanGetWillReturnNotFoundErrorResponse()
    {
        $findOneCallable = function () {
            return;
        };

        $response = $this->resource->get(10, Post::class, $findOneCallable);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testItCanGetWillReturnErrorResponse()
    {
        $findOneCallable = function () {
            throw new \Exception();
        };

        $response = $this->resource->get(10, Post::class, $findOneCallable);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testItCanGetWillReturnErrorResponseBecauseOfInvalidParams()
    {
        $this->fields = new Fields();
        $this->fields->addField('superhero', 'power');

        $this->resource = new GetResource($this->serializer, $this->fields, $this->included);

        $response = $this->resource->get(10, Post::class, $this->findOneCallable);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
    }
}
