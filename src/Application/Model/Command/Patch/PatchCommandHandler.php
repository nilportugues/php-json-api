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
    protected $actionRepository;
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
     * @param ResourceRepository           $actionRepository
     * @param AttributeNameResolverService $resolverService
     */
    public function __construct(
        MappingRepository $mappingRepository,
        PatchAssertion $assertion,
        ResourceRepository $actionRepository,
        AttributeNameResolverService $resolverService
    ) {
        $this->resolverService = $resolverService;
        $this->mappingRepository = $mappingRepository;
        $this->assertion = $assertion;
        $this->actionRepository = $actionRepository;
    }

    /**
     * @param PatchCommand $resource
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(PatchCommand $resource)
    {
        $this->assertion->assert($resource->data(), $resource->className());

        $model = $this->actionRepository->find($resource->id());
        $values = $this->resolverService->resolve($resource->data());

        $this->actionRepository->persist($model, $values);
    }
}
