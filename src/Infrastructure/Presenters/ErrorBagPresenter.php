<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 17/01/16
 * Time: 2:50.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Http;

use NilPortugues\Api\JsonApi\Domain\Model\Errors\Error;
use NilPortugues\Api\JsonApi\Domain\Model\Errors\ErrorBag;

/**
 * Class ErrorResource.
 */
class ErrorBagPresenter
{
    /**
     * @param ErrorBag $errorBag
     *
     * @return array
     */
    public static function toJson(ErrorBag $errorBag)
    {
        $errorBag = self::propagateStatusCode($errorBag);
        $errorBag = self::errorBagToArray($errorBag);

        return [
            'errors' => array_values($errorBag),
        ];
    }

    /**
     * @param ErrorBag $errorBag
     *
     * @return ErrorBag
     */
    private static function propagateStatusCode(ErrorBag $errorBag)
    {
        foreach ($errorBag as $error) {
            $status = $error->status();
            if (empty($status) && !empty($errorBag->httpCode())) {
                $error->setStatus($errorBag->httpCode());
            }
        }

        return $errorBag;
    }

    /**
     * @param ErrorBag $errorBag
     *
     * @return array|ErrorBag
     */
    private static function errorBagToArray(ErrorBag $errorBag)
    {
        $errorBag = $errorBag->toArray();
        foreach ($errorBag as &$error) {
            $error = self::errorToArray($error);
        }

        return $errorBag;
    }

    /**
     * @param Error $error
     *
     * @return array
     */
    private function errorToArray(Error $error)
    {
        return array_filter(
            [
                'id' => $error->id(),
                'links' => $error->links(),
                'status' => $error->status(),
                'code' => $error->code(),
                'title' => $error->title(),
                'detail' => $error->detail(),
                'source' => $error->source(),
                'meta' => $error->meta(),
            ]
        );
    }
}
