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
 * Class FailResponse.
 *
 * When an API call is rejected due to invalid data or call conditions,
 * the JSend object's data key contains an object explaining what went wrong, typically a hash of validation errors
 */
class FailResponse extends AbstractResponse
{
    /**
     * @param string $content
     */
    public function __construct($content)
    {
        $body = json_encode(['status' => 'fail', 'data' => '{{content}}']);

        $this->response = parent::instance(
            str_replace('"{{content}}"', $content, $body),
            400,
            $this->headers
        );
    }
}
