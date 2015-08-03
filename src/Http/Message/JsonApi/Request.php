<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/26/15
 * Time: 1:41 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\Http\Message\JsonApi;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AbstractRequest.
 */
final class Request
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @param string $resourceType
     *
     * @return array
     */
    public function getIncludedFields($resourceType)
    {
        $includedFields = [];
        foreach ($this->getQueryParam('fields', []) as $resourceType => $fields) {
            $includedFields[$resourceType] = array_flip(explode(',', $fields));
        }

        return isset($includedFields[$resourceType]) ? array_keys($includedFields[$resourceType]) : [];
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
     * @param string $baseRelationshipPath
     *
     * @return array
     */
    public function getIncludedRelationships($baseRelationshipPath)
    {
        $relationshipNames = explode(',', $this->getQueryParam('include', ''));
        $relationships = [];

        foreach ($relationshipNames as $relationship) {
            $relationship = ".$relationship.";
            $length = strlen($relationship);
            $dot1 = 0;
            while ($dot1 < $length - 1) {
                $dot2 = strpos($relationship, '.', $dot1 + 1);
                $path = substr($relationship, 1, $dot1 > 0 ? $dot1 - 1 : 0);
                $name = substr($relationship, $dot1 + 1, $dot2 - $dot1 - 1);
                if (isset($relationships[$path]) === false) {
                    $relationships[$path] = [];
                }
                $relationships[$path][$name] = $name;
                $dot1 = $dot2;
            };
        }

        return (isset($relationships[$baseRelationshipPath])) ? $relationships[$baseRelationshipPath] : [];
    }

    /**
     * @return array|null
     */
    public function getSortFields()
    {
        $sort = $this->getAttribute('sort');
        $fields = null;

        if (!empty($sort)) {
            $fields = explode(',', $sort);
            if (!empty($fields)) {
                foreach ($fields as &$field) {
                    if ('-' === $field[0]) {
                        $field = ltrim($field, '-');
                    }
                }
            }
        }

        return $fields;
    }

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {
        return $this->request->getAttribute($name, $default);
    }

    /**
     * @return array|null
     */
    public function getSortDirection()
    {
        $sort = $this->getAttribute('sort');
        $direction = null;

        if (!empty($sort)) {
            $direction = [];

            $fields = explode(',', $sort);
            if (!empty($fields)) {
                foreach ($fields as $field) {
                    $direction[$field] = ('-' === $field[0]) ? 'descending' : 'ascending';
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
        $page = $this->getAttribute('page');
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
        $page = $this->getAttribute('page');
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
        $page = $this->getAttribute('page');

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
        $page = $this->getAttribute('page');
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
        $page = $this->getAttribute('page');
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
        return $this->request->getAttribute('filter', null);
    }
}
