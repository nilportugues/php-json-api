<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/25/15
 * Time: 5:15 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\JsonApi\Helpers;

use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Serializer\Serializer;

final class DataIncludedHelper
{
    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $array
     * @param array                               $data
     */
    public static function setResponseDataIncluded(array &$mappings, array $array, array &$data)
    {
        foreach ($array as $value) {
            if (is_array($value)) {
                if (array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $value)) {
                    $attributes = [];
                    $relationships = [];
                    $type = $value[Serializer::CLASS_IDENTIFIER_KEY];

                    self::addToRelationshipsArray($mappings, $data, $value, $type, $relationships, $attributes);
                    self::addToIncludedArray($mappings, $data, $attributes, $value);
                    continue;
                }

                if (is_array($value)) {
                    foreach ($value as $inArrayValue) {
                        if (is_array($inArrayValue)) {
                            self::setResponseDataIncluded($mappings, $inArrayValue, $data);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $data
     * @param array                               $value
     * @param string                              $type
     * @param array                               $relationships
     * @param array                               $attributes
     */
    private static function addToRelationshipsArray(
        array &$mappings,
        array &$data,
        array &$value,
        $type,
        array &$relationships,
        array &$attributes
    ) {
        foreach ($value as $propertyName => $attribute) {
            if (PropertyHelper::isAttributeProperty($mappings, $propertyName, $type)) {
                $propertyName = DataAttributesHelper::transformToValidMemberName($propertyName);

                if (array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $attribute)) {
                    self::setResponseDataIncluded($mappings, $value, $data);

                    $relationships[$propertyName] = array_merge(
                        DataLinksHelper::setResponseDataLinks($mappings, $attribute),
                        [
                            JsonApiTransformer::DATA_KEY => [
                                $propertyName => PropertyHelper::setResponseDataTypeAndId(
                                        $mappings,
                                        $attribute
                                    ),
                            ],
                        ],
                        $mappings[$type]->getRelationships()
                    );

                    continue;
                }
                $attributes[$propertyName] = $attribute;
            }
        }


        if(!empty($data[JsonApiTransformer::INCLUDED_KEY])) {
            $data[JsonApiTransformer::INCLUDED_KEY] = array_values(array_unique($data[JsonApiTransformer::INCLUDED_KEY], SORT_REGULAR));
        }
    }

    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $data
     * @param array                               $attributes
     * @param array                               $value
     */
    private static function addToIncludedArray(array &$mappings, array &$data, array &$attributes, array &$value)
    {
        if (count($attributes) > 0) {
            $includedData = PropertyHelper::setResponseDataTypeAndId($mappings, $value);

            if (self::hasIdKey($includedData)) {
                $arrayData = array_merge(
                    [
                        JsonApiTransformer::TYPE_KEY => $includedData[JsonApiTransformer::TYPE_KEY],
                        JsonApiTransformer::ID_KEY => $includedData[JsonApiTransformer::ID_KEY],
                        JsonApiTransformer::ATTRIBUTES_KEY => $attributes,
                        JsonApiTransformer::RELATIONSHIPS_KEY => [],
                    ],
                    DataLinksHelper::setResponseDataLinks($mappings, $value)
                );

                $relationshipData = [];
                self::addRelationshipsToIncludedResources(
                    $mappings,
                    $relationshipData,
                    $value,
                    $value[Serializer::CLASS_IDENTIFIER_KEY],
                    $attributes
                );

                if ($relationshipData) {
                    $arrayData[JsonApiTransformer::RELATIONSHIPS_KEY] = array_merge(
                        $arrayData[JsonApiTransformer::RELATIONSHIPS_KEY],
                        $relationshipData
                    );
                }

                $data[JsonApiTransformer::INCLUDED_KEY][] = array_filter($arrayData);
            }
        }
    }

    /**
     * @param array  $mappings
     * @param array  $data
     * @param array  $value
     * @param string $type
     */
    private static function addRelationshipsToIncludedResources(
        array &$mappings,
        array &$data,
        array &$value,
        $type
    ) {
        foreach ($value as $propertyName => $attribute) {
            if (PropertyHelper::isAttributeProperty($mappings, $propertyName, $type)) {
                $propertyName = DataAttributesHelper::transformToValidMemberName($propertyName);

                if (is_array($attribute) && array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $attribute)) {
                    $data[$propertyName][JsonApiTransformer::DATA_KEY] = PropertyHelper::setResponseDataTypeAndId($mappings, $attribute);

                    continue;
                }
            }
        }
    }

    /**
     * @param array $includedData
     *
     * @return bool
     */
    private static function hasIdKey(array &$includedData)
    {
        return array_key_exists(JsonApiTransformer::ID_KEY, $includedData)
        && !empty($includedData[JsonApiTransformer::ID_KEY]);
    }
}
