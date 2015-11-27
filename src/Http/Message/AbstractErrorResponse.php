<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/21/15
 * Time: 1:21 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\JsonApi\Http\Message;

use NilPortugues\Api\JsonApi\Http\ErrorBag;

/**
 * Class AbstractErrorResponse.
 */
abstract class AbstractErrorResponse extends AbstractResponse
{
    /**
     * @var string
     */
    protected $errorCode;

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
            $body = json_encode($errors, JSON_UNESCAPED_SLASHES);
        }

        $this->response = parent::instance($body, $this->httpCode, $this->headers);
    }

    /**
     * @return string
     */
    private function getDefaultError()
    {
        return json_encode(['errors' => [['status' => $this->httpCode, 'code' => $this->errorCode]]]);
    }
}
