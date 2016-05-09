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

use NilPortugues\Api\JsonApi\Http\Request\Parameters\Fields;
use NilPortugues\Api\JsonApi\Http\Request\Parameters\Included;
use NilPortugues\Api\JsonApi\Http\Request\Parameters\Page;
use NilPortugues\Api\JsonApi\Http\Request\Parameters\Sorting;
use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Api\JsonApi\Server\Actions\ListResource;
use NilPortugues\Api\Mapping\Mapper;
use NilPortugues\Tests\Api\JsonApi\Dummy\ComplexObject\Post;
use NilPortugues\Tests\Api\JsonApi\HelperFactory;
use NilPortugues\Tests\Api\JsonApi\HelperMapping;
use Symfony\Component\HttpFoundation\Response;

class ListResourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JsonApiSerializer
     */
    private $serializer;
    /**
     * @var ListResource
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
     * @var Sorting
     */
    private $sorting;
    /**
     * @var Page
     */
    private $page;
    /**
     * @var array
     */
    private $filters = [];

    /**
     * @var callable
     */
    private $totalAmountCallable;

    /**
     * @var callable
     */
    private $resultsCallable;

    /**
     * @var string
     */
    private $routeUri = 'http://localhost/api/v1/posts';

    /**
     *
     */
    public function setUp()
    {
        $this->serializer = new JsonApiSerializer(new JsonApiTransformer(new Mapper(HelperMapping::complex())));

        $this->page = new Page(1, null, null, null, 10);
        $this->fields = new Fields();
        $this->sorting = new Sorting();
        $this->included = new Included();

        $this->resource = new ListResource(
            $this->serializer,
            $this->page,
            $this->fields,
            $this->sorting,
            $this->included,
            $this->filters
        );

        $this->totalAmountCallable = function () {
            return 2015;
        };

        $this->resultsCallable = function () {
            $results = [];
            for ($i = 1; $i <= 10; ++$i) {
                $results[] = HelperFactory::complexPost();
            }

            return $results;
        };
    }

    public function testItCanGet()
    {
        $response = $this->resource->get($this->totalAmountCallable, $this->resultsCallable, $this->routeUri, Post::class);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testItCanGetWillReturnOutOfBoundsErrorResponse()
    {
        $this->page = new Page(2000, null, null, null, 10);

        $this->resource = new ListResource(
            $this->serializer,
            $this->page,
            $this->fields,
            $this->sorting,
            $this->included,
            $this->filters
        );

        $response = $this->resource->get($this->totalAmountCallable, $this->resultsCallable, $this->routeUri, Post::class);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testItCanGetWillReturnErrorResponse()
    {
        $resultsCallable = function () {
            throw new \Exception();
        };

        $response = $this->resource->get($this->totalAmountCallable, $resultsCallable, $this->routeUri, Post::class);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testItCanGetWillReturnErrorResponseBecauseOfInvalidParams()
    {
        $this->fields = new Fields();
        $this->fields->addField('superhero', 'power');

        $this->resource = new ListResource(
            $this->serializer,
            $this->page,
            $this->fields,
            $this->sorting,
            $this->included,
            $this->filters
        );

        $response = $this->resource->get($this->totalAmountCallable, $this->resultsCallable, $this->routeUri, Post::class);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
    }
}
