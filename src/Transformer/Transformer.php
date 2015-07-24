<?php

namespace NilPortugues\Api\Transformer;

use NilPortugues\Api\Mapping\Mapping;
use NilPortugues\Serializer\Serializer;
use NilPortugues\Serializer\Strategy\StrategyInterface;

abstract class Transformer implements StrategyInterface
{
    /**
     * @var Mapping[]
     */
    protected $mappings = [];

    /**
     * @param array $apiMappings
     */
    public function __construct(array $apiMappings)
    {
        $this->mappings = $apiMappings;
    }

    /**
     * Represents the provided $value as a serialized value in string format.
     *
     * @param mixed $value
     *
     * @return string
     */
    abstract public function serialize($value);

    /**
     * Unserialization will fail. This is a transformer.
     *
     * @param string $value
     *
     * @throws TransformerException
     *
     *  @return array
     */
    public function unserialize($value)
    {
        throw new TransformerException(sprintf('%s does not perform unserializations.', __CLASS__));
    }

    /**
     * Removes array keys matching the $unwantedKey array by using recursion.
     *
     * @param array $array
     * @param array $unwantedKey
     */
    protected function recursiveUnset(array &$array, array $unwantedKey)
    {
        foreach ($unwantedKey as $key) {
            if (array_key_exists($key, $array)) {
                unset($array[$key]);
            }
        }

        foreach ($array as &$value) {
            if (is_array($value)) {
                $this->recursiveUnset($value, $unwantedKey);
            }
        }
    }

    /**
     * Replaces the Serializer array structure representing scalar values to the actual scalar value using recursion.
     *
     * @param array $array
     */
    protected function recursiveSetValues(array &$array)
    {
        if (array_key_exists(Serializer::SCALAR_VALUE, $array)) {
            $array = $array[Serializer::SCALAR_VALUE];
        }

        if (is_array($array) && !array_key_exists(Serializer::SCALAR_VALUE, $array)) {
            foreach ($array as &$value) {
                if (is_array($value)) {
                    $this->recursiveSetValues($value);
                }
            }
        }
    }

    /**
     * Simplifies the data structure by removing an array level if data is scalar and has one element in array.
     *
     * @param array $array
     */
    protected function recursiveFlattenOneElementObjectsToScalarType(array &$array)
    {
        if (1 === count($array) && is_scalar(end($array))) {
            $array = array_pop($array);
        }

        if (is_array($array)) {
            foreach ($array as &$value) {
                if (is_array($value)) {
                    $this->recursiveFlattenOneElementObjectsToScalarType($value);
                }
            }
        }
    }

    /**
     * Renames a sets if keys for a given class using recursion.
     *
     * @param array  $array   Array with data
     * @param string $typeKey Scope to do the replacement.
     */
    protected function recursiveRenameKeyValue(array &$array, $typeKey)
    {
        if (array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $array)) {
            $newArray = [];
            $type = $array[Serializer::CLASS_IDENTIFIER_KEY];

            if ($type === $typeKey) {
                $replacements = $this->mappings[$typeKey]->getAliasedProperties();
                if (!empty($replacements)) {
                    foreach ($array as $key => &$value) {
                        $key = (!empty($replacements[$key])) ? $replacements[$key] : $key;
                        $newArray[$key] = $value;

                        if (is_array($newArray[$key])) {
                            $this->recursiveRenameKeyValue($newArray[$key], $typeKey);
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
     * Delete all keys except the ones considered identifier keys or defined in the filter.
     *
     * @param array $array
     * @param $typeKey
     */
    protected function recursiveDeleteKeyIfNotInFilter(array &$array, $typeKey)
    {
        if (array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $array)) {
            $newArray = [];
            $type = $array[Serializer::CLASS_IDENTIFIER_KEY];

            if ($type === $typeKey) {
                $keepKeys = $this->mappings[$typeKey]->getFilterKeys();
                $idProperties = $this->mappings[$typeKey]->getIdProperties();

                if (!empty($keepKeys)) {
                    foreach ($array as $key => &$value) {
                        if ($key == Serializer::CLASS_IDENTIFIER_KEY
                            || (in_array($key, $keepKeys, true)
                                || in_array($key, $idProperties, true))
                        ) {
                            $newArray[$key] = $value;
                            if (is_array($newArray[$key])) {
                                $this->recursiveDeleteKeyIfNotInFilter($newArray[$key], $typeKey);
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
     * @param array  $array   Array with data
     * @param string $typeKey Scope to do the replacement.
     */
    protected function recursiveDeleteKeyValue(array &$array, $typeKey)
    {
        if (array_key_exists(Serializer::CLASS_IDENTIFIER_KEY, $array)) {
            $newArray = [];
            $type = $array[Serializer::CLASS_IDENTIFIER_KEY];

            if ($type === $typeKey) {
                $deletions = $this->mappings[$typeKey]->getHiddenProperties();

                if (!empty($deletions)) {
                    foreach ($array as $key => &$value) {
                        if (!in_array($key, $deletions, true)) {
                            $newArray[$key] = $value;
                            if (is_array($newArray[$key])) {
                                $this->recursiveDeleteKeyValue($newArray[$key], $typeKey);
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
     * Changes all array keys to under_score format using recursion.
     *
     * @param array $array
     */
    protected function recursiveSetKeysToUnderScore(array &$array)
    {
        $newArray = [];
        foreach ($array as $key => &$value) {
            $underscoreKey = $this->camelCaseToUnderscore($key);

            $newArray[$underscoreKey] = $value;
            if (is_array($value)) {
                $this->recursiveSetKeysToUnderScore($newArray[$underscoreKey]);
            }
        }
        $array = $newArray;
    }

    /**
     * Array's type value becomes the key of the provided array using recursion.
     *
     * @param array $array
     */
    protected function recursiveSetTypeAsKey(array &$array)
    {
        if (is_array($array)) {
            foreach ($array as &$value) {
                if (!empty($value[Serializer::CLASS_IDENTIFIER_KEY])) {
                    $key = $value[Serializer::CLASS_IDENTIFIER_KEY];
                    unset($value[Serializer::CLASS_IDENTIFIER_KEY]);
                    $value = [$this->namespaceAsArrayKey($key) => $value];

                    $this->recursiveSetTypeAsKey($value);
                }
            }
        }
    }

    /**
     * Given a class name will return its name without the namespace and in under_score to be used as a key in an array.
     *
     * @param string $key
     *
     * @return string
     */
    protected function namespaceAsArrayKey($key)
    {
        $keys = explode('\\', $key);
        $className = end($keys);

        return $this->camelCaseToUnderscore($className);
    }

    /**
     * Transforms a given string from camelCase to under_score style.
     *
     * @param string $camel
     * @param string $splitter
     *
     * @return string
     */
    protected function camelCaseToUnderscore($camel, $splitter = '_')
    {
        $camel = preg_replace(
            '/(?!^)[[:upper:]][[:lower:]]/',
            '$0',
            preg_replace('/(?!^)[[:upper:]]+/', $splitter.'$0', $camel)
        );

        return strtolower($camel);
    }
}
