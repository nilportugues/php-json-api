<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/29/15
 * Time: 12:52 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\Http\Message\HalJson;

class ResourcePostErrorResponse extends AbstractResponse
{
    /**
     * @var int
     */
    protected $httpCode = 409;
}
