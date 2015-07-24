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
     * @param                                     $typeKey
     */
    public static function renameKeyValue(array &$mappings, array &$array, $typeKey)
    {
        if (array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $array)) {
            $newArray = [];
            $type = $array[Serializer::CLASS_IDENTIFIER_KEY];

            if ($type === $typeKey) {
                $replacements = $mappings[$typeKey]->getAliasedProperties();
                if (!empty($replacements)) {
                    foreach ($array as $key => &$value) {
                        $key = (!empty($replacements[$key])) ? $replacements[$key] : $key;
                        $newArray[$key] = $value;

                        if (is_array($newArray[$key])) {
                            self::renameKeyValue($mappings, $newArray[$key], $typeKey);
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
