<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/15/15
 * Time: 4:34 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Tests\Api\JsonApi\Server\Errors;

use NilPortugues\Api\JsonApi\Server\Errors\OufOfBoundsError;

class OufOfBoundsErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testItWillCreateError()
    {
        $error = new OufOfBoundsError(5, 10);

        $result = json_encode($error, JSON_PRETTY_PRINT);

        $expected = <<<JSON
{
    "code": "out_of_bounds",
    "title": "Ouf Of Bounds",
    "detail": "Page 5 of size 10 was not found.",
    "source": {
        "parameter": "page"
    }
}
JSON;
        $this->assertEquals($expected, $result);
    }
}
