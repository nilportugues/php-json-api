<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/24/15
 * Time: 8:59 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\Transformer\Helpers;

use NilPortugues\Serializer\Serializer;

/**
 * Class RecursiveRenamerHelper.
 */
class RecursiveRenamerHelper
{
    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $array
     * @param string                              $typeKey
     * @param array                               $replacements
     * @param array                               $newArray
     */
    private static function renameKeys(
        array &$mappings,
        array &$array,
        $typeKey,
        array &$replacements,
        array &$newArray
    ) {
        foreach ($array as $key => &$value) {
            $key = (!empty($replacements[$key])) ? $replacements[$key] : $key;
            $newArray[$key] = $value;

            if (is_array($newArray[$key])) {
                self::renameKeyValue($mappings, $newArray[$key], $typeKey);
            }
        }
    }

    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $array
     * @param string                              $typeKey
     */
    public static function renameKeyValue(array &$mappings, array &$array, $typeKey)
    {
        if (array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $array)) {
            $newArray = [];
            $type = $array[Serializer::CLASS_IDENTIFIER_KEY];

            self::renameMatchedClassKeys($mappings, $array, $typeKey, $type, $newArray);

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
    private static function renameMatchedClassKeys(array &$mappings, array &$array, $typeKey, $type, array &$newArray)
    {
        if ($type === $typeKey) {
            $replacements = $mappings[$typeKey]->getAliasedProperties();
            if (!empty($replacements)) {
                self::renameKeys($mappings, $array, $typeKey, $replacements, $newArray);
            }
        }
    }
}
