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

use NilPortugues\Api\JsonApi\Application\Command\Patch\PatchCommand;
use NilPortugues\Api\JsonApi\Application\Command\Patch\PatchCommandHandler;
use NilPortugues\Api\JsonApi\Application\Query\GetOne\GetOneQuery;
use NilPortugues\Api\JsonApi\Application\Query\GetOne\GetOneQueryHandler;
use NilPortugues\Api\JsonApi\Application\Query\GetOne\GetOneResponse;

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
     * @param PatchCommandHandler $patchCommandHandler
     * @param GetOneQueryHandler  $getOneQueryHandler
     */
    public function __construct(PatchCommandHandler $patchCommandHandler, GetOneQueryHandler $getOneQueryHandler)
    {
        $this->commandHandler = $patchCommandHandler;
        $this->queryHandler = $getOneQueryHandler;
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
        $command = new PatchCommand($id, $className, $data);
        $commandHandler = $this->commandHandler;
        $commandHandler($command);

        $query = new GetOneQuery($id, $className);
        $queryHandler = $this->queryHandler;

        return $queryHandler($query);
    }
}
