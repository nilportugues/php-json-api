<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 17/01/16
 * Time: 14:03.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Application\Query\GetOne;

/**
 * Class GetOneResponse.
 */
class GetOneResponse
{
    /**
     * @var int
     */
    private $statusCode;
    /**
     * @var string
     */
    private $body;

    /**
     * GetOneResponse constructor.
     *
     * @param int    $statusCode
     * @param string $body
     */
    public function __construct($statusCode, $body)
    {
        $this->statusCode = $statusCode;
        $this->body = $body;
    }

    /**
     * Returns value for `statusCode`.
     *
     * @return int
     */
    public function statusCode()
    {
        return $this->statusCode;
    }

    /**
     * Returns value for `body`.
     *
     * @return string
     */
    public function body()
    {
        return $this->body;
    }
}
