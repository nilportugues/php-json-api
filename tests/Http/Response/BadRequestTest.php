<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 8/1/15
 * Time: 12:27 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Http\Response;

use NilPortugues\Api\JsonApi\Server\Errors\Error;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Http\Response\BadRequest;

class BadRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testResponse()
    {
        $errorBag = new ErrorBag([
            new Error('Json Error', 'JSON parse error - Expecting property name at line 1 column 2.'),
        ]);

        $response = new BadRequest($errorBag);

        $this->assertEquals(400, $response->getStatusCode());
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
            "status" : 400,
            "title": "Json Error",
            "detail": "JSON parse error - Expecting property name at line 1 column 2."
        }
    ]
}
JSON;

        return json_decode($json, true);
    }
}
