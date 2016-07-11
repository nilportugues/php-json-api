<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/15/15
 * Time: 2:04 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Behaviour\Http\Factory;

use NilPortugues\Api\JsonApi\Http\Factory\RequestFactory;

class RequestFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testItWillReturnARequestInstanceOnly()
    {
        $_SERVER = ['REQUEST_METHOD' => 'GET', 'HTTP_HOST' => 'nilportugues.com', 'REQUEST_TIME' => time()];

        $request1 = RequestFactory::create();
        $request2 = RequestFactory::create();

        $this->assertTrue($request1 === $request2);
    }
}
