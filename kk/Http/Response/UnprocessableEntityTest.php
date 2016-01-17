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

use NilPortugues\Api\JsonApi\Domain\Errors\Error;
use NilPortugues\Api\JsonApi\Domain\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Http\Response\UnprocessableEntity;

class UnprocessableEntityTest extends \PHPUnit_Framework_TestCase
{
    public function testResponse()
    {
        $errorBag = new ErrorBag([
            new Error('Unprocessable Entity', 'Missing `data` Member at document\'s top level.'),
        ]);

        $response = new UnprocessableEntity($errorBag);

        $this->assertEquals(422, $response->getStatusCode());
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
            "status" : 422,
            "title": "Unprocessable Entity",
            "detail": "Missing `data` Member at document's top level."
        }
    ]
}
JSON;

        return json_decode($json, true);
    }
}
