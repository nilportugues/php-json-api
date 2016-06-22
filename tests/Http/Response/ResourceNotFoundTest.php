<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 8/1/15
 * Time: 12:28 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Http\Response\JsonApi;

use NilPortugues\Api\JsonApi\Server\Errors\Error;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Http\Response\ResourceNotFound;

class ResourceNotFoundTest extends \PHPUnit_Framework_TestCase
{
    public function testResponse()
    {
        $errorBag = new ErrorBag([
            new Error('User not found', 'User resource with id 1 was not found.'),
        ]);
        $response = new ResourceNotFound($errorBag);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['application/vnd.api+json'], $response->getHeader('Content-type'));
        $this->assertEquals($this->getJsonError(), json_decode($response->getBody(), true));
    }

    /**
     * @return string
     */
    private function getJsonError()
    {
        $json = <<<JSON
{
    "errors": [
        {
            "status" : 404,
            "title": "User not found",
            "detail": "User resource with id 1 was not found."
        }
    ]
}
JSON;

        return json_decode($json, true);
    }
}
