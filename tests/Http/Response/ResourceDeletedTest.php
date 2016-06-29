<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 8/1/15
 * Time: 12:28 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Http\Response;

use NilPortugues\Api\JsonApi\Http\Response\ResourceDeleted;

class ResourceDeletedTest extends \PHPUnit_Framework_TestCase
{
    public function testResponse()
    {
        $response = new ResourceDeleted();

        $this->assertSame('', (string) $response->getBody());
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals(['application/vnd.api+json'], $response->getHeader('Content-type'));
    }
}
