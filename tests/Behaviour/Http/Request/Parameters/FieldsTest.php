<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/15/15
 * Time: 2:18 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Behaviour\Http\Request\Parameters;

use NilPortugues\Api\JsonApi\Http\Request\Parameters\Fields;

class FieldsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Fields
     */
    private $fields;

    public function setUp()
    {
        $this->fields = new Fields();

        $this->fields->addField('employee', 'name');
        $this->fields->addField('employee', 'surname');
    }

    public function testItWillReturnAllFieldData()
    {
        $expected = [
            'employee' => [
                'name',
                'surname',
            ],
        ];

        $this->assertEquals($expected, $this->fields->get());
    }

    public function testItWillReturnTypeMembers()
    {
        $expected = ['name', 'surname'];

        $this->assertEquals($expected, $this->fields->members('employee'));
    }

    public function testItWillReturnMemberNames()
    {
        $expected = ['employee'];

        $this->assertEquals($expected, $this->fields->types());
    }

    public function testIsEmpty()
    {
        $this->assertFalse($this->fields->isEmpty());
    }
}
