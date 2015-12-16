<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/21/15
 * Time: 9:04 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Http;

use JsonSerializable;

/**
 * Class PaginatedResource.
 */
class PaginatedResource implements JsonSerializable
{
    /**
     * @var int
     */
    private $total;
    /**
     * @var int
     */
    private $pages;
    /**
     * @var int
     */
    private $currentPage;
    /**
     * @var int
     */
    private $pageSize;
    /**
     * @var string
     */
    private $offsetLimit;

    /**
     * @var string
     */
    private $offset;

    /**
     * @var string
     */
    private $cursor;

    /**
     * @var string
     */
    private $data = [];
    /**
     * @var array
     */
    private $include = [];
    /**
     * @var array
     */
    private $links = [];
    /**
     * @var array
     */
    private $meta = [];

    /**
     * @param string $elements
     * @param int    $currentPage
     * @param int    $pageSize
     * @param null   $total
     * @param array  $links
     */
    public function __construct($elements, $currentPage = null, $pageSize = null, $total = null, array $links = [])
    {
        $this->setData($elements);
        $this->setPageSize($pageSize);
        $this->setCurrentPage($currentPage);
        $this->setTotal($total);
        $this->setTotalPages($total, $pageSize);
        $this->setLinks($links);
    }

    /**
     * @param string $cursor
     *
     * @return $this
     */
    public function setPageCursor($cursor)
    {
        $this->cursor = $cursor;
    }

    /**
     * @param array $data
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    private function setData($data)
    {
        $data = (array) json_decode($data, true);

        if (false === array_key_exists('data', $data)) {
            throw new \InvalidArgumentException('Provided JSON has no `data` member defined');
        }

        $this->data = $data['data'];

        if (!empty($data['included'])) {
            $this->include = $data['included'];
        }
    }

    /**
     * @param array $links
     *
     * @return $this
     */
    public function setLinks(array $links)
    {
        foreach ($links as &$href) {
            $href = ['href' => $href];
        }

        $this->links = $links;
    }

    /**
     * @param string $offset
     *
     * @return $this
     */
    public function setPageOffset($offset)
    {
        $this->offset = $offset;
    }

    /**
     * @param string $offsetLimit
     *
     * @return $this
     */
    public function setPageOffsetLimit($offsetLimit)
    {
        $this->offsetLimit = $offsetLimit;
    }

    /**
     * @param $currentPage
     */
    private function setCurrentPage($currentPage)
    {
        $this->currentPage = (int) $currentPage;
    }

    /**
     * @param $pageSize
     */
    private function setPageSize($pageSize)
    {
        $this->pageSize = (int) $pageSize;
    }
    /**
     * @param $total
     */
    private function setTotal($total)
    {
        $this->total = (int) $total;
    }

    /**
     * @param $total
     * @param $pageSize
     */
    private function setTotalPages($total, $pageSize)
    {
        if (0 == $pageSize) {
            $this->pages = 0;

            return;
        }

        $this->pages = (int) ceil($total / $pageSize);
    }

    /**
     * @param array $meta
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $callable = function ($v) {
            return $v !== null;
        };

        $data = array_filter([
                'data' => array_filter($this->data, $callable),
                'included' => array_filter($this->include),
                'links' => array_filter($this->links),
                'meta' => array_filter(
                    array_merge([
                            'page' => array_filter([
                                    'total' => $this->total,
                                    'last' => $this->pages,
                                    'number' => $this->currentPage,
                                    'size' => $this->pageSize,
                                    'limit' => $this->offsetLimit,
                                    'offset' => $this->offset,
                                    'cursor' => $this->cursor,
                                ], $callable),
                        ],
                        $this->meta
                    )
                ),
                'jsonapi' => ['version' => '1.0'],
            ], $callable);

        return $data;
    }
}
