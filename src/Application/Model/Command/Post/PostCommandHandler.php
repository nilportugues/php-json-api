<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/2/15
 * Time: 9:37 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Application\Command\Post;

use Exception;
use NilPortugues\Api\JsonApi\Domain\Contracts\ActionRepository;
use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\Server\Data\DataException;
use NilPortugues\Api\JsonApi\Server\Data\AttributeNameResolverService;
use NilPortugues\Api\JsonApi\Server\Data\PostAssertion;
use NilPortugues\Api\JsonApi\Server\Errors\Error;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;

class PostCommandHandler
{
    /**
     * @var JsonApiSerializer
     */
    protected $serializer;

    /**
     * @var ActionRepository
     */
    protected $repository;
    /**
     * @var AttributeNameResolverService
     */
    protected $resolverService;
    /**
     * @var PostAssertion
     */
    protected $postAssertion;

    /**
     * PostCommandHandler constructor.
     *
     * @param ActionRepository             $repository
     * @param PostAssertion                $postAssertion
     * @param AttributeNameResolverService $resolverService
     * @param JsonApiSerializer            $serializer
     */
    public function __construct(
        ActionRepository $repository,
        PostAssertion $postAssertion,
        AttributeNameResolverService $resolverService,
        JsonApiSerializer $serializer
    ) {
        $this->postAssertion = $postAssertion;
        $this->resolverService = $resolverService;
        $this->serializer = $serializer;
        $this->repository = $repository;
    }

    /**
     * @param PostCommand $createResource
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(PostCommand $createResource)
    {
        $errorBag = new ErrorBag();

        try {
            $this->postAssertion->assert($createResource->data(), $createResource->className(), $errorBag);
            $values = $this->resolverService->resolve($createResource->data());
            $model = $this->repository->persist($createResource->data(), $values, $errorBag);

            $response = $this->resourceCreated($this->serializer->serialize($model));
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
