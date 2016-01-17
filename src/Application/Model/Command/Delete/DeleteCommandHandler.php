<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/2/15
 * Time: 9:38 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Application\Command\Delete;

use Exception;
use NilPortugues\Api\JsonApi\Domain\Contracts\ActionRepository;
use NilPortugues\Api\JsonApi\Domain\Contracts\MappingRepository;
use NilPortugues\Api\JsonApi\Server\Errors\Error;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Server\Errors\NotFoundError;

class DeleteCommandHandler
{
    /**
     * @var ActionRepository
     */
    protected $actionRepository;
    /**
     * @var MappingRepository
     */
    protected $mappingRepository;

    /**
     * DeleteResourceHandler constructor.
     *
     * @param MappingRepository $mappingRepository
     * @param ActionRepository  $actionRepository
     */
    public function __construct(MappingRepository $mappingRepository, ActionRepository $actionRepository)
    {
        $this->mappingRepository = $mappingRepository;
        $this->actionRepository = $actionRepository;
    }

    /**
     * @param DeleteCommand $resource
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(DeleteCommand $resource)
    {
        try {
            $response = null;
            $data = $this->actionRepository->find($resource->id());

            if (empty($data)) {
                $response = $this->buildNotFoundResponse($resource);
            }

            if (null == $response) {
                $this->actionRepository->delete($resource->id());
                $response = $this->resourceDeleted();
            }

            return $response;
        } catch (Exception $e) {
            $badRequest = new Error('Bad Request', 'Request could not be served.');

            return $this->errorResponse(new ErrorBag([$badRequest]));
        }
    }

    /**
     * @param DeleteCommand $resource
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function buildNotFoundResponse(DeleteCommand $resource)
    {
        $mapping = $this->mappingRepository->findByClassName($resource->className());
        $notFoundError = new NotFoundError($mapping->getClassAlias(), $resource->id());
        $response = $this->resourceNotFound(new ErrorBag([$notFoundError]));

        return $response;
    }
}
