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
    protected $number;
    /**
     * @var string|int|null
     */
    protected $cursor;
    /**
     * @var string|int|null
     */
    protected $limit;
    /**
     * @var string|int|null
     */
    protected $offset;
    /**
     * @var string|int|null
     */
    protected $size;

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
     * @param $size
     */
    public function setSize($size)
    {
        $this->size = (int) $size;
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
