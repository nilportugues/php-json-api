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

/**
 * Class RequestInterface.
 */
interface RequestInterface
{
    /**
     * @return array
     */
    public function getSorting();

    /**
     * @return array|null
     */
    public function getPagination();
}
