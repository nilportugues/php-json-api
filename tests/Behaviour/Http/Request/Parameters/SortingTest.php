<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/15/15
 * Time: 2:19 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Behaviour\Http\Request\Parameters;

use NilPortugues\Api\JsonApi\Http\Request\Parameters\Sorting;

class SortingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Sorting
     */
    private $sorting;

    public function setUp()
    {
        $this->sorting = new Sorting();

        $this->sorting->addField('name', 'ascending');
        $this->sorting->addField('id', 'descending');
    }

    public function testGetSorting()
    {
        $this->assertEquals(['name' => 'ascending', 'id' => 'descending'], $this->sorting->sorting());
    }

    public function testGetFields()
    {
        $this->assertEquals(['name', 'id'], $this->sorting->fields());
    }

    public function testGet()
    {
        $this->assertEquals('name,-id', $this->sorting->get());
    }

    public function testIsEmpty()
    {
        $this->assertFalse($this->sorting->isEmpty());
    }
}
