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
        $mappings = $this->serializationStrategy->getMappings();
        $request = RequestFactory::create();

        if ($filters = $request->getFields()) {
            foreach ($filters as $type => $properties) {
                foreach ($mappings as $mapping) {
                    if ($mapping->getClassAlias() === $type) {
                        $mapping->setFilterKeys($properties);
                    }
                }
            }
        }

        return parent::serialize($value);
    }
}
