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
use NilPortugues\Api\Transformer\Helpers\RecursiveFormatterHelper;
use NilPortugues\Serializer\Serializer;

class DataIncludedHelper
{
    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $array
     * @param array                               $data
     * @param null                                $parentType
     */
    public static function setResponseDataIncluded(array &$mappings, array $array, array &$data, $parentType = null)
    {
        $parentType = (null === $parentType) ? $array[Serializer::CLASS_IDENTIFIER_KEY] : $parentType;

        foreach (self::removeTypeAndId($mappings, $array) as $value) {
            if (\is_array($value)) {
                if (\array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $value)) {
                    $attributes = [];
                    $relationships = [];
                    $type = $value[Serializer::CLASS_IDENTIFIER_KEY];

                    self::addToRelationshipsArray($mappings, $data, $value, $type, $relationships, $attributes);
                    if (!(self::isDeleteableIncludedResource($mappings, $parentType, $value))) {
                        self::addToIncludedArray($mappings, $data, $attributes, $value);
                    }
                    continue;
                }

                if (\is_array($value)) {
                    foreach ($value as $inArrayValue) {
                        if (\is_array($inArrayValue)) {
                            $inArrayValue = self::removeResourcesNotIncluded($mappings, $parentType, $inArrayValue);

                            self::setResponseDataIncluded($mappings, $inArrayValue, $data, $parentType);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $copy
     *
     * @return array
     */
    protected static function removeTypeAndId(array &$mappings, array $copy)
    {
        if (!empty($copy[Serializer::CLASS_IDENTIFIER_KEY])) {
            $type = $copy[Serializer::CLASS_IDENTIFIER_KEY];

            if (\is_scalar($type) && !empty($mappings[$type])) {
                foreach ($mappings[$type]->getIdProperties() as $propertyName) {
                    unset($copy[$propertyName]);
                }
                unset($copy[Serializer::CLASS_IDENTIFIER_KEY]);
            }
        }

        return $copy;
    }

    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $data
     * @param array                               $value
     * @param string                              $type
     * @param array                               $relationships
     * @param array                               $attributes
     */
    protected static function addToRelationshipsArray(
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
                if (\array_key_exists(Serializer::MAP_TYPE, $attribute)
                    && count(array_values($attribute[Serializer::SCALAR_VALUE])) > 0
                    && \array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, array_values($attribute[Serializer::SCALAR_VALUE])[0])) {
                    self::setResponseDataIncluded($mappings, $value, $data);
                    continue;
                }

                if (\array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $attribute)) {
                    self::setResponseDataIncluded($mappings, $value, $data);

                    $relationships[$propertyName] = \array_merge(
                        DataLinksHelper::setResponseDataLinks($mappings, $attribute),
                        [
                            JsonApiTransformer::DATA_KEY => [
                                $propertyName => PropertyHelper::setResponseDataTypeAndId($mappings, $attribute),
                            ],
                        ],
                        $mappings[$type]->getRelationships()
                    );

                    continue;
                }
                $attributes[$propertyName] = $attribute;
            }
        }
    }

    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $data
     * @param array                               $attributes
     * @param array                               $value
     */
    protected static function addToIncludedArray(array &$mappings, array &$data, array &$attributes, array &$value)
    {
        if (\count($attributes) > 0) {
            $includedData = PropertyHelper::setResponseDataTypeAndId($mappings, $value);

            if (self::hasIdKey($includedData)) {
                $arrayData = \array_merge(
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
                    $value[Serializer::CLASS_IDENTIFIER_KEY]
                );

                if ($relationshipData) {
                    $arrayData[JsonApiTransformer::RELATIONSHIPS_KEY] = \array_merge(
                        $arrayData[JsonApiTransformer::RELATIONSHIPS_KEY],
                        $relationshipData
                    );

                    $relationships = self::normalizeRelationshipData($value, $arrayData);
                    $arrayData[JsonApiTransformer::RELATIONSHIPS_KEY] = $relationships;
                }

                $existingIndex = false;
                if (array_key_exists(JsonApiTransformer::INCLUDED_KEY, $data)) {
                    $existingIndex = self::findIncludedIndex($data[JsonApiTransformer::INCLUDED_KEY], $arrayData[JsonApiTransformer::ID_KEY], $arrayData[JsonApiTransformer::TYPE_KEY]);
                }
                if ($existingIndex !== false) {
                    $data[JsonApiTransformer::INCLUDED_KEY][$existingIndex] = \array_filter(\array_merge($data[JsonApiTransformer::INCLUDED_KEY][$existingIndex],
                        \array_filter($arrayData, self::filterEmptyArray())), self::filterEmptyArray());
                } else {
                    $data[JsonApiTransformer::INCLUDED_KEY][] = \array_filter($arrayData, self::filterEmptyArray());
                }
            }
        }
        if (!empty($data[JsonApiTransformer::INCLUDED_KEY])) {
            $data[JsonApiTransformer::INCLUDED_KEY] = \array_values(
                \array_unique($data[JsonApiTransformer::INCLUDED_KEY], SORT_REGULAR)
            );
        }
    }

    protected static function filterEmptyArray()
    {
        return function($value) {
            return $value !== null && (!is_array($value) || count($value) > 0);
        };
    }

    protected static function findIncludedIndex($includedData, $idNeedle, $typeNeedle)
    {
        foreach ($includedData as $key => $value) {
            if ($value[JsonApiTransformer::ID_KEY] === $idNeedle && $value[JsonApiTransformer::TYPE_KEY] === $typeNeedle) {
                return $key;
            }
        }

        return false;
    }

    /**
     * @param array $includedData
     *
     * @return bool
     */
    protected static function hasIdKey(array &$includedData)
    {
        return \array_key_exists(JsonApiTransformer::ID_KEY, $includedData)
        && !empty($includedData[JsonApiTransformer::ID_KEY]);
    }

    /**
     * @param array  $mappings
     * @param array  $data
     * @param array  $value
     * @param string $type
     */
    protected static function addRelationshipsToIncludedResources(
        array &$mappings,
        array &$data,
        array &$value,
        $type
    ) {
        foreach ($value as $propertyName => $attribute) {
            if (PropertyHelper::isAttributeProperty($mappings, $propertyName, $type)) {
                $propertyName = DataAttributesHelper::transformToValidMemberName(RecursiveFormatterHelper::camelCaseToUnderscore($propertyName));

                if (\is_array($attribute) && \array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $attribute)) {
                    $data[$propertyName][JsonApiTransformer::DATA_KEY] = PropertyHelper::setResponseDataTypeAndId(
                        $mappings,
                        $attribute
                    );

                    continue;
                }

                if (\is_array($attribute) && \array_key_exists(Serializer::MAP_TYPE, $attribute)) {
                    $relations = [];
                    $elements = $attribute[Serializer::SCALAR_VALUE];
                    foreach ($elements as $arrayValue) {
                        if (\array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $arrayValue)) {
                            $relations[] = PropertyHelper::setResponseDataTypeAndId($mappings, $arrayValue);
                        }
                    }
                    if (count($relations) > 0) {
                        $data[DataLinksHelper::camelCaseToUnderscore($propertyName)][JsonApiTransformer::DATA_KEY] = $relations;
                    }
                    continue;
                }
            }
        }
    }

    /**
     * Enforce with this check that each property leads to a data element.
     *
     * @param array $arrayData
     *
     * @return array
     */
    protected static function normalizeRelationshipData(array &$value, array $arrayData)
    {
        $relationships = [];
        foreach ($arrayData[JsonApiTransformer::RELATIONSHIPS_KEY] as $attribute => $attributeValue) {
            //if $value[data] is not found, get next level where [data] should exist.
            if (!array_key_exists(JsonApiTransformer::DATA_KEY, $attributeValue)) {
                array_shift($attributeValue);
            }


            if (count($attributeValue[JsonApiTransformer::DATA_KEY]) > 0) {
                $relationships[$attribute] = $attributeValue;
            }
        }

        return $relationships;
    }

    /**
     * Remove those resources that do not to appear in the getIncludedResources array.
     *
     * @param array  $mappings
     * @param string $parentType
     * @param array  $inArrayValue
     *
     * @return array
     */
    protected static function removeResourcesNotIncluded(array &$mappings, $parentType, array $inArrayValue)
    {
        foreach ($inArrayValue as $position => $includeValue) {
            if (self::isDeleteableIncludedResource($mappings, $parentType, $includeValue)) {
                unset($inArrayValue[$position]);
            }
        }

        return $inArrayValue;
    }

    /**
     * @param array $mappings
     * @param $parentType
     * @param $includeValue
     *
     * @return bool
     */
    protected static function isDeleteableIncludedResource(array &$mappings, $parentType, $includeValue)
    {
        return !empty($mappings[$parentType])
        && count($mappings[$parentType]->getIncludedResources()) > 0
        && false === in_array($includeValue[Serializer::CLASS_IDENTIFIER_KEY], $mappings[$parentType]->getIncludedResources(), true);
    }
}
