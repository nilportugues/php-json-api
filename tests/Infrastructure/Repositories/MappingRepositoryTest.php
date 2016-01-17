<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 17/01/16
 * Time: 18:49.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Infrastructure\Repositories;

use NilPortugues\Api\JsonApi\Infrastructure\Repositories\MappingRepository;
use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Api\Mapping\Mapper;
use NilPortugues\Tests\Api\JsonApi\Employee;
use NilPortugues\Tests\Api\JsonApi\EmployeeMapping;

class MappingRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MappingRepository
     */
    private $mappingRepository;

    public function setUp()
    {
        $mappings = [EmployeeMapping::mapping()];

        $mapper = new Mapper($mappings);
        $transformer = new JsonApiTransformer($mapper);
        $this->mappingRepository = new MappingRepository($transformer);
    }

    public function testItFindsByAlias()
    {
        $mapping = $this->mappingRepository->findByAlias('worker');

        $this->assertEquals(Employee::class, $mapping->getClassName());
    }

    public function testItFindsByClassName()
    {
        $mapping = $this->mappingRepository->findByClassName(Employee::class);

        $this->assertEquals(Employee::class, $mapping->getClassName());
    }
}
