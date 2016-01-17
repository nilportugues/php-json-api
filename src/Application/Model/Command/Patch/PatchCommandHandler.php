<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/2/15
 * Time: 9:38 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Application\Command\Patch;

use NilPortugues\Api\JsonApi\Domain\Model\Contracts\ResourceRepository;
use NilPortugues\Api\JsonApi\Domain\Model\Contracts\MappingRepository;
use NilPortugues\Api\JsonApi\Server\Data\AttributeNameResolverService;
use NilPortugues\Api\JsonApi\Server\Data\PatchAssertion;

class PatchCommandHandler
{
    /**
     * @var ResourceRepository
     */
    protected $resourceRepository;
    /**
     * @var MappingRepository
     */
    protected $mappingRepository;
    /**
     * @var PatchAssertion
     */
    protected $assertion;
    /**
     * @var AttributeNameResolverService
     */
    protected $resolverService;

    /**
     * PatchCommandHandler constructor.
     *
     * @param MappingRepository            $mappingRepository
     * @param PatchAssertion               $assertion
     * @param ResourceRepository           $resourceRepository
     * @param AttributeNameResolverService $resolverService
     */
    public function __construct(
        MappingRepository $mappingRepository,
        PatchAssertion $assertion,
        ResourceRepository $resourceRepository,
        AttributeNameResolverService $resolverService
    ) {
        $this->resolverService = $resolverService;
        $this->mappingRepository = $mappingRepository;
        $this->assertion = $assertion;
        $this->resourceRepository = $resourceRepository;
    }

    /**
     * @param PatchCommand $resource
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(PatchCommand $resource)
    {
        $this->assertion->assert($resource->data(), $resource->className());

        $model = $this->resourceRepository->find($resource->id());
        $values = $this->resolverService->resolve($resource->data());

        $this->resourceRepository->persist($model, $values);
    }
}
