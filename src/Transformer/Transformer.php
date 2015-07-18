<?php

namespace NilPortugues\Api\Transformer;

use InvalidArgumentException;
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
     * @var string
     */
    protected $firstUrl = '';
    /**
     * @var string
     */
    protected $lastUrl = '';
    /**
     * @var string
     */
    protected $prevUrl = '';
    /**
     * @var string
     */
    protected $nextUrl = '';
    /**
     * @var string
     */
    protected $selfUrl = '';

    /**
     * @param array $apiMappings
     */
    public function __construct(array $apiMappings)
    {
        $this->mappings = $apiMappings;
    }

    /**
     * @param string $self
     *
     * @throws \InvalidArgumentException
     */
    public function setSelfUrl($self)
    {
        $this->selfUrl = (string) $self;
    }

    /**
     * @param string $firstUrl
     *
     * @throws \InvalidArgumentException
     */
    public function setFirstUrl($firstUrl)
    {
        $this->firstUrl = (string) $firstUrl;
    }

    /**
     * @param string $lastUrl
     *
     * @throws \InvalidArgumentException
     */
    public function setLastUrl($lastUrl)
    {
        $this->lastUrl = (string) $lastUrl;
    }

    /**
     * @param $nextUrl
     *
     * @throws \InvalidArgumentException
     */
    public function setNextUrl($nextUrl)
    {
        $this->nextUrl = (string) $nextUrl;
    }

    /**
     * @param $prevUrl
     *
     * @throws \InvalidArgumentException
     */
    public function setPrevUrl($prevUrl)
    {
        $this->prevUrl = (string) $prevUrl;
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    abstract public function serialize($value);

    /**
     * @param $value
     *
     * @throws InvalidArgumentException
     *
     * @return array
     */
    public function unserialize($value)
    {
        throw new InvalidArgumentException(sprintf('%s does not perform unserializations.', __CLASS__));
    }

    /**
     * Converts a underscore string to camelCase.
     *
     * @param string $string
     *
     * @return string
     */
    protected function underscoreToCamelCase($string)
    {
        return str_replace(' ', '', ucwords(strtolower(str_replace(['_', '-'], ' ', $string))));
    }

    /**
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
     * @param array $array
     * @param array $replaceMap
     */
    protected function recursiveChangeKeyNames(array &$array, array $replaceMap)
    {
    }

    /**
     * Renames a key in an array.
     *
     * @param array    $array    Array with data
     * @param string   $typeKey  Scope to do the replacement.
     * @param string   $key      Name of the key holding the value to replace
     * @param \Closure $callable Callable with replacement logic
     */
    protected function recursiveChangeKeyValue(array &$array, $typeKey, $key, \Closure $callable)
    {
    }

    /**
     * Adds a value to an existing identifiable entity containing @type.
     *
     * @param array $array
     * @param       $typeKey
     * @param array $value
     */
    protected function recursiveAddValue(array &$array, $typeKey, array $value)
    {
    }

    /**
     * Array's type value becomes the key of the provided array.
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
     * @param $key
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
     * @param        $camel
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
