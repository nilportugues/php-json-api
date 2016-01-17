<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 17/01/16
 * Time: 0:47.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Application\Query\GetAll;

class GetAllQuery
{
    /**
     * @var string
     */
    protected $route;
    /**
     * @var string
     */
    protected $className;

    /**
     * ListResource constructor.
     *
     * @param string $route
     * @param string $className
     */
    public function __construct($route, $className)
    {
        $this->route = $route;
        $this->className = $className;
    }

    /**
     * Returns value for `route`.
     *
     * @return string
     */
    public function route()
    {
        return $this->route;
    }

    /**
     * Returns value for `className`.
     *
     * @return string
     */
    public function className()
    {
        return $this->className;
    }
}
