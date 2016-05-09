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

use NilPortugues\Api\JsonApi\Server\Errors\InvalidParameterMemberError;

class InvalidParameterMemberErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testItWillCreateError()
    {
        $error = new InvalidParameterMemberError('superpower', 'employee', 'included');
        $result = json_encode($error, JSON_PRETTY_PRINT);

        $expected = <<<JSON
{
    "code": "bad_request",
    "title": "Invalid Parameter",
    "detail": "Parameter member `superpower` not supported.",
    "source": {
        "parameter": "included[employee]"
    }
}
JSON;
        $this->assertEquals($expected, $result);
    }
}
