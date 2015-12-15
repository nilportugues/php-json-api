<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/15/15
 * Time: 4:29 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Server\Actions\Traits;

use NilPortugues\Api\JsonApi\Http\PaginatedResource;
use NilPortugues\Api\JsonApi\Server\Actions\Traits\ResponseTrait;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class ResponseTraitTest extends \PHPUnit_Framework_TestCase
{
    use ResponseTrait;

    public function testItWillReturnErrorResponse()
    {
        $this->assertInstanceOf(Response::class, $this->errorResponse(new ErrorBag()));
    }

    public function testItWillAddHeaders()
    {
        $response = $this->getMockBuilder(ResponseInterface::class)->disableOriginalConstructor()->getMock();

        $this->assertInstanceOf(ResponseInterface::class, $this->addHeaders($response));
    }

    public function testItWillReturnResourceCreated()
    {
        $this->assertInstanceOf(Response::class, $this->resourceCreated(''));
    }

    public function testItWillReturnResourceDeleted()
    {
        $this->assertInstanceOf(Response::class, $this->resourceDeleted());
    }

    public function testItWillReturnResourceNotFound()
    {
        $this->assertInstanceOf(Response::class, $this->resourceNotFound(new ErrorBag()));
    }

    public function testItWillReturnResourceConflicted()
    {
        $this->assertInstanceOf(Response::class, $this->resourceConflicted(new ErrorBag()));
    }

    public function testItWillReturnResourceProcessing()
    {
        $this->assertInstanceOf(Response::class, $this->resourceProcessing(''));
    }

    public function testItWillReturnResourceUpdated()
    {
        $this->assertInstanceOf(Response::class, $this->resourceUpdated(''));
    }

    public function testItWillReturnResponse()
    {
        $this->assertInstanceOf(Response::class, $this->response(
            new PaginatedResource(json_encode(['data' => []])))
        );
    }

    public function testItWillReturnUnsupportedAction()
    {
        $this->assertInstanceOf(Response::class, $this->unsupportedAction(new ErrorBag()));
    }

    public function testItWillReturnUnprocessableEntity()
    {
        $this->assertInstanceOf(Response::class, $this->unprocessableEntity(new ErrorBag()));
    }
}
