<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/25/15
 * Time: 9:54 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\Transformer\Json\Helpers\JsonApi;

use NilPortugues\Api\Transformer\Helpers\RecursiveFormatterHelper;
use NilPortugues\Api\Transformer\Json\JsonApiTransformer;
use NilPortugues\Serializer\Serializer;

/**
 * Class DataLinksHelper.
 */
final class DataLinksHelper
{
    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $value
     *
     * @return array
     */
    public static function setResponseDataLinks(array &$mappings, array &$value)
    {
        $data = [];
        $type = $value[Serializer::CLASS_IDENTIFIER_KEY];

        if (!empty($mappings[$type])) {
            list($idValues, $idProperties) = self::getPropertyAndValues($mappings, $value, $type);
            $selfLink = $mappings[$type]->getResourceUrl();

            if (!empty($selfLink)) {
                $data[JsonApiTransformer::LINKS_KEY][JsonApiTransformer::SELF_LINK] = str_replace(
                    $idProperties,
                    $idValues,
                    $selfLink
                );
            }
        }

        return $data;
    }

    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $value
     * @param string                              $type
     *
     * @return array
     */
    private static function getPropertyAndValues(array &$mappings, array &$value, $type)
    {
        $idValues = [];
        $idProperties = PropertyHelper::getIdProperties($mappings, $type);

        foreach ($idProperties as &$propertyName) {
            $idValues[] = PropertyHelper::getIdValue($value[$propertyName]);
            $propertyName = sprintf('{%s}', $propertyName);
        }
        RecursiveFormatterHelper::flattenObjectsWithSingleKeyScalars($idValues);

        return [$idValues, $idProperties];
    }

    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $array
     * @param array                               $parent
     *
     * @return array
     */
    public static function setResponseDataRelationship(array &$mappings, array &$array, array $parent)
    {
        $data = [JsonApiTransformer::RELATIONSHIPS_KEY => []];

        foreach ($array as $propertyName => $value) {
            if (is_array($value) && array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $value)) {
                $type = $value[Serializer::CLASS_IDENTIFIER_KEY];

                self::relationshipLinksSelf($mappings, $parent, $propertyName, $type, $data, $value);
                self::relationshipLinksRelated($mappings, $parent, $data, $propertyName);
            }
        }

        return (array) array_filter($data);
    }

    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $parent
     * @param string                              $propertyName
     * @param string                              $type
     * @param array                               $data
     * @param array                               $value
     */
    private static function relationshipLinksSelf(
        array &$mappings,
        array &$parent,
        $propertyName,
        $type,
        array &$data,
        array &$value
    ) {
        if (!in_array($propertyName, PropertyHelper::getIdProperties($mappings, $type), true)) {
            $data[JsonApiTransformer::RELATIONSHIPS_KEY][$propertyName] = array_merge(
                array_filter(
                    [
                        JsonApiTransformer::LINKS_KEY => self::setResponseDataRelationshipSelfLinks(
                            $mappings,
                            $parent
                        ),
                    ]
                ),
                [JsonApiTransformer::DATA_KEY => PropertyHelper::setResponseDataTypeAndId($mappings, $value)]
            );
        }
    }

    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $parent
     *
     * @return array
     */
    public static function setResponseDataRelationshipSelfLinks(array &$mappings, array &$parent)
    {
        $data = [];
        $parentType = $parent[Serializer::CLASS_IDENTIFIER_KEY];

        if (!empty($mappings[$parentType])) {
            list($idValues, $idProperties) = self::getPropertyAndValues($mappings, $parent, $parentType);
            $selfLink = $mappings[$parentType]->getRelationshipSelfUrl();

            if (!empty($selfLink)) {
                $data[JsonApiTransformer::SELF_LINK] = str_replace($idProperties, $idValues, $selfLink);
            }
        }

        return $data;
    }

    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $parent
     * @param array                               $data
     * @param string                              $propertyName
     */
    private static function relationshipLinksRelated(array &$mappings, array &$parent, array &$data, $propertyName)
    {
        if (!empty($parent[Serializer::CLASS_IDENTIFIER_KEY]) && !empty($data[JsonApiTransformer::RELATIONSHIPS_KEY][$propertyName])) {
            $parentType = $parent[Serializer::CLASS_IDENTIFIER_KEY];
            $relatedUrl = $mappings[$parentType]->getRelatedUrl();

            if (!empty($relatedUrl)) {
                list($idValues, $idProperties) = self::getPropertyAndValues($mappings, $parent, $parentType);
                $data[JsonApiTransformer::RELATIONSHIPS_KEY][$propertyName][JsonApiTransformer::LINKS_KEY][JsonApiTransformer::RELATED_LINK] = str_replace(
                    $idProperties,
                    $idValues,
                    $relatedUrl
                );
            }
        }
    }
}
