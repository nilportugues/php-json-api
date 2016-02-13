<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/2/15
 * Time: 9:38 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Server\Actions;

use Exception;
use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\Server\Actions\Traits\ResponseTrait;
use NilPortugues\Api\JsonApi\Server\Errors\Error;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Server\Errors\NotFoundError;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\PageRepository;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\ReadRepository;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Repository;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\WriteRepository;

/**
 * Class DeleteResource.
 */
class DeleteResource
{
    use ResponseTrait;

    /**
     * @var \NilPortugues\Api\JsonApi\Server\Errors\ErrorBag
     */
    protected $errorBag;

    /**
     * @var JsonApiSerializer
     */
    protected $serializer;

    /**
     * @var Repository|ReadRepository|WriteRepository|PageRepository
     */
    protected $repository;

    /**
     * DeleteResource constructor.
     *
     * @param Repository        $repository
     * @param JsonApiSerializer $serializer
     */
    public function __construct(Repository $repository, JsonApiSerializer $serializer)
    {
        $this->serializer = $serializer;
        $this->repository = $repository;
        $this->errorBag = new ErrorBag();
    }

    /**
     * @param $id
     * @param $className
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function get($id, $className)
    {
        try {
            $resourceId = new ResourceId($id);

            if (false === $this->repository->exists($resourceId)) {
                $mapping = $this->serializer->getTransformer()->getMappingByClassName($className);

                return $this->resourceNotFound(new ErrorBag([new NotFoundError($mapping->getClassAlias(), $id)]));
            }

            $this->repository->remove($resourceId);

            return $this->resourceDeleted();
        } catch (Exception $e) {
            return $this->errorResponse(new ErrorBag([new Error('Bad Request', 'Request could not be served.')]));
        }
    }
}
