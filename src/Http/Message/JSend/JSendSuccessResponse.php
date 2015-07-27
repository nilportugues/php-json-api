<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/27/15
 * Time: 11:46 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\Http\Message\JSend;

use NilPortugues\Api\Http\Message\Response;

/**
 * Class SuccessResponse.
 *
 * When an API call is successful, the JSend object is used as a simple envelope for the results, using the data key.
 */
class JSendSuccessResponse extends Response
{
    /**
     * @param string $content
     */
    public function __construct($content)
    {
        $body = json_encode(['status' => 'success', 'data' => '{{content}}']);

        $this->response = parent::instance(
            str_replace('"{{content}}"', $content, $body),
            200,
            $this->headers
        );
    }
}
