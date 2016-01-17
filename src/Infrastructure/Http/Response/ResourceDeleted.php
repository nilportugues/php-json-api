<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/29/15
 * Time: 12:44 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Http\Response;

class ResourceDeleted extends AbstractResponse
{
    /**
     * @var int
     */
    protected $httpCode = 204;

    /**
     *
     */
    public function __construct()
    {
        $this->response = self::instance('', $this->httpCode, $this->headers);
    }
}
