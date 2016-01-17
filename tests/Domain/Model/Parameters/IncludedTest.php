<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/15/15
 * Time: 2:18 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Domain\Model\Parameters;

use NilPortugues\Api\JsonApi\Domain\Model\Parameters\Included;

class IncludedTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Included
     */
    private $included;

    public function setUp()
    {
        $this->included = new Included();

        $this->included->add('employee');
        $this->included->add('employee.name');
        $this->included->add('order.employee');
    }

    public function testGet()
    {
        $this->assertEquals(['employee' => ['name'], 'order' => ['employee']], $this->included->get());
    }

    public function testIsEmpty()
    {
        $this->assertFalse($this->included->isEmpty());
    }
}
