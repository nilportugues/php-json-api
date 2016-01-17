<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 17/01/16
 * Time: 14:04.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Application\Service;

use NilPortugues\Api\JsonApi\Application\Command\Put\PutCommandHandler;
use NilPortugues\Api\JsonApi\Application\Query\GetOne\GetOneQueryHandler;

/**
 * Class PutService.
 */
class PutResourceService
{
    /**
     * @var PutCommandHandler
     */
    private $commandHandler;
    /**
     * @var GetOneQueryHandler
     */
    private $queryHandler;

    /**
     * PutService constructor.
     *
     * @param PutCommandHandler  $commandHandler
     * @param GetOneQueryHandler $queryHandler
     */
    public function __construct(PutCommandHandler $commandHandler, GetOneQueryHandler $queryHandler)
    {
        $this->commandHandler = $commandHandler;
        $this->queryHandler = $queryHandler;
    }
}
