<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/15/15
 * Time: 4:33 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Server\Errors;

use NilPortugues\Api\JsonApi\Server\Errors\MissingAttributeError;

class MissingAttributeErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testItWillCreateError()
    {
        $error = new MissingAttributeError('name');
        $result = json_encode($error, JSON_PRETTY_PRINT);

        $expected = <<<JSON
{
    "code": "unprocessable_entity",
    "title": "Missing Attribute",
    "detail": "Attribute `name` is missing.",
    "source": {
        "pointer": "\/data\/attributes\/name"
    }
}
JSON;
        $this->assertEquals($expected, $result);
    }
}
