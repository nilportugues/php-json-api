<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/2/15
 * Time: 9:37 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Application\Query\GetOne;

use NilPortugues\Api\JsonApi\Domain\Model\Contracts\ResourceRepository;
use NilPortugues\Api\JsonApi\Domain\Model\Contracts\MappingRepository;
use NilPortugues\Api\JsonApi\Domain\Model\Parameters\Fields;
use NilPortugues\Api\JsonApi\Domain\Model\Parameters\Included;
use NilPortugues\Api\JsonApi\Domain\Model\Parameters\Sorting;
use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\Server\Actions\Traits\RequestTrait;
use NilPortugues\Api\JsonApi\Server\Query\GetAssertion;

class GetOneQueryHandler
{
    use RequestTrait;

    /**
     * @var JsonApiSerializer
     */
    protected $serializer;

    /**
     * @var Fields
     */
    protected $fields;

    /**
     * @var Included
     */
    protected $included;
    /**
     * @var ResourceRepository
     */
    protected $resourceRepository;
    /**
     * @var MappingRepository
     */
    protected $mappingRepository;

    /**
     * GetResourceHandler constructor.
     *
     * @param MappingRepository  $mappingRepository
     * @param ResourceRepository $resourceRepository
     * @param JsonApiSerializer  $serializer
     * @param Fields             $fields
     * @param Included           $included
     */
    public function __construct(
        MappingRepository $mappingRepository,
        ResourceRepository $resourceRepository,
        JsonApiSerializer $serializer,
        Fields $fields,
        Included $included
    ) {
        $this->mappingRepository = $mappingRepository;
        $this->resourceRepository = $resourceRepository;
        $this->serializer = $serializer;
        $this->fields = $fields;
        $this->included = $included;
    }

    /**
     * @param GetOneQuery $resource
     *
     * @return GetOneResponse
     */
    public function __invoke(GetOneQuery $resource)
    {
        GetAssertion::assert(
            $this->fields,
            $this->included,
            new Sorting(),
            $resource->className()
        );

        $data = $this->resourceRepository->find($resource->id());

        $response = $this->response($this->serializer->serialize($data, $this->fields, $this->included));

        return $response;
    }
}
