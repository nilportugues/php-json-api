<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/29/15
 * Time: 12:38 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\JsonApi\Http\Message;

/**
 * Class BadRequest.
 */
class BadRequest extends AbstractErrorResponse
{
    /**
     * @var int
     */
    protected $httpCode = 400;

    /**
     * @var string
     */
    protected $errorCode = 'Bad Request';
}
