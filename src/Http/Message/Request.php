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
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * Class AbstractRequest.
 */
final class Request extends \Zend\Diactoros\Request
{
    /**
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request = null)
    {
        $this->request = ($request) ? $request : (new DiactorosFactory())->createRequest(SymfonyRequest::createFromGlobals());
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
        $include = $this->getQueryParam('include', []);

        if (!is_string($include)) {
            return [];
        }

        $relationshipNames = \explode(',', $include);
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

        return array_filter($relationships);
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
     * @param int $default
     *
     * @return int
     */
    public function getPageNumber($default = 1)
    {
        $page = $this->getQueryParam('page');
        $number = $default;

        if (!empty($page['number'])) {
            $number = (int) $page['number'];
        }

        return $number;
    }

    /**
     * @return int
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
     * @param int $default
     *
     * @return int
     */
    public function getPageSize($default = 10)
    {
        $page = $this->getQueryParam('page');
        $size = $default;

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
     * @return array
     */
    public function getFilters()
    {
        $filters = (array) $this->getQueryParam('filter', null);

        return $filters;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        $fields = (array) $this->getQueryParam('fields', null);

        $fields = array_filter($fields);
        
        foreach ($fields as &$filter) {
            $filter = \explode(',', $filter);
            $filter = \array_map('trim', $filter);
        }

        return $fields;
    }
}
