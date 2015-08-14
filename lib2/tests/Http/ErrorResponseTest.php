<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 8/1/15
 * Time: 12:44 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Tests\Api\Http\Message\JSend;

use NilPortugues\Api\JSend\Http\Message\ErrorResponse;

class ErrorResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testErrorResponse()
    {
        $response = new ErrorResponse('Internal Server Error.');

        $data = json_decode($response->getBody(), true);

        $this->assertEquals('Internal Server Error.', $data['message']);
        $this->assertEquals('error', $data['status']);
        $this->assertEquals(500, $data['code']);

        $this->assertEquals(['application/json; charset=utf-8'], $response->getHeader('Content-type'));
        $this->assertEquals(500, $response->getStatusCode());
    }
}
