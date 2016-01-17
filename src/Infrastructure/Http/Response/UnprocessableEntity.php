<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/21/15
 * Time: 1:15 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Http\Response;

class UnprocessableEntity extends AbstractErrorResponse
{
    /**
     * @var int
     */
    protected $httpCode = 422;

    /**
     * @var string
     */
    protected $errorCode = 'Unprocesssable Entity';
}
