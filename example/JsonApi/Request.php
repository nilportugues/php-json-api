<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/26/15
 * Time: 1:41 AM
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Example\Api\JsonApi;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Request
 */
final class Request implements RequestInterface
{
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    private $request;
    /**
     * @var array
     */
    private $includedFields = [];
    /**
     * @var array
     */
    private $relationships = [];
    /**
     * @var array
     */
    private $sorting = [];
    /**
     * @var array
     */
    private $pagination = [];
    /**
     * @var array
     */
    private $filters = [];

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
        $this->setIncludedRelationships();
        $this->setIncludedFields();
        $this->setSorting();
    }

    /**
     * @return array
     */
    private function setIncludedRelationships()
    {
        $relationshipNames = explode(",", $this->getQueryParam("include", ""));


        foreach ($relationshipNames as $relationship) {
            $relationship = ".$relationship.";
            $length       = strlen($relationship);
            $dot1         = 0;
            while ($dot1 < $length - 1) {
                $dot2 = strpos($relationship, ".", $dot1 + 1);
                $path = substr($relationship, 1, $dot1 > 0 ? $dot1 - 1 : 0);
                $name = substr($relationship, $dot1 + 1, $dot2 - $dot1 - 1);
                if (isset($this->relationships[$path]) === false) {
                    $this->relationships[$path] = [];
                }
                $this->relationships[$path][$name] = $name;
                $dot1                                      = $dot2;
            };
        }
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
     *
     */
    private function setIncludedFields()
    {
        foreach ($this->getQueryParam("fields", []) as $resourceType => $fields) {
            $this->includedFields[$resourceType] = array_flip(explode(",", $fields));
        }
    }

    /**
     *
     */
    private function setSorting()
    {
        $this->sorting = explode(",", $this->getQueryParam("sort", ""));
    }

    /**
     * @param string $resourceType
     *
     * @return array
     */
    public function getIncludedFields($resourceType)
    {
        return isset($this->includedFields[$resourceType]) ? array_keys($this->includedFields[$resourceType]) : [];
    }

    /**
     * @param string $baseRelationshipPath
     *
     * @return array
     */
    public function getIncludedRelationships($baseRelationshipPath)
    {
        return (isset($this->relationships[$baseRelationshipPath])) ? $this->relationships[$baseRelationshipPath] : [];
    }


    /**
     * @return array
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * @return array|null
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
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
} 