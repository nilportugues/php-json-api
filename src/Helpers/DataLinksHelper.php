<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/25/15
 * Time: 9:54 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\JsonApi\Helpers;

use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Api\Transformer\Helpers\RecursiveFormatterHelper;
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

        if (is_scalar($type) && !empty($mappings[$type])) {
            list($idValues, $idProperties) = RecursiveFormatterHelper::getIdPropertyAndValues($mappings, $value, $type);
            $selfLink = $mappings[$type]->getResourceUrl();

            if (!empty($selfLink)) {
                $data[JsonApiTransformer::LINKS_KEY][JsonApiTransformer::SELF_LINK][JsonApiTransformer::LINKS_HREF] = str_replace(
                    $idProperties,
                    $idValues,
                    $selfLink
                );
            }

            foreach ($mappings[$type]->getUrls() as $name => $url) {
                $data[JsonApiTransformer::LINKS_KEY][$name][JsonApiTransformer::LINKS_HREF] = str_replace(
                    $idProperties,
                    $idValues,
                    $url
                );
            }
        }

        return $data;
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
                $propertyName = DataAttributesHelper::transformToValidMemberName($propertyName);
                $type = $value[Serializer::CLASS_IDENTIFIER_KEY];

                self::relationshipLinksSelf($mappings, $parent, $propertyName, $type, $data, $value);
                self::relationshipLinksRelated($propertyName, $mappings, $parent, $data);
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
        if (!in_array($propertyName, RecursiveFormatterHelper::getIdProperties($mappings, $type), true)) {
            $data[JsonApiTransformer::RELATIONSHIPS_KEY][$propertyName] = array_merge(
                array_filter(
                    [
                        JsonApiTransformer::LINKS_KEY => self::setResponseDataRelationshipSelfLinks(
                                $propertyName,
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
     * @param string                              $propertyName
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $parent
     *
     * @return array
     */
    public static function setResponseDataRelationshipSelfLinks($propertyName, array &$mappings, array &$parent)
    {
        $data = [];
        $parentType = $parent[Serializer::CLASS_IDENTIFIER_KEY];

        if (is_scalar($parentType) && !empty($mappings[$parentType])) {
            list($idValues, $idProperties) = RecursiveFormatterHelper::getIdPropertyAndValues($mappings, $parent, $parentType);

            $selfLink = $mappings[$parentType]->getRelationshipSelfUrl($propertyName);

            if (!empty($selfLink)) {
                $data[JsonApiTransformer::SELF_LINK][JsonApiTransformer::LINKS_HREF] = str_replace(
                    $idProperties,
                    $idValues,
                    $selfLink
                );
            }
        }

        return $data;
    }

    /**
     * @param string                              $propertyName
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $parent
     * @param array                               $data
     */
    private static function relationshipLinksRelated($propertyName, array &$mappings, array &$parent, array &$data)
    {
        if (!empty($parent[Serializer::CLASS_IDENTIFIER_KEY]) && !empty($data[JsonApiTransformer::RELATIONSHIPS_KEY][$propertyName])) {
            $parentType = $parent[Serializer::CLASS_IDENTIFIER_KEY];

            if (is_scalar($parentType)) {
                $relatedUrl = $mappings[$parentType]->getRelatedUrl($propertyName);

                if (!empty($relatedUrl)) {
                    list($idValues, $idProperties) = RecursiveFormatterHelper::getIdPropertyAndValues($mappings, $parent, $parentType);
                    $data[JsonApiTransformer::RELATIONSHIPS_KEY][$propertyName][JsonApiTransformer::LINKS_KEY][JsonApiTransformer::RELATED_LINK][JsonApiTransformer::LINKS_HREF] = str_replace(
                        $idProperties,
                        $idValues,
                        $relatedUrl
                    );
                }
            }
        }
    }
}
