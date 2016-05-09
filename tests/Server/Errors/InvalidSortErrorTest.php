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

use NilPortugues\Api\JsonApi\Server\Errors\InvalidSortError;

class InvalidSortErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testItWillCreateError()
    {
        $error = new InvalidSortError('superpower');
        $result = json_encode($error, JSON_PRETTY_PRINT);

        $expected = <<<JSON
{
    "code": "bad_request",
    "title": "Invalid Sort Parameter",
    "detail": "Sorting by `superpower` not supported.",
    "source": {
        "parameter": "sort"
    }
}
JSON;
        $this->assertEquals($expected, $result);
    }
}
