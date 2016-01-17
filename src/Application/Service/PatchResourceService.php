<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 17/01/16
 * Time: 13:58.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Application\Service;

use Exception;
use NilPortugues\Api\JsonApi\Application\Command\Patch\PatchCommand;
use NilPortugues\Api\JsonApi\Application\Command\Patch\PatchCommandHandler;
use NilPortugues\Api\JsonApi\Application\Query\GetOne\GetOneQuery;
use NilPortugues\Api\JsonApi\Application\Query\GetOne\GetOneQueryHandler;
use NilPortugues\Api\JsonApi\Application\Query\GetOne\GetOneResponse;
use NilPortugues\Api\JsonApi\Domain\Model\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Domain\Model\Errors\NotFoundError;
use NilPortugues\Api\JsonApi\Http\ErrorBagPresenter;
use NilPortugues\Api\JsonApi\Server\Data\ResourceNotFoundException;

/**
 * Class PatchService.
 */
class PatchResourceService
{
    /**
     * @var PatchCommandHandler
     */
    private $commandHandler;
    /**
     * @var GetOneQueryHandler
     */
    private $queryHandler;

    /**
     * PatchService constructor.
     *
     * @param PatchCommandHandler $commandHandler
     * @param GetOneQueryHandler  $queryHandler
     */
    public function __construct(PatchCommandHandler $commandHandler, GetOneQueryHandler $queryHandler)
    {
        $this->commandHandler = $commandHandler;
        $this->queryHandler = $queryHandler;
    }

    /**
     * @param $id
     * @param $className
     * @param $data
     *
     * @return GetOneResponse
     */
    public function __invoke($id, $className, $data)
    {
        try {
            $commandHandler = $this->commandHandler;
            $commandHandler(new PatchCommand($id, $className, $data));

            $queryHandler = $this->queryHandler;
            $response = $queryHandler(new GetOneQuery($id, $className));

        } catch (\Exception $e) {
            $response = $this->exceptionToResponse($id, $className, $e);
        }

        return $response;
    }

    /**
     * @param string|int $id
     * @param string     $className
     * @param Exception  $e
     *
     * @return GetOneResponse
     */
    private function exceptionToResponse($id, $className, Exception $e)
    {
        $presenter = new ErrorBagPresenter();

        switch (get_class($e)) {
            case ResourceNotFoundException::class:
                $notFound = new NotFoundError($className, $id);
                $body = $presenter->toJson(new ErrorBag([$notFound]));

                $response = new GetOneResponse(404, $body, $e);
                break;

            default:
                $response = new GetOneResponse(500, '', $e);
        }

        return $response;
    }
}
