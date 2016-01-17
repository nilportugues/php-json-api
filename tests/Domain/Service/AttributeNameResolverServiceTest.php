<?php

namespace NilPortugues\Tests\Api\JsonApi\Domain\Service\Data;

use NilPortugues\Api\JsonApi\Infrastructure\Repositories\MappingRepository;
use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Api\JsonApi\Domain\Service\AttributeNameResolverService;
use NilPortugues\Api\Mapping\Mapper;
use NilPortugues\Tests\Api\JsonApi\EmployeeMapping;

class AttributeNameResolverServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AttributeNameResolverService
     */
    private $resolverService;

    public function setUp()
    {
        $mappings = [EmployeeMapping::mapping()];

        $mapper = new Mapper($mappings);
        $transformer = new JsonApiTransformer($mapper);
        $mappingRepository = new MappingRepository($transformer);

        $this->resolverService = new AttributeNameResolverService($mappingRepository);
    }

    public function testItCanResolve()
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

        $expected = [
            'firstName' => 'John',
            'surname' => 'Doe',
            'company' => 'Example.com',
            'job_title' => 'CEO',
            'email_address' => 'ceo@example.com',
            'city' => 'Barcelona',
        ];

        $output = $this->resolverService->resolve($data);

        $this->assertEquals($expected, $output);
    }
}
