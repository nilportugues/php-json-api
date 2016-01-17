<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 17/01/16
 * Time: 22:41.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Application\Model;

use NilPortugues\Api\JsonApi\Application\Model\DataFormatAssertion;
use NilPortugues\Api\JsonApi\Domain\Model\Exceptions\InputException;
use NilPortugues\Api\JsonApi\Infrastructure\Repositories\MappingRepository;
use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Api\Mapping\Mapper;
use NilPortugues\Tests\Api\JsonApi\Employee;
use NilPortugues\Tests\Api\JsonApi\EmployeeMapping;

class DataFormatAssertionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DataFormatAssertion
     */
    private $assertion;

    public function setUp()
    {
        $mappings = [EmployeeMapping::mapping()];
        $mapper = new Mapper($mappings);
        $transformer = new JsonApiTransformer($mapper);
        $mappingRepository = new MappingRepository($transformer);

        $this->assertion = new DataFormatAssertion($mappingRepository);
    }

    public function testItWontThrowExceptionOnValidData()
    {
        $data = [
            'type' => 'worker',
            'attributes' => [
                'name' => 'John',
                'family_name' => 'Doe',
                'company' => 'Example.com',
                'job_title' => 'CEO',
                'email_address' => 'ceo@example.com',
                'city' => 'Barcelona',
            ],
        ];

        $this->assertion->assert($data, Employee::class);

        $this->assertTrue(true);
    }

    public function testItWillThrowExceptionWhenFailsAssertingItIsArray()
    {
        $this->setExpectedException(InputException::class);
        $data = null;

        $this->assertion->assert($data, Employee::class);
    }

    public function testItWillThrowExceptionWhenFailsAssertingItHasTypeMember()
    {
        $this->setExpectedException(InputException::class);
        $data = [
            'attributes' => [
                'name' => 'John',
                'family_name' => 'Doe',
                'company' => 'Example.com',
                'job_title' => 'CEO',
                'email_address' => 'ceo@example.com',
                'city' => 'Barcelona',
            ],
        ];

        $this->assertion->assert($data, Employee::class);
    }

    public function testItWillThrowExceptionWhenFailsAssertingItTypeMemberIsExpectedValue()
    {
        $this->setExpectedException(InputException::class);
        $data = [
            'type' => 'not-a-valid-type',
            'attributes' => [
                'name' => 'John',
                'family_name' => 'Doe',
                'company' => 'Example.com',
                'job_title' => 'CEO',
                'email_address' => 'ceo@example.com',
                'city' => 'Barcelona',
            ],
        ];

        $this->assertion->assert($data, Employee::class);
    }

    public function testItWillThrowExceptionWhenFailsAssertingItHasAttributeMember()
    {
        $this->setExpectedException(InputException::class);
        $data = [
            'type' => 'worker',
        ];

        $this->assertion->assert($data, Employee::class);
    }

    public function testItWillThrowExceptionWhenFailsAssertingAttributesExists()
    {
        $this->setExpectedException(InputException::class);
        $data = [
            'type' => 'worker',
            'attributes' => [
                'this-does-not-exist' => 'John',
                'family_name' => 'Doe',
                'company' => 'Example.com',
                'job_title' => 'CEO',
                'email_address' => 'ceo@example.com',
                'city' => 'Barcelona',
            ],
        ];

        $this->assertion->assert($data, Employee::class);
    }
}
