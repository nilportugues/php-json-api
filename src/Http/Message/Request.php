<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/26/15
 * Time: 1:41 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Http\Message;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AbstractRequest.
 */
final class Request extends \Zend\Diactoros\Request
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return array|string
     */
    public function getQueryParam($name, $default = null)
    {
        return isset($this->request->getQueryParams()[$name]) ? $this->request->getQueryParams()[$name] : $default;
    }

    /**
     * @return array
     */
    public function getIncludedRelationships()
    {
        $relationshipNames = \explode(',', $this->getQueryParam('include', ''));
        $relationships = [];

        foreach ($relationshipNames as $relationship) {
            $data = \explode('.', $relationship);
            $type = $data[0];
            $attribute = (!empty($data[1])) ? $data[1] : null;

            if (null === $attribute) {
                $relationships[$type] = $type;
            } else {
                $relationships[$type][] = $attribute;
            }
        }

        return $relationships;
    }

    /**
     * @return array|null
     */
    public function getSortFields()
    {
        $sort = $this->getQueryParam('sort');
        $fields = null;

        if (!empty($sort)) {
            $fields = \explode(',', $sort);
            if (!empty($fields)) {
                foreach ($fields as &$field) {
                    if ('-' === $field[0]) {
                        $field = \ltrim($field, '-');
                    }
                }
            }
        }

        return $fields;
    }

    /**
     * @return array|null
     */
    public function getSortDirection()
    {
        $sort = $this->getQueryParam('sort');

        $direction = null;

        if (!empty($sort)) {
            $direction = [];

            $fields = \explode(',', $sort);
            if (!empty($fields)) {
                foreach ($fields as $field) {
                    $direction[ltrim($field, '-')] = ('-' === $field[0]) ? 'descending' : 'ascending';
                }
            }
        }

        return $direction;
    }

    /**
     * @return int
     */
    public function getPageNumber()
    {
        $page = $this->getQueryParam('page');
        $number = 1;

        if (!empty($page['number'])) {
            $number = (int) $page['number'];
        }

        return $number;
    }

    /**
     * @return int|null
     */
    public function getPageLimit()
    {
        $page = $this->getQueryParam('page');
        $limit = null;

        if (!empty($page['limit'])) {
            $limit = (int) $page['limit'];
        }

        return $limit;
    }

    /**
     * @return string|null
     */
    public function getPageOffset()
    {
        $page = $this->getQueryParam('page');

        $offset = null;
        if (!empty($page['offset'])) {
            $offset = (string) $page['offset'];
        }

        return $offset;
    }

    /**
     * @return int|null
     */
    public function getPageSize()
    {
        $page = $this->getQueryParam('page');
        $size = null;

        if (!empty($page['size'])) {
            $size = (int) $page['size'];
        }

        return $size;
    }

    /**
     * @return null|string
     */
    public function getPageCursor()
    {
        $page = $this->getQueryParam('page');
        $cursor = null;

        if (!empty($page['cursor'])) {
            $cursor = (string) $page['cursor'];
        }

        return $cursor;
    }

    /**
     * @return array|null
     */
    public function getFilters()
    {
        $filters = $this->getQueryParam('filter', null);

        foreach ($filters as &$filter) {
            $filter = \explode(',', $filter);
            $filter = \array_map('trim', $filter);
        }

        return $filters;
    }
}
