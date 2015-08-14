<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/25/15
 * Time: 4:56 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\Transformer\Json\Helpers\JsonApi;

use NilPortugues\Api\Transformer\Helpers\RecursiveFormatterHelper;
use NilPortugues\Api\Transformer\Json\JsonApiTransformer;
use NilPortugues\Serializer\Serializer;

/**
 * Class PropertyHelper.
 */
final class PropertyHelper
{
    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $value
     *
     * @return array
     */
    public static function setResponseDataTypeAndId(array &$mappings, array &$value)
    {
        $type = $value[Serializer::CLASS_IDENTIFIER_KEY];
        $finalType = ($mappings[$type]->getClassAlias()) ? $mappings[$type]->getClassAlias() : $type;

        $ids = [];
        foreach (array_keys($value) as $propertyName) {
            if (in_array($propertyName, RecursiveFormatterHelper::getIdProperties($mappings, $type), true)) {
                $id = RecursiveFormatterHelper::getIdValue($value[$propertyName]);
                $ids[] = (is_array($id)) ? implode(JsonApiTransformer::ID_SEPARATOR, $id) : $id;
            }
        }

        return [
            JsonApiTransformer::TYPE_KEY => self::namespaceAsArrayKey($finalType),
            JsonApiTransformer::ID_KEY => implode(JsonApiTransformer::ID_SEPARATOR, $ids),
        ];
    }

    /**
     * Given a class name will return its name without the namespace and in under_score to be used as a key in an array.
     *
     * @param string $key
     *
     * @return string
     */
    public static function namespaceAsArrayKey($key)
    {
        $keys = explode('\\', $key);
        $className = end($keys);

        return RecursiveFormatterHelper::camelCaseToUnderscore($className);
    }

    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param                                     $propertyName
     * @param                                     $type
     *
     * @return bool
     */
    public static function isAttributeProperty(array &$mappings, $propertyName, $type)
    {
        return Serializer::CLASS_IDENTIFIER_KEY !== $propertyName
        && !in_array($propertyName, RecursiveFormatterHelper::getIdProperties($mappings, $type));
    }
}
