<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 17/01/16
 * Time: 14:05.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Application\Service;

use NilPortugues\Api\JsonApi\Application\Query\GetOne\GetOneQuery;
use NilPortugues\Api\JsonApi\Application\Query\GetOne\GetOneQueryHandler;

/**
 * Class GetService.
 */
class GetResourceService
{
    /**
     * @var GetOneQueryHandler
     */
    private $queryHandler;

    /**
     * GetService constructor.
     *
     * @param GetOneQueryHandler $getOneQueryHandler
     */
    public function __construct(GetOneQueryHandler $getOneQueryHandler)
    {
        $this->queryHandler = $getOneQueryHandler;
    }

    /**
     * @param string|int $id
     * @param string     $className
     *
     * @return GetOneQuery
     */
    public function __invoke($id, $className)
    {
        $query = new GetOneQuery($id, $className);
        $queryHandler = $this->queryHandler;

        return $queryHandler($query);
    }
}
