<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 8/1/15
 * Time: 12:29 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Tests\Api\JsonApi\Http\Response\JsonApi;

use NilPortugues\Api\JsonApi\Server\Errors\Error;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Http\Response\TooManyRequests;

class TooManyRequestsTest extends \PHPUnit_Framework_TestCase
{
    public function testResponse()
    {
        $errorBag = new ErrorBag([
            new Error('Too Many Requests', 'Client is hammering the service.'),
        ]);

        $response = new TooManyRequests($errorBag);

        $this->assertEquals(429, $response->getStatusCode());
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
            "status" : 429,
            "title": "Too Many Requests",
            "detail": "Client is hammering the service."
        }
    ]
}
JSON;

        return json_decode($json, true);
    }
}
