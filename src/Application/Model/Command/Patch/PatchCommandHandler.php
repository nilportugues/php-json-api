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

use Exception;
use NilPortugues\Api\JsonApi\Domain\Contracts\ActionRepository;
use NilPortugues\Api\JsonApi\Domain\Contracts\MappingRepository;
use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\Server\Data\DataException;
use NilPortugues\Api\JsonApi\Server\Data\AttributeNameResolverService;
use NilPortugues\Api\JsonApi\Server\Data\PatchAssertion;
use NilPortugues\Api\JsonApi\Server\Errors\Error;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Server\Errors\NotFoundError;

class PatchCommandHandler
{
    /**
     * @var JsonApiSerializer
     */
    protected $serializer;
    /**
     * @var ActionRepository
     */
    protected $actionRepository;
    /**
     * @var MappingRepository
     */
    protected $mappingRepository;
    /**
     * @var PatchAssertion
     */
    protected $patchAssertion;
    /**
     * @var AttributeNameResolverService
     */
    protected $resolverService;

    /**
     * PatchCommandHandler constructor.
     *
     * @param MappingRepository            $mappingRepository
     * @param PatchAssertion               $patchAssertion
     * @param ActionRepository             $actionRepository
     * @param AttributeNameResolverService $resolverService
     * @param JsonApiSerializer            $serializer
     */
    public function __construct(
        MappingRepository $mappingRepository,
        PatchAssertion $patchAssertion,
        ActionRepository $actionRepository,
        AttributeNameResolverService $resolverService,
        JsonApiSerializer $serializer
    ) {
        $this->resolverService = $resolverService;
        $this->mappingRepository = $mappingRepository;
        $this->patchAssertion = $patchAssertion;
        $this->actionRepository = $actionRepository;
        $this->serializer = $serializer;
    }

    /**
     * @param PatchCommand $resource
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(PatchCommand $resource)
    {
        $errorBag = new ErrorBag();

        try {
            $this->patchAssertion->assert($resource->data(), $resource->className(), $errorBag);

            $model = $this->actionRepository->findBy();

            if (empty($model)) {
                $mapping = $this->mappingRepository->findByClassName($resource->className());

                return $this->resourceNotFound(
                    new ErrorBag(
                        [new NotFoundError($mapping->getClassAlias(), $resource->id())]
                    )
                );
            }

            $this->actionRepository->persist(
                $model,
                $this->resolverService->resolve($resource->data()),
                $errorBag
            );

            $response = $this->resourceUpdated($this->serializer->serialize($model));
        } catch (Exception $e) {
            $response = $this->getErrorResponse($e, $errorBag);
        }

        return $response;
    }

    /**
     * @param Exception $e
     * @param ErrorBag  $errorBag
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getErrorResponse(Exception $e, ErrorBag $errorBag)
    {
        switch (get_class($e)) {
            case DataException::class:
                $response = $this->unprocessableEntity($errorBag);
                break;

            default:
                $response = $this->errorResponse(
                    new ErrorBag([new Error('Bad Request', 'Request could not be served.')])
                );
        }

        return $response;
    }
}
