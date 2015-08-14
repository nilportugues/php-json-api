<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/29/15
 * Time: 12:48 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\HalJson\Http\Message;

class ResourceCreatedResponse extends AbstractResponse
{
    /**
     * @var int
     */
    protected $httpCode = 201;
}
