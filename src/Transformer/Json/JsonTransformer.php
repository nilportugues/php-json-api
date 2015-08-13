<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/18/15
 * Time: 2:26 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\Transformer\Json;

use NilPortugues\Api\Mapping\Mapper;
use NilPortugues\Api\Transformer\Helpers\RecursiveDeleteHelper;
use NilPortugues\Api\Transformer\Helpers\RecursiveFormatterHelper;
use NilPortugues\Api\Transformer\Helpers\RecursiveRenamerHelper;
use NilPortugues\Api\Transformer\Transformer;
use NilPortugues\Serializer\Serializer;

/**
 * Class JsonTransformer.
 */
class JsonTransformer extends Transformer
{
    const META_KEY = 'meta';

    /**
     * @param Mapper $mapper
     */
    public function __construct(Mapper $mapper = null)
    {
        if (null !== $mapper) {
            $this->mappings = $mapper->getClassMap();
        }
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    public function serialize($value)
    {
        return json_encode($this->serialization($value), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    protected function serialization($value)
    {
        if (null !== $this->mappings) {
            /** @var \NilPortugues\Api\Mapping\Mapping $mapping */
            foreach ($this->mappings as $class => $mapping) {
                RecursiveDeleteHelper::deleteProperties($this->mappings, $value, $class);
                RecursiveRenamerHelper::renameKeyValue($this->mappings, $value, $class);
            }
        }

        RecursiveFormatterHelper::formatScalarValues($value);
        RecursiveDeleteHelper::deleteKeys($value, [Serializer::CLASS_IDENTIFIER_KEY]);
        RecursiveFormatterHelper::flattenObjectsWithSingleKeyScalars($value);
        $this->recursiveSetKeysToUnderScore($value);
        $this->setResponseMeta($value);

        return $value;
    }

    /**
     * @param array $response
     */
    private function setResponseMeta(array &$response)
    {
        if (!empty($this->meta)) {
            $response[self::META_KEY] = $this->meta;
        }
    }
}
