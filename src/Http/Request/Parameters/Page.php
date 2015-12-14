<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/14/15
 * Time: 7:52 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Http\Request\Parameters;

/**
 * Class Page.
 */
class Page
{
    /**
     * @var string|int|null
     */
    private $number;
    /**
     * @var string|int|null
     */
    private $cursor;
    /**
     * @var string|int|null
     */
    private $limit;
    /**
     * @var string|int|null
     */
    private $offset;
    /**
     * @var string|int|null
     */
    private $size;

    /**
     * @param $number
     * @param $cursor
     * @param $limit
     * @param $offset
     * @param $size
     */
    public function __construct($number, $cursor, $limit, $offset, $size)
    {
        $this->number = $number;
        $this->cursor = $cursor;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->size = $size;
    }

    /**
     * @return int|string
     */
    public function cursor()
    {
        return $this->cursor;
    }

    /**
     * @return int|string
     */
    public function limit()
    {
        return $this->limit;
    }

    /**
     * @return int|string
     */
    public function number()
    {
        return $this->number;
    }

    /**
     * @return int|string
     */
    public function offset()
    {
        return $this->offset;
    }

    /**
     * @return int|string
     */
    public function size()
    {
        return $this->size;
    }
}
