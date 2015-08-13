<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/24/15
 * Time: 8:55 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\Transformer\Helpers;

use NilPortugues\Serializer\Serializer;

/**
 * Class RecursiveDeleteHelper.
 */
final class RecursiveFilterHelper
{
    /**
     * Delete all keys except the ones considered identifier keys or defined in the filter.
     *
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $array
     * @param string                              $typeKey
     */
    public static function deletePropertiesNotInFilter(array &$mappings, array &$array, $typeKey)
    {
        if (array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $array)) {
            $newArray = [];
            $type = $array[Serializer::CLASS_IDENTIFIER_KEY];

            self::deleteMatchedClassNotInFilterProperties($mappings, $array, $typeKey, $type, $newArray);

            if (!empty($newArray)) {
                $array = $newArray;
            }
        }
    }

    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $array
     * @param string                              $typeKey
     * @param string                              $type
     * @param array                               $newArray
     */
    private static function deleteMatchedClassNotInFilterProperties(
        array &$mappings,
        array &$array,
        $typeKey,
        $type,
        array &$newArray
    ) {
        if ($type === $typeKey) {
            $keepKeys = $mappings[$typeKey]->getFilterKeys();
            $idProperties = $mappings[$typeKey]->getIdProperties();

            if (!empty($keepKeys)) {
                self::filterKeys($mappings, $array, $typeKey, $newArray, $keepKeys, $idProperties);
            }
        }
    }

    /**
     * @param array  $mappings
     * @param array  $array
     * @param string $typeKey
     * @param array  $newArray
     * @param array  $keepKeys
     * @param array  $idProperties
     */
    private static function filterKeys(
        array &$mappings,
        array &$array,
        $typeKey,
        array &$newArray,
        array &$keepKeys,
        array &$idProperties
    ) {
        foreach ($array as $key => &$value) {
            if (self::isPreservableKey($key, $keepKeys, $idProperties)) {
                $newArray[$key] = $value;
                if (is_array($newArray[$key])) {
                    self::deletePropertiesNotInFilter($mappings, $newArray[$key], $typeKey);
                }
            }
        }
    }

    /**
     * @param $key
     * @param $keepKeys
     * @param $idProperties
     *
     * @return bool
     */
    private static function isPreservableKey($key, $keepKeys, $idProperties)
    {
        return $key == Serializer::CLASS_IDENTIFIER_KEY
        || (in_array($key, $keepKeys, true)
            || in_array($key, $idProperties, true));
    }
}
