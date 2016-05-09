<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/15/15
 * Time: 4:32 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Tests\Api\JsonApi\Server\Errors;

use NilPortugues\Api\JsonApi\Server\Errors\InvalidAttributeError;

class InvalidAttributeErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testItWillCreateError()
    {
        $error = new InvalidAttributeError('employee', 'superpower');
        $result = json_encode($error, JSON_PRETTY_PRINT);

        $expected = <<<JSON
{
    "code": "bad_request",
    "title": "Invalid Attribute",
    "detail": "Attribute `employee` for resource `superpower` is not valid.",
    "source": {
        "pointer": "\/data\/attributes"
    }
}
JSON;
        $this->assertEquals($expected, $result);
    }
}
