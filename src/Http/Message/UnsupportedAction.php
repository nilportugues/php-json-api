<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/29/15
 * Time: 12:45 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Http\Message;

use NilPortugues\Api\JsonApi\Http\ErrorBag;

class UnsupportedAction extends AbstractResponse
{
    /**
     * @var int
     */
    protected $httpCode = 403;

    /**
     * ErrorBag as defined in http://jsonapi.org/format/#error-objects;.
     *
     * @link     http://jsonapi.org/format/#error-objects
     *
     * @param ErrorBag $errors
     */
    public function __construct(ErrorBag $errors = null)
    {
        $body = $this->getDefaultError();

        if (null !== $errors) {
            $errors->setHttpCode($this->httpCode);
            $body = json_encode($errors);
        }

        $this->response = parent::instance($body, $this->httpCode, $this->headers);
    }

    /**
     * @return string
     */
    private function getDefaultError()
    {
        return json_encode(['errors' => [['status' => $this->httpCode, 'code' => 'Unsupported Action']]]);
    }
}
