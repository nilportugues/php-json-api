<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/2/15
 * Time: 9:38 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Application\Command\Put;

use NilPortugues\Api\JsonApi\Domain\Model\Contracts\ResourceRepository;
use NilPortugues\Api\JsonApi\Domain\Model\Contracts\MappingRepository;
use NilPortugues\Api\JsonApi\Server\Data\AttributeNameResolverService;
use NilPortugues\Api\JsonApi\Server\Data\PutAssertion;

class PutCommandHandler
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
     * @var PutAssertion
     */
    protected $assertion;
    /**
     * @var AttributeNameResolverService
     */
    protected $resolverService;

    /**
     * PutResourceHandler constructor.
     *
     * @param MappingRepository            $mappingRepository
     * @param ResourceRepository           $actionRepository
     * @param PutAssertion                 $assertion
     * @param AttributeNameResolverService $resolverService
     */
    public function __construct(
        MappingRepository $mappingRepository,
        ResourceRepository $actionRepository,
        PutAssertion $assertion,
        AttributeNameResolverService $resolverService
    ) {
        $this->resolverService = $resolverService;
        $this->mappingRepository = $mappingRepository;
        $this->actionRepository = $actionRepository;
        $this->assertion = $assertion;
    }

    /**
     * @param PutCommand $resource
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(PutCommand $resource)
    {
        $this->assertion->assert($resource->data(), $resource->className());

        $model = $this->actionRepository->find($resource->id());
        $values = $this->resolverService->resolve($resource->data());

        $this->actionRepository->persist($model, $values);
    }
}
