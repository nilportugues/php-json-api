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

use NilPortugues\Api\Http\Message\JSend\FailResponse;

class FailResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testFailResponse()
    {
        $response = new FailResponse(json_encode(['user_name' => 'Must contain alphanumeric values only']));

        $data = json_decode($response->getBody(), true);

        $this->assertEquals('Must contain alphanumeric values only', $data['data']['user_name']);
        $this->assertEquals('fail', $data['status']);

        $this->assertEquals(['application/json; charset=utf-8'], $response->getHeader('Content-type'));
        $this->assertEquals(400, $response->getStatusCode());
    }
}
