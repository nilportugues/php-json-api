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

use NilPortugues\Api\JsonApi\Http\Factory\RequestFactory;
use NilPortugues\Api\JsonApi\Http\Request\Request;
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
     * @param mixed $value
     *
     * @return string
     */
    public function serialize($value)
    {
        $request = RequestFactory::create();

        $this->filterOutResourceFields($request);
        $this->filterOutIncludedResources($request);


        return parent::serialize($value);
    }


    /**
     * @param Request $request
     */
    private function filterOutIncludedResources(Request $request)
    {
        if ($include = $request->getIncludedRelationships()) {
            foreach ($include as $resource => $includeData) {
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
     * @param Request $request
     */
    private function filterOutResourceFields(Request $request)
    {
        if ($filters = $request->getFields()) {
            foreach ($filters as $type => $properties) {
                foreach ($this->serializationStrategy->getMappings() as $mapping) {
                    if ($mapping->getClassAlias() === $type) {
                        $mapping->setFilterKeys($properties);
                    }
                }
            }
        }
    }
}
