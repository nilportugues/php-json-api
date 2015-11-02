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
 * Class ErrorResponse.
 */
class ErrorResponse extends AbstractResponse
{
    protected $httpCode = 400;

    /**
     * Error as defined in http://jsonapi.org/format/#error-objects;.
     *
     * @link http://jsonapi.org/format/#error-objects
     *
     * @param string $message
     * @param int    $code
     * @param null   $data
     */
    public function __construct($message, $code = 400, $data = null)
    {
        $body = \json_encode(
            [
                'errors' => [
                    'id' => '',
                    'links' => [
                        'about' => '',
                    ],
                    'status' => '',
                    'code' => '',
                    'title' => '',
                    'detail' => '',
                    'source' => [
                        'pointer' => '',
                        'parameter' => '',
                    ],
                    'meta' => '',
                ],
            ]
        );

        $this->response = parent::instance($body, $this->httpCode, $this->headers);
    }
}
