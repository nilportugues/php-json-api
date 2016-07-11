<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/15/15
 * Time: 4:33 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Behaviour\Server\Errors;

use NilPortugues\Api\JsonApi\Server\Errors\MissingAttributesError;

class MissingAttributesErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testItWillCreateError()
    {
        $error = new MissingAttributesError();
        $result = json_encode($error, JSON_PRETTY_PRINT);

        $expected = <<<JSON
{
    "code": "bad_request",
    "title": "Bad Request",
    "detail": "Missing `attributes` Member at `data` level.",
    "source": {
        "pointer": "\/data"
    }
}
JSON;
        $this->assertEquals($expected, $result);
    }
}
