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

use Exception;
use NilPortugues\Api\JsonApi\Domain\Contracts\ActionRepository;
use NilPortugues\Api\JsonApi\Domain\Contracts\MappingRepository;
use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\Server\Data\DataException;
use NilPortugues\Api\JsonApi\Server\Data\AttributeNameResolverService;
use NilPortugues\Api\JsonApi\Server\Data\PutAssertion;
use NilPortugues\Api\JsonApi\Server\Errors\Error;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Server\Errors\NotFoundError;

class PutResourceHandler
{
    /**
     * @var \NilPortugues\Api\JsonApi\Server\Errors\ErrorBag
     */
    protected $errorBag;

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
     * @var PutAssertion
     */
    protected $putSpecification;

    /**
     * PutResourceHandler constructor.
     *
     * @param MappingRepository $mappingRepository
     * @param ActionRepository  $actionRepository
     * @param PutAssertion      $putSpecification
     * @param JsonApiSerializer $serializer
     */
    public function __construct(
        MappingRepository $mappingRepository,
        ActionRepository $actionRepository,
        PutAssertion $putSpecification,
        JsonApiSerializer $serializer
    ) {
        $this->mappingRepository = $mappingRepository;
        $this->actionRepository = $actionRepository;
        $this->putSpecification = $putSpecification;

        $this->serializer = $serializer;
        $this->errorBag = new ErrorBag();
    }

    /**
     * @param PutResource $resource
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(PutResource $resource)
    {
        try {
            $this->putSpecification->assert(
                $resource->data(),
                $resource->className(),
                $this->errorBag
            );

            $model = $this->actionRepository->findBy();

            if (empty($model)) {
                $mapping = $this->mappingRepository->findByClassName($resource->className());

                return $this->resourceNotFound(
                    new ErrorBag(
                        [new NotFoundError($mapping->getClassAlias(), $resource->id())]
                    )
                );
            }

            $values = AttributeNameResolverService::resolve($resource->data());
            $this->actionRepository->persist($model, $values, $this->errorBag);

            $response = $this->resourceUpdated($this->serializer->serialize($model));
        } catch (Exception $e) {
            $response = $this->getErrorResponse($e);
        }

        return $response;
    }

    /**
     * @param Exception $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getErrorResponse(Exception $e)
    {
        switch (get_class($e)) {
            case DataException::class:
                $response = $this->unprocessableEntity($this->errorBag);
                break;

            default:
                $response = $this->errorResponse(
                    new ErrorBag([new Error('Bad Request', 'Request could not be served.')])
                );
        }

        return $response;
    }
}
