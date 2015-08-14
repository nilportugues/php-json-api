<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/27/15
 * Time: 11:46 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\JSend\Http\Message;

use NilPortugues\Api\Http\Message\AbstractResponse;

/**
 * Class Response.
 *
 * When an API call is successful, the JSend object is used as a simple envelope for the results, using the data key.
 */
class Response extends AbstractResponse
{
    /**
     * @param string $content
     */
    public function __construct($content)
    {
        $this->response = parent::instance(
            sprintf('{"status": "success", %s}', substr(substr($content, 1), 0, -1)),
            200,
            $this->headers
        );
    }
}
