<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/29/15
 * Time: 12:43 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\Json\Http\Message;

use NilPortugues\Api\Http\Message\AbstractResponse;

class ResourceProcessingResponse extends AbstractResponse
{
    /**
     * @var int
     */
    protected $httpCode = 202;
}
