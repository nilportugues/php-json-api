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

use NilPortugues\Api\JsonApi\Domain\Errors\Error;
use NilPortugues\Api\JsonApi\Domain\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Http\Response\ResourceConflicted;

class ResourceConflictedTest extends \PHPUnit_Framework_TestCase
{
    public function testResponse()
    {
        $errorBag = new ErrorBag([
            new Error('Conflicted modification', 'There seems to be an inconsistency in your resource.'),
        ]);
        $response = new ResourceConflicted($errorBag);

        $this->assertEquals(409, $response->getStatusCode());
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
            "status" : 409,
            "title": "Conflicted modification",
            "detail": "There seems to be an inconsistency in your resource."
        }
    ]
}
JSON;

        return json_decode($json, true);
    }
}
