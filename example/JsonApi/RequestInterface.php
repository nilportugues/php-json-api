<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/26/15
 * Time: 1:48 AM
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Example\Api\JsonApi;


interface RequestInterface
{
    /**
     * @param string $resourceType
     * @return array
     */
    public function getIncludedFields($resourceType);

    /**
     * @param string $baseRelationshipPath
     * @return array
     */
    public function getIncludedRelationships($baseRelationshipPath);

    /**
     * @return array
     */
    public function getSorting();

    /**
     * @return array|null
     */
    public function getPagination();

    /**
     * @return array
     */
    public function getFilters();

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getAttribute($name, $default = null);

    /**
     * @param string $name
     * @param mixed $default
     * @return array|string
     */
    public function getQueryParam($name, $default = null);
} 
