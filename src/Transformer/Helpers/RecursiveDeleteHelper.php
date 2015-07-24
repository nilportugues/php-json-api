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
        self::unsetKeys($array, $unwantedKey);

        foreach ($array as &$value) {
            if (is_array($value)) {
                self::deleteKeys($value, $unwantedKey);
            }
        }
    }

    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $array
     * @param string                              $typeKey
     * @param array                               $deletions
     * @param array                               $newArray
     */
    private static function deleteNextLevelProperties(
        array &$mappings,
        array &$array,
        $typeKey,
        array &$deletions,
        array &$newArray
    ) {
        foreach ($array as $key => &$value) {
            if (!in_array($key, $deletions, true)) {
                $newArray[$key] = $value;
                if (is_array($newArray[$key])) {
                    self::deleteProperties($mappings, $newArray[$key], $typeKey);
                }
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

            self::deleteMatchedClassProperties($mappings, $array, $typeKey, $newArray);

            if (!empty($newArray)) {
                $array = $newArray;
            }
        }
    }

    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $array
     * @param string                              $typeKey
     * @param array                               $newArray
     */
    private static function deleteMatchedClassProperties(array &$mappings, array &$array, $typeKey, array &$newArray)
    {
        $type = $array[Serializer::CLASS_IDENTIFIER_KEY];
        if ($type === $typeKey) {
            $deletions = $mappings[$typeKey]->getHiddenProperties();
            if (!empty($deletions)) {
                self::deleteNextLevelProperties($mappings, $array, $typeKey, $deletions, $newArray);
            }
        }
    }

    /**
     * @param array $array
     * @param array $unwantedKey
     */
    private static function unsetKeys(array &$array, array &$unwantedKey)
    {
        foreach ($unwantedKey as $key) {
            if (array_key_exists($key, $array)) {
                unset($array[$key]);
            }
        }
    }
}
