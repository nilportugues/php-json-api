<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/2/15
 * Time: 9:37 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Server\Actions;

use Exception;
use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\Server\Actions\Traits\RequestTrait;
use NilPortugues\Api\JsonApi\Server\Actions\Traits\ResponseTrait;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Server\Errors\NotFoundError;
use NilPortugues\Api\JsonApi\Server\Query\QueryException;
use NilPortugues\Api\JsonApi\Server\Query\QueryObject;

/**
 * Class GetResource.
 */
class GetResource
{
    use RequestTrait;
    use ResponseTrait;

    /**
     * @var \NilPortugues\Api\JsonApi\Server\Errors\ErrorBag
     */
    private $errorBag;

    /**
     * @var JsonApiSerializer
     */
    private $serializer;

    /**
     * @param JsonApiSerializer $serializer
     */
    public function __construct(JsonApiSerializer $serializer)
    {
        $this->serializer = $serializer;
        $this->errorBag = new ErrorBag();
    }

    /**
     * @param          $id
     * @param          $className
     * @param callable $callable
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function get($id, $className, callable $callable)
    {
        try {
            QueryObject::assert($this->serializer, $this->errorBag);
            $data = $callable();

            $response = $this->response($this->serializer->serialize($data, $this->apiRequest()));
        } catch (Exception $e) {
            $response = $this->getErrorResponse($id, $className, $e);
        }

        return $response;
    }

    /**
     * @param           $id
     * @param           $className
     * @param Exception $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function getErrorResponse($id, $className, Exception $e)
    {
        switch (get_class($e)) {
            case QueryException::class:
                $response = $this->errorResponse($this->errorBag);
                break;

            default:
                $mapping = $this->serializer->getTransformer()->getMappingByClassName($className);

                $response = $this->resourceNotFound(
                    new ErrorBag([new NotFoundError($mapping->getClassAlias(), $id)])
                );
        }

        return $response;
    }
}
