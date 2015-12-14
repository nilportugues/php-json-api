<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/21/15
 * Time: 3:27 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi;

use NilPortugues\Api\JsonApi\Http\Request\Parameters\Fields;
use NilPortugues\Api\JsonApi\Http\Request\Parameters\Included;
use NilPortugues\Serializer\DeepCopySerializer;

/**
 * Class JsonApiSerializer.
 */
class JsonApiSerializer extends DeepCopySerializer
{
    /**
     * @var JsonApiTransformer
     */
    protected $serializationStrategy;

    /**
     * @param JsonApiTransformer $strategy
     */
    public function __construct(JsonApiTransformer $strategy)
    {
        parent::__construct($strategy);
    }

    /**
     * @return JsonApiTransformer
     */
    public function getTransformer()
    {
        return $this->serializationStrategy;
    }

    /**
     * @param mixed                            $value
     * @param Http\Request\Parameters\Fields   $fields
     * @param Http\Request\Parameters\Included $included
     *
     * @return string
     */
    public function serialize($value, Fields $fields = null, Included $included = null)
    {
        if ($fields) {
            $this->filterOutResourceFields($fields);
        }

        if ($included) {
            $this->filterOutIncludedResources($included);
        }

        return parent::serialize($value);
    }

    /**
     * @param Http\Request\Parameters\Included $included
     */
    private function filterOutIncludedResources(Included $included)
    {
        if (false === $included->isEmpty()) {
            foreach ($included->get() as $resource => $includeData) {
                foreach ($this->serializationStrategy->getMappings() as $mapping) {
                    $mapping->filteringIncludedResources(true);
                    if (is_array($includeData)) {
                        foreach ($includeData as $subResource) {
                            $this->serializationStrategy->getMappingByAlias($subResource)->addIncludedResource(
                                $this->serializationStrategy->getMappingByAlias($resource)->getClassName()
                            );
                        }
                        break;
                    }

                    $mapping->addIncludedResource(
                        $this->serializationStrategy->getMappingByAlias($resource)->getClassName()
                    );
                }
            }
        }
    }

    /**
     * @param Http\Request\Parameters\Fields $fields
     */
    private function filterOutResourceFields(Fields $fields)
    {
        if (false === $fields->isEmpty()) {
            foreach ($fields->get() as $type => $properties) {
                foreach ($this->serializationStrategy->getMappings() as $mapping) {
                    if ($mapping->getClassAlias() === $type) {
                        $mapping->setFilterKeys($properties);
                    }
                }
            }
        }
    }
}
