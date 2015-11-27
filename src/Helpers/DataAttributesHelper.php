<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/25/15
 * Time: 5:05 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\JsonApi\Helpers;

use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Api\Transformer\Helpers\RecursiveFormatterHelper;
use NilPortugues\Serializer\Serializer;

/**
 * Class DataAttributesHelper.
 */
final class DataAttributesHelper
{
    /**
     * @link http://jsonapi.org/format/#document-member-names-reserved-characters
     *
     * @var array
     */
    private static $forbiddenMemberNameCharacters = [
        '+',
        ',',
        '.',
        '[',
        ']',
        '!',
        '"',
        '#',
        '$',
        '%',
        '&',
        '\'',
        '(',
        ')',
        '*',
        '/',
        ':',
        ';',
        '<',
        '=',
        '>',
        '?',
        '@',
        '\\',
        '^',
        '`',
        '{',
        '|',
        '}',
        '~',
    ];

    /**
     * @link http://jsonapi.org/format/#document-member-names-allowed-characters
     *
     * @var array
     */
    private static $forbiddenAsFirstOrLastCharacter = [
        '-',
        '_',
        ' ',
    ];

    /**
     * Changes all array keys to under_score format using recursion.
     *
     * @param array $array
     */
    protected static function recursiveSetKeysToUnderScore(array &$array)
    {
        $newArray = [];
        foreach ($array as $key => &$value) {
            $underscoreKey = RecursiveFormatterHelper::camelCaseToUnderscore($key);
            $newArray[$underscoreKey] = $value;

            if (\is_array($value)) {
                self::recursiveSetKeysToUnderScore($newArray[$underscoreKey]);
            }
        }
        $array = $newArray;
    }

    /**
     * @param \NilPortugues\Api\Mapping\Mapping[] $mappings
     * @param array                               $array
     *
     * @return array
     */
    public static function setResponseDataAttributes(array &$mappings, array &$array)
    {
        $attributes = [];
        $type = $array[Serializer::CLASS_IDENTIFIER_KEY];
        $idProperties = RecursiveFormatterHelper::getIdProperties($mappings, $type);

        foreach ($array as $propertyName => $value) {
            if (\in_array($propertyName, $idProperties, true)) {
                continue;
            }

            $keyName = self::transformToValidMemberName(RecursiveFormatterHelper::camelCaseToUnderscore($propertyName));

            if (!empty($value[Serializer::CLASS_IDENTIFIER_KEY]) && empty($mappings[$value[Serializer::CLASS_IDENTIFIER_KEY]])) {
                $copy = $value;
                self::recursiveSetKeysToUnderScore($copy);
                $attributes[$keyName] = $copy;
                continue;
            }

            if (self::isScalarValue($value) && empty($mappings[$value[Serializer::SCALAR_TYPE]])) {
                $attributes[$keyName] = $value;
                continue;
            }

            if (\is_array($value) && !array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $value)) {
                if (self::containsClassIdentifierKey($value)) {
                    $attributes[$keyName] = $value;
                }
            }
        }

        return [JsonApiTransformer::ATTRIBUTES_KEY => $attributes];
    }

    /**
     * @param string $attributeName
     *
     * @return string
     */
    public static function transformToValidMemberName($attributeName)
    {
        $attributeName = \str_replace(self::$forbiddenMemberNameCharacters, '', $attributeName);

        $attributeName = \ltrim($attributeName, \implode('', self::$forbiddenAsFirstOrLastCharacter));
        $attributeName = \rtrim($attributeName, \implode('', self::$forbiddenAsFirstOrLastCharacter));

        return $attributeName;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    private static function isScalarValue($value)
    {
        return \is_array($value)
        && \array_key_exists(Serializer::SCALAR_TYPE, $value)
        && \array_key_exists(Serializer::SCALAR_VALUE, $value);
    }

    /**
     * @param array $input
     * @param bool  $foundIdentifierKey
     *
     * @return bool
     */
    private static function containsClassIdentifierKey(array $input, $foundIdentifierKey = false)
    {
        if (!is_array($input)) {
            return $foundIdentifierKey || false;
        }

        if (\in_array(Serializer::CLASS_IDENTIFIER_KEY, $input, true)) {
            return true;
        }

        if (!empty($input[Serializer::SCALAR_VALUE])) {
            $input = $input[Serializer::SCALAR_VALUE];

            if (\is_array($input)) {
                foreach ($input as $value) {
                    if (\is_array($value)) {
                        $foundIdentifierKey = $foundIdentifierKey
                            || self::containsClassIdentifierKey($value, $foundIdentifierKey);
                    }
                }
            }
        }

        return !$foundIdentifierKey;
    }
}
