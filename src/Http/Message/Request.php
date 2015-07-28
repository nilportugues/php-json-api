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
abstract class Request
{
    /**
     * @var RequestInterface
     */
    private $request;

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
    abstract public function getSorting();

    /**
     * @return array|null
     */
    abstract public function getPagination();
}
