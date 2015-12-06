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
use NilPortugues\Api\JsonApi\Server\Actions\Traits\RequestTrait;
use NilPortugues\Api\JsonApi\Server\Actions\Traits\ResponseTrait;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Server\Errors\NotFoundError;

/**
 * Class DeleteResource.
 */
class DeleteResource
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
            $callable();

            return $this->resourceDeleted();
        } catch (Exception $e) {
            $mapping = $this->serializer->getTransformer()->getMappingByClassName($className);

            $errors = new ErrorBag([new NotFoundError($mapping->getClassAlias(), $id)]);

            return $this->resourceNotFound($errors);
        }
    }
}
