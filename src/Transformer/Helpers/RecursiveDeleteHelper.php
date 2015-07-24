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
final class RecursiveDeleteHelper
{
    /**
     * Removes array keys matching the $unwantedKey array by using recursion.
     *
     * @param array $array
     * @param array $unwantedKey
     */
    public static function deleteKeys(array &$array, array $unwantedKey)
    {
        foreach ($unwantedKey as $key) {
            if (array_key_exists($key, $array)) {
                unset($array[$key]);
            }
        }

        foreach ($array as &$value) {
            if (is_array($value)) {
                self::deleteKeys($value, $unwantedKey);
            }
        }
    }

    /**
     * Delete all keys except the ones considered identifier keys or defined in the filter.
     *
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $array
     * @param $typeKey
     */
    public static function deletePropertiesNotInFilter(array &$mappings, array &$array, $typeKey)
    {
        if (array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $array)) {
            $newArray = [];
            $type = $array[Serializer::CLASS_IDENTIFIER_KEY];

            if ($type === $typeKey) {
                $keepKeys = $mappings[$typeKey]->getFilterKeys();
                $idProperties = $mappings[$typeKey]->getIdProperties();

                if (!empty($keepKeys)) {
                    foreach ($array as $key => &$value) {
                        if ($key == Serializer::CLASS_IDENTIFIER_KEY
                            || (in_array($key, $keepKeys, true)
                                || in_array($key, $idProperties, true))
                        ) {
                            $newArray[$key] = $value;
                            if (is_array($newArray[$key])) {
                                self::deletePropertiesNotInFilter($mappings, $newArray[$key], $typeKey);
                            }
                        }
                    }
                }
            }

            if (!empty($newArray)) {
                $array = $newArray;
            }
        }
    }

    /**
     * Removes a sets if keys for a given class using recursion.
     *
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $array    Array with data
     * @param string                              $typeKey  Scope to do the replacement.
     */
    public static function deleteProperties(array &$mappings, array &$array, $typeKey)
    {
        if (array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $array)) {
            $newArray = [];
            $type = $array[Serializer::CLASS_IDENTIFIER_KEY];

            if ($type === $typeKey) {
                $deletions = $mappings[$typeKey]->getHiddenProperties();

                if (!empty($deletions)) {
                    foreach ($array as $key => &$value) {
                        if (!in_array($key, $deletions, true)) {
                            $newArray[$key] = $value;
                            if (is_array($newArray[$key])) {
                                self::deleteProperties($mappings, $newArray[$key], $typeKey);
                            }
                        }
                    }
                }
            }

            if (!empty($newArray)) {
                $array = $newArray;
            }
        }
    }
}
