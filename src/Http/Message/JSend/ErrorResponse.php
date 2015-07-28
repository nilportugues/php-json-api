<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/27/15
 * Time: 11:52 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\Http\Message\JSend;

use NilPortugues\Api\Http\Message\AbstractResponse;

/**
 * Class ErrorResponse.
 */
class ErrorResponse extends AbstractResponse
{
    /**
     * @param string $message
     * @param int    $code
     * @param null   $data
     */
    public function __construct($message, $code = 500, $data = null)
    {
        $body = json_encode(array_filter([
            'status' => 'error',
            'code' => $code,
            'message' => (string) $message,
            'data' => $data,
        ]));

        $this->response = parent::instance($body, 500, $this->headers);
    }
}
