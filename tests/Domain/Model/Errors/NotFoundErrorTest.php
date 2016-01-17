<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/15/15
 * Time: 4:33 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Domain\Errors;

use NilPortugues\Api\JsonApi\Domain\Model\Errors\NotFoundError;

class NotFoundErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testItWillCreateError()
    {
        $error = new NotFoundError('employee', '1000');

    }
}
