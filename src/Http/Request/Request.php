<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/26/15
 * Time: 1:41 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Http\Request;

use NilPortugues\Api\JsonApi\Http\Request\Parameters\Fields;
use NilPortugues\Api\JsonApi\Http\Request\Parameters\Included;
use NilPortugues\Api\JsonApi\Http\Request\Parameters\Page;
use NilPortugues\Api\JsonApi\Http\Request\Parameters\Sorting;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * Class AbstractRequest.
 */
class Request extends \Zend\Diactoros\Request
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
    protected function getQueryParam($name, $default = null)
    {
        return isset($this->request->getQueryParams()[$name]) ? $this->request->getQueryParams()[$name] : $default;
    }

    /**
     * @return Included
     */
    public function getIncludedRelationships()
    {
        $include = $this->getQueryParam('include', []);
        $included = new Included();

        if (is_string($include)) {
            $includeNames = \explode(',', $include);
            foreach ($includeNames as $relationship) {
                $included->add($relationship);
            }
        }

        return $included;
    }

    /**
     * @return \NilPortugues\Api\JsonApi\Http\Request\Parameters\Sorting
     */
    public function getSort()
    {
        $sort = $this->getQueryParam('sort');
        $sorting = new Sorting();

        if (!empty($sort) && is_string($sort)) {
            $members = \explode(',', $sort);
            if (!empty($members)) {
                foreach ($members as $field) {
                    $key = ltrim($field, '-');
                    $sorting->addField($key, ('-' === $field[0]) ? 'descending' : 'ascending');
                }
            }
        }

        return $sorting;
    }

    /**
     * @return \NilPortugues\Api\JsonApi\Http\Request\Parameters\Page
     */
    public function getPage()
    {
        $queryParam = $this->getQueryParam('page');

        $page = new Page(
            (!empty($queryParam['number'])) ? $queryParam['number'] : 1,
            (!empty($queryParam['cursor'])) ? $queryParam['cursor'] : null,
            (!empty($queryParam['limit'])) ? $queryParam['limit'] : null,
            (!empty($queryParam['offset'])) ? $queryParam['offset'] : null,
            (!empty($queryParam['size'])) ? $queryParam['size'] : 10
        );

        return $page;
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
     * @return \NilPortugues\Api\JsonApi\Http\Request\Parameters\Fields
     */
    public function getFields()
    {
        $fields = (array) $this->getQueryParam('fields', null);
        $fields = array_filter($fields);

        $object = new Fields();
        foreach ($fields as $type => &$members) {
            $members = \explode(',', $members);
            $members = \array_map('trim', $members);

            foreach ($members as $member) {
                $object->addField($type, $member);
            }
        }

        return $object;
    }
}
