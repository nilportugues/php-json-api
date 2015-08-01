<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/27/15
 * Time: 11:43 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace  NilPortugues\Api\Http\Message;

use Psr\Http\Message\RequestInterface;

/**
 * Class RequestInterface.
 */
abstract class AbstractRequest
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return array
     */
    abstract public function getSortFields();

    /**
     * @return array
     */
    abstract public function getSortDirection();

    /**
     * @return int|null
     */
    abstract public function getPageNumber();

    /**
     * @return int|null
     */
    abstract public function getPageLimit();

    /**
     * @return int|null
     */
    abstract public function getPageOffset();

    /**
     * @return int|null
     */
    abstract public function getPageSize();

    /**
     * @return string|null
     */
    abstract public function getPageCursor();
}
