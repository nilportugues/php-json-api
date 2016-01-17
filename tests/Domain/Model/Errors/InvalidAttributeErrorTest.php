<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/15/15
 * Time: 4:32 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Domain\Errors;

use NilPortugues\Api\JsonApi\Domain\Model\Errors\InvalidAttributeError;

class InvalidAttributeErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testItWillCreateError()
    {
        $error = new InvalidAttributeError('employee', 'superpower');
    }
}
